<?php

namespace App\Livewire\Admin\Branch;

use App\Models\Branch;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('ویرایش شعبه')]
class Edit extends Component
{
    public Branch $branch;

    public string $name = '';
    public string $address = '';
    public $flexible_desk_capacity = null;

    public function mount(Branch $branch)
    {
        $this->branch = $branch;
        $this->name = $branch->name;
        $this->address = $branch->address;
        $this->flexible_desk_capacity = $branch->flexible_desk_capacity;
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:30'],
            'flexible_desk_capacity' => ['required', 'integer', 'min:0'],
        ];
    }

    protected function messages(): array
    {
        return [
            'name.required' => 'نام شعبه الزامی است.',
            'name.string' => 'نام شعبه باید متن باشد.',
            'name.max' => 'حداکثر طول نام ۲۵۵ کاراکتر است.',

            'address.required' => 'آدرس شعبه الزامی است.',
            'address.string' => 'آدرس شعبه باید متن باشد.',
            'address.max' => 'حداکثر طول آدرس 30 کاراکتر است.',

            'flexible_desk_capacity.required' => 'ظرفیت میزهای منعطف الزامی است.',
            'flexible_desk_capacity.integer' => 'ظرفیت باید عدد صحیح باشد.',
            'flexible_desk_capacity.min' => 'حداقل ظرفیت نمی‌تواند کمتر از ۰ باشد.',
        ];
    }

    public function update()
    {
        $data = $this->validate();
        $this->branch->update($data);
        session()->flash('success', 'شعبه با موفقیت به‌روزرسانی شد.');
        return redirect()->route('admin.branches.index');
    }

    public function render()
    {
        return view('livewire.admin.branch.edit');
    }
}
