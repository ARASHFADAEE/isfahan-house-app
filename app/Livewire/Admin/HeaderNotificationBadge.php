<?php

namespace App\Livewire\Admin;

use App\Models\Notification;
use Livewire\Component;

class HeaderNotificationBadge extends Component
{
    public int $count = 0;

    public function mount(): void
    {
        $this->refreshCount();
    }

    public function refreshCount(): void
    {
        $this->count = (int) Notification::query()
            ->where('type', 'system')
            ->where('status', 'pending')
            ->count();
    }

    public function render()
    {
        // Poll periodically to keep badge fresh
        $this->refreshCount();
        return view('livewire.admin.header_notification_badge');
    }
}