<div class="container py-4">
  <div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">لیست اعلان‌ها</h5>
      <a class="btn btn-primary" href="{{ route('admin.notifications.create') }}">ارسال اعلان</a>
    </div>
    <div class="card-body">
      <div class="row g-3 mb-3">
        <div class="col-md-3">
          <label class="form-label">نوع</label>
          <select class="form-select" wire:model.live="type">
            <option value="">همه</option>
            <option value="system">سیستمی</option>
            <option value="sms">پیامک</option>
            <option value="email">ایمیل</option>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">وضعیت</label>
          <select class="form-select" wire:model.live="status">
            <option value="">همه</option>
            <option value="pending">در انتظار</option>
            <option value="sent">ارسال‌شده</option>
            <option value="failed">ناموفق</option>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">رویداد</label>
          <select class="form-select" wire:model.live="event_type">
            <option value="">همه</option>
            <option value="subscription_created">ایجاد اشتراک</option>
            <option value="subscription_approved">تایید اشتراک</option>
            <option value="subscription_expiring">رو به اتمام</option>
            <option value="subscription_expired">منقضی شده</option>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">جستجو</label>
          <input type="text" class="form-control" placeholder="پیام یا نام/ایمیل/تلفن کاربر" wire:model.live="search" />
        </div>
      </div>

      @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>کاربر</th>
              <th>نوع</th>
              <th>رویداد</th>
              <th>وضعیت</th>
              <th>پیام</th>
              <th>تاریخ</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($notifications as $n)
              <tr>
                <td>{{ $n->id }}</td>
                <td>{{ trim(($n->user?->first_name ?? '') . ' ' . ($n->user?->last_name ?? '')) ?: ($n->user?->name ?? '-') }}</td>
                <td>{{ $n->type }}</td>
                <td>{{ $n->event_type }}</td>
                <td>{{ $n->status }}</td>
                <td>{{ \Illuminate\Support\Str::limit($n->message, 80) }}</td>
                <td>{{ \Hekmatinasser\Verta\Verta::instance($n->created_at)->format('Y/m/d H:i') }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center">اعلانی یافت نشد.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{ $notifications->links() }}
    </div>
  </div>
</div>