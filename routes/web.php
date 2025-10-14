<?php

use App\Livewire\Dashboard;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::prefix('admin')->group(function(){


    Route::get('/dashboard',Dashboard::class)->name('admin.dashboard.index');
});
