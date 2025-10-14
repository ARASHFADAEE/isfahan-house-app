<?php

namespace App\Livewire\Admin\User;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('مدیریت کاربران')]
class Index extends Component
{
    use WithPagination;

    public function delete(int $id): void
    {
        $user = User::findOrFail($id);
        $user->delete();
        session()->flash('success', 'کاربر با موفقیت حذف شد.');
        $this->resetPage();
    }

    public function render()
    {
        $users = User::latest()->paginate(10);
        return view('livewire.admin.user.index', compact('users'));
    }
}