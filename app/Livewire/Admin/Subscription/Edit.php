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
#[Title('ویرایش اشتراک')]
class Edit extends Component
{
    public Subscription $subscription;

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

    public function mount(Subscription $subscription): void
    {
        $this->subscription = $subscription;
        $this->user_id = $subscription->user_id;
        $this->subscription_type_id = $subscription->subscription_type_id;
        $this->branch_id = $subscription->branch_id;
        $this->discount_id = $subscription->discount_id;
        $this->start_datetime = $subscription->start_datetime;
        $this->end_datetime = $subscription->end_datetime;
        $this->status = $subscription->status;
        $this->total_price = $subscription->total_price;

        $this->selectedUserName = $subscription->user?->name;
        $this->userSearch = $this->selectedUserName ?? '';
        $this->types = SubscriptionType::orderBy('name')->get();
        $this->branches = Branch::orderBy('name')->get();
        $this->discounts = Discount::orderBy('id','desc')->get();
    }

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
        // Query the model directly to avoid calling collection methods on an array property
        $type = SubscriptionType::find($this->subscription_type_id);
        if ($type && $type->duration_days) {
            $this->end_datetime = Carbon::parse($this->start_datetime)
                ->addDays((int) $type->duration_days)
                ->format('Y-m-d\TH:i');
        }
    }

    public function update(): void
    {
        $this->validate();

        $this->subscription->update([
            'user_id' => $this->user_id,
            'subscription_type_id' => $this->subscription_type_id,
            'branch_id' => $this->branch_id,
            'start_datetime' => $this->start_datetime,
            'end_datetime' => $this->end_datetime,
            'status' => $this->status,
            'total_price' => $this->total_price,
            'discount_id' => $this->discount_id,
        ]);

        session()->flash('success', 'اشتراک با موفقیت به‌روزرسانی شد.');
        redirect()->route('admin.subscriptions.index');
    }

    public function render()
    {
        return view('livewire.admin.subscription.edit');
    }
}