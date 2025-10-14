<?php

namespace App\Livewire\Admin\Discount;

use App\Models\Discount;
use App\Models\SubscriptionType;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('لیست تخفیف‌ها')]
class Index extends Component
{
    use WithPagination;

    public ?int $subscription_type_id = null;
    public string $search = '';
    public $subscriptionTypes = [];

    public function mount(): void
    {
        $this->subscriptionTypes = SubscriptionType::orderBy('name')->get();
    }

    public function updatedSubscriptionTypeId(): void { $this->resetPage(); }
    public function updatedSearch(): void { $this->resetPage(); }

    public function render()
    {
        $query = Discount::query()->with(['user','subscriptionType']);
        if ($this->subscription_type_id) {
            $query->where('subscription_type_id', $this->subscription_type_id);
        }
        if (trim($this->search) !== '') {
            $like = '%' . trim($this->search) . '%';
            $query->where(function($q) use ($like){
                $q->whereHas('user', function($uq) use ($like){
                    $uq->where('name','like',$like)
                       ->orWhere('email','like',$like)
                       ->orWhere('phone','like',$like)
                       ->orWhere('first_name','like',$like)
                       ->orWhere('last_name','like',$like);
                })
                ->orWhereHas('subscriptionType', fn($st) => $st->where('name','like',$like));
            });
        }

        $discounts = $query->orderBy('id','desc')->paginate(10);

        return view('livewire.admin.discount.index', [
            'discounts' => $discounts,
        ]);
    }

    public function delete(int $id): void
    {
        $discount = Discount::find($id);
        if (!$discount) {
            return;
        }
        $discount->delete();
        session()->flash('success', 'تخفیف با موفقیت حذف شد.');
        $this->resetPage();
    }
}