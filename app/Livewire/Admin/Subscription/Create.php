<?php

namespace App\Livewire\Admin\Subscription;

use App\Models\Subscription;
use App\Models\User;
use App\Models\Branch;
use App\Models\SubscriptionType;
use App\Models\Discount;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('ایجاد اشتراک')]
class Create extends Component
{
    public ?int $user_id = null;
    public ?int $subscription_type_id = null;
    public ?int $branch_id = null;
    public ?int $discount_id = null;
    public string $start_datetime = '';
    public string $end_datetime = '';
    public string $status = 'pending';
    public $total_price = '';

    public string $userSearch = '';
    public $userResults = [];
    public ?string $selectedUserName = null;
    public $types = [];
    public $branches = [];
    public $discounts = [];

    protected function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'subscription_type_id' => ['required', 'exists:subscription_types,id'],
            'branch_id' => ['required', 'exists:branches,id'],
            'start_datetime' => ['required', 'date'],
            'end_datetime' => ['required', 'date', 'after_or_equal:start_datetime'],
            'status' => ['required', 'in:pending,active,expired'],
            'total_price' => ['required', 'numeric', 'min:0'],
            'discount_id' => ['nullable', 'exists:discounts,id'],
        ];
    }

    protected function messages(): array
    {
        return [
            'user_id.required' => 'انتخاب کاربر الزامی است.',
            'user_id.exists' => 'کاربر انتخاب‌شده معتبر نیست.',

            'subscription_type_id.required' => 'انتخاب نوع اشتراک الزامی است.',
            'subscription_type_id.exists' => 'نوع اشتراک انتخاب‌شده معتبر نیست.',

            'branch_id.required' => 'انتخاب شعبه الزامی است.',
            'branch_id.exists' => 'شعبه انتخاب‌شده معتبر نیست.',

            'start_datetime.required' => 'زمان شروع الزامی است.',
            'start_datetime.date' => 'فرمت زمان شروع معتبر نیست.',

            'end_datetime.required' => 'زمان پایان الزامی است.',
            'end_datetime.date' => 'فرمت زمان پایان معتبر نیست.',
            'end_datetime.after_or_equal' => 'زمان پایان باید بعد یا برابر زمان شروع باشد.',

            'status.required' => 'وضعیت اشتراک الزامی است.',
            'status.in' => 'وضعیت انتخاب‌شده معتبر نیست.',

            'total_price.required' => 'مبلغ کل الزامی است.',
            'total_price.numeric' => 'مبلغ کل باید عددی باشد.',
            'total_price.min' => 'مبلغ کل نمی‌تواند منفی باشد.',

            'discount_id.exists' => 'کد تخفیف انتخاب‌شده معتبر نیست.',
        ];
    }

    public function mount(): void
    {
        $this->types = SubscriptionType::orderBy('name')->get();
        $this->branches = Branch::orderBy('name')->get();
        $this->discounts = Discount::orderBy('id','desc')->get();
    }

    public function updatedUserSearch($value): void
    {
        $term = trim($value);
        if (mb_strlen($term) < 2) {
            $this->userResults = [];
            return;
        }

        $this->userResults = User::query()
            ->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('email', 'like', "%{$term}%")
                  ->orWhere('phone', 'like', "%{$term}%");
            })
            ->orderBy('name')
            ->limit(10)
            ->get();
    }

    public function selectUser(int $id): void
    {
        $user = User::findOrFail($id);
        $this->user_id = $user->id;
        $this->selectedUserName = $user->name;
        $this->userSearch = $user->name;
        $this->userResults = [];
    }

    public function clearSelectedUser(): void
    {
        $this->user_id = null;
        $this->selectedUserName = null;
        $this->userSearch = '';
        $this->userResults = [];
    }

    public function updatedSubscriptionTypeId(): void
    {
        $this->recomputeEndDatetime();
    }

    public function updatedStartDatetime(): void
    {
        $this->recomputeEndDatetime();
    }

    private function recomputeEndDatetime(): void
    {
        if (!$this->start_datetime || !$this->subscription_type_id) {
            return;
        }
        // Use a direct query to avoid calling collection methods on an array-typed property
        $type = SubscriptionType::find($this->subscription_type_id);
        if ($type && $type->duration_days) {
            $this->end_datetime = Carbon::parse($this->start_datetime)
                ->addDays((int) $type->duration_days)
                ->format('Y-m-d\TH:i');
        }
    }

    public function save(): void
    {
        $this->validate();

        Subscription::create([
            'user_id' => $this->user_id,
            'subscription_type_id' => $this->subscription_type_id,
            'branch_id' => $this->branch_id,
            'start_datetime' => $this->start_datetime,
            'end_datetime' => $this->end_datetime,
            'status' => $this->status,
            'total_price' => $this->total_price,
            'discount_id' => $this->discount_id,
        ]);

        session()->flash('success', 'اشتراک جدید با موفقیت ایجاد شد.');
        redirect()->route('admin.subscriptions.index');
    }

    public function render()
    {
        return view('livewire.admin.subscription.create');
    }
}