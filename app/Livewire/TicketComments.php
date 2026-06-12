<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Comment;
use App\Models\Ticket;

class TicketComments extends Component
{
    public Ticket $ticket;
    public $body = '';

    // Livewire Island - komponen ini di-poll secara berkala untuk efek realtime
    // Dapat juga digunakan dengan wire:poll untuk memperbarui komentar
    protected $listeners = ['commentAdded' => '$refresh'];

    protected function rules()
    {
        return [
            'body' => 'required|string|min:3|max:2000',
        ];
    }

    protected $messages = [
        'body.required' => 'Komentar tidak boleh kosong.',
        'body.min' => 'Komentar minimal 3 karakter.',
        'body.max' => 'Komentar maksimal 2000 karakter.',
    ];

    public function placeholder()
    {
        return <<<'HTML'
        <div class="space-y-4 animate-pulse">
            <h3 class="h-6 w-1/3 bg-outline-variant/30 rounded mb-6"></h3>
            <div class="space-y-4">
                <div class="flex gap-3">
                    <div class="h-8 w-8 rounded-full bg-outline-variant/30"></div>
                    <div class="flex-1 space-y-2">
                        <div class="h-4 w-1/4 bg-outline-variant/30 rounded"></div>
                        <div class="h-10 w-3/4 bg-outline-variant/30 rounded-2xl"></div>
                    </div>
                </div>
                <div class="flex gap-3 flex-row-reverse">
                    <div class="h-8 w-8 rounded-full bg-outline-variant/30"></div>
                    <div class="flex-1 space-y-2 flex flex-col items-end">
                        <div class="h-4 w-1/4 bg-outline-variant/30 rounded"></div>
                        <div class="h-10 w-2/3 bg-outline-variant/30 rounded-2xl"></div>
                    </div>
                </div>
            </div>
        </div>
        HTML;
    }

    public function addComment()
    {
        $this->validate();

        Comment::create([
            'ticket_id' => $this->ticket->id,
            'user_id' => auth()->id(),
            'body' => $this->body,
        ]);

        $this->body = '';

        // Emit event agar komponen me-refresh tampilan komentar
        $this->dispatch('commentAdded');
        
        // Dispatch toast notification
        $this->dispatch('toast', message: 'Komentar berhasil ditambahkan.', type: 'success');
    }

    public function render()
    {
        // Muat ulang komentar setiap kali komponen di-render (efek Island/polling)
        $comments = Comment::where('ticket_id', $this->ticket->id)
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('livewire.ticket-comments', [
            'comments' => $comments,
        ]);
    }
}
