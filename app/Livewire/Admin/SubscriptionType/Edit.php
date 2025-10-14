<?php

namespace App\Livewire\Admin\SubscriptionType;

use App\Models\SubscriptionType;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('ویرایش نوع اشتراک')]
class Edit extends Component
{
    public SubscriptionType $subscriptionType;

    public string $name = '';
    public $price = '';
    public $duration_days = '';
    public bool $requires_admin_approval = false;

    public function mount(SubscriptionType $subscriptionType): void
    {
        $this->subscriptionType = $subscriptionType;
        $this->name = $subscriptionType->name;
        $this->price = $subscriptionType->price;
        $this->duration_days = $subscriptionType->duration_days;
        $this->requires_admin_approval = (bool) $subscriptionType->requires_admin_approval;
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration_days' => ['required', 'integer', 'min:1'],
            'requires_admin_approval' => ['boolean'],
        ];
    }

    protected function messages(): array
    {
        return [
            'name.required' => 'نام نوع اشتراک الزامی است.',
            'name.max' => 'نام نمی‌تواند بیش از ۲۵۵ کاراکتر باشد.',
            'price.required' => 'قیمت الزامی است.',
            'price.numeric' => 'قیمت باید عددی باشد.',
            'price.min' => 'قیمت نمی‌تواند منفی باشد.',
            'duration_days.required' => 'تعداد روزهای اشتراک الزامی است.',
            'duration_days.integer' => 'تعداد روزها باید عدد صحیح باشد.',
            'duration_days.min' => 'تعداد روزها باید حداقل ۱ باشد.',
        ];
    }

    public function update(): void
    {
        $data = $this->validate();
        $this->subscriptionType->update($data);
        session()->flash('success', 'نوع اشتراک با موفقیت به‌روزرسانی شد.');
        redirect()->route('admin.subscription_types.index');
    }

    public function render()
    {
        return view('livewire.admin.subscription_type.edit');
    }
}