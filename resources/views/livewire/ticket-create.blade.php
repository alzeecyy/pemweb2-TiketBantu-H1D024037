<div>
    <!-- Header Section -->
    <div class="mb-8 flex justify-between items-center">
        <h2 class="text-3xl font-black text-on-surface tracking-tight">
            Buat Tiket <span class="text-primary italic">Pengaduan Baru</span>
        </h2>
        <a href="{{ route('tickets.index') }}" class="inline-flex items-center px-4 py-2 bg-surface-container hover:bg-surface-container-high border border-outline-variant/30 rounded-xl font-bold text-xs text-on-surface-variant uppercase tracking-wider transition-all">
            <span class="material-symbols-outlined text-sm mr-1">arrow_back</span> Kembali
        </a>
    </div>

    <!-- Glassmorphism Form Card -->
    <div class="max-w-3xl mx-auto">
        <div class="glass-card rounded-2xl p-8 shadow-xl border border-outline-variant/20">
            <form wire:submit="save" enctype="multipart/form-data" class="space-y-6">
                @if(auth()->user()->role === 'admin')
                    <!-- Pelapor / User Dropdown (Admin Only) -->
                    <div>
                        <label for="user_id" class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-2">Pelapor / Atas Nama</label>
                        <select wire:model="user_id" id="user_id" class="w-full rounded-lg border-outline-variant/30 bg-surface-container-low text-sm focus:ring-primary focus:border-primary transition-all">
                            <option value="">-- Pilih Pelapor --</option>
                            @foreach($reporters as $reporter)
                                <option value="{{ $reporter->id }}">{{ $reporter->name }} ({{ $reporter->email }} - {{ ucfirst($reporter->role) }})</option>
                            @endforeach
                        </select>
                        @error('user_id') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>
                @endif

                <!-- Judul Pengaduan -->
                <div>
                    <label for="title" class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-2">Judul Pengaduan</label>
                    <input wire:model="title" type="text" id="title" class="w-full rounded-lg border-outline-variant/30 bg-surface-container-low text-sm focus:ring-primary focus:border-primary transition-all placeholder:text-on-surface-variant/40" placeholder="Contoh: Koneksi internet di ruangan IT terputus">
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

                <!-- Lampiran Berkas -->
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-2">Lampiran Berkas (Opsional - Max 5MB)</label>
                    <div class="flex items-center justify-center w-full">
                        <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-outline-variant/30 border-dashed rounded-xl cursor-pointer bg-surface-container-low hover:bg-surface-container transition-all">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <span class="material-symbols-outlined text-on-surface-variant/60 text-3xl mb-1">cloud_upload</span>
                                <p class="text-xs text-on-surface-variant font-medium">Klik untuk unggah berkas lampiran</p>
                                <p class="text-[10px] text-on-surface-variant/60 mt-1">PNG, JPG, PDF (Maks. 5MB)</p>
                            </div>
                            <input wire:model="attachment" type="file" id="attachment" class="hidden" />
                        </label>
                    </div>

                    <!-- Progress Loading Upload File -->
                    <div wire:loading wire:target="attachment" class="text-xs text-on-surface-variant/70 mt-2 flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm animate-spin">sync</span> Mengunggah berkas...
                    </div>

                    @error('attachment') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror

                    <!-- Preview File Terunggah -->
                    @if ($attachment)
                        <div class="mt-4 p-3 rounded-xl bg-surface-container-low border border-outline-variant/20 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary text-xl">description</span>
                                <span class="text-xs font-bold text-on-surface truncate max-w-xs">{{ $attachment->getClientOriginalName() }}</span>
                            </div>
                            <button type="button" wire:click="$set('attachment', null)" class="text-red-500 hover:text-red-700">
                                <span class="material-symbols-outlined text-sm">delete</span>
                            </button>
                        </div>
                        
                        @if(in_array($attachment->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'gif']))
                            <div class="mt-3">
                                <img src="{{ $attachment->temporaryUrl() }}" class="h-32 object-cover rounded-xl border border-outline-variant/20 shadow-sm">
                            </div>
                        @endif
                    @endif
                </div>

                <!-- Tombol Aksi -->
                <div class="flex justify-end border-t border-outline-variant/20 pt-4">
                    <button type="submit" wire:loading.attr="disabled" class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-primary to-secondary text-white rounded-xl font-bold text-xs uppercase tracking-wider hover:scale-[1.02] active:scale-[0.98] transition-all duration-150 shadow-md shadow-primary/20">
                        <span class="material-symbols-outlined text-sm mr-1">send</span> Kirim Pengaduan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

