{{-- Livewire Island: Komponen komentar terpisah yang diperbarui realtime via wire:poll --}}
<div wire:poll.10s>
    {{-- Header --}}
    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
        <svg class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
        </svg>
        Diskusi & Komentar
        <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700">
            {{ $comments->count() }}
        </span>
    </h3>

    {{-- Daftar Komentar --}}
    <div class="space-y-4 mb-6 max-h-[500px] overflow-y-auto pr-1" id="comments-list">
        @forelse($comments as $comment)
            <div class="flex gap-3 {{ $comment->user_id === auth()->id() ? 'flex-row-reverse' : '' }}">
                {{-- Avatar --}}
                <div class="shrink-0">
                    <div class="h-8 w-8 rounded-full flex items-center justify-center text-xs font-bold shadow
                        {{ $comment->user->role === 'admin' ? 'bg-red-500 text-white' :
                           ($comment->user->role === 'agent' ? 'bg-indigo-500 text-white' : 'bg-gray-400 text-white') }}">
                        {{ strtoupper(substr($comment->user->name, 0, 2)) }}
                    </div>
                </div>

                {{-- Bubble --}}
                <div class="flex flex-col {{ $comment->user_id === auth()->id() ? 'items-end' : 'items-start' }} max-w-[80%]">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-xs font-semibold text-gray-700">{{ $comment->user->name }}</span>
                        @if($comment->user->role !== 'user')
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold uppercase
                                {{ $comment->user->role === 'admin' ? 'bg-red-100 text-red-700' : 'bg-indigo-100 text-indigo-700' }}">
                                {{ $comment->user->role }}
                            </span>
                        @endif
                        <span class="text-[10px] text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="px-4 py-2.5 rounded-2xl text-sm leading-relaxed shadow-sm
                        {{ $comment->user_id === auth()->id()
                            ? 'bg-indigo-600 text-white rounded-tr-none'
                            : 'bg-gray-100 text-gray-800 rounded-tl-none' }}">
                        {{ $comment->body }}
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-8 text-gray-400">
                <svg class="mx-auto h-10 w-10 text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                </svg>
                <p class="text-sm">Belum ada komentar. Jadilah yang pertama!</p>
            </div>
        @endforelse
    </div>

    {{-- Form Tambah Komentar --}}
    <div class="border-t pt-4">
        <form wire:submit="addComment">
            <div class="flex gap-3 items-start">
                {{-- Avatar user login --}}
                <div class="shrink-0">
                    <div class="h-8 w-8 rounded-full flex items-center justify-center text-xs font-bold shadow
                        {{ auth()->user()->role === 'admin' ? 'bg-red-500 text-white' :
                           (auth()->user()->role === 'agent' ? 'bg-indigo-500 text-white' : 'bg-gray-400 text-white') }}">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                </div>

                {{-- Input + Submit --}}
                <div class="flex-1">
                    <textarea
                        wire:model="body"
                        rows="2"
                        placeholder="Tulis balasan atau update..."
                        class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm resize-none"
                    ></textarea>
                    @error('body')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="shrink-0">
                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none transition ease-in-out duration-150 disabled:opacity-50"
                    >
                        <span wire:loading.remove wire:target="addComment">Kirim</span>
                        <span wire:loading wire:target="addComment">...</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Auto-scroll ke bawah setelah komentar baru ditambahkan --}}
    <script>
        document.addEventListener('livewire:updated', () => {
            const list = document.getElementById('comments-list');
            if (list) list.scrollTop = list.scrollHeight;
        });
    </script>
</div>
