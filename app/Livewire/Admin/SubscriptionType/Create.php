<?php

namespace App\Livewire\Admin\SubscriptionType;

use App\Models\SubscriptionType;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('ایجاد نوع اشتراک')]
class Create extends Component
{
    public string $name = '';
    public $price = '';
    public $duration_days = '';
    public bool $requires_admin_approval = false;

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

    public function save(): void
    {
        $data = $this->validate();
        SubscriptionType::create($data);
        session()->flash('success', 'نوع اشتراک جدید با موفقیت ایجاد شد.');
        redirect()->route('admin.subscription_types.index');
    }

    public function render()
    {
        return view('livewire.admin.subscription_type.create');
    }
}