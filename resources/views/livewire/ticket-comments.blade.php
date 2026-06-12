<div wire:poll.10s>
    {{-- Header --}}
    <h3 class="text-lg font-bold text-on-surface mb-6 flex items-center gap-2">
        <span class="material-symbols-outlined text-primary text-2xl">forum</span>
        Diskusi & Komentar
        <span class="ml-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black bg-primary-fixed text-on-primary-fixed-variant">
            {{ $comments->count() }}
        </span>
    </h3>

    {{-- Daftar Komentar --}}
    <div class="space-y-4 mb-6 max-h-[500px] overflow-y-auto pr-1" id="comments-list">
        @forelse($comments as $comment)
            @php
                $isCurrentUser = $comment->user_id === auth()->id();
                // Map styles according to role
                $avatarBg = 'bg-surface-container text-on-surface';
                if ($comment->user->role === 'admin') {
                    $avatarBg = 'bg-primary-fixed text-on-primary-fixed-variant';
                } elseif ($comment->user->role === 'agent') {
                    $avatarBg = 'bg-tertiary-fixed text-on-tertiary-fixed-variant';
                }
            @endphp
            <div class="flex gap-3 {{ $isCurrentUser ? 'flex-row-reverse' : '' }}">
                {{-- Avatar --}}
                <div class="shrink-0">
                    <div class="h-8 w-8 rounded-full flex items-center justify-center text-xs font-black shadow-sm {{ $avatarBg }}">
                        {{ strtoupper(substr($comment->user->name, 0, 2)) }}
                    </div>
                </div>

                {{-- Bubble --}}
                <div class="flex flex-col {{ $isCurrentUser ? 'items-end' : 'items-start' }} max-w-[80%]">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-xs font-black text-on-surface">{{ $comment->user->name }}</span>
                        @if($comment->user->role !== 'user')
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-black uppercase
                                {{ $comment->user->role === 'admin' ? 'bg-primary-fixed text-on-primary-fixed-variant' : 'bg-tertiary-fixed text-on-tertiary-fixed-variant' }}">
                                {{ $comment->user->role }}
                            </span>
                        @endif
                        <span class="text-[9px] font-medium text-on-surface-variant/60">{{ $comment->created_at->diffForHumans() }}</span>
                    </div>
                    
                    <div class="px-4 py-2.5 rounded-2xl text-sm leading-relaxed shadow-sm font-medium
                        {{ $isCurrentUser
                            ? 'bg-gradient-to-r from-primary to-secondary text-white rounded-tr-none'
                            : 'bg-surface-container-low text-on-surface border border-outline-variant/15 rounded-tl-none' }}">
                        {{ $comment->body }}
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12 text-on-surface-variant/40">
                <span class="material-symbols-outlined text-5xl mb-2">chat_bubble_outline</span>
                <p class="text-sm font-medium">Belum ada komentar. Jadilah yang pertama!</p>
            </div>
        @endforelse
    </div>

    {{-- Form Tambah Komentar --}}
    <div class="border-t border-outline-variant/20 pt-6">
        <form wire:submit="addComment">
            <div class="flex gap-3 items-start">
                {{-- Avatar user login --}}
                <div class="shrink-0">
                    <div class="h-8 w-8 rounded-full flex items-center justify-center text-xs font-black shadow-sm bg-gradient-to-br from-primary to-secondary text-white">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                </div>

                {{-- Input + Submit --}}
                <div class="flex-1">
                    <textarea
                        wire:model="body"
                        rows="2"
                        placeholder="Tulis balasan atau update..."
                        class="w-full rounded-lg border-outline-variant/30 bg-surface-container-low text-sm focus:ring-primary focus:border-primary transition-all placeholder:text-on-surface-variant/40 resize-none"
                    ></textarea>
                    @error('body')
                        <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                    @enderror
                </div>

                <div class="shrink-0">
                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        class="inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-primary to-secondary text-white rounded-xl text-xs font-bold hover:scale-[1.02] active:scale-[0.98] transition-all duration-150 disabled:opacity-50 shadow-md shadow-primary/10"
                    >
                        <span wire:loading.remove wire:target="addComment" class="flex items-center gap-1">
                            Kirim <span class="material-symbols-outlined text-xs">send</span>
                        </span>
                        <span wire:loading wire:target="addComment" class="material-symbols-outlined animate-spin text-sm">sync</span>
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

