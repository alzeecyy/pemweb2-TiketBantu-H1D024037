<div wire:poll.15s>
    <!-- Header Section -->
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <h2 class="text-3xl font-black text-on-surface tracking-tight">
            Detail Tiket <span class="text-primary italic">#TKT-{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</span>
        </h2>
        <div class="flex flex-wrap items-center gap-2">
            @if(
                auth()->user()->role === 'admin' ||
                (auth()->user()->role === 'agent' && (is_null($ticket->agent_id) || $ticket->agent_id === auth()->id())) ||
                (auth()->user()->role === 'user' && $ticket->user_id === auth()->id() && $ticket->status === 'baru')
            )
                <a href="{{ route('tickets.edit', $ticket->id) }}" class="inline-flex items-center px-4 py-2 bg-primary text-white hover:scale-105 rounded-xl font-bold text-xs uppercase tracking-wider transition-all shadow-md shadow-primary/20">
                    <span class="material-symbols-outlined text-sm mr-1">edit</span> Ubah Tiket
                </a>
            @endif

            @if(auth()->user()->role === 'admin')
                <button 
                    x-data 
                    x-on:click="if(confirm('Apakah Anda yakin ingin menghapus tiket ini? (Tindakan ini akan mengarsipkan tiket)')) { $wire.deleteTicket() }" 
                    class="inline-flex items-center px-4 py-2 bg-error text-white hover:scale-105 rounded-xl font-bold text-xs uppercase tracking-wider transition-all shadow-md shadow-error/20"
                >
                    <span class="material-symbols-outlined text-sm mr-1">delete</span> Hapus
                </button>
            @endif

            <a href="{{ route('tickets.index') }}" class="inline-flex items-center px-4 py-2 bg-surface-container hover:bg-surface-container-high border border-outline-variant/30 rounded-xl font-bold text-xs text-on-surface-variant uppercase tracking-wider transition-all">
                <span class="material-symbols-outlined text-sm mr-1">arrow_back</span> Kembali
            </a>
        </div>
    </div>



    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Ticket Content (Left - 2 Columns) -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Ticket Info Card -->
            <div class="glass-card rounded-2xl p-8 shadow-xl border border-outline-variant/20">
                <!-- Header / Title -->
                <div class="border-b border-outline-variant/20 pb-4 mb-6">
                    <span class="inline-block px-3 py-1 mb-2 text-[10px] font-black uppercase tracking-wider bg-primary-fixed text-on-primary-fixed-variant rounded-full">
                        {{ $ticket->category->name }}
                    </span>
                    <h3 class="text-2xl font-black text-on-surface leading-tight">{{ $ticket->title }}</h3>
                    <div class="text-xs text-on-surface-variant mt-2 flex items-center gap-1 font-medium">
                        <span class="material-symbols-outlined text-sm">schedule</span>
                        <span>Dilaporkan pada {{ $ticket->created_at->format('d M Y, H:i') }} oleh <strong>{{ $ticket->user->name }}</strong> ({{ $ticket->user->email }})</span>
                    </div>
                </div>

                <!-- Description -->
                <div class="text-on-surface text-sm whitespace-pre-line leading-relaxed mb-8 font-medium">
                    {{ $ticket->description }}
                </div>

                <!-- Attachments (Lampiran Berkas) -->
                @if($ticket->attachments->isNotEmpty())
                    <div class="border-t border-outline-variant/20 pt-6">
                        <h4 class="text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-4">Lampiran Berkas:</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($ticket->attachments as $attachment)
                                @php
                                    $ext = strtolower(pathinfo($attachment->file_name, PATHINFO_EXTENSION));
                                    $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                    $fileUrl = url('storage/' . $attachment->file_path);
                                @endphp

                                <div class="rounded-xl border border-outline-variant/25 bg-surface-container-low hover:bg-surface-container transition-all overflow-hidden">
                                    {{-- Preview gambar inline --}}
                                    @if($isImage)
                                        <a href="{{ $fileUrl }}" target="_blank" class="block">
                                            <img src="{{ $fileUrl }}" alt="{{ $attachment->file_name }}" class="w-full h-40 object-cover hover:scale-105 transition-transform duration-300" loading="lazy">
                                        </a>
                                    @endif

                                    <div class="flex items-center p-3">
                                        <div class="mr-3 text-primary">
                                            <span class="material-symbols-outlined text-2xl">{{ $isImage ? 'image' : 'description' }}</span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-bold text-on-surface truncate">{{ $attachment->file_name }}</p>
                                            <p class="text-[9px] text-on-surface-variant uppercase tracking-wider mt-0.5">Lampiran #{{ $attachment->id }}</p>
                                        </div>
                                        <div class="ml-2">
                                            <a href="{{ $fileUrl }}" target="_blank" class="inline-flex items-center px-2.5 py-1 bg-primary text-white text-[10px] font-black uppercase rounded-lg hover:scale-105 transition-all">
                                                Lihat
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Livewire Island: Komponen Komentar Realtime -->
            <div class="glass-card rounded-2xl p-8 shadow-xl border border-outline-variant/20">
                <livewire:ticket-comments lazy :ticket="$ticket" :key="'comments-'.$ticket->id" />
            </div>
        </div>

        <!-- Sidebar Controls (Right - 1 Column) -->
        <div class="space-y-8">
            <!-- Ticket Meta Details -->
            <div class="glass-card rounded-2xl p-6 shadow-xl border border-outline-variant/20">
                <h3 class="text-xs font-bold text-on-surface uppercase tracking-widest border-b border-outline-variant/20 pb-3 mb-4 flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm text-primary">info</span> Detail Status
                </h3>

                <div class="space-y-4">
                    <!-- Status Badge -->
                    <div>
                        <span class="block text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">
                            Status Tiket:
                        </span>
                        <div class="flex gap-1">
                            <span wire:show="status === 'baru'" class="px-3 py-1 inline-flex text-xs leading-5 font-black rounded-full uppercase bg-error-container text-on-error-container">
                                Baru
                            </span>
                            <span wire:show="status === 'diproses'" class="px-3 py-1 inline-flex text-xs leading-5 font-black rounded-full uppercase bg-tertiary-fixed text-on-tertiary-fixed-variant">
                                Diproses
                            </span>
                            <span wire:show="status === 'selesai'" class="px-3 py-1 inline-flex text-xs leading-5 font-black rounded-full uppercase bg-green-100 text-green-800">
                                Selesai
                            </span>
                            <span wire:show="status === 'ditutup'" class="px-3 py-1 inline-flex text-xs leading-5 font-black rounded-full uppercase bg-surface-variant text-on-surface-variant">
                                Ditutup
                            </span>
                        </div>
                    </div>

                    <!-- Priority Badge -->
                    <div>
                        <span class="block text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">Prioritas:</span>
                        <div class="flex gap-1">
                            <span wire:show="priority === 'high'" class="px-3 py-1 inline-flex text-xs leading-5 font-black rounded-full uppercase bg-primary-fixed text-on-primary-fixed-variant">
                                Urgent
                            </span>
                            <span wire:show="priority === 'medium'" class="px-3 py-1 inline-flex text-xs leading-5 font-black rounded-full uppercase bg-tertiary-fixed text-on-tertiary-fixed-variant">
                                Medium
                            </span>
                            <span wire:show="priority === 'low'" class="px-3 py-1 inline-flex text-xs leading-5 font-black rounded-full uppercase bg-surface-variant text-on-surface-variant">
                                Low
                            </span>
                        </div>
                    </div>

                    <!-- Assigned Agent -->
                    <div>
                        <span class="block text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">Petugas (Agen):</span>
                        @if($ticket->agent)
                            <div class="flex items-center text-sm text-on-surface font-bold">
                                <div class="h-8 w-8 rounded-full bg-gradient-to-br from-primary to-secondary text-white font-black text-xs flex items-center justify-center mr-2 shadow-sm">
                                    {{ strtoupper(substr($ticket->agent->name, 0, 2)) }}
                                </div>
                                <span>{{ $ticket->agent->name }}</span>
                            </div>
                        @else
                            <div class="flex items-center gap-1 text-xs text-red-500 font-bold italic">
                                <span class="material-symbols-outlined text-sm">error</span> Belum ditugaskan ke petugas
                            </div>
                        @endif
                    </div>

                    <!-- SLA / Waktu Penyelesaian -->
                    @if($ticket->closed_at)
                        <div class="border-t border-outline-variant/20 pt-4 mt-4">
                            <span class="block text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">SLA (Waktu Penyelesaian):</span>
                            <span class="text-xs font-black text-secondary block bg-secondary-fixed/20 p-3 rounded-xl border border-secondary-fixed-dim/20">
                                <span class="material-symbols-outlined text-sm align-middle mr-1">timer</span>
                                {{ $ticket->created_at->diff($ticket->closed_at)->format('%d hari, %h jam, %i menit') }}
                            </span>
                            <span class="text-[9px] text-on-surface-variant font-medium mt-1.5 block">
                                Selesai pada {{ $ticket->closed_at->format('d M Y, H:i') }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Role-based Actions -->
            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'agent')
                <div class="glass-card rounded-2xl p-6 shadow-xl border border-outline-variant/20 space-y-6">
                    <h3 class="text-xs font-bold text-on-surface uppercase tracking-widest border-b border-outline-variant/20 pb-3 flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm text-primary">admin_panel_settings</span> Kontrol Agen
                    </h3>

                    <!-- Claim Ticket (Agent if unassigned) -->
                    @if(is_null($ticket->agent_id) && auth()->user()->role === 'agent')
                        <div>
                            <button wire:click="claimTicket" class="w-full text-center inline-flex justify-center items-center px-4 py-2.5 bg-gradient-to-r from-primary to-secondary text-white rounded-xl font-bold text-xs uppercase tracking-wider hover:scale-[1.02] active:scale-[0.98] transition-all duration-150 shadow-md shadow-primary/20">
                                <span class="material-symbols-outlined text-sm mr-1">assignment_turned_in</span> Ambil Alih Tiket
                            </button>
                        </div>
                    @endif

                    <!-- Update Status (Admin & Agent) -->
                    @if(!is_null($ticket->agent_id) || auth()->user()->role === 'admin')
                        <div>
                            <label for="update_status" class="block text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mb-2">Ubah Status Tiket</label>
                            <div class="flex gap-2">
                                <select wire:model="status" id="update_status" class="flex-1 rounded-lg border-outline-variant/30 bg-surface-container-low text-xs focus:ring-primary focus:border-primary transition-all">
                                    <option value="baru">Baru</option>
                                    <option value="diproses">Diproses</option>
                                    <option value="selesai">Selesai</option>
                                    <option value="ditutup">Ditutup</option>
                                </select>
                                <button wire:click="updateStatus" class="px-4 py-1.5 bg-gradient-to-r from-primary to-secondary text-white rounded-xl text-xs font-bold hover:scale-[1.02] transition-all shadow-md shadow-primary/10">
                                    Simpan
                                </button>
                            </div>
                        </div>
                    @endif

                    <!-- Update Priority (Admin & Agent) -->
                    <div>
                        <label for="update_priority" class="block text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mb-2">Sesuaikan Prioritas</label>
                        <div class="flex gap-2">
                            <select wire:model="priority" id="update_priority" class="flex-1 rounded-lg border-outline-variant/30 bg-surface-container-low text-xs focus:ring-primary focus:border-primary transition-all">
                                <option value="low">Low (Biasa)</option>
                                <option value="medium">Medium (Sedang)</option>
                                <option value="high">High (Mendesak)</option>
                            </select>
                            <button wire:click="updatePriority" class="px-4 py-1.5 bg-gradient-to-r from-primary to-secondary text-white rounded-xl text-xs font-bold hover:scale-[1.02] transition-all shadow-md shadow-primary/10">
                                Simpan
                            </button>
                        </div>
                    </div>

                    <!-- Assign Agent (Admin Only) -->
                    @if(auth()->user()->role === 'admin')
                        <div>
                            <label for="assign_agent" class="block text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mb-2">Tugaskan Petugas/Agen</label>
                            <div class="flex gap-2">
                                <select wire:model="agent_id" id="assign_agent" class="flex-1 rounded-lg border-outline-variant/30 bg-surface-container-low text-xs focus:ring-primary focus:border-primary transition-all">
                                    <option value="">-- Tanpa Agen --</option>
                                    @foreach($agents as $agent)
                                        <option value="{{ $agent->id }}">{{ $agent->name }} ({{ ucfirst($agent->role) }})</option>
                                    @endforeach
                                </select>
                                <button wire:click="assignAgent" class="px-4 py-1.5 bg-gradient-to-r from-primary to-secondary text-white rounded-xl text-xs font-bold hover:scale-[1.02] transition-all shadow-md shadow-primary/10">
                                    Tugaskan
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

