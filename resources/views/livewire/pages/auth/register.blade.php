<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <!-- Brand elements matching mockup -->
    <div class="flex flex-col items-center mb-8">
        <div class="w-14 h-14 bg-primary rounded-2xl flex items-center justify-center shadow-lg shadow-primary/30 mb-4 animate-bounce-short">
            <span class="material-symbols-outlined text-white text-3xl">confirmation_number</span>
        </div>
        <h1 class="text-3xl font-black text-on-surface tracking-tight">Daftar Akun</h1>
        <span class="inline-block px-3 py-1 mt-2 text-[10px] font-bold uppercase tracking-wider text-primary bg-primary/10 rounded-full">
            Buat Akun Baru TiketBantu
        </span>
    </div>

    <form wire:submit="register" class="space-y-4" x-data="{ showPw: false, showConfirmPw: false }">
        <!-- Name -->
        <div>
            <label for="name" class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-2">Nama Lengkap</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-primary">
                    <span class="material-symbols-outlined text-xl">person</span>
                </span>
                <input wire:model="name" id="name" type="text" name="name" required autofocus autocomplete="name"
                    class="w-full pl-11 pr-4 py-3 rounded-2xl border border-outline-variant/30 bg-surface-container-low text-sm focus:ring-primary focus:border-primary transition-all placeholder:text-on-surface-variant/30"
                    placeholder="Nama lengkap Anda" />
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-2">Email Address</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-primary">
                    <span class="material-symbols-outlined text-xl">mail</span>
                </span>
                <input wire:model="email" id="email" type="email" name="email" required autocomplete="username"
                    class="w-full pl-11 pr-4 py-3 rounded-2xl border border-outline-variant/30 bg-surface-container-low text-sm focus:ring-primary focus:border-primary transition-all placeholder:text-on-surface-variant/30"
                    placeholder="email@contoh.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-2">Password</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-primary">
                    <span class="material-symbols-outlined text-xl">lock</span>
                </span>
                <input wire:model="password" id="password" :type="showPw ? 'text' : 'password'" name="password" required autocomplete="new-password"
                    class="w-full pl-11 pr-10 py-3 rounded-2xl border border-outline-variant/30 bg-surface-container-low text-sm focus:ring-primary focus:border-primary transition-all placeholder:text-on-surface-variant/30"
                    placeholder="Min. 8 karakter" />
                <button type="button" @click="showPw = !showPw" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-on-surface-variant/40 hover:text-primary transition-colors focus:outline-none">
                    <span class="material-symbols-outlined text-xl" x-text="showPw ? 'visibility_off' : 'visibility'">visibility</span>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-2">Konfirmasi Password</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-primary">
                    <span class="material-symbols-outlined text-xl">lock</span>
                </span>
                <input wire:model="password_confirmation" id="password_confirmation" :type="showConfirmPw ? 'text' : 'password'" name="password_confirmation" required autocomplete="new-password"
                    class="w-full pl-11 pr-10 py-3 rounded-2xl border border-outline-variant/30 bg-surface-container-low text-sm focus:ring-primary focus:border-primary transition-all placeholder:text-on-surface-variant/30"
                    placeholder="Ulangi password" />
                <button type="button" @click="showConfirmPw = !showConfirmPw" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-on-surface-variant/40 hover:text-primary transition-colors focus:outline-none">
                    <span class="material-symbols-outlined text-xl" x-text="showConfirmPw ? 'visibility_off' : 'visibility'">visibility</span>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full py-3 bg-gradient-to-r from-primary to-secondary text-white rounded-full font-black text-sm uppercase tracking-wider hover:scale-[1.01] active:scale-[0.99] transition-all duration-150 shadow-lg shadow-primary/25 mt-4 flex items-center justify-center gap-2">
            Daftar Sekarang
        </button>
    </form>

    <!-- Login Link -->
    <div class="mt-6 pt-6 border-t border-outline-variant/20 text-center">
        <p class="text-xs text-on-surface-variant font-medium">
            Sudah terdaftar? 
            <a href="{{ route('login') }}" class="text-primary hover:underline font-bold">
                Masuk
            </a>
        </p>
    </div>
</div>

