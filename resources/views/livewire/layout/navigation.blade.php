<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }

    /**
     * Fetch recent activity for live notifications dropdown.
     */
    public function with(): array
    {
        $user = auth()->user();
        $notifications = [];

        if ($user) {
            // 1. Admin notifications: new tickets, comments
            if ($user->role === 'admin') {
                $recentTickets = \App\Models\Ticket::orderBy('created_at', 'desc')->limit(3)->get();
                foreach ($recentTickets as $ticket) {
                    $notifications[] = [
                        'id' => 't-' . $ticket->id,
                        'text' => "Tiket baru #{$ticket->id}: {$ticket->title}",
                        'time' => $ticket->created_at->diffForHumans(),
                        'url' => route('tickets.show', $ticket->id),
                        'icon' => 'add_alert',
                        'color' => 'bg-primary/15 text-primary',
                    ];
                }
            } 
            // 2. Agent notifications: assigned to me, unassigned tickets
            elseif ($user->role === 'agent') {
                $assignedTickets = \App\Models\Ticket::where('agent_id', $user->id)
                    ->orderBy('updated_at', 'desc')->limit(2)->get();
                foreach ($assignedTickets as $ticket) {
                    $notifications[] = [
                        'id' => 't-a-' . $ticket->id,
                        'text' => "Tiket #{$ticket->id} ditugaskan ke Anda.",
                        'time' => $ticket->updated_at->diffForHumans(),
                        'url' => route('tickets.show', $ticket->id),
                        'icon' => 'assignment_ind',
                        'color' => 'bg-secondary/15 text-secondary',
                    ];
                }

                $unassignedTickets = \App\Models\Ticket::whereNull('agent_id')
                    ->orderBy('created_at', 'desc')->limit(2)->get();
                foreach ($unassignedTickets as $ticket) {
                    $notifications[] = [
                        'id' => 't-u-' . $ticket->id,
                        'text' => "Tiket baru #{$ticket->id} belum ditugaskan.",
                        'time' => $ticket->created_at->diffForHumans(),
                        'url' => route('tickets.show', $ticket->id),
                        'icon' => 'help_outline',
                        'color' => 'bg-tertiary/15 text-tertiary',
                    ];
                }
            } 
            // 3. User notifications: status changes on my tickets
            else {
                $myTickets = \App\Models\Ticket::where('user_id', $user->id)
                    ->orderBy('updated_at', 'desc')->limit(3)->get();
                foreach ($myTickets as $ticket) {
                    $statusText = ucfirst($ticket->status);
                    $notifications[] = [
                        'id' => 't-m-' . $ticket->id,
                        'text' => "Status tiket #{$ticket->id} Anda: {$statusText}",
                        'time' => $ticket->updated_at->diffForHumans(),
                        'url' => route('tickets.show', $ticket->id),
                        'icon' => $ticket->status === 'selesai' ? 'check_circle' : 'pending_actions',
                        'color' => $ticket->status === 'selesai' ? 'bg-success/15 text-success' : 'bg-primary/15 text-primary',
                    ];
                }
            }

            // 4. Comments notifications: comments by others on relevant tickets
            $recentComments = \App\Models\Comment::with(['user', 'ticket'])
                ->whereHas('ticket', function ($q) use ($user) {
                    if ($user->role === 'user') {
                        $q->where('user_id', $user->id);
                    } elseif ($user->role === 'agent') {
                        $q->where('agent_id', $user->id);
                    }
                })
                ->where('user_id', '!=', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(2)
                ->get();

            foreach ($recentComments as $comment) {
                $notifications[] = [
                    'id' => 'c-' . $comment->id,
                    'text' => "{$comment->user->name} berkomentar di #{$comment->ticket->id}",
                    'time' => $comment->created_at->diffForHumans(),
                    'url' => route('tickets.show', $comment->ticket_id),
                    'icon' => 'chat',
                    'color' => 'bg-tertiary/15 text-tertiary',
                ];
            }
        }

        return [
            'notifications' => $notifications
        ];
    }
}; ?>

<div class="sticky top-0 z-50 w-full py-4 px-4 sm:px-6 lg:px-8 bg-transparent">
<header x-data="{ open: false, profileOpen: false }" class="max-w-7xl mx-auto bg-white shadow-lg rounded-full px-8 py-3 flex justify-between items-center border border-outline-variant/10">
    <div class="flex items-center gap-4">
        <a href="{{ route('tickets.index') }}" wire:navigate class="text-2xl font-black text-primary italic tracking-tighter hover:scale-105 transition-transform duration-300">
            TiketBantu
        </a>
        
        <!-- Desktop Nav: Dashboard (leftmost) -> others -->
        <nav class="hidden md:flex gap-6 ml-8">
            @if(auth()->user()->role === 'admin')
                <a class="{{ request()->routeIs('dashboard') ? 'text-primary font-bold border-b-2 border-primary pb-1' : 'text-on-surface-variant font-medium' }} hover:scale-105 transition-transform duration-300 ease-out" href="{{ route('dashboard') }}" wire:navigate>Home</a>
                <a class="{{ (request()->routeIs('tickets.*') && !request()->routeIs('tickets.create')) ? 'text-primary font-bold border-b-2 border-primary pb-1' : 'text-on-surface-variant font-medium' }} hover:scale-105 transition-transform duration-300 ease-out" href="{{ route('tickets.index') }}" wire:navigate>Tickets</a>
                <a class="{{ request()->routeIs('categories.*') ? 'text-primary font-bold border-b-2 border-primary pb-1' : 'text-on-surface-variant font-medium' }} hover:scale-105 transition-transform duration-300 ease-out" href="{{ route('categories.index') }}" wire:navigate>Categories</a>
                <a class="{{ request()->routeIs('users.*') ? 'text-primary font-bold border-b-2 border-primary pb-1' : 'text-on-surface-variant font-medium' }} hover:scale-105 transition-transform duration-300 ease-out" href="{{ route('users.index') }}" wire:navigate>Customers</a>
            @elseif(auth()->user()->role === 'agent')
                <a class="{{ request()->routeIs('dashboard') ? 'text-primary font-bold border-b-2 border-primary pb-1' : 'text-on-surface-variant font-medium' }} hover:scale-105 transition-transform duration-300 ease-out" href="{{ route('dashboard') }}" wire:navigate>Home</a>
                <a class="{{ request()->routeIs('tickets.*') ? 'text-primary font-bold border-b-2 border-primary pb-1' : 'text-on-surface-variant font-medium' }} hover:scale-105 transition-transform duration-300 ease-out" href="{{ route('tickets.index') }}" wire:navigate>Tickets</a>
            @else {{-- user / pelapor --}}
                <a class="{{ (request()->routeIs('tickets.index') || request()->routeIs('tickets.show') || request()->routeIs('tickets.edit')) ? 'text-primary font-bold border-b-2 border-primary pb-1' : 'text-on-surface-variant font-medium' }} hover:scale-105 transition-transform duration-300 ease-out" href="{{ route('tickets.index') }}" wire:navigate>Tickets</a>
                <a class="{{ request()->routeIs('dashboard') ? 'text-primary font-bold border-b-2 border-primary pb-1' : 'text-on-surface-variant font-medium' }} hover:scale-105 transition-transform duration-300 ease-out" href="{{ route('dashboard') }}" wire:navigate>Dashboard</a>
                <a class="{{ request()->routeIs('tickets.create') ? 'text-primary font-bold border-b-2 border-primary pb-1' : 'text-on-surface-variant font-medium' }} hover:scale-105 transition-transform duration-300 ease-out" href="{{ route('tickets.create') }}" wire:navigate>Create Ticket</a>
            @endif
        </nav>
    </div>
    
    <div class="flex items-center gap-3">
        <!-- Interactive Notifications Dropdown -->
        <div class="relative" x-data="{ notificationsOpen: false }">
            <button @click="notificationsOpen = !notificationsOpen" class="p-2 text-on-surface-variant hover:scale-110 transition-transform relative focus:outline-none flex items-center justify-center">
                <span class="material-symbols-outlined text-2xl">notifications</span>
                @if(count($notifications) > 0)
                    <span class="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-primary rounded-full ring-2 ring-background animate-pulse"></span>
                @endif
            </button>
 
            <!-- Dropdown list (Solid white background) -->
            <div x-show="notificationsOpen" @click.outside="notificationsOpen = false" x-transition class="absolute right-0 mt-3 w-80 bg-white border border-outline-variant/20 rounded-2xl shadow-2xl py-3 z-50">
                <div class="px-4 pb-2 border-b border-outline-variant/20 flex justify-between items-center">
                    <span class="text-xs font-black text-on-surface uppercase tracking-wider">Notifikasi Baru</span>
                    @if(count($notifications) > 0)
                        <span class="px-2 py-0.5 text-[10px] font-bold bg-primary/10 text-primary rounded-full">{{ count($notifications) }} Baru</span>
                    @endif
                </div>
                
                <div class="max-h-72 overflow-y-auto mt-2 divide-y divide-outline-variant/10">
                    @forelse($notifications as $n)
                        <a href="{{ $n['url'] }}" wire:navigate class="flex items-start gap-3 px-4 py-3 hover:bg-primary/5 transition-colors block">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 {{ $n['color'] }}">
                                <span class="material-symbols-outlined text-sm">{{ $n['icon'] }}</span>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs font-bold text-on-surface leading-snug">{{ $n['text'] }}</p>
                                <span class="text-[9px] text-on-surface-variant/60 font-semibold block mt-1 text-left">{{ $n['time'] }}</span>
                            </div>
                        </a>
                    @empty
                        <div class="px-4 py-6 text-center text-on-surface-variant/60 text-xs italic">
                            Tidak ada notifikasi baru.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
 
        <!-- User Dropdown Menu -->
        <div class="relative">
            <button @click="profileOpen = !profileOpen" class="flex items-center focus:outline-none">
                <div class="w-9 h-9 rounded-full border-2 border-primary overflow-hidden bg-gradient-to-br from-primary to-secondary flex items-center justify-center text-white font-black text-xs shadow-md hover:scale-105 transition-transform duration-300">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
            </button>
 
            <!-- Dropdown Menu items (Solid white background) -->
            <div x-show="profileOpen" @click.outside="profileOpen = false" x-transition class="absolute right-0 mt-3 w-48 bg-white border border-outline-variant/20 rounded-2xl shadow-2xl py-2 z-50">
                <div class="px-4 py-2 border-b border-outline-variant/20">
                    <p class="text-sm font-black text-on-surface">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-on-surface-variant truncate">{{ auth()->user()->email }}</p>
                    <span class="inline-block px-2 py-0.5 mt-1 text-[9px] font-bold uppercase rounded-full bg-primary-fixed text-on-primary-fixed-variant">
                        {{ auth()->user()->role }}
                    </span>
                </div>
                
                <a href="{{ route('profile') }}" wire:navigate class="block w-full text-left px-4 py-2 text-sm text-on-surface hover:bg-primary/10 transition-colors">
                    {{ __('Profile') }}
                </a>
 
                <button wire:click="logout" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-error-container/50 transition-colors">
                    {{ __('Log Out') }}
                </button>
            </div>
        </div>
 
        <!-- Mobile Menu Hamburger -->
        <button @click="open = !open" class="md:hidden p-2 text-on-surface-variant hover:scale-110 transition-transform">
            <span class="material-symbols-outlined" x-text="open ? 'close' : 'menu'">menu</span>
        </button>
    </div>
 
    <!-- Mobile Drawer (Solid white background) -->
    <div x-show="open" @click.outside="open = false" x-transition class="absolute top-20 left-6 right-6 bg-white border border-outline-variant/20 rounded-3xl shadow-2xl py-4 px-6 md:hidden z-50">
        <nav class="flex flex-col gap-2">
            @if(auth()->user()->role === 'admin')
                <a class="text-on-surface font-medium hover:text-primary transition-colors py-2 border-b border-outline-variant/10" href="{{ route('dashboard') }}" wire:navigate>Home</a>
                <a class="text-on-surface font-medium hover:text-primary transition-colors py-2 border-b border-outline-variant/10" href="{{ route('tickets.index') }}" wire:navigate>Tickets</a>
                <a class="text-on-surface font-medium hover:text-primary transition-colors py-2 border-b border-outline-variant/10" href="{{ route('categories.index') }}" wire:navigate>Categories</a>
                <a class="text-on-surface font-medium hover:text-primary transition-colors py-2" href="{{ route('users.index') }}" wire:navigate>Customers</a>
            @elseif(auth()->user()->role === 'agent')
                <a class="text-on-surface font-medium hover:text-primary transition-colors py-2 border-b border-outline-variant/10" href="{{ route('dashboard') }}" wire:navigate>Home</a>
                <a class="text-on-surface font-medium hover:text-primary transition-colors py-2" href="{{ route('tickets.index') }}" wire:navigate>Tickets</a>
            @else {{-- user / pelapor --}}
                <a class="text-on-surface font-medium hover:text-primary transition-colors py-2 border-b border-outline-variant/10" href="{{ route('tickets.index') }}" wire:navigate>Tickets</a>
                <a class="text-on-surface font-medium hover:text-primary transition-colors py-2 border-b border-outline-variant/10" href="{{ route('dashboard') }}" wire:navigate>Dashboard</a>
                <a class="text-on-surface font-medium hover:text-primary transition-colors py-2" href="{{ route('tickets.create') }}" wire:navigate>Create Ticket</a>
            @endif
        </nav>
    </div>
</header>
</div>
