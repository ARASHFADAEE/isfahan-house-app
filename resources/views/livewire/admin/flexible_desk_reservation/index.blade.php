<div>
  <div class="container-fluid" style="margin-top:40px ">
    <div class="row">
      <div class="col-12 d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">رزرو میزهای منعطف</h4>
      </div>
      <div class="col-12">
        @if (session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card mb-3">
          <div class="card-body">
            <div class="row g-3 align-items-end">
              <div class="col-md-3">
                <label class="form-label">فیلتر شعبه</label>
                <select class="form-select" wire:model.live="branch_id">
                  <option value="">همه شعبه‌ها</option>
                  @foreach($this->branches as $branch)
                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-3">
                <label class="form-label">وضعیت رزرو</label>
                <input type="text" class="form-control" placeholder="مثلاً pending / confirmed / canceled" wire:model.live="status" />
              </div>
              <div class="col-md-3">
                <label class="form-label">تاریخ رزرو</label>
                <input type="date" class="form-control" wire:model.live="reservation_date" />
              </div>
              <div class="col-md-3">
                <label class="form-label">جستجو کاربر/شعبه</label>
                <input type="text" class="form-control" placeholder="نام، ایمیل، تلفن یا شعبه" wire:model.live="search" />
              </div>
            </div>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>#</th>
                <th>کاربر</th>
                <th>شعبه</th>
                <th>تاریخ رزرو</th>
                <th>وضعیت</th>
                <th>عملیات</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($reservations as $reservation)
                <tr>
                  <td>{{ $reservation->id }}</td>
                  <td>{{ trim(($reservation->user?->first_name ?? '') . ' ' . ($reservation->user?->last_name ?? '')) ?: ($reservation->user?->name ?? '-') }}</td>
                  <td>{{ $reservation->branch?->name }}</td>
                  <td>{{ \Illuminate\Support\Carbon::parse($reservation->reservation_date)->format('Y-m-d') }}</td>
                  <td>{{ $reservation->status }}</td>
                  <td>
                    <button class="btn btn-sm btn-light-danger ms-1"
                            onclick="confirm('آیا از حذف این رزرو مطمئن هستید؟') || event.stopImmediatePropagation()"
                            wire:click="delete({{ $reservation->id }})">حذف</button>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="text-center">هیچ رزروی یافت نشد.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <div class="mt-3">
          {{ $reservations->links() }}
        </div>
      </div>
    </div>
  </div>
</div>