<?php

namespace App\Livewire\Admin\Locker;

use App\Models\Locker;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('مدیریت کمدها')]
class Index extends Component
{
    use WithPagination;

    public ?int $branch_id = null;
    public ?string $status = null; // 'available', 'reserved', or null
    public string $search = '';
    public $branches = [];

    public function mount(): void
    {
        $this->branches = \App\Models\Branch::orderBy('name')->get();
    }

    public function delete(int $id): void
    {
        $locker = Locker::findOrFail($id);
        $locker->delete();
        session()->flash('success', 'کمد با موفقیت حذف شد.');
        $this->resetPage();
    }

    public function render()
    {
        $query = Locker::with(['branch', 'user'])->latest();
        if ($this->branch_id) {
            $query->where('branch_id', $this->branch_id);
        }
        if (in_array($this->status, ['available', 'reserved'], true)) {
            $query->where('status', $this->status);
        }
        $term = trim($this->search);
        if ($term !== '') {
            $like = '%' . $term . '%';
            $query->where(function ($q) use ($like) {
                $q->where('locker_number', 'like', $like)
                    ->orWhereHas('user', function ($uq) use ($like) {
                        $uq->where('first_name', 'like', $like)
                            ->orWhere('last_name', 'like', $like)
                            ->orWhere('name', 'like', $like)
                            ->orWhere('email', 'like', $like)
                            ->orWhere('phone', 'like', $like);
                    })
                    ->orWhereHas('branch', function ($bq) use ($like) {
                        $bq->where('name', 'like', $like);
                    });
            });
        }
        $lockers = $query->paginate(10);
        return view('livewire.admin.locker.index', compact('lockers'));
    }

    public function updatedBranchId($value): void
    {
        $this->branch_id = $value ? (int) $value : null;
        $this->resetPage();
    }

    public function updatedStatus($value): void
    {
        $this->status = $value ?: null;
        $this->resetPage();
    }

    public function updatedSearch($value): void
    {
        $this->search = $value ?? '';
        $this->resetPage();
    }
}