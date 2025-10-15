<div>
  <div class="container-fluid" style="margin-top:40px ">
    <div class="row">
      <div class="col-12 d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">مدیریت کاربران</h4>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">افزودن کاربر</a>
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
                <th>نام کامل</th>
                <th>ایمیل</th>
                <th>شماره تماس</th>
                <th>نقش</th>
                <th>عملیات</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($users as $user)
                <tr>
                  <td>{{ $user->id }}</td>
                  <td>{{ trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: $user->name }}</td>
                  <td>{{ $user->email }}</td>
                  <td>{{ $user->phone ?? '-' }}</td>
                  <td>{{ $user->role }}</td>
                  <td>
                    <a href="{{ route('admin.users.edit', ['user' => $user->id]) }}" class="btn btn-sm btn-light-primary">ویرایش</a>
                    <button class="btn btn-sm btn-light-danger ms-1"
                            onclick="confirm('آیا از حذف این کاربر مطمئن هستید؟') || event.stopImmediatePropagation()"
                            wire:click="delete({{ $user->id }})">حذف</button>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="text-center">هیچ کاربری یافت نشد.</td>
                </tr>
              @endforelse
            </tbody>

          </table>
          {{ $users->links() }}

        </div>

      </div>
    </div>
  </div>
</div>
