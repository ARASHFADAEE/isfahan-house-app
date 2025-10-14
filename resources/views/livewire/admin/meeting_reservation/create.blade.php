<div>
    <div class="d-flex align-items-center justify-content-between mb-3" style="margin-top:40px ">
        <h3 class="mb-0">ایجاد رزرو اتاق جلسه</h3>
        <a href="{{ route('admin.meeting_rooms.index') }}" class="btn btn-secondary">بازگشت به اتاق‌ها</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">اتاق جلسه</label>
                    <select class="form-select" wire:model.live="meeting_room_id">
                        <option value="">انتخاب کنید</option>
                        @foreach($meetingRooms as $room)
                            <option value="{{ $room->id }}">{{ optional($room->branch)->name }} - اتاق {{ $room->room_number }}</option>
                        @endforeach
                    </select>
                    @error('meeting_room_id') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">کاربر</label>
                    <input type="text" class="form-control" placeholder="جستجوی زنده کاربر" wire:model.live.debounce.300ms="user_search" {{ empty($meeting_room_id) ? 'disabled' : '' }} />
                    @if (!empty($user_results))
                        <div class="border rounded mt-1" style="max-height:180px; overflow:auto;">
                            @foreach($user_results as $u)
                                <div class="d-flex align-items-center justify-content-between p-2 border-bottom">
                                    <div>{{ $u->name ?? ($u->first_name.' '.$u->last_name) }} - {{ $u->phone }}</div>
                                    <button class="btn btn-sm btn-outline-primary" wire:click="chooseUser({{ $u->id }})">انتخاب</button>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    @if ($user_id)
                        <div class="small text-success mt-1">کاربر انتخاب‌شده: {{ optional(\App\Models\User::find($user_id))->name }}</div>
                    @endif
                    @error('user_id') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">مدت زمان</label>
                    <select class="form-select" wire:model.live="duration_hours" {{ empty($user_id) ? 'disabled' : '' }}>
                        <option value="">انتخاب کنید</option>
                        <option value="1">۱ ساعت</option>
                        <option value="2">۲ ساعت</option>
                        <option value="3">۳ ساعت</option>
                    </select>
                    @error('duration_hours') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row g-3 mt-3">
                <div class="col-md-6">
                    <label class="form-label">تاریخ رزرو (شمسی)</label>
                    <input type="text" class="form-control" id="reservationDate" data-jdp data-jdp-min-date="today" placeholder="انتخاب تاریخ" wire:model.live="reservation_date" {{ empty($duration_hours) ? 'disabled' : '' }} />
                    @error('reservation_date_gregorian') <div class="text-danger small">{{ $message }}</div> @enderror
                    <div class="form-text">تاریخ انتخاب‌شده تبدیل به میلادی شده و در سرور ذخیره می‌شود.</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">ساعت‌های آزاد</label>
                    <div class="d-flex flex-wrap gap-2" wire:poll.5s="refreshAvailableTimes">
                        @forelse($available_times as $time)
                            <button class="btn btn-sm {{ $selected_time === $time ? 'btn-primary' : 'btn-outline-secondary' }}" wire:click="$set('selected_time','{{ $time }}')">{{ $time }}</button>
                        @empty
                            <div class="text-muted">ابتدا اتاق، کاربر، مدت زمان و تاریخ را انتخاب کنید.</div>
                        @endforelse
                    </div>
                    <div class="mt-2 text-muted" wire:loading.delay wire:target="meeting_room_id, reservation_date, duration_hours">در حال محاسبه ساعت‌های آزاد...</div>
                    @if ($selected_time)
                        <div class="small text-success mt-2">ساعت انتخاب‌شده: {{ $selected_time }}</div>
                    @endif
                    @error('selected_time') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mt-3">
                @php $canSave = $meeting_room_id && $user_id && $duration_hours && $reservation_date_gregorian && $selected_time; @endphp
                <button class="btn btn-primary" wire:click="save" {{ $canSave ? '' : 'disabled' }} wire:loading.attr="disabled">ثبت رزرو</button>
                @unless ($canSave)
                    <div class="small text-muted mt-2">برای فعال شدن دکمه ثبت، همه مراحل را تکمیل کنید.</div>
                @endunless
            </div>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/@majidh1/jalalidatepicker/dist/jalalidatepicker.min.css">
    @endpush
    @push('scripts')
        <script type="text/javascript" src="https://unpkg.com/@majidh1/jalalidatepicker/dist/jalalidatepicker.min.js"></script>
        <script>
            document.addEventListener('livewire:init', function(){
                if (window.jalaliDatepicker) {
                    window.jalaliDatepicker.startWatch();
                }
            });
            document.addEventListener('livewire:navigated', function(){
                if (window.jalaliDatepicker) {
                    window.jalaliDatepicker.startWatch();
                }
            });
        </script>
    @endpush
</div>