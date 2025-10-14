<div class="container py-4">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">ایجاد تراکنش</h5>
      <a class="btn btn-light" href="{{ route('admin.transactions.index') }}">بازگشت به لیست</a>
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
            <label class="form-label">شعبه</label>
            <select class="form-select" wire:model.live="branch_id">
              <option value="">انتخاب کنید</option>
              @foreach ($branches as $branch)
                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
              @endforeach
            </select>
            @error('branch_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
          </div>

          <div class="col-md-4">
            <label class="form-label">مبلغ</label>
            <input type="number" step="0.01" class="form-control" wire:model.live="amount" />
            @error('amount')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
          </div>
          <div class="col-md-4">
            <label class="form-label">روش پرداخت</label>
            <select class="form-select" wire:model.live="payment_method">
              <option value="online">آنلاین</option>
              <option value="card">کارت</option>
              <option value="cash">نقدی</option>
            </select>
            @error('payment_method')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
          </div>
          <div class="col-md-4">
            <label class="form-label">وضعیت</label>
            <select class="form-select" wire:model.live="status">
              <option value="pending">در انتظار</option>
              <option value="completed">تکمیل‌شده</option>
              <option value="failed">ناموفق</option>
            </select>
            @error('status')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
          </div>

          <div class="col-md-6">
            <label class="form-label">شناسه اشتراک (اختیاری)</label>
            <input type="number" class="form-control" wire:model.live="subscription_id" />
            @error('subscription_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
          </div>
          <div class="col-md-6">
            <label class="form-label">شناسه رزرو جلسه (اختیاری)</label>
            <input type="number" class="form-control" wire:model.live="meeting_reservation_id" />
            @error('meeting_reservation_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
          </div>

          <div class="col-12">
            <button class="btn btn-success" type="submit" wire:loading.attr="disabled">ثبت تراکنش</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>