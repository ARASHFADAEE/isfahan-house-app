<?php

namespace App\Modules\Sms\Providers;

use App\Modules\Sms\Contracts\SmsProviderInterface;
use RuntimeException;

class MelliSmsProvider implements SmsProviderInterface
{
    protected string $wsdl;
    protected ?string $username;
    protected ?string $password;
    protected ?string $from;
    protected array $templateBodyIds = [];

    public function __construct(array $config)
    {
        $this->wsdl = (string) ($config['wsdl'] ?? '');
        $this->username = $config['username'] ?? null;
        $this->password = $config['password'] ?? null;
        $this->from = $config['from'] ?? null;
        $this->templateBodyIds = (array) ($config['template_body_ids'] ?? []);

        if (!$this->username || !$this->password) {
            throw new RuntimeException('Melli SMS provider requires username and password.');
        }
        if (!$this->wsdl) {
            throw new RuntimeException('Melli SMS provider requires WSDL endpoint.');
        }
    }

    public function send(string $to, string $message, array $options = []): array
    {
        // Plain text sending via template-less base number is not always supported; attempt generic SOAP call fallback.
        return $this->soapCall('SendSimpleSMS', [
            'username' => $this->username,
            'password' => $this->password,
            'to' => $to,
            'from' => $options['from'] ?? $this->from,
            'text' => $message,
        ]);
    }

    public function sendTemplate(string $to, string $template, array $variables = [], array $options = []): array
    {
        $bodyId = $this->templateBodyIds[$template] ?? ($options['bodyId'] ?? null);
        if (!$bodyId) {
            return [
                'success' => false,
                'status' => 'failed',
                'error' => 'Template bodyId not configured for template: '.$template,
            ];
        }

        // MelliPayamak expects a comma-separated string for recipients; variables concatenated as comma-separated string.
        $text = $this->formatVariables($variables);
        $recipients = $options['recipients'] ?? $to;

        return $this->soapCall('SendByBaseNumber3', [
            'username' => $this->username,
            'password' => $this->password,
            'text' => $text,
            'to' => is_array($recipients) ? implode(',', $recipients) : $recipients,
            'bodyId' => (string) $bodyId,
        ]);
    }

    public function sendBulk(array $recipients, string $message, array $options = []): array
    {
        // Bulk plain text fallback; if base-number routing is required, provider-specific logic may vary.
        return $this->soapCall('SendSimpleSMS', [
            'username' => $this->username,
            'password' => $this->password,
            'to' => implode(',', $recipients),
            'from' => $options['from'] ?? $this->from,
            'text' => $message,
        ]);
    }

    protected function soapCall(string $method, array $params): array
    {
        if (!class_exists('SoapClient')) {
            return [
                'success' => false,
                'status' => 'failed',
                'error' => 'PHP SOAP extension is not available.',
            ];
        }

        try {
            $client = new \SoapClient($this->wsdl);
            $response = $client->__soapCall($method, [$params]);

            // Normalize response into a consistent result shape.
            $raw = json_encode($response);
            $status = 'sent';
            $success = true;
            $messageId = null;

            // Attempt to extract a message ID if present (provider-specific).
            if (is_object($response)) {
                $responseArray = (array) $response;
                $messageId = $responseArray['SendSimpleSMSResult'] ?? $responseArray['SendByBaseNumber3Result'] ?? null;
            }

            return [
                'success' => $success,
                'status' => $status,
                'provider_message_id' => $messageId,
                'raw_response' => $raw,
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'status' => 'failed',
                'error' => $e->getMessage(),
                'raw_response' => null,
            ];
        }
    }

    protected function formatVariables(array $variables): string
    {
        // Common formats are comma-separated values; fall back to key=value pairs joined by '|'.
        if (array_is_list($variables)) {
            return implode(',', $variables);
        }
        $pairs = [];
        foreach ($variables as $k => $v) {
            $pairs[] = $k.'='.$v;
        }
        return implode('|', $pairs);
    }
}