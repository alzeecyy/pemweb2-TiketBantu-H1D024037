<div>
    <!-- Headline Greeting -->
    <div class="mb-12">
        <h2 class="text-4xl md:text-6xl font-black text-on-surface font-display tracking-tight leading-tight">
            Selamat Datang, <span class="text-primary italic">{{ auth()->user()->name }}</span>
        </h2>
        <p class="text-on-surface-variant mt-4 text-lg max-w-2xl font-medium">
            Berikut ringkasan aktivitas sistem Anda hari ini.
        </p>
    </div>

    <!-- ==================== ADMIN DASHBOARD ==================== -->
    @if(auth()->user()->role === 'admin')
        <!-- Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <div class="glass-card p-6 rounded-lg flex flex-col justify-between h-48 border-t-4 border-t-primary hover:scale-[1.02] transition-all duration-300">
                <span class="material-symbols-outlined text-primary text-3xl">analytics</span>
                <div>
                    <p class="text-5xl font-black text-on-surface mb-1">{{ $totalTickets }}</p>
                    <p class="text-sm font-bold text-on-surface-variant uppercase tracking-widest">Total Pengaduan</p>
                </div>
            </div>

            <div class="glass-card p-6 rounded-lg flex flex-col justify-between h-48 border-t-4 border-t-error hover:scale-[1.02] transition-all duration-300">
                <span class="material-symbols-outlined text-error text-3xl">inbox</span>
                <div>
                    <p class="text-5xl font-black text-on-surface mb-1">{{ $baruCount }}</p>
                    <p class="text-sm font-bold text-on-surface-variant uppercase tracking-widest">Tiket Baru</p>
                </div>
            </div>

            <div class="glass-card p-6 rounded-lg flex flex-col justify-between h-48 border-t-4 border-t-tertiary hover:scale-[1.02] transition-all duration-300">
                <span class="material-symbols-outlined text-tertiary text-3xl">pending_actions</span>
                <div>
                    <p class="text-5xl font-black text-on-surface mb-1">{{ $prosesCount }}</p>
                    <p class="text-sm font-bold text-on-surface-variant uppercase tracking-widest">Sedang Diproses</p>
                </div>
            </div>

            <div class="glass-card p-6 rounded-lg flex flex-col justify-between h-48 border-t-4 border-t-success hover:scale-[1.02] transition-all duration-300">
                <span class="material-symbols-outlined text-success text-3xl">check_circle</span>
                <div>
                    <p class="text-5xl font-black text-on-surface mb-1">{{ $selesaiCount }}</p>
                    <p class="text-sm font-bold text-on-surface-variant uppercase tracking-widest">Selesai</p>
                </div>
            </div>
        </div>
    @endif

    <!-- ==================== AGENT DASHBOARD ==================== -->
    @if(auth()->user()->role === 'agent')
        <!-- Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-12">
            <div class="glass-card p-6 rounded-lg flex flex-col justify-between h-48 border-t-4 border-t-primary hover:scale-[1.02] transition-all duration-300">
                <span class="material-symbols-outlined text-primary text-3xl">assignment</span>
                <div>
                    <p class="text-5xl font-black text-on-surface mb-1">{{ $assignedTicketsCount }}</p>
                    <p class="text-sm font-bold text-on-surface-variant uppercase tracking-widest">Ditugaskan ke Saya</p>
                </div>
            </div>

            <div class="glass-card p-6 rounded-lg flex flex-col justify-between h-48 border-t-4 border-t-error hover:scale-[1.02] transition-all duration-300">
                <span class="material-symbols-outlined text-error text-3xl">priority_high</span>
                <div>
                    <p class="text-5xl font-black text-on-surface mb-1">{{ $highPriorityCount }}</p>
                    <p class="text-sm font-bold text-on-surface-variant uppercase tracking-widest">Prioritas Tinggi</p>
                </div>
            </div>

            <div class="glass-card p-6 rounded-lg flex flex-col justify-between h-48 border-t-4 border-t-tertiary hover:scale-[1.02] transition-all duration-300">
                <span class="material-symbols-outlined text-tertiary text-3xl">autorenew</span>
                <div>
                    <p class="text-5xl font-black text-on-surface mb-1">{{ $unresolvedCount }}</p>
                    <p class="text-sm font-bold text-on-surface-variant uppercase tracking-widest">Belum Selesai</p>
                </div>
            </div>
        </div>

        <!-- Prioritas akan ditampilkan di bawah untuk semua role -->
    @endif

    <!-- ==================== USER/PELAPOR DASHBOARD ==================== -->
    @if(auth()->user()->role === 'user')
        <!-- Quick Action & Stats Grid (Flat 6-Column Layout) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-6 mb-12">
            <div class="glass-card p-6 rounded-lg flex flex-col justify-between h-48 border-t-4 border-t-primary hover:scale-[1.02] transition-all duration-300">
                <span class="material-symbols-outlined text-primary text-3xl">assignment_turned_in</span>
                <div>
                    <p class="text-5xl font-black text-on-surface mb-1">{{ $totalTickets }}</p>
                    <p class="text-sm font-bold text-on-surface-variant uppercase tracking-widest text-xs">Total Tiket Saya</p>
                </div>
            </div>

            <!-- Tiket Baru -->
            <div class="glass-card p-6 rounded-lg flex flex-col justify-between h-48 border-t-4 border-t-error hover:scale-[1.02] transition-all duration-300">
                <span class="material-symbols-outlined text-error text-3xl">inbox</span>
                <div>
                    <p class="text-5xl font-black text-on-surface mb-1">{{ $baruCount }}</p>
                    <p class="text-sm font-bold text-on-surface-variant uppercase tracking-widest text-xs">Tiket Baru</p>
                </div>
            </div>

            <!-- Sedang Aktif -->
            <div class="glass-card p-6 rounded-lg flex flex-col justify-between h-48 border-t-4 border-t-tertiary hover:scale-[1.02] transition-all duration-300">
                <span class="material-symbols-outlined text-tertiary text-3xl">sync</span>
                <div>
                    <p class="text-5xl font-black text-on-surface mb-1">{{ $prosesCount }}</p>
                    <p class="text-sm font-bold text-on-surface-variant uppercase tracking-widest text-xs">Dalam Proses</p>
                </div>
            </div>

            <!-- Selesai -->
            <div class="glass-card p-6 rounded-lg flex flex-col justify-between h-48 border-t-4 border-t-success hover:scale-[1.02] transition-all duration-300">
                <span class="material-symbols-outlined text-success text-3xl">verified</span>
                <div>
                    <p class="text-5xl font-black text-on-surface mb-1">{{ $selesaiCount }}</p>
                    <p class="text-sm font-bold text-on-surface-variant uppercase tracking-widest text-xs">Selesai</p>
                </div>
            </div>

            <!-- Ditutup -->
            <div class="glass-card p-6 rounded-lg flex flex-col justify-between h-48 border-t-4 border-t-outline-variant hover:scale-[1.02] transition-all duration-300">
                <span class="material-symbols-outlined text-outline-variant text-3xl">cancel</span>
                <div>
                    <p class="text-5xl font-black text-on-surface mb-1">{{ $ditutupCount }}</p>
                    <p class="text-sm font-bold text-on-surface-variant uppercase tracking-widest text-xs">Ditutup</p>
                </div>
            </div>

            <!-- Quick Action Card (Buat Tiket) -->
            <div class="glass-card rounded-lg p-6 border border-outline-variant/30 flex flex-col justify-between text-center bg-gradient-to-br from-primary/10 to-secondary/10 h-48 hover:scale-[1.02] transition-all duration-300">
                <div class="flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-primary text-2xl">support_agent</span>
                    <h3 class="font-black text-on-surface text-sm">Butuh Bantuan?</h3>
                </div>
                <p class="text-[10px] text-on-surface-variant font-medium leading-tight px-1">
                    Buat tiket pengaduan baru dan petugas kami akan segera membantu.
                </p>
                <a href="{{ route('tickets.create') }}" wire:navigate class="w-full py-2 bg-gradient-to-r from-primary to-secondary text-white rounded-xl text-[10px] font-bold uppercase tracking-wider hover:scale-[1.02] active:scale-[0.98] transition-all duration-150 shadow-md shadow-primary/20">
                    Buat Tiket
                </a>
            </div>
        </div>

        <!-- Prioritas akan ditampilkan di bawah untuk semua role -->
    @endif

    <!-- ==================== PRIORITAS TIKET (SEMUA ROLE) ==================== -->
    <div class="mt-8">
        <h3 class="text-2xl font-black text-on-surface tracking-tight mb-6">Prioritas Tiket Terbuka</h3>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- URGENT -->
            <div class="glass-card rounded-2xl p-6 border border-error/30 shadow-lg shadow-error/10 relative overflow-hidden group">
                <div class="absolute top-0 left-0 w-full h-1 bg-error"></div>
                <div class="flex items-center gap-2 mb-4">
                    <span class="material-symbols-outlined text-error animate-pulse">warning</span>
                    <h4 class="font-black text-error tracking-wider uppercase">Urgent</h4>
                    <span class="ml-auto bg-error/10 text-error text-xs font-bold px-2 py-0.5 rounded-full">{{ count($urgentTickets) }}</span>
                </div>
                
                @if($urgentTickets->isEmpty())
                    <p class="text-sm text-on-surface-variant/60 italic py-4">Tidak ada tiket urgent.</p>
                @else
                    <div class="space-y-3">
                        @foreach($urgentTickets as $ticket)
                            <a href="{{ route('tickets.show', $ticket->id) }}" wire:navigate class="block p-3 rounded-xl bg-surface hover:bg-error/5 border border-outline-variant/10 transition-colors">
                                <div class="flex justify-between items-start mb-1">
                                    <span class="text-[10px] font-black text-on-surface-variant">#{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</span>
                                    <span class="text-[10px] font-bold text-on-surface-variant">{{ $ticket->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm font-bold text-on-surface line-clamp-1 mb-1">{{ $ticket->title }}</p>
                                <p class="text-xs text-on-surface-variant">{{ $ticket->user->name }} &bull; {{ ucfirst($ticket->status) }}</p>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- MEDIUM -->
            <div class="glass-card rounded-2xl p-6 border border-orange-500/30 shadow-lg shadow-orange-500/10 relative overflow-hidden group">
                <div class="absolute top-0 left-0 w-full h-1 bg-orange-500"></div>
                <div class="flex items-center gap-2 mb-4">
                    <span class="material-symbols-outlined text-orange-500">priority</span>
                    <h4 class="font-black text-orange-500 tracking-wider uppercase">Medium</h4>
                    <span class="ml-auto bg-orange-500/10 text-orange-500 text-xs font-bold px-2 py-0.5 rounded-full">{{ count($mediumTickets) }}</span>
                </div>
                
                @if($mediumTickets->isEmpty())
                    <p class="text-sm text-on-surface-variant/60 italic py-4">Tidak ada tiket medium.</p>
                @else
                    <div class="space-y-3">
                        @foreach($mediumTickets as $ticket)
                            <a href="{{ route('tickets.show', $ticket->id) }}" wire:navigate class="block p-3 rounded-xl bg-surface hover:bg-orange-500/5 border border-outline-variant/10 transition-colors">
                                <div class="flex justify-between items-start mb-1">
                                    <span class="text-[10px] font-black text-on-surface-variant">#{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</span>
                                    <span class="text-[10px] font-bold text-on-surface-variant">{{ $ticket->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm font-bold text-on-surface line-clamp-1 mb-1">{{ $ticket->title }}</p>
                                <p class="text-xs text-on-surface-variant">{{ $ticket->user->name }} &bull; {{ ucfirst($ticket->status) }}</p>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- LOW -->
            <div class="glass-card rounded-2xl p-6 border border-yellow-500/30 shadow-lg shadow-yellow-500/10 relative overflow-hidden group">
                <div class="absolute top-0 left-0 w-full h-1 bg-yellow-500"></div>
                <div class="flex items-center gap-2 mb-4">
                    <span class="material-symbols-outlined text-yellow-500">low_priority</span>
                    <h4 class="font-black text-yellow-500 tracking-wider uppercase">Low</h4>
                    <span class="ml-auto bg-yellow-500/10 text-yellow-500 text-xs font-bold px-2 py-0.5 rounded-full">{{ count($lowTickets) }}</span>
                </div>
                
                @if($lowTickets->isEmpty())
                    <p class="text-sm text-on-surface-variant/60 italic py-4">Tidak ada tiket low.</p>
                @else
                    <div class="space-y-3">
                        @foreach($lowTickets as $ticket)
                            <a href="{{ route('tickets.show', $ticket->id) }}" wire:navigate class="block p-3 rounded-xl bg-surface hover:bg-yellow-500/5 border border-outline-variant/10 transition-colors">
                                <div class="flex justify-between items-start mb-1">
                                    <span class="text-[10px] font-black text-on-surface-variant">#{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</span>
                                    <span class="text-[10px] font-bold text-on-surface-variant">{{ $ticket->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm font-bold text-on-surface line-clamp-1 mb-1">{{ $ticket->title }}</p>
                                <p class="text-xs text-on-surface-variant">{{ $ticket->user->name }} &bull; {{ ucfirst($ticket->status) }}</p>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>
