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
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>#</th>
                <th>شعبه</th>
                <th>شماره میز</th>
                <th>وضعیت</th>
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
                    <a href="{{ route('admin.desks.edit', $desk->id) }}" class="btn btn-sm btn-light-primary">ویرایش</a>
                    <button class="btn btn-sm btn-light-danger ms-1"
                            onclick="confirm('آیا از حذف این میز مطمئن هستید؟') || event.stopImmediatePropagation()"
                            wire:click="delete({{ $desk->id }})">حذف</button>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center">هیچ میزی یافت نشد.</td>
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