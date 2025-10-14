<?php

namespace App\Livewire\Admin\Branch;

use App\Models\Branch;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('مدیریت شعبه‌ها')]
class Index extends Component
{
    use WithPagination;

    public function delete(int $id): void
    {
        $branch = Branch::findOrFail($id);
        $branch->delete();
        session()->flash('success', 'شعبه با موفقیت حذف شد.');
        $this->resetPage();
    }

    public function render()
    {
        $branches = Branch::latest()->paginate(10);
        return view('livewire.admin.branch.index', compact('branches'));
    }
}