<div>
    <div class="d-flex align-items-center justify-content-between mb-3" style="margin-top:40px ">
        <h3 class="mb-0">مدیریت اشتراک‌ها</h3>
        <a href="{{ route('admin.subscriptions.create') }}" class="btn btn-primary">ایجاد اشتراک جدید</a>
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
                            <th>کاربر</th>
                            <th>نوع اشتراک</th>
                            <th>شعبه</th>
                            <th>شروع</th>
                            <th>پایان</th>
                            <th>وضعیت</th>
                            <th>مبلغ کل</th>
                            <th class="text-end">عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subscriptions as $subscription)
                            <tr>
                                <td>{{ $subscription->id }}</td>
                                <td>{{ $subscription->user?->name ?? '-' }}</td>
                                <td>{{ $subscription->subscriptionType?->name ?? '-' }}</td>
                                <td>{{ $subscription->branch?->name ?? '-' }}</td>
                                <td>{{ $subscription->start_datetime }}</td>
                                <td>{{ $subscription->end_datetime }}</td>
                                <td>{{ $subscription->status }}</td>
                                <td>{{ number_format($subscription->total_price) }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.subscriptions.edit', $subscription) }}" class="btn btn-sm btn-warning">ویرایش</a>
                                    <button class="btn btn-sm btn-danger" wire:click="delete({{ $subscription->id }})" onclick="return confirm('آیا از حذف این اشتراک مطمئن هستید؟')">حذف</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">هیچ اشتراکی ثبت نشده است.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $subscriptions->links() }}
    </div>
</div>
