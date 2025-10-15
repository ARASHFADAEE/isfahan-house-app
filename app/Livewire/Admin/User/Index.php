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

    public string $search = '';
    public ?string $role = null; // 'user', 'admin', 'ceo' or null

    protected $queryString = [
        'search' => ['except' => ''],
        'role' => ['except' => null],
    ];

    public function delete(int $id): void
    {
        $user = User::findOrFail($id);
        $user->delete();
        session()->flash('success', 'کاربر با موفقیت حذف شد.');
        $this->resetPage();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatedRole(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search !== '', function ($q) {
                $like = '%' . trim($this->search) . '%';
                $q->where(function ($qq) use ($like) {
                    $qq->where('name', 'like', $like)
                        ->orWhere('first_name', 'like', $like)
                        ->orWhere('last_name', 'like', $like)
                        ->orWhere('email', 'like', $like)
                        ->orWhere('phone', 'like', $like);
                });
            })
            ->when(!empty($this->role), function ($q) {
                $q->where('role', $this->role);
            })
            ->latest()
            ->paginate(15);
        return view('livewire.admin.user.index', compact('users'));
    }
}
