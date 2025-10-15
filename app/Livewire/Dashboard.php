<?php

namespace App\Livewire;

use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\Desk;
use App\Models\Notification;
use App\Models\Setting;
use Illuminate\Support\Carbon;
use Hekmatinasser\Verta\Verta;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('داشبورد')]
class Dashboard extends Component
{
    public int $totalSubscriptions = 0;
    public int $activeSubscriptions = 0;

    public ?array $latestTransactionToday = null; // ['amount' => float, 'at' => Carbon, 'user' => string, 'branch' => string]

    public int $freeDesks = 0;

    public array $recentSubscriptions = []; // last 5

    public array $dashboardNotifications = []; // latest system notifications

    public array $sales = [
        'current_week' => 0,
        'previous_week' => 0,
        'delta_percent' => null,
    ];

    public function mount(): void
    {
        Carbon::setLocale('fa');
    }

    public function render()
    {
        $this->computeMetrics();

        return view('livewire.dashboard', [
            'totalSubscriptions' => $this->totalSubscriptions,
            'activeSubscriptions' => $this->activeSubscriptions,
            'latestTransactionToday' => $this->latestTransactionToday,
            'freeDesks' => $this->freeDesks,
            'recentSubscriptions' => $this->recentSubscriptions,
            'dashboardNotifications' => $this->dashboardNotifications,
            'sales' => $this->sales,
        ]);
    }

    protected function computeMetrics(): void
    {
        // Generate notifications automatically on dashboard load (system type)
        $this->generateSubscriptionNotifications();

        // Subscriptions
        $this->totalSubscriptions = (int) Subscription::count();
        $this->activeSubscriptions = (int) Subscription::whereIn('status', ['pending', 'active'])->count();

        // Latest transaction today
        $latest = Transaction::query()
            ->with(['user', 'branch'])
            ->whereDate('created_at', Carbon::today())
            ->orderByDesc('created_at')
            ->first();

        if ($latest) {
            $userName = trim(((($latest->user->first_name ?? '') . ' ' . ($latest->user->last_name ?? '')))) ?: ($latest->user->name ?? 'کاربر');
            $branchName = $latest->branch->name ?? '—';
            $at = Carbon::parse($latest->created_at);
            $this->latestTransactionToday = [
                'amount' => (float) $latest->amount,
                'at' => $at,
                'at_jalali' => Verta::instance($at)->format('Y/m/d H:i'),
                'user' => $userName,
                'branch' => $branchName,
            ];
        } else {
            $this->latestTransactionToday = null;
        }

        // Free desks
        $this->freeDesks = (int) Desk::where('status', 'available')->count();

        // Recent subscriptions
        $this->recentSubscriptions = Subscription::query()
            ->with(['user', 'branch'])
            ->orderByDesc('id')
            ->limit(5)
            ->get()
            ->map(function ($s) {
                $created = Carbon::parse($s->created_at);
                return [
                    'user' => trim((($s->user->first_name ?? '') . ' ' . ($s->user->last_name ?? ''))) ?: ($s->user->name ?? 'کاربر'),
                    'branch' => $s->branch->name ?? '—',
                    'status' => $s->status,
                    'created_at' => $created,
                    'created_jalali' => Verta::instance($created)->format('Y/m/d H:i'),
                ];
            })
            ->all();

        // Sales (subscriptions only) for current week vs previous week
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $prevStart = (clone $startOfWeek)->subWeek();
        $prevEnd = (clone $endOfWeek)->subWeek();

        $currentWeekTotal = (float) Transaction::query()
            ->whereNotNull('subscription_id')
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->sum('amount');

        $previousWeekTotal = (float) Transaction::query()
            ->whereNotNull('subscription_id')
            ->where('status', 'completed')
            ->whereBetween('created_at', [$prevStart, $prevEnd])
            ->sum('amount');

        $deltaPercent = null;
        if ($previousWeekTotal > 0) {
            $deltaPercent = round((($currentWeekTotal - $previousWeekTotal) / $previousWeekTotal) * 100, 2);
        } elseif ($currentWeekTotal > 0) {
            $deltaPercent = 100.0;
        } else {
            $deltaPercent = 0.0;
        }

        $this->sales = [
            'current_week' => $currentWeekTotal,
            'previous_week' => $previousWeekTotal,
            'delta_percent' => $deltaPercent,
        ];

        // Dashboard notifications list (latest 10 system notifications)
        $this->dashboardNotifications = Notification::query()
            ->with('user')
            ->where('type', 'system')
            ->orderByDesc('id')
            ->limit(10)
            ->get()
            ->map(function ($n) {
                $created = Carbon::parse($n->created_at);
                return [
                    'id' => $n->id,
                    'user' => trim((($n->user->first_name ?? '') . ' ' . ($n->user->last_name ?? ''))) ?: ($n->user->name ?? 'کاربر'),
                    'message' => $n->message,
                    'event_type' => $n->event_type,
                    'status' => $n->status,
                    'created_at' => $created,
                    'created_jalali' => Verta::instance($created)->format('Y/m/d H:i'),
                ];
            })
            ->all();
    }

    protected function generateSubscriptionNotifications(): void
    {
        // Threshold for expiring soon
        $now = Carbon::now();
        $daysThreshold = (int) (Setting::get('notification.subscription_expiring_days', 7));
        $expiringUntil = (clone $now)->addDays(max($daysThreshold, 1));

        // Expired subscriptions
        $expired = Subscription::query()
            ->with(['user', 'branch'])
            ->whereIn('status', ['active', 'pending'])
            ->where('end_datetime', '<', $now)
            ->orderByDesc('end_datetime')
            ->limit(100)
            ->get();

        foreach ($expired as $s) {
            $end = Carbon::parse($s->end_datetime);
            $externalId = 'subscription:' . $s->id;
            $exists = Notification::query()
                ->where('event_type', 'subscription_expired')
                ->where('external_id', $externalId)
                ->exists();
            if ($exists) continue;

            $msg = sprintf(
                'اشتراک کاربر %s در شعبه %s در تاریخ %s منقضی شده است.',
                trim(($s->user?->first_name ?? '') . ' ' . ($s->user?->last_name ?? '')) ?: ($s->user?->name ?? 'نامشخص'),
                $s->branch?->name ?? 'نامشخص',
                Verta::instance($end)->format('Y/m/d H:i')
            );

            Notification::create([
                'user_id' => $s->user_id,
                'message' => $msg,
                'type' => 'system',
                'event_type' => 'subscription_expired',
                'status' => 'pending',
                'external_id' => $externalId,
            ]);
        }

        // Expiring soon subscriptions
        $expiring = Subscription::query()
            ->with(['user', 'branch'])
            ->whereIn('status', ['active', 'pending'])
            ->whereBetween('end_datetime', [$now, $expiringUntil])
            ->orderBy('end_datetime', 'asc')
            ->limit(100)
            ->get();

        foreach ($expiring as $s) {
            $end = Carbon::parse($s->end_datetime);
            $daysLeft = max($now->diffInDays($end, false), 0);
            $externalId = 'subscription:' . $s->id;
            $exists = Notification::query()
                ->where('event_type', 'subscription_expiring')
                ->where('external_id', $externalId)
                ->exists();
            if ($exists) continue;

            $msg = sprintf(
                'اشتراک کاربر %s تا %s منقضی می‌شود. %d روز مانده.',
                trim(($s->user?->first_name ?? '') . ' ' . ($s->user?->last_name ?? '')) ?: ($s->user?->name ?? 'نامشخص'),
                Verta::instance($end)->format('Y/m/d H:i'),
                $daysLeft
            );

            Notification::create([
                'user_id' => $s->user_id,
                'message' => $msg,
                'type' => 'system',
                'event_type' => 'subscription_expiring',
                'status' => 'pending',
                'external_id' => $externalId,
            ]);
        }
    }
}
