<?php

namespace App\Livewire\Admin;

use App\Models\Subscription;
use App\Models\Setting;
use Illuminate\Support\Carbon;
use Hekmatinasser\Verta\Verta;
use Livewire\Component;

class HeaderNotifications extends Component
{
    public array $items = [];

    public function mount(): void
    {
        Carbon::setLocale('fa');

        $now = Carbon::now();
        $daysThreshold = (int) (Setting::get('notification.subscription_expiring_days', 7));
        $expiringUntil = (clone $now)->addDays(max($daysThreshold, 1));

        // Expired subscriptions (end_datetime in the past)
        $expired = Subscription::query()
            ->with(['user', 'branch', 'subscriptionType'])
            ->whereIn('status', ['active', 'pending'])
            ->where('end_datetime', '<', $now)
            ->orderBy('end_datetime', 'desc')
            ->limit(20)
            ->get();

        foreach ($expired as $s) {
            $end = Carbon::parse($s->end_datetime);
            $this->items[] = [
                'kind' => 'expired',
                'title' => 'اشتراک منقضی شده',
                'message' => sprintf(
                    'اشتراک کاربر %s در شعبه %s در تاریخ %s منقضی شده است.',
                    trim(($s->user?->first_name ?? '') . ' ' . ($s->user?->last_name ?? '')) ?: ($s->user?->name ?? 'نامشخص'),
                    $s->branch?->name ?? 'نامشخص',
                    Verta::instance($end)->format('Y/m/d H:i')
                ),
                'date_badge' => $end->diffForHumans($now, ['syntax' => Carbon::DIFF_ABSOLUTE]) . ' پیش',
                'link' => route('admin.subscriptions.edit', $s->id),
                'avatar' => asset('panel/assets/images/ai_avtar/1.jpg'),
                'badge_class' => 'text-light-danger',
            ];
        }

        // Expiring soon subscriptions (within threshold days)
        $expiring = Subscription::query()
            ->with(['user', 'branch', 'subscriptionType'])
            ->whereIn('status', ['active', 'pending'])
            ->whereBetween('end_datetime', [$now, $expiringUntil])
            ->orderBy('end_datetime', 'asc')
            ->limit(20)
            ->get();

        foreach ($expiring as $s) {
            $end = Carbon::parse($s->end_datetime);
            $daysLeft = $now->diffInDays($end, false);
            $this->items[] = [
                'kind' => 'expiring',
                'title' => 'اشتراک در حال اتمام',
                'message' => sprintf(
                    'اشتراک کاربر %s تا %s منقضی می‌شود. %d روز مانده.',
                    trim(($s->user?->first_name ?? '') . ' ' . ($s->user?->last_name ?? '')) ?: ($s->user?->name ?? 'نامشخص'),
                    Verta::instance($end)->format('Y/m/d H:i'),
                    max($daysLeft, 0)
                ),
                'date_badge' => 'رو به اتمام',
                'link' => route('admin.subscriptions.edit', $s->id),
                'avatar' => asset('panel/assets/images/ai_avtar/5.jpg'),
                'badge_class' => 'text-light-warning',
            ];
        }
    }

    public function render()
    {
        return view('livewire.admin.header_notifications');
    }
}