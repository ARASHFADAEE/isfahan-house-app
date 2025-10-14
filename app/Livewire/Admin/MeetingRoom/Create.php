<?php

namespace App\Livewire\Admin\MeetingRoom;

use App\Models\MeetingRoom;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('ایجاد اتاق جلسه')]
class Create extends Component
{
    public ?int $branch_id = null;
    public string $room_number = '';
    public $price_per_hour = '';

    public $branches = [];

    public function mount(): void
    {
        $this->branches = \App\Models\Branch::orderBy('name')->get();
    }

    protected function rules(): array
    {
        return [
            'branch_id' => ['required','integer', Rule::exists('branches','id')],
            'room_number' => [
                'required','string','max:50',
                Rule::unique('meeting_rooms','room_number')->where(fn($q) => $q->where('branch_id',$this->branch_id))
            ],
            'price_per_hour' => ['nullable','numeric','min:0'],
        ];
    }

    public function save(): void
    {
        $this->validate();
        MeetingRoom::create([
            'branch_id' => $this->branch_id,
            'room_number' => $this->room_number,
            'price_per_hour' => $this->price_per_hour ?: null,
        ]);
        session()->flash('success', 'اتاق جلسه با موفقیت ایجاد شد.');
        redirect()->route('admin.meeting_rooms.index');
    }

    public function render()
    {
        return view('livewire.admin.meeting_room.create');
    }
}