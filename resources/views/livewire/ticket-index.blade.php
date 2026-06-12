<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tiket Pengaduan') }}
            </h2>
            @if(auth()->user()->role === 'user' || auth()->user()->role === 'admin')
                <a href="{{ route('tickets.create') }}" wire:navigate class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    + Buat Tiket Baru
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filter & Search Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Search Input -->
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari Tiket</label>
                            <input wire:model.live.debounce.300ms="search" type="text" id="search" placeholder="Cari judul, deskripsi, pelapor..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <!-- Category Filter -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                            <select wire:model.live="category_id" id="category" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Priority Filter -->
                        <div>
                            <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">Prioritas</label>
                            <select wire:model.live="priority" id="priority" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Semua Prioritas</option>
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select wire:model.live="status" id="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Semua Status</option>
                                <option value="baru">Baru</option>
                                <option value="diproses">Diproses</option>
                                <option value="selesai">Selesai</option>
                                <option value="ditutup">Ditutup</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Info drag-and-drop untuk admin/agent --}}
            @if($canSort)
                <div class="mb-4 flex items-center gap-2 text-xs text-indigo-600 bg-indigo-50 border border-indigo-200 rounded-lg px-4 py-2.5">
                    <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                    </svg>
                    <span><strong>Drag & Drop:</strong> Seret baris tiket menggunakan ikon ⠿ di kolom paling kiri untuk mengurutkan prioritas penanganan.</span>
                </div>
            @endif

            <!-- Tickets List Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($tickets->isEmpty())
                        <div class="text-center py-8 text-gray-500">
                            Tidak ada tiket pengaduan ditemukan.
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        @if($canSort)
                                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider w-10">
                                                <span class="sr-only">Urutkan</span>
                                            </th>
                                        @endif
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul / ID</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelapor</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prioritas</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agen</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                        <th scope="col" class="relative px-6 py-3">
                                            <span class="sr-only">Aksi</span>
                                        </th>
                                    </tr>
                                </thead>
                                {{-- wire:sort pada tbody — Tantangan Khusus Livewire 4 --}}
                                <tbody
                                    @if($canSort) wire:sort="handleSort" @endif
                                    class="bg-white divide-y divide-gray-200"
                                >
                                    @foreach($tickets as $ticket)
                                        <tr
                                            wire:key="ticket-{{ $ticket->id }}"
                                            @if($canSort) wire:sort:item="{{ $ticket->id }}" @endif
                                            class="hover:bg-gray-50 {{ $canSort ? 'cursor-grab active:cursor-grabbing' : '' }} transition-colors duration-150"
                                        >
                                            {{-- Drag Handle (hanya admin/agent) --}}
                                            @if($canSort)
                                                <td class="px-3 py-4 text-center text-gray-400 hover:text-indigo-500 transition-colors">
                                                    <span class="text-lg leading-none select-none" title="Seret untuk mengurutkan">⠿</span>
                                                </td>
                                            @endif
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-semibold text-gray-900">
                                                    <a href="{{ route('tickets.show', $ticket->id) }}" wire:navigate class="hover:text-indigo-600 hover:underline">
                                                        {{ $ticket->title }}
                                                    </a>
                                                </div>
                                                <div class="text-xs text-gray-500">#TKT-{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $ticket->user->name }}</div>
                                                <div class="text-xs text-gray-500">{{ $ticket->user->email }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $ticket->category->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <!-- Client-side directive requirement: menggunakan x-show / wire:show / wire:text jika diperlukan.
                                                     Kita gunakan perpaduan standar Alpine untuk merender dynamic badge -->
                                                <div x-data="{ priority: '{{ $ticket->priority }}' }">
                                                    <span x-show="priority === 'high'" class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                        High
                                                    </span>
                                                    <span x-show="priority === 'medium'" class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        Medium
                                                    </span>
                                                    <span x-show="priority === 'low'" class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                        Low
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div x-data="{ status: '{{ $ticket->status }}' }">
                                                    <span x-show="status === 'baru'" class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                        Baru
                                                    </span>
                                                    <span x-show="status === 'diproses'" class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                                        Diproses
                                                    </span>
                                                    <span x-show="status === 'selesai'" class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        Selesai
                                                    </span>
                                                    <span x-show="status === 'ditutup'" class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-200 text-gray-850">
                                                        Ditutup
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                @if($ticket->agent)
                                                    <span class="text-gray-900 font-medium">{{ $ticket->agent->name }}</span>
                                                @else
                                                    <span class="text-xs text-red-500 italic font-medium">Belum Ditugaskan</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $ticket->created_at->format('d M Y, H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('tickets.show', $ticket->id) }}" wire:navigate class="text-indigo-600 hover:text-indigo-900">Detail</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $tickets->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

