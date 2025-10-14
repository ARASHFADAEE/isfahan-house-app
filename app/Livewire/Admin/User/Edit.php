<?php

namespace App\Livewire\Admin\User;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('ویرایش کاربر')]
class Edit extends Component
{
    public User $user;
    public string $first_name = '';
    public string $last_name = '';
    public string $email = '';
    public ?string $phone = null;
    public ?string $password = null;
    public string $role = 'user';

    public function mount(User $user): void
    {
        $this->user = $user;
        $this->first_name = (string) $user->first_name;
        $this->last_name = (string) $user->last_name;
        $this->email = (string) $user->email;
        $this->phone = $user->phone;
        $this->role = (string) $user->role;
    }

    protected function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $this->user->id],
            'phone' => ['nullable', 'regex:/^09[0-9]{9}$/'],
            'password' => ['nullable', 'string', 'min:8'],
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

            'password.min' => 'رمز عبور حداقل باید ۸ کاراکتر باشد.',

            'role.required' => 'نقش کاربر الزامی است.',
            'role.in' => 'نقش انتخاب‌شده معتبر نیست.',
        ];
    }

    public function update(): void
    {
        $this->validate();

        $this->user->update([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'name' => trim($this->first_name . ' ' . $this->last_name),
            'email' => $this->email,
            'phone' => $this->phone,
            'role' => $this->role,
            // Only update password if provided
            'password' => $this->password ?: $this->user->password,
        ]);

        session()->flash('success', 'اطلاعات کاربر با موفقیت به‌روزرسانی شد.');
        redirect()->route('admin.users.index');
    }

    public function render()
    {
        return view('livewire.admin.user.edit');
    }
}