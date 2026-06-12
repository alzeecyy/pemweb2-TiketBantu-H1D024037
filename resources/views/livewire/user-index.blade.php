<div x-data="{ showCreate: @entangle('showCreateModal'), showEdit: @entangle('showEditModal') }">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        <div>
            <h2 class="text-3xl md:text-5xl font-black text-on-surface font-display tracking-tight">
                Manajemen <span class="text-primary italic">User</span>
            </h2>
            <p class="text-on-surface-variant mt-2 text-sm font-medium">
                Kelola data pengguna, perbarui peran, dan tambahkan agen support atau administrator baru.
            </p>
        </div>
        
        <button wire:click="openCreateModal" class="px-5 py-2.5 bg-gradient-to-r from-primary to-secondary text-white rounded-xl font-bold text-xs uppercase tracking-wider hover:scale-[1.02] active:scale-[0.98] transition-all duration-150 shadow-md shadow-primary/20 flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">person_add</span> Tambah User
        </button>
    </div>

    <!-- Alert Notifications -->
    @if (session()->has('message'))
        <div class="mb-6 p-4 rounded-xl bg-success/10 border border-success/30 text-success text-xs font-bold flex items-center gap-2">
            <span class="material-symbols-outlined">check_circle</span>
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-6 p-4 rounded-xl bg-error/10 border border-error/30 text-error text-xs font-bold flex items-center gap-2">
            <span class="material-symbols-outlined">error</span>
            {{ session('error') }}
        </div>
    @endif

    <!-- Filters & Search -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <!-- Search -->
        <div class="md:col-span-2 relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-on-surface-variant/40">
                <span class="material-symbols-outlined">search</span>
            </span>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama atau email..." 
                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-outline-variant/30 bg-surface-container-low text-sm focus:ring-primary focus:border-primary transition-all placeholder:text-on-surface-variant/40" />
        </div>

        <!-- Role Filter -->
        <div>
            <select wire:model.live="roleFilter" 
                class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/30 bg-surface-container-low text-sm focus:ring-primary focus:border-primary transition-all text-on-surface">
                <option value="">Semua Peran</option>
                <option value="admin">Admin</option>
                <option value="agent">Agen Support</option>
                <option value="user">Pelapor (User)</option>
            </select>
        </div>
    </div>

    <!-- User Table List -->
    <div class="glass-card rounded-2xl border border-outline-variant/20 overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="text-on-surface-variant border-b border-outline-variant/20 uppercase text-xs tracking-wider bg-surface/30">
                        <th class="px-6 py-4 font-black">Nama Pengguna</th>
                        <th class="px-6 py-4 font-black">Email</th>
                        <th class="px-6 py-4 font-black">Peran</th>
                        <th class="px-6 py-4 font-black">Tanggal Bergabung</th>
                        <th class="px-6 py-4 font-black text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/10">
                    @forelse($users as $u)
                        <tr class="hover:bg-primary/5 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-primary-container/40 flex items-center justify-center text-primary font-black text-xs border border-primary/20">
                                        {{ strtoupper(substr($u->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-on-surface">{{ $u->name }}</p>
                                        @if($u->id === auth()->id())
                                            <span class="text-[9px] bg-primary/20 text-primary font-bold px-1.5 py-0.5 rounded-full uppercase">Anda</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-medium text-on-surface-variant">
                                {{ $u->email }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider
                                    @if($u->role === 'admin') bg-primary-fixed text-on-primary-fixed-variant border border-primary/20
                                    @elseif($u->role === 'agent') bg-secondary-fixed text-on-secondary-fixed-variant border border-secondary/20
                                    @else bg-surface-variant text-on-surface-variant border border-outline-variant/30
                                    @endif">
                                    {{ $u->role === 'admin' ? 'Admin' : ($u->role === 'agent' ? 'Agen' : 'Pelapor') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs font-semibold text-on-surface-variant">
                                {{ $u->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="openEditModal({{ $u->id }})" class="p-1.5 text-primary hover:bg-primary/10 rounded-lg transition-colors" title="Edit Peran">
                                        <span class="material-symbols-outlined text-lg">edit</span>
                                    </button>
                                    @if($u->id !== auth()->id())
                                        <button onclick="confirm('Apakah Anda yakin ingin menghapus user ini?') || event.stopImmediatePropagation()" 
                                            wire:click="deleteUser({{ $u->id }})" class="p-1.5 text-error hover:bg-error-container/50 rounded-lg transition-colors" title="Hapus User">
                                            <span class="material-symbols-outlined text-lg">delete</span>
                                        </button>
                                    @else
                                        <span class="w-8"></span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-on-surface-variant/60 italic">
                                Tidak ada data user ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($users->hasPages())
            <div class="px-6 py-4 bg-surface/10 border-t border-outline-variant/10">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    <!-- Create User Modal -->
    <div x-show="showCreate" class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50 flex items-center justify-center" style="display: none;">
        <!-- Backdrop -->
        <div x-show="showCreate" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" 
            class="fixed inset-0 transform transition-all bg-on-surface/30 backdrop-blur-sm" @click="showCreate = false"></div>

        <!-- Modal Card -->
        <div x-show="showCreate" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
            class="w-full sm:max-w-md p-6 rounded-2xl glass-card border border-outline-variant/20 shadow-2xl z-50 overflow-hidden relative">
            
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-black text-on-surface flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">person_add</span> Tambah User Baru
                </h3>
                <button @click="showCreate = false" class="text-on-surface-variant hover:text-on-surface">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <form wire:submit.prevent="createUser" class="space-y-4">
                <!-- Name -->
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-2">Nama Lengkap</label>
                    <input wire:model="name" type="text" class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/30 bg-surface-container-low text-sm focus:ring-primary focus:border-primary transition-all text-on-surface" placeholder="Masukkan nama lengkap" required />
                    @error('name') <span class="text-error text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-2">Email Address</label>
                    <input wire:model="email" type="email" class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/30 bg-surface-container-low text-sm focus:ring-primary focus:border-primary transition-all text-on-surface" placeholder="email@contoh.com" required />
                    @error('email') <span class="text-error text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-2">Password</label>
                    <input wire:model="password" type="password" class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/30 bg-surface-container-low text-sm focus:ring-primary focus:border-primary transition-all text-on-surface" placeholder="Minimal 8 karakter" required />
                    @error('password') <span class="text-error text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Role -->
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-2">Peran (Role)</label>
                    <select wire:model="role" class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/30 bg-surface-container-low text-sm focus:ring-primary focus:border-primary transition-all text-on-surface">
                        <option value="user">Pelapor (User)</option>
                        <option value="agent">Agen Support</option>
                        <option value="admin">Administrator</option>
                    </select>
                    @error('role') <span class="text-error text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-outline-variant/10">
                    <button type="button" @click="showCreate = false" class="px-4 py-2 bg-surface-container-high hover:bg-surface-variant text-on-surface font-bold text-xs uppercase tracking-wider rounded-xl transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-primary to-secondary text-white rounded-xl font-bold text-xs uppercase tracking-wider hover:scale-[1.02] active:scale-[0.98] transition-all duration-150 shadow-md">
                        Simpan User
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div x-show="showEdit" class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50 flex items-center justify-center" style="display: none;">
        <!-- Backdrop -->
        <div x-show="showEdit" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" 
            class="fixed inset-0 transform transition-all bg-on-surface/30 backdrop-blur-sm" @click="showEdit = false"></div>

        <!-- Modal Card -->
        <div x-show="showEdit" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
            class="w-full sm:max-w-md p-6 rounded-2xl glass-card border border-outline-variant/20 shadow-2xl z-50 overflow-hidden relative">
            
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-black text-on-surface flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">edit</span> Edit Profil & Peran User
                </h3>
                <button @click="showEdit = false" class="text-on-surface-variant hover:text-on-surface">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <form wire:submit.prevent="updateUser" class="space-y-4">
                <!-- Name -->
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-2">Nama Lengkap</label>
                    <input wire:model="editingName" type="text" class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/30 bg-surface-container-low text-sm focus:ring-primary focus:border-primary transition-all text-on-surface" required />
                    @error('editingName') <span class="text-error text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-2">Email Address</label>
                    <input wire:model="editingEmail" type="email" class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/30 bg-surface-container-low text-sm focus:ring-primary focus:border-primary transition-all text-on-surface" required />
                    @error('editingEmail') <span class="text-error text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Role -->
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-2">Peran (Role)</label>
                    <select wire:model="editingRole" class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/30 bg-surface-container-low text-sm focus:ring-primary focus:border-primary transition-all text-on-surface" {{ $editingUserId === auth()->id() ? 'disabled' : '' }}>
                        <option value="user">Pelapor (User)</option>
                        <option value="agent">Agen Support</option>
                        <option value="admin">Administrator</option>
                    </select>
                    @if($editingUserId === auth()->id())
                        <span class="text-on-surface-variant/60 text-[10px] mt-1 block font-medium">Anda tidak dapat mengubah peran Anda sendiri.</span>
                    @endif
                    @error('editingRole') <span class="text-error text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-outline-variant/10">
                    <button type="button" @click="showEdit = false" class="px-4 py-2 bg-surface-container-high hover:bg-surface-variant text-on-surface font-bold text-xs uppercase tracking-wider rounded-xl transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-primary to-secondary text-white rounded-xl font-bold text-xs uppercase tracking-wider hover:scale-[1.02] active:scale-[0.98] transition-all duration-150 shadow-md">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
