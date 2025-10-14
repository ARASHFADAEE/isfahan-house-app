<?php

namespace App\Livewire\Admin\MeetingRoom;

use App\Models\MeetingRoom;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('مدیریت اتاق‌های جلسات')]
class Index extends Component
{
    use WithPagination;

    public ?int $branch_id = null;
    public string $search = '';
    public $branches = [];

    public function mount(): void
    {
        $this->branches = \App\Models\Branch::orderBy('name')->get();
    }

    public function updatedBranchId(): void
    {
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = MeetingRoom::query()->with('branch');
        if ($this->branch_id) {
            $query->where('branch_id', $this->branch_id);
        }
        if (trim($this->search) !== '') {
            $like = '%' . trim($this->search) . '%';
            $query->where(function($q) use ($like) {
                $q->where('room_number', 'like', $like)
                  ->orWhereHas('branch', fn($b) => $b->where('name', 'like', $like));
            });
        }
        $rooms = $query->orderBy('id', 'desc')->paginate(10);

        return view('livewire.admin.meeting_room.index', [
            'rooms' => $rooms,
        ]);
    }
}