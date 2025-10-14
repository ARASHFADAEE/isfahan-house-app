<?php

namespace App\Livewire\Admin\FlexibleDeskReservation;

use App\Models\FlexibleDeskReservation;
use App\Models\Branch;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('رزرو میزهای منعطف')]
class Index extends Component
{
    use WithPagination;

    public ?int $branch_id = null;
    public ?string $status = null; // free text filter, e.g., pending/confirmed/canceled
    public ?string $reservation_date = null; // YYYY-MM-DD
    public string $search = '';
    public $branches = [];

    public function mount(): void
    {
        $this->branches = Branch::orderBy('name')->get();
    }

    public function delete(int $id): void
    {
        $reservation = FlexibleDeskReservation::findOrFail($id);
        $reservation->delete();
        session()->flash('success', 'رزرو با موفقیت حذف شد.');
        $this->resetPage();
    }

    public function render()
    {
        $query = FlexibleDeskReservation::with(['user', 'branch'])->latest();

        if ($this->branch_id) {
            $query->where('branch_id', $this->branch_id);
        }
        if ($this->status) {
            $query->where('status', $this->status);
        }
        if ($this->reservation_date) {
            $query->whereDate('reservation_date', $this->reservation_date);
        }

        $term = trim($this->search);
        if ($term !== '') {
            $like = '%' . $term . '%';
            $query->where(function ($q) use ($like) {
                $q->orWhereHas('user', function ($uq) use ($like) {
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

        $reservations = $query->paginate(10);
        return view('livewire.admin.flexible_desk_reservation.index', compact('reservations'));
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

    public function updatedReservationDate($value): void
    {
        $this->reservation_date = $value ?: null;
        $this->resetPage();
    }

    public function updatedSearch($value): void
    {
        $this->search = $value ?? '';
        $this->resetPage();
    }
}