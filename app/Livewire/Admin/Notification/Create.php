<?php

namespace App\Livewire\Admin\Notification;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('ارسال اعلان')]
class Create extends Component
{
    public string $audience = 'user'; // 'user' or 'all'
    public ?int $user_id = null;
    public string $user_search = '';
    public array $user_results = [];

    public string $type = 'system';
    public ?string $event_type = null; // optional
    public string $message = '';
    public string $status = 'pending';

    protected function rules(): array
    {
        return [
            'audience' => ['required', Rule::in(['user','all'])],
            'user_id' => [$this->audience === 'user' ? 'required' : 'nullable','integer', Rule::exists('users','id')],
            'type' => ['required', Rule::in(['sms','email','system'])],
            'event_type' => ['nullable', Rule::in(['subscription_created','subscription_approved','subscription_expiring','subscription_expired'])],
            'message' => ['required','string','max:500'],
            'status' => ['required', Rule::in(['sent','pending','failed'])],
        ];
    }

    public function updatedAudience(): void
    {
        // Reset selected user when switching audience
        if ($this->audience === 'all') {
            $this->user_id = null;
        }
    }

    public function updatedUserSearch(): void
    {
        $term = trim($this->user_search);
        if ($term === '') { $this->user_results = []; return; }
        $like = "%$term%";
        $this->user_results = User::query()
            ->where(function($q) use ($like){
                $q->where('name','like',$like)
                  ->orWhere('email','like',$like)
                  ->orWhere('phone','like',$like)
                  ->orWhere('first_name','like',$like)
                  ->orWhere('last_name','like',$like);
            })
            ->orderBy('id','desc')
            ->limit(10)
            ->get()
            ->map(function($u){
                return [
                    'id' => $u->id,
                    'label' => trim(($u->first_name.' '.$u->last_name)) ?: ($u->name ?? '-') ,
                    'email' => $u->email,
                    'phone' => $u->phone,
                ];
            })
            ->toArray();
    }

    public function selectUser(int $id): void
    {
        $this->user_id = $id;
        $this->user_search = '';
        $this->user_results = [];
    }

    public function save(): void
    {
        $this->validate();

        if ($this->audience === 'all') {
            // Create a notification record for each user
            User::query()->chunk(100, function($users){
                foreach ($users as $user) {
                    Notification::create([
                        'user_id' => $user->id,
                        'message' => $this->message,
                        'type' => $this->type,
                        'event_type' => $this->event_type ?: 'subscription_created',
                        'status' => $this->status,
                        'external_id' => null,
                    ]);
                }
            });
            session()->flash('success', 'اعلان برای همه کاربران ثبت شد.');
        } else {
            Notification::create([
                'user_id' => $this->user_id,
                'message' => $this->message,
                'type' => $this->type,
                'event_type' => $this->event_type ?: 'subscription_created',
                'status' => $this->status,
                'external_id' => null,
            ]);
            session()->flash('success', 'اعلان برای کاربر انتخاب‌شده ثبت شد.');
        }

        redirect()->route('admin.notifications.index');
    }

    public function render()
    {
        return view('livewire.admin.notification.create');
    }
}