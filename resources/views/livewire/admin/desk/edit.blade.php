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
                        <select class="form-select" wire:model.lazy="branch_id">
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

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">ذخیره تغییرات</button>
                </div>
            </form>
        </div>
    </div>
</div>