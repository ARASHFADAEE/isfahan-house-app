<div>
    <div class="d-flex align-items-center justify-content-between mb-3" style="margin-top:40px ">
        <h3 class="mb-0">مدیریت اتاق‌های جلسات</h3>
        <a href="{{ route('admin.meeting_rooms.create') }}" class="btn btn-primary">ایجاد اتاق جلسه</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <label class="form-label">شعبه</label>
            <select class="form-select" wire:model="branch_id">
                <option value="">همه شعبه‌ها</option>
                @foreach($branches as $b)
                    <option value="{{ $b->id }}">{{ $b->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-9">
            <label class="form-label">جستجو</label>
            <input type="text" class="form-control" placeholder="شماره اتاق یا نام شعبه" wire:model.debounce.300ms="search" />
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>شعبه</th>
                            <th>شماره اتاق</th>
                            <th>هزینه هر ساعت</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rooms as $room)
                            <tr>
                                <td>{{ $room->id }}</td>
                                <td>{{ optional($room->branch)->name }}</td>
                                <td>{{ $room->room_number }}</td>
                                <td>{{ $room->price_per_hour ? number_format($room->price_per_hour) : '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">اتاقی یافت نشد.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $rooms->links() }}
    </div>
</div>