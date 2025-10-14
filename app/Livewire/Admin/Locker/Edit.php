<?php

namespace App\Livewire\Admin\Locker;

use App\Models\Locker;
use App\Models\Subscription;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('ویرایش کمد')]
class Edit extends Component
{
    public Locker $locker;

    public ?int $branch_id = null;
    public ?string $locker_number = null;
    public string $status = 'available';
    public ?int $subscription_id = null;
    public ?int $user_id = null;
    public string $assignment = 'subscription';
    public string $user_search = '';
    public $user_results = [];

    public $branches = [];
    public $subscriptions = [];

    public function mount(Locker $locker): void
    {
        $this->locker = $locker;
        $this->branch_id = $locker->branch_id;
        $this->locker_number = $locker->locker_number;
        $this->status = $locker->status;
        $this->subscription_id = $locker->subscription_id;
        $this->user_id = $locker->user_id;
        $this->assignment = $this->subscription_id ? 'subscription' : ($this->user_id ? 'user' : 'subscription');

        $this->branches = \App\Models\Branch::orderBy('name')->get();
        $this->loadSubscriptions();
    }

    protected function rules(): array
    {
        return [
            'branch_id' => ['required', 'exists:branches,id'],
            'locker_number' => ['required', 'string', 'max:255', 'unique:lockers,locker_number,' . $this->locker->id . ',id,branch_id,' . ($this->branch_id ?? 'NULL')],
            'status' => ['required', 'in:available,reserved'],
            'subscription_id' => [
                function ($attribute, $value, $fail) {
                    if ($this->status === 'reserved' && $this->assignment === 'subscription') {
                        if (!$value) {
                            return $fail('انتخاب اشتراک برای وضعیت رزرو الزامی است.');
                        }
                        $exists = Subscription::where('id', $value)
                            ->where('branch_id', $this->branch_id)
                            ->whereIn('status', ['active', 'pending'])
                            ->exists();
                        if (!$exists) {
                            return $fail('اشتراک معتبر برای این شعبه یافت نشد یا وضعیت آن مناسب نیست.');
                        }
                    }
                }
            ],
            'user_id' => [
                function ($attribute, $value, $fail) {
                    if ($this->status === 'reserved' && $this->assignment === 'user') {
                        if (!$value) {
                            return $fail('انتخاب کاربر برای وضعیت رزرو الزامی است.');
                        }
                        $exists = \App\Models\User::where('id', $value)->exists();
                        if (!$exists) {
                            return $fail('کاربر معتبر یافت نشد.');
                        }
                    }
                }
            ],
        ];
    }

    protected function messages(): array
    {
        return [
            'branch_id.required' => 'انتخاب شعبه الزامی است.',
            'branch_id.exists' => 'شعبه انتخاب شده معتبر نیست.',
            'locker_number.required' => 'شماره کمد را وارد کنید.',
            'locker_number.unique' => 'شماره کمد در این شعبه تکراری است.',
            'status.required' => 'انتخاب وضعیت الزامی است.',
            'status.in' => 'وضعیت انتخاب شده معتبر نیست.',
        ];
    }

    public function update(): void
    {
        $this->validate();

        if ($this->status === 'reserved') {
            if ($this->assignment === 'subscription' && $this->subscription_id) {
                $this->user_id = Subscription::find($this->subscription_id)?->user_id;
            }
            if ($this->assignment === 'user') {
                $this->subscription_id = null;
            }
        } else {
            $this->subscription_id = null;
            $this->user_id = null;
        }

        $this->locker->update([
            'branch_id' => $this->branch_id,
            'locker_number' => $this->locker_number,
            'status' => $this->status,
            'subscription_id' => $this->status === 'reserved' ? $this->subscription_id : null,
            'user_id' => $this->user_id,
        ]);

        session()->flash('success', 'کمد با موفقیت به‌روزرسانی شد.');
        $this->redirect(route('admin.lockers.index'), navigate: true);
    }

    public function updatedBranchId($value): void
    {
        $this->branch_id = $value ? (int) $value : null;
        $this->subscription_id = null;
        $this->user_id = null;
        $this->loadSubscriptions();
    }

    public function updatedSubscriptionId($value): void
    {
        $this->subscription_id = $value ? (int) $value : null;
        $this->user_id = $this->subscription_id ? Subscription::find($this->subscription_id)?->user_id : null;
    }

    protected function loadSubscriptions(): void
    {
        if ($this->branch_id) {
            $this->subscriptions = Subscription::where('branch_id', $this->branch_id)
                ->whereIn('status', ['active', 'pending'])
                ->orderBy('id', 'desc')
                ->get();
        } else {
            $this->subscriptions = collect();
        }
    }

    public function render()
    {
        return view('livewire.admin.locker.edit');
    }

    public function updatedUserSearch($value): void
    {
        $term = trim($value ?? '');
        if ($term === '') {
            $this->user_results = [];
            return;
        }
        $like = '%' . $term . '%';
        $this->user_results = \App\Models\User::query()
            ->where(function($q) use ($like) {
                $q->where('name', 'like', $like)
                  ->orWhere('first_name', 'like', $like)
                  ->orWhere('last_name', 'like', $like)
                  ->orWhere('email', 'like', $like)
                  ->orWhere('phone', 'like', $like);
            })
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();
    }

    public function chooseUser(int $id): void
    {
        $this->user_id = $id;
        $this->user_search = '';
        $this->user_results = [];
    }
}