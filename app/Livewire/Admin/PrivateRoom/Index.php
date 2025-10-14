<?php

namespace App\Livewire\Admin\PrivateRoom;

use App\Models\PrivateRoom;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('مدیریت اتاق‌های اختصاصی')]
class Index extends Component
{
    use WithPagination;

    public ?int $branch_id = null;
    public ?string $status = null;
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

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        PrivateRoom::where('id', $id)->delete();
        session()->flash('success', 'اتاق اختصاصی حذف شد.');
        $this->resetPage();
    }

    public function render()
    {
        $query = PrivateRoom::query()->with(['branch', 'user']);

        if ($this->branch_id) {
            $query->where('branch_id', $this->branch_id);
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if (trim($this->search) !== '') {
            $like = '%' . trim($this->search) . '%';
            $query->where(function($q) use ($like) {
                $q->where('room_number', 'like', $like)
                  ->orWhereHas('user', function($uq) use ($like) {
                      $uq->where('name', 'like', $like)
                         ->orWhere('email', 'like', $like)
                         ->orWhere('phone', 'like', $like);
                  })
                  ->orWhereHas('branch', function($bq) use ($like) {
                      $bq->where('name', 'like', $like);
                  });
            });
        }

        $rooms = $query->orderBy('id', 'desc')->paginate(10);

        return view('livewire.admin.private_room.index', [
            'rooms' => $rooms,
        ]);
    }
}