<div>
    <!-- Headline -->
    <div class="mb-12">
        <h2 class="text-4xl md:text-6xl font-black text-on-surface font-display tracking-tight leading-tight">
            Kelola <span class="text-primary italic">Kategori</span>
        </h2>
        <p class="text-on-surface-variant mt-4 text-lg max-w-2xl font-medium">
            Kelola data kategori tiket pengaduan pada sistem TiketBantu.
        </p>
    </div>

    <!-- Alert Messages -->
    @if (session()->has('message'))
        <div class="mb-6 p-4 rounded-xl bg-primary-fixed text-on-primary-fixed-variant border border-primary/20 flex items-center gap-2 shadow-sm">
            <span class="material-symbols-outlined">check_circle</span>
            <span class="text-sm font-bold">{{ session('message') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-6 p-4 rounded-xl bg-red-100 text-red-800 border border-red-200 flex items-center gap-2 shadow-sm">
            <span class="material-symbols-outlined">error</span>
            <span class="text-sm font-bold">{{ session('error') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Add Category Form (Left) -->
        <div class="glass-card rounded-2xl p-6 border border-outline-variant/20 h-fit">
            <h3 class="text-lg font-black text-on-surface mb-6 flex items-center gap-2 border-b border-outline-variant/20 pb-3">
                <span class="material-symbols-outlined text-primary">add_circle</span> Tambah Kategori
            </h3>

            <form wire:submit.prevent="create" class="space-y-4">
                <div>
                    <label for="name" class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-2">Nama Kategori</label>
                    <input wire:model="name" type="text" id="name" class="w-full rounded-lg border-outline-variant/30 bg-surface-container-low text-sm focus:ring-primary focus:border-primary transition-all placeholder:text-on-surface-variant/40" placeholder="Contoh: Server Hardware">
                    @error('name') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-2.5 bg-gradient-to-r from-primary to-secondary text-white rounded-xl text-xs font-bold uppercase tracking-wider hover:scale-[1.02] active:scale-[0.98] transition-all duration-150 shadow-md shadow-primary/20">
                        Tambah Kategori
                    </button>
                </div>
            </form>
        </div>

        <!-- Categories Table (Right) -->
        <div class="lg:col-span-2 glass-card rounded-2xl p-6 border border-outline-variant/20">
            <h3 class="text-lg font-black text-on-surface mb-6 flex items-center gap-2 border-b border-outline-variant/20 pb-3">
                <span class="material-symbols-outlined text-secondary">category</span> Daftar Kategori
            </h3>

            @if($categories->isEmpty())
                <p class="text-on-surface-variant/60 text-sm py-4 italic text-center">Belum ada kategori terdaftar.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="text-on-surface-variant border-b border-outline-variant/20 uppercase text-xs tracking-wider">
                                <th class="py-3 font-bold">Nama Kategori</th>
                                <th class="py-3 font-bold text-center">Jumlah Tiket</th>
                                <th class="py-3 font-bold text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/10">
                            @foreach($categories as $category)
                                <tr class="hover:bg-primary/5 transition-colors">
                                    <td class="py-4 font-medium">
                                        @if($editingCategoryId === $category->id)
                                            <!-- Inline Edit Input -->
                                            <div class="flex items-center gap-2">
                                                <input wire:model="editingCategoryName" type="text" class="rounded-lg border-outline-variant/30 bg-surface-container-low text-xs py-1.5 focus:ring-primary focus:border-primary transition-all">
                                                <button wire:click="update" class="px-3 py-1.5 bg-success text-white text-[10px] font-black uppercase rounded-lg hover:scale-105 transition-all">
                                                    Simpan
                                                </button>
                                                <button wire:click="cancelEdit" class="px-3 py-1.5 bg-surface-container text-on-surface-variant text-[10px] font-black uppercase rounded-lg hover:scale-105 transition-all">
                                                    Batal
                                                </button>
                                            </div>
                                            @error('editingCategoryName') <span class="text-red-500 text-[10px] block mt-1 font-medium">{{ $message }}</span> @enderror
                                        @else
                                            {{ $category->name }}
                                        @endif
                                    </td>
                                    <td class="py-4 text-center font-bold text-on-surface-variant">
                                        <span class="px-3 py-1 bg-surface-container text-on-surface text-xs font-bold rounded-full border border-outline-variant/10">
                                            {{ $category->tickets_count }}
                                        </span>
                                    </td>
                                    <td class="py-4 text-right">
                                        @if($editingCategoryId !== $category->id)
                                            <div class="flex justify-end gap-2">
                                                <button wire:click="edit({{ $category->id }})" class="inline-flex items-center gap-0.5 text-xs font-black text-secondary hover:text-secondary-container transition-colors uppercase tracking-wider">
                                                    <span class="material-symbols-outlined text-sm">edit</span> Ubah
                                                </button>
                                                <button 
                                                    x-data 
                                                    x-on:click="if(confirm('Apakah Anda yakin ingin menghapus kategori ini?')) { $wire.delete({{ $category->id }}) }" 
                                                    class="inline-flex items-center gap-0.5 text-xs font-black text-error hover:text-error-container transition-colors uppercase tracking-wider"
                                                >
                                                    <span class="material-symbols-outlined text-sm">delete</span> Hapus
                                                </button>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
