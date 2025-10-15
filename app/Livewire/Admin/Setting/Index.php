<?php

namespace App\Livewire\Admin\Setting;

use App\Models\Setting;
use App\Models\Branch;
use Illuminate\Support\Str;
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
            $publicUrl = asset('storage/' . $path);
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

    public function render()
    {
        return view('livewire.admin.setting.index');
    }
}