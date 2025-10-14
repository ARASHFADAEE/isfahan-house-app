<div>
    <div class="d-flex align-items-center justify-content-between mb-3" style="margin-top:40px ">
        <h3 class="mb-0">ایجاد اشتراک</h3>
        <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-secondary">بازگشت</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-light-danger border-0 mb-3">
            <div class="d-flex align-items-start">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <div>
                    <div class="fw-bold mb-1">لطفا خطاهای زیر را برطرف کنید:</div>
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form wire:submit.prevent="save">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">کاربر</label>
                        <input type="text" class="form-control" placeholder="جستجوی کاربر (نام، ایمیل، موبایل)" wire:model.live.debounce.400ms="userSearch">
                        @if($selectedUserName)
                            <div class="mt-1 small">کاربر انتخاب‌شده: <span class="badge bg-primary">{{ $selectedUserName }}</span> <button type="button" class="btn btn-link btn-sm" wire:click="clearSelectedUser">حذف انتخاب</button></div>
                        @endif
                        @error('user_id')<div class="text-danger small">{{ $message }}</div>@enderror
                        @if(mb_strlen($userSearch) >= 2 && count($userResults) > 0)
                            <div class="list-group mt-1">
                                @foreach($userResults as $u)
                                    <button type="button" class="list-group-item list-group-item-action" wire:click="selectUser({{ $u->id }})">
                                        {{ $u->name }}
                                        <span class="text-muted">— {{ $u->email }} — {{ $u->phone }}</span>
                                    </button>
                                @endforeach
                            </div>
                        @elseif(mb_strlen($userSearch) >= 2)
                            <div class="mt-1 text-muted small">کاربری یافت نشد.</div>
                        @endif
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">نوع اشتراک</label>
                        <select class="form-select" wire:model.lazy="subscription_type_id">
                            <option value="">انتخاب کنید</option>
                            @foreach($types as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                        @error('subscription_type_id')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">شعبه</label>
                        <select class="form-select" wire:model.lazy="branch_id">
                            <option value="">انتخاب کنید</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                        @error('branch_id')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">کد تخفیف (اختیاری)</label>
                        <select class="form-select" wire:model.lazy="discount_id">
                            <option value="">بدون تخفیف</option>
                            @foreach($discounts as $discount)
                                <option value="{{ $discount->id }}">{{ $discount->id }}</option>
                            @endforeach
                        </select>
                        @error('discount_id')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">زمان شروع</label>
                        <input type="datetime-local" class="form-control" wire:model.lazy="start_datetime">
                        @error('start_datetime')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">زمان پایان</label>
                        <input type="datetime-local" class="form-control" wire:model.lazy="end_datetime" readonly>
                        <div class="form-text">بر اساس تعداد روزهای نوع اشتراک به‌صورت خودکار محاسبه می‌شود.</div>
                        @error('end_datetime')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">وضعیت</label>
                        <select class="form-select" wire:model.lazy="status">
                            <option value="pending">در انتظار</option>
                            <option value="active">فعال</option>
                            <option value="expired">منقضی</option>
                        </select>
                        @error('status')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">مبلغ کل</label>
                        <input type="number" class="form-control" wire:model.lazy="total_price" min="0" step="0.01">
                        @error('total_price')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">ثبت اشتراک</button>
                </div>
            </form>
        </div>
    </div>
</div>
