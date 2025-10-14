<div>
    <div class="d-flex align-items-center justify-content-between mb-3" style="margin-top:40px ">
        <h3 class="mb-0">مدیریت انواع اشتراک</h3>
        <a href="{{ route('admin.subscription_types.create') }}" class="btn btn-primary">ایجاد نوع اشتراک</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>نام</th>
                            <th>قیمت</th>
                            <th>تعداد روزها</th>
                            <th>نیاز به تأیید مدیر</th>
                            <th class="text-end">عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($types as $type)
                            <tr>
                                <td>{{ $type->id }}</td>
                                <td>{{ $type->name }}</td>
                                <td>{{ number_format($type->price) }}</td>
                                <td>{{ $type->duration_days ?? '-' }}</td>
                                <td>{{ $type->requires_admin_approval ? 'بله' : 'خیر' }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.subscription_types.edit', $type) }}" class="btn btn-sm btn-warning">ویرایش</a>
                                    <button class="btn btn-sm btn-danger" wire:click="delete({{ $type->id }})" onclick="return confirm('حذف این نوع اشتراک؟')">حذف</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">هیچ نوع اشتراکی ثبت نشده است.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $types->links() }}
    </div>
</div>
