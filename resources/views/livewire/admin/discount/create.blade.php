<div class="container py-4">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">ایجاد تخفیف</h5>
      <a class="btn btn-light" href="{{ route('admin.discounts.index') }}">بازگشت به لیست</a>
    </div>
    <div class="card-body">
      @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      <form wire:submit.prevent="save">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">جستجوی کاربر</label>
            <input type="text" class="form-control" placeholder="نام، ایمیل یا تلفن" wire:model.live.debounce.300ms="user_search" />
            @if (!empty($user_results))
              <div class="list-group mt-2">
                @foreach ($user_results as $u)
                  <button type="button" class="list-group-item list-group-item-action" wire:click="selectUser({{ $u['id'] }})">
                    {{ $u['label'] }}
                    <span class="text-muted d-block small">{{ $u['email'] }} {{ $u['phone'] ? ' / '.$u['phone'] : '' }}</span>
                  </button>
                @endforeach
              </div>
            @endif
            @error('user_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            @if ($user_id)
              <div class="alert alert-info mt-2">کاربر انتخاب‌شده: <strong>{{ optional(\App\Models\User::find($user_id))->name }}</strong></div>
            @endif
          </div>

          <div class="col-md-6">
            <label class="form-label">نوع اشتراک</label>
            <select class="form-select" wire:model.live="subscription_type_id">
              <option value="">انتخاب کنید</option>
              @foreach ($subscriptionTypes as $type)
                <option value="{{ $type->id }}">{{ $type->name }}</option>
              @endforeach
            </select>
            @error('subscription_type_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
          </div>

          <div class="col-md-4">
            <label class="form-label">درصد تخفیف</label>
            <input type="number" step="0.01" class="form-control" wire:model.live="discount_percentage" />
            @error('discount_percentage')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
          </div>
          <div class="col-md-4">
            <label class="form-label">اعتبار تا (اختیاری)</label>
            <input type="date" class="form-control" wire:model.live="valid_until" />
            @error('valid_until')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            <div class="form-text">در صورت خالی بودن، تخفیف دائمی است.</div>
          </div>

          <div class="col-12">
            <button class="btn btn-success" type="submit" wire:loading.attr="disabled">ثبت تخفیف</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>