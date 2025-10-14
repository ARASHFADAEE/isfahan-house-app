<div>
  <div class="container-fluid" style="margin-top:40px ">
    <div class="row">
      <div class="col-12 d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">مدیریت شعبه‌ها</h4>
        <a href="/branches/create" class="btn btn-primary">افزودن شعبه</a>
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
                <th>نام</th>
                <th>آدرس</th>
                <th>ظرفیت میز انعطاف‌پذیر</th>
                <th>عملیات</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($branches as $branch)
                <tr>
                  <td>{{ $branch->id }}</td>
                  <td>{{ $branch->name }}</td>
                  <td>{{ $branch->address }}</td>
                  <td>{{ $branch->flexible_desk_capacity }}</td>
                  <td>
                    <a href="/branches/{{ $branch->id }}/edit" class="btn btn-sm btn-light-primary">ویرایش</a>
                    <button class="btn btn-sm btn-light-danger ms-1"
                            onclick="confirm('آیا از حذف این شعبه مطمئن هستید؟') || event.stopImmediatePropagation()"
                            wire:click="delete({{ $branch->id }})">حذف</button>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center">هیچ شعبه‌ای یافت نشد.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <div class="mt-3">
          {{ $branches->links() }}
        </div>
      </div>
    </div>
  </div>
</div>
