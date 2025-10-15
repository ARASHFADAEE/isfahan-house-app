<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h2 class="h5 m-0">تنظیمات سیستم</h2>
        <button class="btn btn-primary" wire:click="save" wire:loading.attr="disabled">
            ذخیره تنظیمات
        </button>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <!-- Branding -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">برندینگ</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">نام سایت</label>
                        <input type="text" class="form-control" wire:model.defer="site_name" placeholder="مثلاً خانه اصفهان">
                        @error('site_name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">لوگو فعلی</label>
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ $logo_upload ? $logo_upload->temporaryUrl() : $logo_url }}" alt="لوگو" style="height:48px; width:auto;" onerror="this.style.display='none'">
                            <input type="text" class="form-control" wire:model.defer="logo_url" placeholder="آدرس لوگو (اختیاری)">
                        </div>
                        @error('logo_url') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">آپلود لوگو</label>
                        <input type="file" class="form-control" wire:model="logo_upload" accept="image/*">
                        @error('logo_upload') <small class="text-danger">{{ $message }}</small> @enderror
                        <div class="form-text">حداکثر 2MB، فرمت‌های تصویر مجاز</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- General -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">تنظیمات عمومی</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">شعبه پیش‌فرض</label>
                        <select class="form-select" wire:model.defer="default_branch_id">
                            <option value="">— بدون پیش‌فرض —</option>
                            @foreach ($branches as $b)
                                <option value="{{ $b->id }}">{{ $b->name }}</option>
                            @endforeach
                        </select>
                        @error('default_branch_id') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">تعداد آیتم در هر صفحه</label>
                        <input type="number" class="form-control" wire:model.defer="items_per_page" min="5" max="100">
                        @error('items_per_page') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- SMS -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">پیامک (SMS)</div>
                <div class="card-body">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" role="switch" id="smsEnabled" wire:model.defer="sms_enabled">
                        <label class="form-check-label" for="smsEnabled">فعال‌سازی ارسال پیامک</label>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">سرویس‌دهنده</label>
                        <select class="form-select" wire:model.defer="sms_provider">
                            <option value="kavenegar">کاوه نگار</option>
                            <option value="farapayamak">فراپیامک</option>
                            <option value="ghasedak">قاصدک</option>
                            <option value="melli">ملی‌پیامک</option>
                            <option value="custom">سفارشی</option>
                        </select>
                        @error('sms_provider') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">API Key</label>
                        <input type="text" class="form-control" wire:model.defer="sms_api_key" placeholder="کلید دسترسی">
                        @error('sms_api_key') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">شماره یا فرستنده</label>
                        <input type="text" class="form-control" wire:model.defer="sms_sender" placeholder="شماره/فرستنده">
                        @error('sms_sender') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    @if ($sms_provider === 'melli')
                        <hr/>
                        <div class="mb-2 fw-bold">تنظیمات اختصاصی ملی‌پیامک</div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">نام کاربری</label>
                                <input type="text" class="form-control" wire:model.defer="sms_melli_username" placeholder="نام کاربری ملی‌پیامک">
                                @error('sms_melli_username') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">رمز عبور</label>
                                <input type="text" class="form-control" wire:model.defer="sms_melli_password" placeholder="رمز عبور ملی‌پیامک">
                                @error('sms_melli_password') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">WSDL</label>
                                <input type="text" class="form-control" wire:model.defer="sms_melli_wsdl" placeholder="آدرس WSDL (مثلاً https://bpm.melipayamak.ir/SmsService.asmx?wsdl)">
                                @error('sms_melli_wsdl') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">فرستنده اختصاصی (اختیاری)</label>
                                <input type="text" class="form-control" wire:model.defer="sms_melli_from" placeholder="در صورت خالی از مقدار عمومی استفاده می‌شود">
                                @error('sms_melli_from') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>

                        <div class="mt-3">
                            <div class="fw-bold mb-2">کد الگو (BodyId) رویدادهای اشتراک</div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">خرید اشتراک — کاربر</label>
                                    <input type="text" class="form-control" wire:model.defer="sms_template_subscription_purchase_user" placeholder="BodyId الگو">
                                    @error('sms_template_subscription_purchase_user') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">خرید اشتراک — مدیر</label>
                                    <input type="text" class="form-control" wire:model.defer="sms_template_subscription_purchase_admin" placeholder="BodyId الگو">
                                    @error('sms_template_subscription_purchase_admin') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">تمدید اشتراک — کاربر</label>
                                    <input type="text" class="form-control" wire:model.defer="sms_template_subscription_renew_user" placeholder="BodyId الگو">
                                    @error('sms_template_subscription_renew_user') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">تمدید اشتراک — مدیر</label>
                                    <input type="text" class="form-control" wire:model.defer="sms_template_subscription_renew_admin" placeholder="BodyId الگو">
                                    @error('sms_template_subscription_renew_admin') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">انقضای اشتراک — کاربر</label>
                                    <input type="text" class="form-control" wire:model.defer="sms_template_subscription_expire_user" placeholder="BodyId الگو">
                                    @error('sms_template_subscription_expire_user') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">انقضای اشتراک — مدیر</label>
                                    <input type="text" class="form-control" wire:model.defer="sms_template_subscription_expire_admin" placeholder="BodyId الگو">
                                    @error('sms_template_subscription_expire_admin') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                            <div class="form-text mt-2">
                                راهنما: در پنل ملی‌پیامک از بخش «ارسال هوشمند/الگو» الگوی خود را ثبت کنید، پس از تایید، کد BodyId آن الگو را در فیلد مربوطه وارد نمایید. متغیرهای الگو را به ترتیب با کاما ارسال می‌کنیم (مثال: <code>code, name</code>).
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Notifications -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">اعلان‌ها</div>
                <div class="card-body">
                    <div class="mb-2">کانال‌های پیش‌فرض ارسال اعلان:</div>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="system" id="chSystem" wire:model.defer="notification_channels">
                            <label class="form-check-label" for="chSystem">سیستمی</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="sms" id="chSms" wire:model.defer="notification_channels">
                            <label class="form-check-label" for="chSms">پیامک</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="email" id="chEmail" wire:model.defer="notification_channels">
                            <label class="form-check-label" for="chEmail">ایمیل</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users Import (JSON) -->
        <div class="col-lg-12 mb-4">
            <div class="card">
                <div class="card-header">ایمپورت کاربران از JSON</div>
                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label">انتخاب فایل JSON کاربران</label>
                        <input type="file" class="form-control" wire:model="users_json_upload" accept=".json,application/json">
                        @error('users_json_upload') <small class="text-danger">{{ $message }}</small> @enderror
                        <div class="form-text">ساختار فایل مشابه <code>users.json</code> باشد؛ فیلدهای رایج: <code>first_name</code>، <code>last_name</code>، <code>username</code>، <code>email</code>، <code>mobile_user</code>.</div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button class="btn btn-light-primary" wire:click="importUsers" wire:loading.attr="disabled">ایمپورت کاربران</button>
                        <div class="text-secondary" wire:loading wire:target="importUsers">در حال پردازش...</div>
                    </div>

                    @if (!empty($import_stats))
                        <div class="mt-3">
                            <div>ایجاد: {{ $import_stats['created'] ?? 0 }} | بروزرسانی: {{ $import_stats['updated'] ?? 0 }} | رد شده: {{ $import_stats['skipped'] ?? 0 }} | خطا: {{ $import_stats['errors'] ?? 0 }}</div>
                            @if (!empty($import_stats['error_samples']))
                                <ul class="text-danger mt-2">
                                    @foreach ($import_stats['error_samples'] as $msg)
                                        <li>{{ $msg }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Meeting / Reservation -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">جلسات و رزروها</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">مدت پیش‌فرض جلسه (ساعت)</label>
                        <input type="number" class="form-control" wire:model.defer="meeting_default_duration_hours" min="1" max="8">
                        @error('meeting_default_duration_hours') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="row g-3">
                        <div class="col">
                            <label class="form-label">ساعت شروع کار</label>
                            <input type="time" class="form-control" wire:model.defer="meeting_business_open">
                            @error('meeting_business_open') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col">
                            <label class="form-label">ساعت پایان کار</label>
                            <input type="time" class="form-control" wire:model.defer="meeting_business_close">
                            @error('meeting_business_close') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3 d-flex">
        <button class="btn btn-primary ms-auto" wire:click="save" wire:loading.attr="disabled">ذخیره</button>
    </div>

    <div wire:loading class="position-fixed top-0 start-0 w-100 h-100" style="background:#0001"></div>
</div>