<div class="container py-4">
  <div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">لیست تخفیف‌ها</h5>
      <a class="btn btn-primary" href="{{ route('admin.discounts.create') }}">ایجاد تخفیف</a>
    </div>
    <div class="card-body">
      <div class="row g-3 mb-3">
        <div class="col-md-4">
          <label class="form-label">نوع اشتراک</label>
          <select class="form-select" wire:model.live="subscription_type_id">
            <option value="">همه</option>
            @foreach ($subscriptionTypes as $type)
              <option value="{{ $type->id }}">{{ $type->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-8">
          <label class="form-label">جستجو</label>
          <input type="text" class="form-control" placeholder="کاربر یا نوع اشتراک" wire:model.live="search" />
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
              <th>نوع اشتراک</th>
              <th>درصد تخفیف</th>
              <th>اعتبار تا</th>
              <th>ایجاد شده</th>
              <th>عملیات</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($discounts as $d)
              <tr>
                <td>{{ $d->id }}</td>
                <td>{{ trim(($d->user?->first_name ?? '') . ' ' . ($d->user?->last_name ?? '')) ?: ($d->user?->name ?? '-') }}</td>
                <td>{{ $d->subscriptionType?->name }}</td>
                <td>{{ $d->discount_percentage }}%</td>
                <td>{{ $d->valid_until ? \Illuminate\Support\Carbon::parse($d->valid_until)->format('Y-m-d') : 'دائمی' }}</td>
                <td>{{ \Illuminate\Support\Carbon::parse($d->created_at)->format('Y-m-d') }}</td>
                <td>
                  <button class="btn btn-sm btn-light-danger"
                          onclick="confirm('آیا از حذف این تخفیف مطمئن هستید؟') || event.stopImmediatePropagation()"
                          wire:click="delete({{ $d->id }})"
                          wire:loading.attr="disabled">
                    حذف
                  </button>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center">تخفیفی یافت نشد.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{ $discounts->links() }}
    </div>
  </div>
</div>