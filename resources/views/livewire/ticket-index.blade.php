<div>
    <!-- Floating FAB to Create Ticket -->
    @if(auth()->user()->role === 'user' || auth()->user()->role === 'admin')
        <a href="{{ route('tickets.create') }}" class="fixed bottom-8 right-8 w-16 h-16 bg-primary text-white rounded-full shadow-2xl shadow-primary/40 flex items-center justify-center group hover:scale-110 active:scale-95 transition-all z-40">
            <span class="material-symbols-outlined text-3xl group-hover:rotate-90 transition-transform duration-300">add</span>
        </a>
    @endif

    <!-- Headline Section -->
    <div class="mb-12">
        <h2 class="text-4xl md:text-6xl font-black text-on-surface font-display tracking-tight leading-tight">
            Daftar Tiket <span class="text-primary italic">Pengaduan</span>
        </h2>
        <p class="text-on-surface-variant mt-4 text-lg max-w-2xl font-medium">
            Solusi terpercaya untuk setiap aduan Anda.
        </p>
    </div>

    <!-- Bento Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        <!-- Tiket Baru -->
        <div class="glass-card p-6 rounded-lg flex flex-col justify-between h-48 group hover:scale-[1.02] transition-all duration-300 border-t-4 border-t-primary">
            <span class="material-symbols-outlined text-primary text-3xl">inbox</span>
            <div>
                <p class="text-5xl font-black text-on-surface mb-1">{{ $baruCount }}</p>
                <p class="text-sm font-bold text-on-surface-variant uppercase tracking-widest">Tiket Baru</p>
            </div>
        </div>

        <!-- Dalam Proses -->
        <div class="glass-card p-6 rounded-lg flex flex-col justify-between h-48 group hover:scale-[1.02] transition-all duration-300 border-t-4 border-t-tertiary">
            <span class="material-symbols-outlined text-tertiary text-3xl">pending_actions</span>
            <div>
                <p class="text-5xl font-black text-on-surface mb-1">{{ $prosesCount }}</p>
                <p class="text-sm font-bold text-on-surface-variant uppercase tracking-widest">Dalam Proses</p>
            </div>
        </div>

        <!-- Selesai -->
        <div class="glass-card p-6 rounded-lg flex flex-col justify-between h-48 group hover:scale-[1.02] transition-all duration-300 border-t-4 border-t-secondary">
            <span class="material-symbols-outlined text-secondary text-3xl">verified</span>
            <div>
                <p class="text-5xl font-black text-on-surface mb-1">{{ $selesaiCount }}</p>
                <p class="text-sm font-bold text-on-surface-variant uppercase tracking-widest">Selesai</p>
            </div>
        </div>

        <!-- SLA Success Rate -->
        <div class="relative overflow-hidden p-6 rounded-lg flex flex-col justify-between h-48 bg-primary text-white shadow-xl shadow-primary/30 group hover:scale-[1.02] transition-all duration-300">
            <div class="absolute -right-4 -top-4 opacity-20 group-hover:rotate-12 transition-transform duration-500">
                <span class="material-symbols-outlined text-9xl">auto_awesome</span>
            </div>
            <span class="material-symbols-outlined text-3xl">speed</span>
            <div>
                <p class="text-4xl font-black mb-1">{{ $slaSuccess }}%</p>
                <p class="text-sm font-bold uppercase tracking-widest opacity-90">SLA Success</p>
            </div>
        </div>
    </div>

    <!-- Filter & Search Card -->
    <div class="glass-card rounded-2xl p-6 mb-8 shadow-lg border border-outline-variant/20">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search Input -->
            <div>
                <label for="search" class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-2">Cari Tiket</label>
                <div class="flex items-center bg-surface-container-low rounded-lg px-3 py-1.5 border border-outline-variant/30 focus-within:ring-2 focus-within:ring-primary focus-within:border-transparent transition-all">
                    <span class="material-symbols-outlined text-on-surface-variant text-sm mr-2">search</span>
                    <input wire:model.live.debounce.300ms="search" type="text" id="search" placeholder="Cari judul, deskripsi, pelapor..." class="bg-transparent border-none focus:ring-0 text-sm w-full placeholder:text-on-surface-variant/40">
                </div>
            </div>

            <!-- Category Filter -->
            <div>
                <label for="category" class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-2">Kategori</label>
                <select wire:model.live="category_id" id="category" class="w-full rounded-lg border-outline-variant/30 bg-surface-container-low text-sm focus:ring-primary focus:border-primary transition-all">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Priority Filter -->
            <div>
                <label for="priority" class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-2">Prioritas</label>
                <select wire:model.live="priority" id="priority" class="w-full rounded-lg border-outline-variant/30 bg-surface-container-low text-sm focus:ring-primary focus:border-primary transition-all">
                    <option value="">Semua Prioritas</option>
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                </select>
            </div>

            <!-- Status Filter -->
            <div>
                <label for="status" class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-2">Status</label>
                <select wire:model.live="status" id="status" class="w-full rounded-lg border-outline-variant/30 bg-surface-container-low text-sm focus:ring-primary focus:border-primary transition-all">
                    <option value="">Semua Status</option>
                    <option value="baru">Baru</option>
                    <option value="diproses">Diproses</option>
                    <option value="selesai">Selesai</option>
                    <option value="ditutup">Ditutup</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Info drag-and-drop untuk admin/agent --}}
    @if($canSort)
        <div class="mb-6 flex items-center gap-2 text-xs text-primary bg-primary-fixed/20 border border-primary-fixed-dim/30 rounded-xl px-4 py-3">
            <span class="material-symbols-outlined text-sm">info</span>
            <span><strong>Drag & Drop:</strong> Seret kartu tiket menggunakan ikon ⠿ di bagian pojok kanan atas kartu untuk mengurutkan prioritas penanganan.</span>
        </div>
    @endif

    <!-- Ticket Grid: Boarding Pass Style -->
    @if($tickets->isEmpty())
        <div class="glass-card text-center py-12 rounded-2xl shadow border border-outline-variant/20">
            <span class="material-symbols-outlined text-5xl text-on-surface-variant/40 mb-2">inbox</span>
            <p class="text-on-surface-variant font-medium">Tidak ada tiket pengaduan ditemukan.</p>
        </div>
    @else
        <div 
            @if($canSort)
                wire:sortable="handleSort"
            @endif
            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"
        >
            @foreach($tickets as $ticket)
                @php
                    // Map styles according to priority
                    if ($ticket->priority === 'high') {
                        $bgColor = 'bg-red-500';
                        $neonGlow = 'neon-glow-red';
                        $bannerText = 'URGENT';
                        $statusLabelColor = 'text-red-500';
                    } elseif ($ticket->priority === 'medium') {
                        $bgColor = 'bg-orange-500';
                        $neonGlow = 'neon-glow-orange';
                        $bannerText = 'MEDIUM';
                        $statusLabelColor = 'text-orange-500';
                    } else { // low
                        $bgColor = 'bg-yellow-500';
                        $neonGlow = 'neon-glow-yellow';
                        $bannerText = 'LOW';
                        $statusLabelColor = 'text-yellow-500';
                    }
                @endphp
                
                <div 
                    wire:key="ticket-{{ $ticket->id }}"
                    @if($canSort)
                        wire:sortable.item="{{ $ticket->id }}"
                    @endif
                    x-data
                    x-on:click="if (!$event.target.closest('a, button, [wire\:sortable\.handle]')) { Livewire.navigate('{{ route('tickets.show', $ticket->id) }}') }"
                    class="relative glass-card ticket-pass p-0 overflow-hidden cursor-pointer hover:scale-[1.03] transition-[transform,box-shadow] duration-200 hover:shadow-2xl hover:shadow-primary/10"
                >
                    <!-- Inner wrapper to enable unified rotation/scale of card content & bottom banner during dragging -->
                    <div class="w-full h-full relative draggable-inner-wrapper">
                        <div class="p-6 relative z-10">
                            <div class="flex justify-between items-start mb-6">
                                <div class="space-y-1">
                                    <p class="text-[10px] uppercase font-bold tracking-[0.2em] {{ $statusLabelColor }}">TICKET-ID</p>
                                    <h3 class="text-xl font-black text-on-surface">#TKT-{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</h3>
                                </div>
                                
                                <div class="flex items-center gap-2">
                                    <div class="flex gap-1">
                                        @if($ticket->priority === 'high')
                                            <div class="bg-red-500 text-white px-3 py-1 rounded-full text-[10px] font-black uppercase">
                                                Urgent
                                            </div>
                                        @elseif($ticket->priority === 'medium')
                                            <div class="bg-orange-500 text-white px-3 py-1 rounded-full text-[10px] font-black uppercase">
                                                Medium
                                            </div>
                                        @else
                                            <div class="bg-yellow-500 text-white px-3 py-1 rounded-full text-[10px] font-black uppercase">
                                                Low
                                            </div>
                                        @endif
                                    </div>
                                    
                                    @if($canSort)
                                        <div wire:sortable.handle class="cursor-grab active:cursor-grabbing text-on-surface-variant/40 hover:text-primary transition-colors text-lg" title="Seret untuk mengurutkan" @click.prevent>
                                            ⠿
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Nested Glass Details Pane -->
                            <div class="glass-sub-card p-4 rounded-2xl mb-4 space-y-4">
                                <!-- Reporter & Ticket Title Info -->
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full border-2 border-white/60 shadow-sm bg-gradient-to-br from-primary to-secondary flex items-center justify-center text-white font-black text-xs shrink-0">
                                        {{ strtoupper(substr($ticket->user->name, 0, 2)) }}
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="font-bold text-sm text-on-surface leading-tight truncate">{{ $ticket->user->name }}</p>
                                        <p class="text-xs text-on-surface-variant font-medium truncate mt-0.5">
                                            <a href="{{ route('tickets.show', $ticket->id) }}" class="hover:underline hover:text-primary transition-colors">
                                                {{ $ticket->title }}
                                            </a>
                                        </p>
                                    </div>
                                </div>

                                <div class="glass-divider my-2"></div>

                                <!-- Details Section -->
                                <div class="flex justify-between items-center text-xs">
                                    <div>
                                        <p class="text-[9px] font-bold text-on-surface-variant/70 uppercase tracking-widest mb-0.5">Dibuat Pada</p>
                                        <p class="font-bold text-on-surface">{{ $ticket->created_at->format('d M, H:i') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[9px] font-bold text-on-surface-variant/70 uppercase tracking-widest mb-0.5">Status</p>
                                        <div class="flex justify-end">
                                            @if($ticket->status === 'baru')
                                                <span class="font-black text-error">Baru</span>
                                            @elseif($ticket->status === 'diproses')
                                                <span class="font-black text-tertiary">Diproses</span>
                                            @elseif($ticket->status === 'selesai')
                                                <span class="font-black text-success">Selesai</span>
                                            @else
                                                <span class="font-black text-on-surface-variant">Ditutup</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Action Buttons -->
                            <div class="flex items-center justify-between border-t border-outline-variant/10 pt-4 pb-12">
                                <a href="{{ route('tickets.show', $ticket->id) }}" class="inline-flex items-center gap-1 text-xs font-black text-primary hover:text-primary-container transition-colors uppercase tracking-wider">
                                    <span class="material-symbols-outlined text-sm">visibility</span> Detail
                                </a>
                                
                                @if(
                                    auth()->user()->role === 'admin' ||
                                    (auth()->user()->role === 'agent' && (is_null($ticket->agent_id) || $ticket->agent_id === auth()->id())) ||
                                    (auth()->user()->role === 'user' && $ticket->user_id === auth()->id() && $ticket->status === 'baru')
                                )
                                    <a href="{{ route('tickets.edit', $ticket->id) }}" class="inline-flex items-center gap-1 text-xs font-black text-secondary hover:text-secondary-container transition-colors uppercase tracking-wider">
                                        <span class="material-symbols-outlined text-sm">edit</span> Ubah
                                    </a>
                                @endif
                            </div>
                        </div>

                        <!-- Bottom Status Banner -->
                        <div class="absolute bottom-0 left-0 w-full h-6 {{ $bgColor }} {{ $neonGlow }} flex items-center justify-center">
                            <span class="text-[10px] font-black text-white uppercase tracking-[0.5em]">{{ $bannerText }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $tickets->links() }}
        </div>
    @endif
</div>

