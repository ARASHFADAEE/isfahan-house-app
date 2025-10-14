<?php

namespace App\Livewire\Admin\Subscription;

use App\Models\Subscription;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('مدیریت اشتراک‌ها')]
class Index extends Component
{
    use WithPagination;

    public function delete(int $id): void
    {
        $subscription = Subscription::findOrFail($id);
        $subscription->delete();
        session()->flash('success', 'اشتراک با موفقیت حذف شد.');
        $this->resetPage();
    }

    public function render()
    {
        $subscriptions = Subscription::with(['user', 'subscriptionType', 'branch'])->latest()->paginate(10);
        return view('livewire.admin.subscription.index', compact('subscriptions'));
    }
}