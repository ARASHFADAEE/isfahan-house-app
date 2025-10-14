<?php

namespace App\Livewire\Admin\User;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('ایجاد کاربر')]
class Create extends Component
{
    public string $first_name = '';
    public string $last_name = '';
    public string $email = '';
    public ?string $phone = null;
    public string $password = '';
    public string $role = 'user';

    protected function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'regex:/^09[0-9]{9}$/', 'unique:users,phone'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'in:user,admin,ceo'],
        ];
    }

    protected function messages(): array
    {
        return [
            'first_name.required' => 'نام کوچک الزامی است.',
            'first_name.string' => 'نام کوچک باید متن باشد.',
            'first_name.max' => 'حداکثر طول نام کوچک ۲۵۵ کاراکتر است.',

            'last_name.required' => 'نام خانوادگی الزامی است.',
            'last_name.string' => 'نام خانوادگی باید متن باشد.',
            'last_name.max' => 'حداکثر طول نام خانوادگی ۲۵۵ کاراکتر است.',

            'email.required' => 'ایمیل الزامی است.',
            'email.email' => 'فرمت ایمیل صحیح نیست.',
            'email.unique' => 'این ایمیل قبلاً ثبت شده است.',
            'email.max' => 'حداکثر طول ایمیل ۲۵۵ کاراکتر است.',

            'phone.regex' => 'شماره تماس باید با 09 شروع شده و 11 رقمی باشد.',
            'phone.unique' => 'این شماره تماس قبلاً ثبت شده است.',

            'password.required' => 'رمز عبور الزامی است.',
            'password.min' => 'رمز عبور حداقل باید ۸ کاراکتر باشد.',

            'role.required' => 'نقش کاربر الزامی است.',
            'role.in' => 'نقش انتخاب‌شده معتبر نیست.',
        ];
    }

    public function save(): void
    {
        $this->validate();

        $user = User::create([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'name' => trim($this->first_name . ' ' . $this->last_name),
            'email' => $this->email,
            'phone' => $this->phone,
            'password' => $this->password,
            'role' => $this->role,
        ]);

        session()->flash('success', 'کاربر جدید با موفقیت ایجاد شد.');
        redirect()->route('admin.users.index');
    }

    public function render()
    {
        return view('livewire.admin.user.create');
    }
}