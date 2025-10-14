<?php

namespace App\Livewire\Admin\Desk;

use App\Models\Desk;
use App\Models\Branch;
use App\Models\Subscription;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('ویرایش میز')]
class Edit extends Component
{
    public Desk $desk;

    public ?int $branch_id = null;
    public string $desk_number = '';
    public string $status = 'available';
    public ?int $subscription_id = null;
    public ?int $user_id = null;

    public $branches = [];
    public $subscriptions = [];

    public function mount(Desk $desk): void
    {
        $this->desk = $desk;
        $this->branch_id = $desk->branch_id;
        $this->desk_number = $desk->desk_number;
        $this->status = $desk->status;
        $this->subscription_id = $desk->subscription_id;
        $this->user_id = $desk->user_id;
        $this->branches = Branch::orderBy('name')->get();
        $this->loadSubscriptions();
    }

    protected function messages(): array
    {
        return [
            'branch_id.required' => 'انتخاب شعبه الزامی است.',
            'branch_id.exists' => 'شعبه انتخاب‌شده معتبر نیست.',
            'desk_number.required' => 'شماره میز الزامی است.',
            'desk_number.string' => 'شماره میز باید متن باشد.',
            'desk_number.max' => 'شماره میز نمی‌تواند بیش از ۵۰ کاراکتر باشد.',
            'desk_number.unique' => 'این شماره میز در شعبه انتخاب‌شده از قبل وجود دارد.',
            'status.required' => 'وضعیت میز الزامی است.',
            'status.in' => 'وضعیت انتخاب‌شده معتبر نیست.',
        ];
    }

    protected function rules(): array
    {
        return [
            'branch_id' => ['required', 'exists:branches,id'],
            'desk_number' => [
                'required', 'string', 'max:50',
                Rule::unique('desks', 'desk_number')
                    ->where(fn ($q) => $q->where('branch_id', $this->branch_id))
                    ->ignore($this->desk->id),
            ],
            'status' => ['required', 'in:available,reserved'],
            'subscription_id' => [
                Rule::requiredIf(fn () => $this->status === 'reserved'),
                Rule::exists('subscriptions', 'id')->where(function ($q) {
                    if ($this->branch_id) {
                        $q->where('branch_id', $this->branch_id);
                    }
                    $q->whereIn('status', ['pending', 'active']);
                }),
            ],
        ];
    }

    public function update(): void
    {
        $this->validate();

        $userId = null;
        if ($this->status === 'reserved' && $this->subscription_id) {
            $subscription = Subscription::find($this->subscription_id);
            $userId = $subscription?->user_id;
        }

        $this->desk->update([
            'branch_id' => $this->branch_id,
            'desk_number' => $this->desk_number,
            'status' => $this->status,
            'subscription_id' => $this->status === 'reserved' ? $this->subscription_id : null,
            'user_id' => $userId,
        ]);

        session()->flash('success', 'تغییرات میز با موفقیت ذخیره شد.');
        redirect()->route('admin.desks.index');
    }

    public function updatedBranchId($value): void
    {
        $this->branch_id = $value ? (int) $value : null;
        $this->loadSubscriptions();
        $this->subscription_id = null;
        $this->user_id = null;
    }

    public function updatedSubscriptionId($value): void
    {
        $this->subscription_id = $value ? (int) $value : null;
        if ($this->subscription_id) {
            $subscription = Subscription::find($this->subscription_id);
            $this->user_id = $subscription?->user_id;
        } else {
            $this->user_id = null;
        }
    }

    protected function loadSubscriptions(): void
    {
        $query = Subscription::query()->with(['user', 'branch'])
            ->whereIn('status', ['pending', 'active']);
        if ($this->branch_id) {
            $query->where('branch_id', $this->branch_id);
        }
        $this->subscriptions = $query->orderByDesc('id')->limit(50)->get();
    }

    public function render()
    {
        return view('livewire.admin.desk.edit');
    }
}