<div>
    <div class="d-flex align-items-center justify-content-between mb-3" style="margin-top:40px ">
        <h3 class="mb-0">ایجاد اتاق اختصاصی</h3>
        <a href="{{ route('admin.private_rooms.index') }}" class="btn btn-secondary">بازگشت به لیست</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">شعبه</label>
                    <select class="form-select" wire:model="branch_id">
                        <option value="">انتخاب کنید</option>
                        @foreach($branches as $b)
                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                        @endforeach
                    </select>
                    @error('branch_id') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">شماره اتاق</label>
                    <input type="text" class="form-control" wire:model.defer="room_number" />
                    @error('room_number') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">وضعیت</label>
                    <select class="form-select" wire:model="status">
                        <option value="available">آزاد</option>
                        <option value="reserved">رزرو</option>
                    </select>
                    @error('status') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>
            </div>

            @if($status === 'reserved')
                <hr>
                <div class="mb-3">
                    <label class="form-label">نحوه اختصاص</label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="assignBySubscription" wire:model="assignment" value="subscription">
                            <label class="form-check-label" for="assignBySubscription">از طریق اشتراک</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="assignByUser" wire:model="assignment" value="user">
                            <label class="form-check-label" for="assignByUser">مستقیم به کاربر</label>
                        </div>
                    </div>
                </div>

                @if($assignment === 'subscription')
                    <div class="mb-3">
                        <label class="form-label">انتخاب اشتراک (در حال بررسی یا فعال)</label>
                        <select class="form-select" wire:model="subscription_id">
                            <option value="">انتخاب کنید</option>
                            @foreach($this->subscriptions as $s)
                                <option value="{{ $s->id }}">#{{ $s->id }} - {{ optional($s->user)->name ?? ($s->user->first_name.' '.$s->user->last_name) }} - {{ optional($s->branch)->name }}</option>
                            @endforeach
                        </select>
                        @error('subscription_id') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">کاربر رزرو کننده</label>
                        <input type="text" class="form-control" value="{{ optional(\App\Models\User::find($user_id))->name }}" disabled />
                    </div>
                @else
                    <div class="mb-3">
                        <label class="form-label">جستجوی کاربر</label>
                        <input type="text" class="form-control" placeholder="نام، ایمیل یا تلفن" wire:model.debounce.300ms="user_search" />
                        @error('user_id') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                    @if(!empty($user_results))
                        <ul class="list-group mb-3">
                            @foreach($user_results as $u)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        {{ $u->name ?? ($u->first_name.' '.$u->last_name) }}
                                        <div class="text-muted" style="font-size:12px">{{ $u->email }} | {{ $u->phone }}</div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-primary" wire:click="chooseUser({{ $u->id }})">انتخاب</button>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                    @if($user_id)
                        <div class="alert alert-info">کاربر انتخاب شده: {{ optional(\App\Models\User::find($user_id))->name }}</div>
                    @endif
                @endif
            @endif

            <div class="mt-3">
                <button class="btn btn-primary" wire:click="save">ثبت</button>
            </div>
        </div>
    </div>
</div>