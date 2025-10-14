<div>
  <div class="container-fluid" style="margin-top:40px ">
    <div class="row">
      <div class="col-12 d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">ویرایش شعبه: {{ $name }}</h4>
        <a href="/branches" class="btn btn-light-secondary">بازگشت</a>
      </div>
      <div class="col-12">
        @if ($errors->any())
          <div class="alert alert-light-danger" role="alert">
            <strong>خطا در اعتبارسنجی فرم</strong>
            <ul class="mb-0 mt-2">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif
        <form wire:submit.prevent="update" class="app-form">
          <div class="mb-3">
            <label class="form-label">نام شعبه</label>
            <input type="text" class="form-control" wire:model.defer="name" />
            @error('name') <div class="text-danger">{{ $message }}</div> @enderror
          </div>
          <div class="mb-3">
            <label class="form-label">آدرس</label>
            <textarea class="form-control" rows="3" wire:model.defer="address"></textarea>
            @error('address') <div class="text-danger">{{ $message }}</div> @enderror
          </div>
          <div class="mb-3">
            <label class="form-label">ظرفیت میز انعطاف‌پذیر</label>
            <input type="number" min="0" class="form-control" wire:model.defer="flexible_desk_capacity" />
            @error('flexible_desk_capacity') <div class="text-danger">{{ $message }}</div> @enderror
          </div>
          <button type="submit" class="btn btn-primary">ذخیره تغییرات</button>
        </form>
      </div>
    </div>
  </div>
</div>
