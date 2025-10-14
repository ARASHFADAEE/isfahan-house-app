<div>
  <div class="container-fluid" style="margin-top:40px ">
    <div class="row">
      <div class="col-12 d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">مدیریت میزها</h4>
        <a href="{{ route('admin.desks.create') }}" class="btn btn-primary">افزودن میز</a>
      </div>
      <div class="col-12">
        @if (session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="card mb-3">
          <div class="card-body">
            <div class="row g-3 align-items-end">
              <div class="col-md-4">
                <label class="form-label">فیلتر شعبه</label>
                <select class="form-select" wire:model.live="branch_id">
                  <option value="">همه شعبه‌ها</option>
                  @foreach($this->branches as $branch)
                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-4">
                <label class="form-label">فیلتر وضعیت</label>
                <select class="form-select" wire:model.live="status">
                  <option value="">همه وضعیت‌ها</option>
                  <option value="available">آزاد</option>
                  <option value="reserved">رزرو شده</option>
                </select>
              </div>
              <div class="col-md-4">
                <label class="form-label">جستجو</label>
                <input type="text" class="form-control" placeholder="شماره میز / نام کاربر / شعبه" wire:model.live="search" />
              </div>
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>#</th>
                <th>شعبه</th>
                <th>شماره میز</th>
                <th>وضعیت</th>
                <th>رزرو برای</th>
                <th>عملیات</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($desks as $desk)
                <tr>
                  <td>{{ $desk->id }}</td>
                  <td>{{ $desk->branch?->name }}</td>
                  <td>{{ $desk->desk_number }}</td>
                  <td>{{ $desk->status === 'available' ? 'آزاد' : 'رزرو شده' }}</td>
                  <td>
                    @if($desk->status === 'reserved')
                      {{ trim(($desk->user?->first_name ?? '') . ' ' . ($desk->user?->last_name ?? '')) ?: ($desk->user?->name ?? '-') }}
                    @else
                      -
                    @endif
                  </td>
                  <td>
                    <a href="{{ route('admin.desks.edit', $desk->id) }}" class="btn btn-sm btn-light-primary">ویرایش</a>
                    <button class="btn btn-sm btn-light-danger ms-1"
                            onclick="confirm('آیا از حذف این میز مطمئن هستید؟') || event.stopImmediatePropagation()"
                            wire:click="delete({{ $desk->id }})">حذف</button>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="text-center">هیچ میزی یافت نشد.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <div class="mt-3">
          {{ $desks->links() }}
        </div>
      </div>
    </div>
  </div>
</div>