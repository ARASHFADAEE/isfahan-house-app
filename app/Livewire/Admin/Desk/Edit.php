<?php

namespace App\Livewire\Admin\Desk;

use App\Models\Desk;
use App\Models\Branch;
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

    public $branches = [];

    public function mount(Desk $desk): void
    {
        $this->desk = $desk;
        $this->branch_id = $desk->branch_id;
        $this->desk_number = $desk->desk_number;
        $this->status = $desk->status;
        $this->branches = Branch::orderBy('name')->get();
    }

    protected function rules(): array
    {
        return [
            'branch_id' => ['required', 'exists:branches,id'],
            'desk_number' => ['required', 'string', 'max:50', Rule::unique('desks', 'desk_number')->where(fn ($q) => $q->where('branch_id', $this->branch_id))->ignore($this->desk->id)],
            'status' => ['required', 'in:available,reserved'],
        ];
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

    public function update(): void
    {
        $this->validate();

        $this->desk->update([
            'branch_id' => $this->branch_id,
            'desk_number' => $this->desk_number,
            'status' => $this->status,
        ]);

        session()->flash('success', 'میز با موفقیت به‌روزرسانی شد.');
        redirect()->route('admin.desks.index');
    }

    public function render()
    {
        return view('livewire.admin.desk.edit');
    }
}