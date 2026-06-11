<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Tiket #TKT-{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}
            </h2>
            <a href="{{ route('tickets.index') }}" wire:navigate class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none transition ease-in-out duration-150">
                Kembali ke Daftar
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Flash Message -->
            @if (session()->has('message'))
                <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
                    {{ session('message') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Ticket Content (Left - 2 Columns) -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <!-- Header / Title -->
                        <div class="border-b pb-4 mb-4">
                            <span class="text-xs font-semibold uppercase tracking-wider text-indigo-600 mb-1 block">
                                {{ $ticket->category->name }}
                            </span>
                            <h3 class="text-2xl font-bold text-gray-900">{{ $ticket->title }}</h3>
                            <div class="text-xs text-gray-500 mt-2">
                                Dilaporkan pada {{ $ticket->created_at->format('d M Y, H:i') }} oleh <strong>{{ $ticket->user->name }}</strong> ({{ $ticket->user->email }})
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="text-gray-700 text-sm whitespace-pre-line leading-relaxed mb-6">
                            {{ $ticket->description }}
                        </div>

                        <!-- Attachments (Lampiran Berkas) -->
                        @if($ticket->attachments->isNotEmpty())
                            <div class="border-t pt-4">
                                <h4 class="text-sm font-semibold text-gray-800 mb-2">Lampiran Berkas:</h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    @foreach($ticket->attachments as $attachment)
                                        <div class="flex items-center p-3 border rounded-md bg-gray-50 hover:bg-gray-100 transition">
                                            <div class="mr-3 text-indigo-600">
                                                <!-- Icon attachment -->
                                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-xs font-medium text-gray-900 truncate">{{ $attachment->file_name }}</p>
                                                <p class="text-[10px] text-gray-500">Lampiran #{{ $attachment->id }}</p>
                                            </div>
                                            <div class="ml-2">
                                                <a href="{{ asset('storage/' . $attachment->file_path) }}" target="_blank" class="text-xs font-semibold text-indigo-600 hover:text-indigo-900">
                                                    Lihat
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Livewire Island: Komponen Komentar Realtime (wire:poll di dalam komponen) --}}
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <livewire:ticket-comments :ticket="$ticket" :key="'comments-'.$ticket->id" />
                    </div>
                </div>

                <!-- Sidebar Controls (Right - 1 Column) -->
                <div class="space-y-6">
                    <!-- Ticket Meta Details -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider border-b pb-2 mb-4">Detail Status</h3>

                        <div class="space-y-4">
                            <!-- Status Badge -->
                            <div>
                                <span class="block text-xs font-medium text-gray-500 mb-1">Status Tiket:</span>
                                <div x-data="{ status: '{{ $ticket->status }}' }">
                                    <span x-show="status === 'baru'" class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Baru
                                    </span>
                                    <span x-show="status === 'diproses'" class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                        Diproses
                                    </span>
                                    <span x-show="status === 'selesai'" class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Selesai
                                    </span>
                                    <span x-show="status === 'ditutup'" class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-200 text-gray-850">
                                        Ditutup
                                    </span>
                                </div>
                            </div>

                            <!-- Priority Badge -->
                            <div>
                                <span class="block text-xs font-medium text-gray-500 mb-1">Prioritas:</span>
                                <div x-data="{ priority: '{{ $ticket->priority }}' }">
                                    <span x-show="priority === 'high'" class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        High (Mendesak)
                                    </span>
                                    <span x-show="priority === 'medium'" class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Medium (Sedang)
                                    </span>
                                    <span x-show="priority === 'low'" class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Low (Biasa)
                                    </span>
                                </div>
                            </div>

                            <!-- Assigned Agent -->
                            <div>
                                <span class="block text-xs font-medium text-gray-500 mb-1">Petugas (Agen):</span>
                                @if($ticket->agent)
                                    <div class="flex items-center text-sm text-gray-900 font-medium">
                                        <div class="h-6 w-6 rounded-full bg-gray-300 flex items-center justify-center mr-2 text-[10px] text-gray-700">
                                            {{ strtoupper(substr($ticket->agent->name, 0, 2)) }}
                                        </div>
                                        {{ $ticket->agent->name }}
                                    </div>
                                @else
                                    <span class="text-xs text-red-500 italic font-medium">Belum ditugaskan ke petugas manapun</span>
                                @endif
                            </div>

                            <!-- SLA / Lama Penyelesaian (Nilai Tambah) -->
                            @if($ticket->closed_at)
                                <div class="border-t pt-3 mt-3">
                                    <span class="block text-xs font-medium text-gray-500 mb-1">SLA (Waktu Penyelesaian):</span>
                                    <span class="text-xs font-semibold text-indigo-700 block bg-indigo-50 p-2 rounded">
                                        {{ $ticket->created_at->diff($ticket->closed_at)->format('%d hari, %h jam, %i menit') }}
                                    </span>
                                    <span class="text-[9px] text-gray-400 mt-1 block">
                                        Selesai pada {{ $ticket->closed_at->format('d M Y, H:i') }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Role-based Actions -->
                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'agent')
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 space-y-6">
                            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider border-b pb-2">Kontrol Agen</h3>

                            <!-- Claim Ticket (Only visible to Agent if unassigned) -->
                            @if(is_null($ticket->agent_id) && auth()->user()->role === 'agent')
                                <div>
                                    <button wire:click="claimTicket" class="w-full text-center inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 transition ease-in-out duration-150">
                                        Ambil Alih Tiket Ini
                                    </button>
                                </div>
                            @endif

                            <!-- Update Status (Admin & Agent) -->
                            @if(!is_null($ticket->agent_id) || auth()->user()->role === 'admin')
                                <div>
                                    <label for="update_status" class="block text-xs font-medium text-gray-500 mb-1">Ubah Status Tiket</label>
                                    <div class="flex gap-2">
                                        <select wire:model="status" id="update_status" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-xs">
                                            <option value="baru">Baru</option>
                                            <option value="diproses">Diproses</option>
                                            <option value="selesai">Selesai</option>
                                            <option value="ditutup">Ditutup</option>
                                        </select>
                                        <button wire:click="updateStatus" class="px-3 py-1.5 bg-gray-800 text-white rounded-md text-xs font-semibold hover:bg-gray-700 transition">
                                            Simpan
                                        </button>
                                    </div>
                                </div>
                            @endif

                            <!-- Assign Agent (Only Admin) -->
                            @if(auth()->user()->role === 'admin')
                                <div>
                                    <label for="assign_agent" class="block text-xs font-medium text-gray-500 mb-1">Tugaskan Petugas/Agen</label>
                                    <div class="flex gap-2">
                                        <select wire:model="agent_id" id="assign_agent" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-xs">
                                            <option value="">-- Tanpa Agen --</option>
                                            @foreach($agents as $agent)
                                                <option value="{{ $agent->id }}">{{ $agent->name }} ({{ ucfirst($agent->role) }})</option>
                                            @endforeach
                                        </select>
                                        <button wire:click="assignAgent" class="px-3 py-1.5 bg-gray-800 text-white rounded-md text-xs font-semibold hover:bg-gray-700 transition">
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
    </div>
</div>
