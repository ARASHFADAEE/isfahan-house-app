<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">فهرست کمدها</h5>
                    <a href="{{ route('admin.lockers.create') }}" class="btn btn-primary">ایجاد کمد جدید</a>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-3">
                            <label class="form-label">شعبه</label>
                            <select class="form-select" wire:model.live="branch_id">
                                <option value="">همه شعب</option>
                                @foreach($this->branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">وضعیت</label>
                            <select class="form-select" wire:model.live="status">
                                <option value="">همه وضعیت‌ها</option>
                                <option value="available">آزاد</option>
                                <option value="reserved">رزرو شده</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">جستجو</label>
                            <input type="text" class="form-control" placeholder="شماره کمد، نام/ایمیل/تلفن کاربر، نام شعبه" wire:model.live="search" />
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
                                    <th>شعبه</th>
                                    <th>شماره کمد</th>
                                    <th>وضعیت</th>
                                    <th>رزرو برای</th>
                                    <th>عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($lockers as $locker)
                                    <tr>
                                        <td>{{ $locker->id }}</td>
                                        <td>{{ optional($locker->branch)->name }}</td>
                                        <td>{{ $locker->locker_number }}</td>
                                        <td>
                                            @if($locker->status === 'reserved')
                                                <span class="badge bg-warning text-dark">رزرو شده</span>
                                            @else
                                                <span class="badge bg-success">آزاد</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($locker->user)
                                                {{ $locker->user->name ?? ($locker->user->first_name.' '.$locker->user->last_name) }}
                                                <div class="text-muted" style="font-size: 12px;">{{ $locker->user->email }}</div>
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.lockers.edit', $locker) }}" class="btn btn-sm btn-secondary">ویرایش</a>
                                            <button class="btn btn-sm btn-danger" wire:click="delete({{ $locker->id }})" onclick="return confirm('آیا از حذف کمد مطمئن هستید؟')">حذف</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">هیچ کمدی یافت نشد.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div>
                        {{ $lockers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>