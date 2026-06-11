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
