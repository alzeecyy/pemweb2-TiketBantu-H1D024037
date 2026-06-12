<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700;900&display=swap" rel="stylesheet"/>
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-background text-on-surface">
        <div class="mesh-gradient"></div>
        <div class="mesh-gradient-extra"></div>
        <div class="min-h-screen flex flex-col relative">
            <livewire:layout.navigation />

            <!-- Page Content -->
            <main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 mb-24 lg:mb-0">
                {{ $slot }}
            </main>
        </div>

        <!-- Premium Toast Notification System -->
        <div x-data="{
            toasts: [],
            add(toast) {
                toast.id = Date.now();
                this.toasts.push(toast);
                setTimeout(() => {
                    this.remove(toast.id);
                }, 4000);
            },
            remove(id) {
                this.toasts = this.toasts.filter(t => t.id !== id);
            }
        }"
        @toast.window="add($event.detail)"
        x-init="
            @if(session()->has('message'))
                $nextTick(() => { add({ message: '{{ session('message') }}', type: 'success' }) });
            @endif
            @if(session()->has('error'))
                $nextTick(() => { add({ message: '{{ session('error') }}', type: 'error' }) });
            @endif
        "
        class="fixed top-6 right-6 z-50 flex flex-col gap-3 pointer-events-none max-w-sm w-full"
        >
            <template x-for="toast in toasts" :key="toast.id">
                <div x-show="true"
                     x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="translate-x-full opacity-0"
                     x-transition:enter-end="translate-x-0 opacity-100"
                     x-transition:leave="transition ease-in duration-200 transform"
                     x-transition:leave-start="translate-x-0 opacity-100"
                     x-transition:leave-end="translate-x-full opacity-0"
                     class="pointer-events-auto p-4 rounded-2xl shadow-2xl flex items-center gap-3 border backdrop-blur-md transition-all duration-300 bg-surface-container-high/90 border-outline-variant/20 text-on-surface"
                     :class="{
                         'bg-primary-fixed/95 border-primary/20 text-on-primary-fixed-variant': toast.type === 'success',
                         'bg-error-container/95 border-error/20 text-on-error-container': toast.type === 'error',
                         'bg-warning-container/95 border-warning/20 text-on-warning-container': toast.type === 'warning',
                         'bg-surface-container-high/95 border-outline-variant/20 text-on-surface': toast.type === 'info' || !toast.type
                     }"
                >
                    <span class="material-symbols-outlined text-2xl shrink-0"
                          x-text="toast.type === 'success' ? 'check_circle' : (toast.type === 'error' ? 'error' : (toast.type === 'warning' ? 'warning' : 'info'))">
                    </span>
                    <div class="flex-1 text-sm font-bold" x-text="toast.message"></div>
                    <button @click="remove(toast.id)" class="text-on-surface-variant hover:text-on-surface shrink-0 p-1 rounded-full hover:bg-surface-variant/20 transition-all">
                        <span class="material-symbols-outlined text-sm">close</span>
                    </button>
                </div>
            </template>
        </div>

        <!-- Livewire Sortable Plugin -->
        <script src="https://cdn.jsdelivr.net/npm/livewire-sortable@1.0.0/dist/livewire-sortable.min.js" defer></script>
    </body>
</html>


