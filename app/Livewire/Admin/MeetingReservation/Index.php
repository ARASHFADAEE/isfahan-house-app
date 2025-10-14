<?php

namespace App\Livewire\Admin\MeetingReservation;

use App\Models\MeetingReservation;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithPagination;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('لیست رزروهای اتاق جلسات')]
class Index extends Component
{
    use WithPagination;

    public function delete(int $id): void
    {
        $reservation = MeetingReservation::find($id);
        if (!$reservation) { return; }
        $reservation->delete();
        // If last item on page was deleted, ensure pagination resets appropriately
        $this->resetPage();
        session()->flash('success', 'رزرو حذف شد.');
    }

    public function render()
    {
        $reservations = MeetingReservation::with(['meetingRoom.branch','user'])
            ->orderBy('id','desc')
            ->paginate(15);

        return view('livewire.admin.meeting_reservation.index', [
            'reservations' => $reservations,
        ]);
    }
}