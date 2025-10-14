<?php

namespace App\Livewire\Admin\SubscriptionType;

use App\Models\SubscriptionType;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('مدیریت انواع اشتراک')]
class Index extends Component
{
    use WithPagination;

    public function delete(int $id): void
    {
        $type = SubscriptionType::findOrFail($id);
        $type->delete();
        session()->flash('success', 'نوع اشتراک با موفقیت حذف شد.');
        $this->resetPage();
    }

    public function render()
    {
        $types = SubscriptionType::latest()->paginate(10);
        return view('livewire.admin.subscription_type.index', compact('types'));
    }
}