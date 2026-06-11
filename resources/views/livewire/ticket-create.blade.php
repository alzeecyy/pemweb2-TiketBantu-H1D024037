<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Buat Tiket Pengaduan Baru') }}
            </h2>
            <a href="{{ route('tickets.index') }}" wire:navigate class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none transition ease-in-out duration-150">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form wire:submit="save" enctype="multipart/form-data">
                        <!-- Judul Pengaduan -->
                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Judul Pengaduan</label>
                            <input wire:model="title" type="text" id="title" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Contoh: Koneksi internet di ruangan IT terputus">
                            @error('title') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <!-- Kategori -->
                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Kategori Masalah</label>
                                <select wire:model="category_id" id="category_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Prioritas -->
                            <div>
                                <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">Tingkat Prioritas</label>
                                <select wire:model="priority" id="priority" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="low">Low (Biasa)</option>
                                    <option value="medium">Medium (Sedang)</option>
                                    <option value="high">High (Mendesak)</option>
                                </select>
                                @error('priority') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Deskripsi Masalah -->
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Masalah</label>
                            <textarea wire:model="description" id="description" rows="5" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Jelaskan detail masalah yang Anda alami..."></textarea>
                            @error('description') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Lampiran Berkas (Attachment) -->
                        <div class="mb-6">
                            <label for="attachment" class="block text-sm font-medium text-gray-700 mb-1">Lampiran Berkas (Opsional - Max 5MB)</label>
                            <input wire:model="attachment" type="file" id="attachment" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            
                            <!-- Progress Loading Upload File -->
                            <div wire:loading wire:target="attachment" class="text-xs text-gray-500 mt-2">
                                Mengunggah berkas...
                            </div>

                            @error('attachment') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror

                            <!-- Preview File Terunggah (Khusus Gambar) -->
                            @if ($attachment && in_array($attachment->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'gif']))
                                <div class="mt-4">
                                    <span class="block text-xs font-medium text-gray-500 mb-1">Preview Gambar:</span>
                                    <img src="{{ $attachment->temporaryUrl() }}" class="h-32 object-cover rounded-md border shadow-sm">
                                </div>
                            @endif
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="flex justify-end border-t pt-4">
                            <button type="submit" wire:loading.attr="disabled" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Kirim Pengaduan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
