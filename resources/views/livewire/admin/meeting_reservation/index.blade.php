<div>
    <div class="d-flex align-items-center justify-content-between mb-3" style="margin-top:40px">
        <h3 class="mb-0">لیست رزروهای اتاق جلسات</h3>
        <a href="{{ route('admin.meeting_reservations.create') }}" class="btn btn-primary">ایجاد رزرو جدید</a>
    </div>

    <div class="card">
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>اتاق</th>
                            <th>شعبه</th>
                            <th>کاربر</th>
                            <th>تاریخ (میلادی)</th>
                            <th>مدت (ساعت)</th>
                            <th>ساعت شروع</th>
                            <th>ساعت پایان</th>
                            <th>وضعیت</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reservations as $res)
                            @php
                                $start = \Illuminate\Support\Carbon::parse($res->reservation_date);
                                $end = (clone $start)->addHours($res->duration_hours);
                            @endphp
                            <tr>
                                <td>{{ $res->id }}</td>
                                <td>اتاق {{ optional($res->meetingRoom)->room_number }}</td>
                                <td>{{ optional(optional($res->meetingRoom)->branch)->name }}</td>
                                <td>{{ optional($res->user)->name }}</td>
                                <td>{{ $start->format('Y-m-d') }}</td>
                                <td>{{ $res->duration_hours }}</td>
                                <td>{{ $start->format('H:i') }}</td>
                                <td>{{ $end->format('H:i') }}</td>
                                <td>{{ $res->status }}</td>
                                <td>
                                    <button class="btn btn-sm btn-danger" wire:click="delete({{ $res->id }})" wire:loading.attr="disabled" onclick="return confirm('آیا از حذف این رزرو مطمئن هستید؟')">حذف</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-muted">رزروی یافت نشد.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $reservations->links() }}
        </div>
    </div>
</div>