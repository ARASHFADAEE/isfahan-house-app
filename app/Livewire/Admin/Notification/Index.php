<?php

namespace App\Livewire\Admin\Notification;

use App\Models\Notification;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('لیست اعلان‌ها')]
class Index extends Component
{
    use WithPagination;

    public ?string $type = null; // sms/email/system
    public ?string $status = null; // sent/pending/failed
    public ?string $event_type = null; // subscription_* enums
    public string $search = '';

    public function updatedType(): void { $this->resetPage(); }
    public function updatedStatus(): void { $this->resetPage(); }
    public function updatedEventType(): void { $this->resetPage(); }
    public function updatedSearch(): void { $this->resetPage(); }

    public function render()
    {
        $query = Notification::query()->with('user');
        if ($this->type) { $query->where('type', $this->type); }
        if ($this->status) { $query->where('status', $this->status); }
        if ($this->event_type) { $query->where('event_type', $this->event_type); }
        if (trim($this->search) !== '') {
            $like = '%' . trim($this->search) . '%';
            $query->where(function($q) use ($like){
                $q->where('message','like',$like)
                  ->orWhereHas('user', function($uq) use ($like){
                      $uq->where('name','like',$like)
                         ->orWhere('email','like',$like)
                         ->orWhere('phone','like',$like)
                         ->orWhere('first_name','like',$like)
                         ->orWhere('last_name','like',$like);
                  });
            });
        }

        $notifications = $query->orderBy('id','desc')->paginate(10);

        return view('livewire.admin.notification.index', [
            'notifications' => $notifications,
        ]);
    }
}