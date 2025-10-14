<div>
  <div class="container-fluid" style="margin-top:40px ">
    <div class="row">
      <div class="col-12 d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">ایجاد کاربر</h4>
        <a href="{{ route('admin.users.index') }}" class="btn btn-light-secondary">بازگشت</a>
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
        <form wire:submit.prevent="save" class="app-form">
          <div class="mb-3">
            <label class="form-label">نام کوچک</label>
            <input type="text" class="form-control" wire:model.defer="first_name" />
            @error('first_name') <div class="text-danger">{{ $message }}</div> @enderror
          </div>
          <div class="mb-3">
            <label class="form-label">نام خانوادگی</label>
            <input type="text" class="form-control" wire:model.defer="last_name" />
            @error('last_name') <div class="text-danger">{{ $message }}</div> @enderror
          </div>
          <div class="mb-3">
            <label class="form-label">ایمیل</label>
            <input type="email" class="form-control" wire:model.defer="email" />
            @error('email') <div class="text-danger">{{ $message }}</div> @enderror
          </div>
          <div class="mb-3">
            <label class="form-label">شماره تماس</label>
            <input type="text" class="form-control" wire:model.defer="phone" />
            @error('phone') <div class="text-danger">{{ $message }}</div> @enderror
          </div>
          <div class="mb-3">
            <label class="form-label">نقش</label>
            <select class="form-select" wire:model.defer="role">
              <option value="user">کاربر</option>
              <option value="admin">ادمین</option>
              <option value="ceo">مدیرعامل</option>
            </select>
            @error('role') <div class="text-danger">{{ $message }}</div> @enderror
          </div>
          <div class="mb-3">
            <label class="form-label">رمز عبور</label>
            <input type="password" class="form-control" wire:model.defer="password" />
            @error('password') <div class="text-danger">{{ $message }}</div> @enderror
          </div>
          <button type="submit" class="btn btn-primary">ذخیره</button>
        </form>
      </div>
    </div>
  </div>
</div>
