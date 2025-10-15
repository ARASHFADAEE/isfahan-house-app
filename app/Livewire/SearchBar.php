<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Branch;
use Livewire\Component;

class SearchBar extends Component
{
    public string $query = '';

    public array $shortcuts = [];
    public array $users = [];
    public array $branches = [];

    public function mount(): void
    {
        $this->shortcuts = [
            [
                'label' => 'تنظیمات سیستم',
                'url' => route('admin.settings.index'),
                'icon' => 'ph-duotone ph-gear',
            ],
            [
                'label' => 'مدیریت کاربران',
                'url' => route('admin.users.index'),
                'icon' => 'ph-duotone ph-users-three',
            ],
            [
                'label' => 'مدیریت شعبه‌ها',
                'url' => route('admin.branches.index'),
                'icon' => 'ph-duotone ph-buildings',
            ],
        ];
    }

    public function updatingQuery(): void
    {
        // Reset results while typing
        $this->users = [];
        $this->branches = [];
    }

    public function updatedQuery(): void
    {
        $q = trim($this->query);
        if (mb_strlen($q) < 2) {
            $this->users = [];
            $this->branches = [];
            return;
        }

        $like = '%' . $q . '%';

        $users = User::query()
            ->where(function($qq) use ($like) {
                $qq->where('name', 'like', $like)
                    ->orWhere('first_name', 'like', $like)
                    ->orWhere('last_name', 'like', $like)
                    ->orWhere('email', 'like', $like)
                    ->orWhere('phone', 'like', $like);
            })
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get(['id','name','first_name','last_name','email']);

        $this->users = $users->map(function ($u) {
            $full = trim(($u->first_name ?: '') . ' ' . ($u->last_name ?: '')) ?: ($u->name ?: '-');
            return [
                'label' => $full . ' (' . ($u->email ?: '-') . ')',
                'url' => route('admin.users.index', ['search' => $this->query]),
                'id' => $u->id,
            ];
        })->toArray();

        $branches = Branch::query()
            ->where('name', 'like', $like)
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get(['id','name']);

        $this->branches = $branches->map(function ($b) {
            return [
                'label' => ($b->name ?: '-') . ' #' . $b->id,
                'url' => route('admin.branches.edit', ['branch' => $b->id]),
                'id' => $b->id,
            ];
        })->toArray();
    }

    public function render()
    {
        return view('livewire.search-bar');
    }
}