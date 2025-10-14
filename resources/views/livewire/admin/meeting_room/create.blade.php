<div>
    <div class="d-flex align-items-center justify-content-between mb-3" style="margin-top:40px ">
        <h3 class="mb-0">ایجاد اتاق جلسه</h3>
        <a href="{{ route('admin.meeting_rooms.index') }}" class="btn btn-secondary">بازگشت به لیست</a>
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
                    <label class="form-label">هزینه هر ساعت</label>
                    <input type="number" class="form-control" wire:model.defer="price_per_hour" />
                    @error('price_per_hour') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mt-3">
                <button class="btn btn-primary" wire:click="save">ثبت</button>
            </div>
        </div>
    </div>
</div>