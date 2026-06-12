<?php

use App\Livewire\Dashboard;
use App\Livewire\TicketIndex;
use App\Livewire\TicketCreate;
use App\Livewire\TicketShow;
use App\Livewire\TicketEdit;
use App\Livewire\CategoryIndex;
use App\Livewire\UserIndex;

Route::redirect('/', 'tickets');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', Dashboard::class)->name('dashboard');
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth'])->group(function () {
    Route::get('tickets', TicketIndex::class)->name('tickets.index');
    Route::get('tickets/create', TicketCreate::class)->name('tickets.create');
    Route::get('tickets/{ticket}', TicketShow::class)->name('tickets.show');
    Route::get('tickets/{ticket}/edit', TicketEdit::class)->name('tickets.edit');

    // Admin Only
    Route::middleware(['role:admin'])->group(function () {
        Route::get('categories', CategoryIndex::class)->name('categories.index');
        Route::get('users', UserIndex::class)->name('users.index');
    });
});

require __DIR__.'/auth.php';
