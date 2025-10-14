<?php

namespace App\Livewire\Admin\PrivateRoom;

use App\Models\PrivateRoom;
use App\Models\Subscription;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('ایجاد اتاق اختصاصی')]
class Create extends Component
{
    public ?int $branch_id = null;
    public string $room_number = '';
    public string $status = 'available';
    public ?int $subscription_id = null;
    public ?int $user_id = null;
    public string $assignment = 'subscription';
    public string $user_search = '';
    public $user_results = [];

    public $branches = [];
    public $subscriptions = [];

    public function mount(): void
    {
        $this->branches = \App\Models\Branch::orderBy('name')->get();
        $this->loadSubscriptions();
    }

    protected function rules(): array
    {
        return [
            'branch_id' => ['required', 'integer', Rule::exists('branches', 'id')],
            'room_number' => [
                'required','string','max:50',
                Rule::unique('private_rooms', 'room_number')->where(fn($q) => $q->where('branch_id', $this->branch_id))
            ],
            'status' => ['required', Rule::in(['available','reserved'])],
            'subscription_id' => [
                function ($attribute, $value, $fail) {
                    if ($this->status === 'reserved' && $this->assignment === 'subscription') {
                        if (!$value) return $fail('انتخاب اشتراک برای رزرو الزامی است.');
                        $ok = Subscription::where('id', $value)
                            ->where('branch_id', $this->branch_id)
                            ->whereIn('status', ['active','pending'])
                            ->exists();
                        if (!$ok) return $fail('اشتراک معتبر برای این شعبه یافت نشد.');
                    }
                }
            ],
            'user_id' => [
                function ($attribute, $value, $fail) {
                    if ($this->status === 'reserved' && $this->assignment === 'user') {
                        if (!$value) return $fail('انتخاب کاربر برای رزرو الزامی است.');
                        $exists = \App\Models\User::where('id', $value)->exists();
                        if (!$exists) return $fail('کاربر معتبر یافت نشد.');
                    }
                }
            ],
        ];
    }

    public function save(): void
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

        PrivateRoom::create([
            'branch_id' => $this->branch_id,
            'room_number' => $this->room_number,
            'status' => $this->status,
            'subscription_id' => $this->subscription_id,
            'user_id' => $this->user_id,
        ]);

        session()->flash('success', 'اتاق اختصاصی با موفقیت ایجاد شد.');
        redirect()->route('admin.private_rooms.index');
    }

    protected function loadSubscriptions(): void
    {
        if ($this->branch_id) {
            $this->subscriptions = Subscription::where('branch_id', $this->branch_id)
                ->whereIn('status', ['active','pending'])
                ->orderBy('id','desc')
                ->get();
        } else {
            $this->subscriptions = collect();
        }
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
            $this->user_id = Subscription::find($this->subscription_id)?->user_id;
        } else {
            $this->user_id = null;
        }
    }

    public function updatedUserSearch($value): void
    {
        $term = trim($value ?? '');
        if ($term === '') { $this->user_results = []; return; }
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

    public function render()
    {
        return view('livewire.admin.private_room.create');
    }
}