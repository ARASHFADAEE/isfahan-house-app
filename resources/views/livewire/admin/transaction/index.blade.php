<div class="container py-4">
  <div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">لیست تراکنش‌ها</h5>
      <a class="btn btn-primary" href="{{ route('admin.transactions.create') }}">ایجاد تراکنش</a>
    </div>
    <div class="card-body">
      <div class="row g-3 mb-3">
        <div class="col-md-3">
          <label class="form-label">شعبه</label>
          <select class="form-select" wire:model.live="branch_id">
            <option value="">همه</option>
            @foreach ($branches as $branch)
              <option value="{{ $branch->id }}">{{ $branch->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">وضعیت</label>
          <select class="form-select" wire:model.live="status">
            <option value="">همه</option>
            <option value="pending">در انتظار</option>
            <option value="completed">تکمیل‌شده</option>
            <option value="failed">ناموفق</option>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">روش پرداخت</label>
          <select class="form-select" wire:model.live="payment_method">
            <option value="">همه</option>
            <option value="online">آنلاین</option>
            <option value="card">کارت</option>
            <option value="cash">نقدی</option>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">جستجو</label>
          <input type="text" class="form-control" placeholder="کد تراکنش، نام/ایمیل/تلفن" wire:model.live="search" />
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
              <th>شعبه</th>
              <th>مبلغ</th>
              <th>روش پرداخت</th>
              <th>کد تراکنش</th>
              <th>وضعیت</th>
              <th>تاریخ</th>
              <th>عملیات</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($transactions as $trx)
              <tr>
                <td>{{ $trx->id }}</td>
                <td>{{ trim(($trx->user?->first_name ?? '') . ' ' . ($trx->user?->last_name ?? '')) ?: ($trx->user?->name ?? '-') }}</td>
                <td>{{ $trx->branch?->name }}</td>
                <td>{{ number_format($trx->amount, 0) }}</td>
                <td>{{ $trx->payment_method }}</td>
                <td><code>{{ $trx->transaction_code }}</code></td>
                <td>{{ $trx->status }}</td>
                <td>{{ \Illuminate\Support\Carbon::parse($trx->created_at)->format('Y-m-d H:i') }}</td>
                <td>
                  <button class="btn btn-sm btn-light-danger"
                          onclick="confirm('آیا از حذف این تراکنش مطمئن هستید؟') || event.stopImmediatePropagation()"
                          wire:click="delete({{ $trx->id }})"
                          wire:loading.attr="disabled">
                    حذف
                  </button>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="9" class="text-center">تراکنشی یافت نشد.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{ $transactions->links() }}
    </div>
  </div>
</div>