<?php

namespace App\Livewire\Admin\Setting;

use App\Models\Setting;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;

#[Layout('components.layouts.app')]
class Index extends Component
{
    use WithFileUploads;

    // Branding
    public $site_name;
    public $logo_upload; // temporary upload
    public $logo_url;

    // General
    public $default_branch_id;
    public $items_per_page;

    // SMS Settings
    public $sms_enabled;
    public $sms_provider;
    public $sms_api_key;
    public $sms_sender;

    // SMS - MelliPayamak credentials and templates
    public $sms_melli_username;
    public $sms_melli_password;
    public $sms_melli_wsdl;
    public $sms_melli_from;
    public $sms_template_subscription_purchase_user;
    public $sms_template_subscription_purchase_admin;
    public $sms_template_subscription_renew_user;
    public $sms_template_subscription_renew_admin;
    public $sms_template_subscription_expire_user;
    public $sms_template_subscription_expire_admin;

    // Notifications
    public $notification_channels = []; // ['system','sms','email']

    // Meeting/Reservation
    public $meeting_default_duration_hours;
    public $meeting_business_open;
    public $meeting_business_close;

    public $branches = [];

    // Users import
    public $users_json_upload; // temporary upload
    public array $import_stats = [];

    public function mount()
    {
        $this->branches = Branch::query()->orderBy('name')->get();

        // Branding
        $this->site_name = Setting::get('general.site_name', config('app.name'));
        $this->logo_url = Setting::get('branding.logo_url', asset('panel/assets/images/logo/1.png'));

        // General
        $this->default_branch_id = Setting::get('general.default_branch_id');
        $this->items_per_page = Setting::get('general.items_per_page', 10);

        // SMS
        $this->sms_enabled = (bool) (Setting::get('sms.enabled', '0'));
        $this->sms_provider = Setting::get('sms.provider', 'kavenegar');
        $this->sms_api_key = Setting::get('sms.api_key');
        $this->sms_sender = Setting::get('sms.sender');

        // SMS - MelliPayamak
        $this->sms_melli_username = Setting::get('sms.melli.username');
        $this->sms_melli_password = Setting::get('sms.melli.password');
        $this->sms_melli_wsdl = Setting::get('sms.melli.wsdl');
        $this->sms_melli_from = Setting::get('sms.melli.from');

        // SMS - Templates (BodyId codes)
        $this->sms_template_subscription_purchase_user = Setting::get('sms.templates.subscription_purchase_user');
        $this->sms_template_subscription_purchase_admin = Setting::get('sms.templates.subscription_purchase_admin');
        $this->sms_template_subscription_renew_user = Setting::get('sms.templates.subscription_renew_user');
        $this->sms_template_subscription_renew_admin = Setting::get('sms.templates.subscription_renew_admin');
        $this->sms_template_subscription_expire_user = Setting::get('sms.templates.subscription_expire_user');
        $this->sms_template_subscription_expire_admin = Setting::get('sms.templates.subscription_expire_admin');

        // Notifications
        $this->notification_channels = Setting::getJson('notifications.default_channels', ['system']);

        // Meeting
        $this->meeting_default_duration_hours = Setting::get('meeting.default_duration_hours', 2);
        $this->meeting_business_open = Setting::get('meeting.business_open', '08:00');
        $this->meeting_business_close = Setting::get('meeting.business_close', '20:00');
    }

    public function updatedLogoUpload()
    {
        $this->validate([
            'logo_upload' => 'nullable|image|max:2048',
        ]);
    }

    public function updatedUsersJsonUpload()
    {
        $this->validate([
            'users_json_upload' => 'nullable|file|mimetypes:application/json,text/plain|max:20480',
        ]);
    }

    public function save()
    {
        $this->validate([
            'site_name' => 'required|string|min:2|max:100',
            'logo_upload' => 'nullable|image|max:2048',
            'logo_url' => 'nullable|string|max:255',
            'default_branch_id' => 'nullable|exists:branches,id',
            'items_per_page' => 'required|integer|min:5|max:100',
            'sms_enabled' => 'nullable|boolean',
            'sms_provider' => 'required|string|in:kavenegar,farapayamak,ghasedak,melli,custom',
            'sms_api_key' => 'nullable|string|max:255',
            'sms_sender' => 'nullable|string|max:100',
            // Melli credentials
            'sms_melli_username' => 'nullable|string|max:255',
            'sms_melli_password' => 'nullable|string|max:255',
            'sms_melli_wsdl' => 'nullable|string|max:255',
            'sms_melli_from' => 'nullable|string|max:100',
            // Template BodyIds
            'sms_template_subscription_purchase_user' => 'nullable|string|max:100',
            'sms_template_subscription_purchase_admin' => 'nullable|string|max:100',
            'sms_template_subscription_renew_user' => 'nullable|string|max:100',
            'sms_template_subscription_renew_admin' => 'nullable|string|max:100',
            'sms_template_subscription_expire_user' => 'nullable|string|max:100',
            'sms_template_subscription_expire_admin' => 'nullable|string|max:100',
            'notification_channels' => 'array',
            'notification_channels.*' => 'string|in:system,sms,email',
            'meeting_default_duration_hours' => 'required|integer|min:1|max:8',
            'meeting_business_open' => 'required|string',
            'meeting_business_close' => 'required|string',
        ]);

        // Branding: upload logo if provided
        if ($this->logo_upload) {
            $filename = 'logo-' . Str::random(10) . '.' . $this->logo_upload->getClientOriginalExtension();
            $path = $this->logo_upload->storeAs('uploads/branding', $filename, 'public');
            // Build public URL without relying on FilesystemAdapter::url()
            $basePublicUrl = (string) config('filesystems.disks.public.url', rtrim((string) config('app.url'), '/') . '/storage');
            $publicUrl = rtrim($basePublicUrl, '/') . '/' . ltrim($path, '/');
            $this->logo_url = $publicUrl;
            Setting::set('branding.logo_url', $publicUrl, 'branding', 'string');
        } elseif (!empty($this->logo_url)) {
            Setting::set('branding.logo_url', $this->logo_url, 'branding', 'string');
        }

        // General
        Setting::set('general.site_name', $this->site_name, 'general', 'string');
        Setting::set('general.default_branch_id', (string)($this->default_branch_id ?? ''), 'general', 'string');
        Setting::set('general.items_per_page', (string)$this->items_per_page, 'general', 'int');

        // SMS
        Setting::set('sms.enabled', $this->sms_enabled ? '1' : '0', 'sms', 'bool');
        Setting::set('sms.provider', $this->sms_provider, 'sms', 'string');
        Setting::set('sms.api_key', $this->sms_api_key, 'sms', 'string');
        Setting::set('sms.sender', $this->sms_sender, 'sms', 'string');

        // SMS - MelliPayamak
        Setting::set('sms.melli.username', $this->sms_melli_username, 'sms', 'string');
        Setting::set('sms.melli.password', $this->sms_melli_password, 'sms', 'string');
        Setting::set('sms.melli.wsdl', $this->sms_melli_wsdl, 'sms', 'string');
        Setting::set('sms.melli.from', $this->sms_melli_from, 'sms', 'string');

        // SMS - Templates (BodyId codes)
        Setting::set('sms.templates.subscription_purchase_user', $this->sms_template_subscription_purchase_user, 'sms', 'string');
        Setting::set('sms.templates.subscription_purchase_admin', $this->sms_template_subscription_purchase_admin, 'sms', 'string');
        Setting::set('sms.templates.subscription_renew_user', $this->sms_template_subscription_renew_user, 'sms', 'string');
        Setting::set('sms.templates.subscription_renew_admin', $this->sms_template_subscription_renew_admin, 'sms', 'string');
        Setting::set('sms.templates.subscription_expire_user', $this->sms_template_subscription_expire_user, 'sms', 'string');
        Setting::set('sms.templates.subscription_expire_admin', $this->sms_template_subscription_expire_admin, 'sms', 'string');

        // Notifications
        Setting::set('notifications.default_channels', $this->notification_channels, 'notifications', 'json');

        // Meeting
        Setting::set('meeting.default_duration_hours', (string)$this->meeting_default_duration_hours, 'meeting', 'int');
        Setting::set('meeting.business_open', $this->meeting_business_open, 'meeting', 'string');
        Setting::set('meeting.business_close', $this->meeting_business_close, 'meeting', 'string');

        session()->flash('success', 'تنظیمات با موفقیت ذخیره شد.');
    }

    public function importUsers(): void
    {
        $this->validate([
            'users_json_upload' => 'required|file|mimetypes:application/json,text/plain|max:20480',
        ]);

        // Allow long-running import for large files
        @set_time_limit(0);
        @ini_set('max_execution_time', '0');

        $path = $this->users_json_upload->getRealPath();
        $raw = @file_get_contents($path);
        $data = json_decode($raw, true);

        if (!is_array($data)) {
            session()->flash('error', 'فایل JSON معتبر نیست یا ساختار آن آرایه‌ای از آبجکت‌ها نیست.');
            return;
        }

        $created = 0;
        $updated = 0;
        $skipped = 0;
        $errors = 0;
        $errorSamples = [];

        foreach ($data as $index => $row) {
            try {
                if (!is_array($row)) {
                    $skipped++;
                    continue;
                }

                $first = trim((string)($row['first_name'] ?? ''));
                $last = trim((string)($row['last_name'] ?? ''));
                $username = trim((string)($row['username'] ?? ''));
                $email = trim((string)($row['email'] ?? ''));
                $mobile = trim((string)($row['mobile_user'] ?? ''));

                // Build display name
                $name = trim($first . ' ' . $last);
                if ($name === '') {
                    $name = $username !== '' ? $username : ($mobile !== '' ? $mobile : 'کاربر وارداتی');
                }

                // Try to find existing user by email or phone
                $found = null;
                if ($email !== '') {
                    $found = User::query()->where('email', $email)->first();
                }
                if (!$found && $mobile !== '') {
                    $found = User::query()->where('phone', $mobile)->first();
                }

                if ($found) {
                    $found->first_name = $first !== '' ? $first : ($found->first_name ?? '');
                    $found->last_name = $last !== '' ? $last : ($found->last_name ?? '');
                    $found->name = $name;
                    if ($mobile !== '') {
                        $found->phone = $mobile;
                    }
                    // only update email if provided and unique
                    if ($email !== '' && $email !== $found->email) {
                        if (!User::query()->where('email', $email)->exists()) {
                            $found->email = $email;
                        }
                    }
                    $found->save();
                    $updated++;
                    continue;
                }

                // Prepare unique, valid email for creation
                $finalEmail = $email;
                if ($finalEmail === '') {
                    if ($mobile !== '') {
                        $finalEmail = 'u' . preg_replace('/[^0-9]/', '', $mobile) . '@import.local';
                    } elseif ($username !== '') {
                        $slug = Str::slug($username, '_');
                        $finalEmail = 'u_' . $slug . '@import.local';
                    } else {
                        $finalEmail = 'u_' . Str::random(8) . '@import.local';
                    }
                    // ensure uniqueness
                    $suffix = 1;
                    $baseEmail = $finalEmail;
                    while (User::query()->where('email', $finalEmail)->exists()) {
                        $finalEmail = preg_replace('/@/', '+' . $suffix . '@', $baseEmail, 1);
                        $suffix++;
                    }
                }

                $passwordPlain = Str::random(10);

                User::query()->create([
                    'name' => $name,
                    'first_name' => $first,
                    'last_name' => $last,
                    'email' => $finalEmail,
                    'phone' => $mobile !== '' ? $mobile : null,
                    'password' => Hash::make($passwordPlain),
                    'role' => 'user',
                ]);
                $created++;
            } catch (\Throwable $e) {
                $errors++;
                if (count($errorSamples) < 5) {
                    $errorSamples[] = "ردیف {$index}: " . $e->getMessage();
                }
            }
        }

        $this->import_stats = [
            'created' => $created,
            'updated' => $updated,
            'skipped' => $skipped,
            'errors' => $errors,
            'error_samples' => $errorSamples,
        ];

        session()->flash('success', "ایمپورت کاربران انجام شد: ایجاد {$created}، بروزرسانی {$updated}، رد شده {$skipped}، خطا {$errors}");

        // Reset file input
        $this->users_json_upload = null;
    }

    public function render()
    {
        return view('livewire.admin.setting.index');
    }
}