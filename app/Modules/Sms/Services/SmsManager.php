<?php

namespace App\Modules\Sms\Services;

use App\Models\Setting;
use App\Models\SmsLog;
use App\Modules\Sms\Contracts\SmsProviderInterface;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Arr;

class SmsManager
{
    protected Container $app;

    // Fluent builder state
    protected array $recipients = [];
    protected ?string $message = null;
    protected ?string $template = null;
    protected array $variables = [];
    protected ?string $providerName = null;
    protected array $options = [];

    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    // Builder API
    public function to(string|array $recipients): self
    {
        $this->recipients = array_values(array_filter((array) $recipients));
        return $this;
    }

    public function message(string $text): self
    {
        $this->message = $text;
        return $this;
    }

    public function template(string $name): self
    {
        $this->template = $name;
        return $this;
    }

    public function vars(array $vars): self
    {
        $this->variables = $vars;
        return $this;
    }

    public function via(string $provider): self
    {
        $this->providerName = $provider;
        return $this;
    }

    public function option(string $key, mixed $value): self
    {
        $this->options[$key] = $value;
        return $this;
    }

    public function options(array $options): self
    {
        $this->options = array_merge($this->options, $options);
        return $this;
    }

    // Immediate send
    public function send(): array
    {
        $enabled = $this->getEnabled();
        $providerName = $this->providerName ?: (string) config('sms.default_provider');
        $provider = $this->resolveProvider($providerName);

        $result = [
            'success' => false,
            'status' => 'failed',
        ];

        if (!$enabled) {
            $result = ['success' => false, 'status' => 'disabled'];
        } else {
            if ($this->template) {
                $to = $this->recipients[0] ?? '';
                $result = $provider->sendTemplate($to, $this->template, $this->variables, [
                    'recipients' => $this->recipients,
                    ...$this->options,
                ]);
            } else {
                $result = $this->dispatchPlain($provider);
            }
        }

        $this->log($providerName, $result);
        $this->reset();
        return $result;
    }

    protected function dispatchPlain(SmsProviderInterface $provider): array
    {
        if (count($this->recipients) > 1) {
            return $provider->sendBulk($this->recipients, (string) $this->message, $this->options);
        }
        return $provider->send($this->recipients[0] ?? '', (string) $this->message, $this->options);
    }

    // Queue sending via job
    public function queue(?string $queue = null, ?int $delaySeconds = null): void
    {
        $payload = [
            'recipients' => $this->recipients,
            'message' => $this->message,
            'template' => $this->template,
            'variables' => $this->variables,
            'provider' => $this->providerName ?: (string) config('sms.default_provider'),
            'options' => $this->options,
        ];

        $job = new \App\Jobs\SendSmsJob($payload);
        if ($queue) $job->onQueue($queue);
        if ($delaySeconds) $job->delay(now()->addSeconds($delaySeconds));
        dispatch($job);
        $this->reset();
    }

    // Entry point used by queued job execution
    public function sendFromJob(array $payload): array
    {
        $this->to($payload['recipients'] ?? [])
            ->via((string) ($payload['provider'] ?? config('sms.default_provider')))
            ->options($payload['options'] ?? []);

        if (!empty($payload['template'])) {
            $this->template((string) $payload['template'])
                 ->vars((array) ($payload['variables'] ?? []));
        } else {
            $this->message((string) ($payload['message'] ?? ''));
        }

        return $this->send();
    }

    protected function resolveProvider(string $name): SmsProviderInterface
    {
        $providers = (array) config('sms.providers');
        $cfg = $providers[$name] ?? null;
        if (!$cfg || empty($cfg['class'])) {
            throw new \RuntimeException('Unknown SMS provider: '.$name);
        }
        $class = $cfg['class'];
        $config = Arr::except($cfg, ['class']);
        $config = $this->applySettingsOverrides($name, $config);
        return new $class($config);
    }

    protected function getEnabled(): bool
    {
        // Prefer dynamic setting if present; otherwise fallback to config.
        $enabledSetting = null;
        try {
            $enabledSetting = Setting::get('sms.enabled');
        } catch (\Throwable $e) {
            // ignore if settings table not present yet
        }
        return (bool) ($enabledSetting ?? config('sms.enabled'));
    }

    protected function log(string $provider, array $result): void
    {
        try {
            SmsLog::create([
                'recipients' => $this->recipients,
                'message' => $this->message,
                'template' => $this->template,
                'variables' => $this->variables,
                'provider' => $provider,
                'status' => $result['status'] ?? ($result['success'] ? 'sent' : 'failed'),
                'provider_message_id' => $result['provider_message_id'] ?? null,
                'raw_response' => $result['raw_response'] ?? null,
                'error' => $result['error'] ?? null,
                'sent_at' => now(),
            ]);
        } catch (\Throwable $e) {
            // Avoid throwing during logging; rely on app logs if desired
            logger()->warning('Failed to persist SMS log: '.$e->getMessage());
        }
    }

    protected function reset(): void
    {
        $this->recipients = [];
        $this->message = null;
        $this->template = null;
        $this->variables = [];
        $this->providerName = null;
        $this->options = [];
    }

    protected function applySettingsOverrides(string $provider, array $config): array
    {
        // Global sender override
        try {
            $sender = Setting::get('sms.sender');
            if (!empty($sender)) {
                $config['from'] = $sender;
            }
        } catch (\Throwable $e) {
            // ignore
        }

        // Provider-specific overrides
        if ($provider === 'melli') {
            try {
                $username = Setting::get('sms.melli.username');
                $password = Setting::get('sms.melli.password');
                $wsdl = Setting::get('sms.melli.wsdl');
                $fromSpecific = Setting::get('sms.melli.from');
                if (!empty($username)) $config['username'] = $username;
                if (!empty($password)) $config['password'] = $password;
                if (!empty($wsdl)) $config['wsdl'] = $wsdl;
                if (!empty($fromSpecific)) $config['from'] = $fromSpecific;

                // Template BodyId mappings
                $tpl = array_filter([
                    'subscription_purchase_user' => Setting::get('sms.templates.subscription_purchase_user'),
                    'subscription_purchase_admin' => Setting::get('sms.templates.subscription_purchase_admin'),
                    'subscription_renew_user' => Setting::get('sms.templates.subscription_renew_user'),
                    'subscription_renew_admin' => Setting::get('sms.templates.subscription_renew_admin'),
                    'subscription_expire_user' => Setting::get('sms.templates.subscription_expire_user'),
                    'subscription_expire_admin' => Setting::get('sms.templates.subscription_expire_admin'),
                ]);
                if (!empty($tpl)) {
                    $config['template_body_ids'] = array_merge((array)($config['template_body_ids'] ?? []), $tpl);
                }
            } catch (\Throwable $e) {
                // ignore
            }
        }
        return $config;
    }
}