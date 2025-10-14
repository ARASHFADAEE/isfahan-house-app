<div class="container py-4">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">ارسال اعلان</h5>
      <a class="btn btn-light" href="{{ route('admin.notifications.index') }}">بازگشت به لیست</a>
    </div>
    <div class="card-body">
      @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      <form wire:submit.prevent="save">
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">گیرنده</label>
            <select class="form-select" wire:model.live="audience">
              <option value="user">کاربر مشخص</option>
              <option value="all">همه کاربران</option>
            </select>
          </div>

          @if ($audience === 'user')
            <div class="col-md-8">
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
          @endif

          <div class="col-md-4">
            <label class="form-label">نوع اعلان</label>
            <select class="form-select" wire:model.live="type">
              <option value="system">سیستمی</option>
              <option value="sms">پیامک</option>
              <option value="email">ایمیل</option>
            </select>
            @error('type')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
          </div>
          <div class="col-md-4">
            <label class="form-label">نوع رویداد (اختیاری)</label>
            <select class="form-select" wire:model.live="event_type">
              <option value="">—</option>
              <option value="subscription_created">ایجاد اشتراک</option>
              <option value="subscription_approved">تایید اشتراک</option>
              <option value="subscription_expiring">رو به اتمام</option>
              <option value="subscription_expired">منقضی شده</option>
            </select>
            @error('event_type')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
          </div>

          <div class="col-12">
            <label class="form-label">متن پیام</label>
            <textarea class="form-control" rows="4" wire:model.live="message" placeholder="متن اعلان..."></textarea>
            @error('message')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
          </div>

          <div class="col-md-4">
            <label class="form-label">وضعیت</label>
            <select class="form-select" wire:model.live="status">
              <option value="pending">در انتظار</option>
              <option value="sent">ارسال‌شده</option>
              <option value="failed">ناموفق</option>
            </select>
            @error('status')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
          </div>

          <div class="col-12">
            <button class="btn btn-success" type="submit" wire:loading.attr="disabled">ارسال اعلان</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>