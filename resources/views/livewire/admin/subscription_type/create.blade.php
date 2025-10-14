<div>
    <div class="d-flex align-items-center justify-content-between mb-3" style="margin-top:40px ">
        <h3 class="mb-0">ایجاد نوع اشتراک</h3>
        <a href="{{ route('admin.subscription_types.index') }}" class="btn btn-secondary">بازگشت</a>
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
                        <label class="form-label">نام</label>
                        <input type="text" class="form-control" wire:model.lazy="name">
                        @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">قیمت</label>
                        <input type="number" class="form-control" wire:model.lazy="price" min="0" step="0.01">
                        @error('price')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">تعداد روزها</label>
                        <input type="number" class="form-control" wire:model.lazy="duration_days" min="1" step="1">
                        @error('duration_days')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 d-flex align-items-center">
                        <div class="form-check mt-4">
                            <input class="form-check-input" type="checkbox" id="requires_admin_approval" wire:model="requires_admin_approval">
                            <label class="form-check-label" for="requires_admin_approval">نیاز به تأیید مدیر</label>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">ثبت نوع اشتراک</button>
                </div>
            </form>
        </div>
    </div>
</div>
