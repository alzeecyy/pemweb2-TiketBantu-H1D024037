<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <!-- Brand elements matching mockup -->
    <div class="flex flex-col items-center mb-8">
        <div class="w-14 h-14 bg-primary rounded-2xl flex items-center justify-center shadow-lg shadow-primary/30 mb-4 animate-bounce-short">
            <span class="material-symbols-outlined text-white text-3xl">confirmation_number</span>
        </div>
        <h1 class="text-3xl font-black text-on-surface tracking-tight">TiketBantu</h1>
        <span class="inline-block px-3 py-1 mt-2 text-[10px] font-bold uppercase tracking-wider text-primary bg-primary/10 rounded-full">
            Ajukan Aduanmu
        </span>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login" class="space-y-5">
        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-2">Email Address</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-primary">
                    <span class="material-symbols-outlined text-xl">mail</span>
                </span>
                <input wire:model="form.email" id="email" type="email" name="email" required autofocus autocomplete="username"
                    class="w-full pl-11 pr-4 py-3 rounded-2xl border border-outline-variant/30 bg-surface-container-low text-sm focus:ring-primary focus:border-primary transition-all placeholder:text-on-surface-variant/30"
                    placeholder="hello@example.com" />
            </div>
            <x-input-error :messages="$errors->get('form.email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div x-data="{ showPw: false }">
            <div class="flex justify-between items-center mb-2">
                <label for="password" class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider">Password</label>
                @if (Route::has('password.request'))
                    <a class="text-xs text-secondary hover:text-primary transition-colors font-bold" href="{{ route('password.request') }}" wire:navigate>
                        Forgot?
                    </a>
                @endif
            </div>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-primary">
                    <span class="material-symbols-outlined text-xl">lock</span>
                </span>
                <input wire:model="form.password" id="password" :type="showPw ? 'text' : 'password'" name="password" required autocomplete="current-password"
                    class="w-full pl-11 pr-10 py-3 rounded-2xl border border-outline-variant/30 bg-surface-container-low text-sm focus:ring-primary focus:border-primary transition-all placeholder:text-on-surface-variant/30"
                    placeholder="••••••••" />
                <button type="button" @click="showPw = !showPw" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-on-surface-variant/40 hover:text-primary transition-colors focus:outline-none">
                    <span class="material-symbols-outlined text-xl" x-text="showPw ? 'visibility_off' : 'visibility'">visibility</span>
                </button>
            </div>
            <x-input-error :messages="$errors->get('form.password')" class="mt-1" />
        </div>

        <!-- Remember Me -->
        <div class="block">
            <label for="remember" class="inline-flex items-center cursor-pointer">
                <input wire:model="form.remember" id="remember" type="checkbox" class="rounded border-outline-variant/30 text-primary shadow-sm focus:ring-primary focus:ring-offset-0 bg-surface-container-low" name="remember">
                <span class="ms-2 text-xs font-semibold text-on-surface-variant">Stay signed in for 30 days</span>
            </label>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full py-3 bg-gradient-to-r from-primary to-secondary text-white rounded-full font-black text-sm uppercase tracking-wider hover:scale-[1.01] active:scale-[0.99] transition-all duration-150 shadow-lg shadow-primary/25 mt-2 flex items-center justify-center gap-2">
            Sign In
        </button>
    </form>
    
    <!-- Register Link -->
    <div class="mt-8 pt-6 border-t border-outline-variant/20 text-center">
        <p class="text-xs text-on-surface-variant font-medium">
            New to the portal? 
            <a href="{{ route('register') }}" wire:navigate class="text-primary hover:underline font-bold">
                Create an account
            </a>
        </p>
    </div>
</div>
