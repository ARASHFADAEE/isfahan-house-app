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
