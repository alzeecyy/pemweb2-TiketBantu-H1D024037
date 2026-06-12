<div>
    <!-- Header Section -->
    <div class="mb-8 flex justify-between items-center">
        <h2 class="text-3xl font-black text-on-surface tracking-tight">
            Ubah Tiket <span class="text-primary italic">#TKT-{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</span>
        </h2>
        <a href="{{ route('tickets.show', $ticket->id) }}" wire:navigate class="inline-flex items-center px-4 py-2 bg-surface-container hover:bg-surface-container-high border border-outline-variant/30 rounded-xl font-bold text-xs text-on-surface-variant uppercase tracking-wider transition-all">
            <span class="material-symbols-outlined text-sm mr-1">arrow_back</span> Batal
        </a>
    </div>

    <!-- Glassmorphism Form Card -->
    <div class="max-w-3xl mx-auto">
        <div class="glass-card rounded-2xl p-8 shadow-xl border border-outline-variant/20">
            <form wire:submit="save" class="space-y-6">
                
                @if(auth()->user()->role === 'admin' || (auth()->user()->role === 'user' && $ticket->status === 'baru'))
                    <!-- Judul Pengaduan (Admin & User) -->
                    <div>
                        <label for="title" class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-2">Judul Pengaduan</label>
                        <input wire:model="title" type="text" id="title" class="w-full rounded-lg border-outline-variant/30 bg-surface-container-low text-sm focus:ring-primary focus:border-primary transition-all placeholder:text-on-surface-variant/40" placeholder="Koneksi internet di ruangan IT terputus">
                        @error('title') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Kategori -->
                        <div>
                            <label for="category_id" class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-2">Kategori Masalah</label>
                            <select wire:model="category_id" id="category_id" class="w-full rounded-lg border-outline-variant/30 bg-surface-container-low text-sm focus:ring-primary focus:border-primary transition-all">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                        </div>

                        <!-- Prioritas -->
                        <div>
                            <label for="priority" class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-2">Tingkat Prioritas</label>
                            <select wire:model="priority" id="priority" class="w-full rounded-lg border-outline-variant/30 bg-surface-container-low text-sm focus:ring-primary focus:border-primary transition-all">
                                <option value="low">Low (Biasa)</option>
                                <option value="medium">Medium (Sedang)</option>
                                <option value="high">High (Mendesak)</option>
                            </select>
                            @error('priority') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Deskripsi Masalah -->
                    <div>
                        <label for="description" class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-2">Deskripsi Masalah</label>
                        <textarea wire:model="description" id="description" rows="5" class="w-full rounded-lg border-outline-variant/30 bg-surface-container-low text-sm focus:ring-primary focus:border-primary transition-all placeholder:text-on-surface-variant/40" placeholder="Jelaskan detail masalah yang Anda alami..."></textarea>
                        @error('description') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>
                @else
                    <!-- read-only view of Title & Description for Agent / User when status !== baru -->
                    <div class="p-6 bg-surface-container-low rounded-2xl border border-outline-variant/20 space-y-4">
                        <div>
                            <span class="block text-[10px] font-bold text-on-surface-variant uppercase tracking-widest">Judul Pengaduan</span>
                            <p class="text-base font-bold text-on-surface mt-1">{{ $ticket->title }}</p>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold text-on-surface-variant uppercase tracking-widest">Deskripsi</span>
                            <p class="text-sm text-on-surface mt-1 whitespace-pre-line leading-relaxed font-medium">{{ $ticket->description }}</p>
                        </div>
                    </div>
                @endif

                <!-- Admin & Agent fields: Status & Assigned Agent -->
                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'agent')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-outline-variant/20 pt-6">
                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-2">Status Tiket</label>
                            <select wire:model="status" id="status" class="w-full rounded-lg border-outline-variant/30 bg-surface-container-low text-sm focus:ring-primary focus:border-primary transition-all">
                                <option value="baru">Baru</option>
                                <option value="diproses">Diproses</option>
                                <option value="selesai">Selesai</option>
                                <option value="ditutup">Ditutup</option>
                            </select>
                            @error('status') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                        </div>

                        @if(auth()->user()->role === 'agent')
                            <!-- Priority (Agent can adjust) -->
                            <div>
                                <label for="priority_agent" class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-2">Sesuaikan Prioritas</label>
                                <select wire:model="priority" id="priority_agent" class="w-full rounded-lg border-outline-variant/30 bg-surface-container-low text-sm focus:ring-primary focus:border-primary transition-all">
                                    <option value="low">Low (Biasa)</option>
                                    <option value="medium">Medium (Sedang)</option>
                                    <option value="high">High (Mendesak)</option>
                                </select>
                                @error('priority') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        <!-- Assigned Agent (Admin Only) -->
                        @if(auth()->user()->role === 'admin')
                            <div>
                                <label for="agent_id" class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-2">Petugas / Agen Penanggung Jawab</label>
                                <select wire:model="agent_id" id="agent_id" class="w-full rounded-lg border-outline-variant/30 bg-surface-container-low text-sm focus:ring-primary focus:border-primary transition-all">
                                    <option value="">-- Belum Ditugaskan --</option>
                                    @foreach($agents as $agent)
                                        <option value="{{ $agent->id }}">{{ $agent->name }} ({{ ucfirst($agent->role) }})</option>
                                    @endforeach
                                </select>
                                @error('agent_id') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                            </div>
                        @else
                            <!-- Display Assigned Agent for Agent read-only -->
                            <div>
                                <span class="block text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mb-2">Petugas / Agen</span>
                                <p class="text-sm font-bold text-on-surface py-2.5">
                                    {{ $ticket->agent ? $ticket->agent->name : 'Belum Ditugaskan' }}
                                </p>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Tombol Aksi -->
                <div class="flex justify-end border-t border-outline-variant/20 pt-4">
                    <button type="submit" wire:loading.attr="disabled" class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-primary to-secondary text-white rounded-xl font-bold text-xs uppercase tracking-wider hover:scale-[1.02] active:scale-[0.98] transition-all duration-150 shadow-md shadow-primary/20">
                        <span class="material-symbols-outlined text-sm mr-1">save</span> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
