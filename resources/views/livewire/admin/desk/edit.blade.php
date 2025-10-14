<div>
    <div class="d-flex align-items-center justify-content-between mb-3" style="margin-top:40px ">
        <h3 class="mb-0">ویرایش میز</h3>
        <a href="{{ route('admin.desks.index') }}" class="btn btn-secondary">بازگشت</a>
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
            <form wire:submit.prevent="update">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">شعبه</label>
                        <select class="form-select" wire:model.live="branch_id">
                            <option value="">انتخاب کنید</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                        @error('branch_id')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">شماره میز</label>
                        <input type="text" class="form-control" wire:model.lazy="desk_number" maxlength="50">
                        @error('desk_number')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">وضعیت</label>
                        <select class="form-select" wire:model.lazy="status">
                            <option value="available">آزاد</option>
                            <option value="reserved">رزرو شده</option>
                        </select>
                        @error('status')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                </div>

                @if ($status === 'reserved')
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label class="form-label">اشتراک فعال (هم‌شعبه)</label>
                            <select class="form-select" wire:model.live="subscription_id">
                                <option value="">— انتخاب اشتراک —</option>
                                @forelse($subscriptions as $sub)
                                    <option value="{{ $sub->id }}">#{{ $sub->id }} — {{ $sub->user?->name ?? 'کاربر' }} — {{ $sub->branch?->name ?? 'شعبه' }}</option>
                                @empty
                                    <option value="" disabled>اشتراک فعالی برای شعبه انتخاب‌شده یافت نشد</option>
                                @endforelse
                            </select>
                            @error('subscription_id')<div class="text-danger small">{{ $message }}</div>@enderror
                            @if (empty($branch_id))
                                <div class="text-muted small mt-1">ابتدا شعبه را انتخاب کنید تا اشتراک‌های فعال همان شعبه بارگذاری شود.</div>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">کاربر اشتراک</label>
                            <input type="text" class="form-control" value="{{ optional($subscriptions->firstWhere('id', $subscription_id))->user?->name }}" disabled>
                        </div>
                    </div>
                @endif

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">ذخیره تغییرات</button>
                </div>
            </form>
        </div>
    </div>
</div>