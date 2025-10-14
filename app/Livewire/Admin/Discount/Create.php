<?php

namespace App\Livewire\Admin\Discount;

use App\Models\Discount;
use App\Models\SubscriptionType;
use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('ایجاد تخفیف')]
class Create extends Component
{
    public ?int $user_id = null; // permanent per-user discount when valid_until is null
    public string $user_search = '';
    public array $user_results = [];

    public ?int $subscription_type_id = null;
    public $discount_percentage = '';
    public ?string $valid_until = null; // optional; null means permanent

    public $subscriptionTypes = [];

    public function mount(): void
    {
        $this->subscriptionTypes = SubscriptionType::orderBy('name')->get();
    }

    protected function rules(): array
    {
        return [
            'user_id' => ['required','integer', Rule::exists('users','id')],
            'subscription_type_id' => ['required','integer', Rule::exists('subscription_types','id')],
            'discount_percentage' => ['required','numeric','min:0','max:100'],
            'valid_until' => ['nullable','date'],
        ];
    }

    public function updatedUserSearch(): void
    {
        $term = trim($this->user_search);
        if ($term === '') { $this->user_results = []; return; }
        $like = "%$term%";
        $this->user_results = User::query()
            ->where(function($q) use ($like){
                $q->where('name','like',$like)
                  ->orWhere('email','like',$like)
                  ->orWhere('phone','like',$like)
                  ->orWhere('first_name','like',$like)
                  ->orWhere('last_name','like',$like);
            })
            ->orderBy('id','desc')
            ->limit(10)
            ->get()
            ->map(function($u){
                return [
                    'id' => $u->id,
                    'label' => trim(($u->first_name.' '.$u->last_name)) ?: ($u->name ?? '-') ,
                    'email' => $u->email,
                    'phone' => $u->phone,
                ];
            })
            ->toArray();
    }

    public function selectUser(int $id): void
    {
        $this->user_id = $id;
        $this->user_search = '';
        $this->user_results = [];
    }

    public function save(): void
    {
        $this->validate();

        Discount::create([
            'user_id' => $this->user_id,
            'subscription_type_id' => $this->subscription_type_id,
            'discount_percentage' => $this->discount_percentage,
            'valid_until' => $this->valid_until ?: null,
        ]);

        session()->flash('success', 'تخفیف دائمی برای کاربر ثبت شد.');
        redirect()->route('admin.discounts.index');
    }

    public function render()
    {
        return view('livewire.admin.discount.create');
    }
}