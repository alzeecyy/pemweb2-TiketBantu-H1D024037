<?php

use App\Livewire\TicketIndex;
use App\Livewire\TicketCreate;
use App\Livewire\TicketShow;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth'])->group(function () {
    Route::get('tickets', TicketIndex::class)->name('tickets.index');
    Route::get('tickets/create', TicketCreate::class)->name('tickets.create');
    Route::get('tickets/{ticket}', TicketShow::class)->name('tickets.show');
});

require __DIR__.'/auth.php';
