<?php

use App\Livewire\Dashboard;
use App\Livewire\Admin\Branch\Index as BranchIndex;
use App\Livewire\Admin\Branch\Create as BranchCreate;
use App\Livewire\Admin\Branch\Edit as BranchEdit;
use App\Livewire\Admin\User\Index as UserIndex;
use App\Livewire\Admin\User\Create as UserCreate;
use App\Livewire\Admin\User\Edit as UserEdit;
use App\Livewire\Admin\Subscription\Index as SubscriptionIndex;
use App\Livewire\Admin\Subscription\Create as SubscriptionCreate;
use App\Livewire\Admin\Subscription\Edit as SubscriptionEdit;
use App\Livewire\Admin\SubscriptionType\Index as SubscriptionTypeIndex;
use App\Livewire\Admin\SubscriptionType\Create as SubscriptionTypeCreate;
use App\Livewire\Admin\SubscriptionType\Edit as SubscriptionTypeEdit;
use App\Livewire\Admin\Desk\Index as DeskIndex;
use App\Livewire\Admin\Desk\Create as DeskCreate;
use App\Livewire\Admin\Desk\Edit as DeskEdit;
use App\Livewire\Admin\FlexibleDeskReservation\Index as FlexibleDeskReservationIndex;
use App\Livewire\Admin\Locker\Index as LockerIndex;
use App\Livewire\Admin\Locker\Create as LockerCreate;
use App\Livewire\Admin\Locker\Edit as LockerEdit;
use App\Livewire\Admin\PrivateRoom\Index as PrivateRoomIndex;
use App\Livewire\Admin\PrivateRoom\Create as PrivateRoomCreate;
use App\Livewire\Admin\PrivateRoom\Edit as PrivateRoomEdit;
use App\Livewire\Admin\MeetingRoom\Index as MeetingRoomIndex;
use App\Livewire\Admin\MeetingRoom\Create as MeetingRoomCreate;
use App\Livewire\Admin\MeetingReservation\Create as MeetingReservationCreate;
use App\Livewire\Admin\MeetingReservation\Index as MeetingReservationIndex;
use App\Livewire\Admin\Transaction\Index as TransactionIndex;
use App\Livewire\Admin\Transaction\Create as TransactionCreate;
use App\Livewire\Admin\Discount\Index as DiscountIndex;
use App\Livewire\Admin\Discount\Create as DiscountCreate;
use App\Livewire\Admin\Notification\Index as NotificationIndex;
use App\Livewire\Admin\Notification\Create as NotificationCreate;
use App\Livewire\Admin\Setting\Index as SettingIndex;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::prefix('admin')->group(function(){


    Route::get('/dashboard',Dashboard::class)->name('admin.dashboard.index');

    // Admin dashboard-only routes here
});

// Branches management (top-level to match nav links)
Route::get('/branches', BranchIndex::class)->name('admin.branches.index');
Route::get('/branches/create', BranchCreate::class)->name('admin.branches.create');
Route::get('/branches/{branch}/edit', BranchEdit::class)->name('admin.branches.edit');

// Users management (top-level to match nav links)
Route::get('/users', UserIndex::class)->name('admin.users.index');
Route::get('/users/create', UserCreate::class)->name('admin.users.create');
Route::get('/users/{user}/edit', UserEdit::class)->name('admin.users.edit');

// Subscriptions management (top-level to match nav links)
Route::get('/subscriptions', SubscriptionIndex::class)->name('admin.subscriptions.index');
Route::get('/subscriptions/create', SubscriptionCreate::class)->name('admin.subscriptions.create');
Route::get('/subscriptions/{subscription}/edit', SubscriptionEdit::class)->name('admin.subscriptions.edit');

// Subscription Types management
Route::get('/subscription-types', SubscriptionTypeIndex::class)->name('admin.subscription_types.index');
Route::get('/subscription-types/create', SubscriptionTypeCreate::class)->name('admin.subscription_types.create');
Route::get('/subscription-types/{subscriptionType}/edit', SubscriptionTypeEdit::class)->name('admin.subscription_types.edit');

// Desks management
Route::get('/desks', DeskIndex::class)->name('admin.desks.index');
Route::get('/desks/create', DeskCreate::class)->name('admin.desks.create');
Route::get('/desks/{desk}/edit', DeskEdit::class)->name('admin.desks.edit');

// Lockers management
Route::get('/lockers', LockerIndex::class)->name('admin.lockers.index');
Route::get('/lockers/create', LockerCreate::class)->name('admin.lockers.create');
Route::get('/lockers/{locker}/edit', LockerEdit::class)->name('admin.lockers.edit');

// Flexible desk reservations
Route::get('/flexible-desk-reservations', FlexibleDeskReservationIndex::class)->name('admin.flexible_desk_reservations.index');

// Meeting rooms management
Route::get('/meeting-rooms', MeetingRoomIndex::class)->name('admin.meeting_rooms.index');
Route::get('/meeting-rooms/create', MeetingRoomCreate::class)->name('admin.meeting_rooms.create');

// Admin meeting reservations
Route::get('/meeting-reservations', MeetingReservationIndex::class)->name('admin.meeting_reservations.index');
Route::get('/meeting-reservations/create', MeetingReservationCreate::class)->name('admin.meeting_reservations.create');

// Private rooms (monthly) management
Route::get('/private-rooms', PrivateRoomIndex::class)->name('admin.private_rooms.index');
Route::get('/private-rooms/create', PrivateRoomCreate::class)->name('admin.private_rooms.create');
Route::get('/private-rooms/{privateRoom}/edit', PrivateRoomEdit::class)->name('admin.private_rooms.edit');

// Transactions
Route::get('/transactions', TransactionIndex::class)->name('admin.transactions.index');
Route::get('/transactions/create', TransactionCreate::class)->name('admin.transactions.create');

// Discounts
Route::get('/discounts', DiscountIndex::class)->name('admin.discounts.index');
Route::get('/discounts/create', DiscountCreate::class)->name('admin.discounts.create');

// Notifications
Route::get('/notifications', NotificationIndex::class)->name('admin.notifications.index');
Route::get('/notifications/create', NotificationCreate::class)->name('admin.notifications.create');

// Settings
Route::get('/settings', SettingIndex::class)->name('admin.settings.index');
