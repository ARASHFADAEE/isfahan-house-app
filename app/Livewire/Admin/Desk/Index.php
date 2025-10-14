<?php

namespace App\Livewire\Admin\Desk;

use App\Models\Desk;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('مدیریت میزها')]
class Index extends Component
{
    use WithPagination;

    public function delete(int $id): void
    {
        $desk = Desk::findOrFail($id);
        $desk->delete();
        session()->flash('success', 'میز با موفقیت حذف شد.');
        $this->resetPage();
    }

    public function render()
    {
        $desks = Desk::with('branch')->latest()->paginate(10);
        return view('livewire.admin.desk.index', compact('desks'));
    }
}