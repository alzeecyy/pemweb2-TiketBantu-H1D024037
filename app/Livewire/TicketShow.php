<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\TicketStatusChanged;

class TicketShow extends Component
{
    public Ticket $ticket;
    public $status;
    public $agent_id;

    public function mount(Ticket $ticket)
    {
        // Muat relasi
        $this->ticket = $ticket->load(['category', 'user', 'agent', 'attachments']);
        $this->status = $ticket->status;
        $this->agent_id = $ticket->agent_id;

        // Cek hak akses melihat detail tiket
        $user = auth()->user();
        if ($user->role === 'user' && $this->ticket->user_id !== $user->id) {
            abort(403, 'Anda tidak diizinkan melihat tiket ini.');
        }
        if ($user->role === 'agent' && $this->ticket->agent_id !== null && $this->ticket->agent_id !== $user->id) {
            abort(403, 'Tiket ini ditugaskan ke agen lain.');
        }
    }

    // Mengambil alih tiket (oleh agen itu sendiri)
    public function claimTicket()
    {
        $user = auth()->user();
        if ($user->role !== 'agent' && $user->role !== 'admin') {
            abort(403);
        }

        $oldStatus = $this->ticket->status;

        $this->ticket->update([
            'agent_id' => $user->id,
            'status' => 'diproses' // Otomatis ubah status ke diproses saat diambil
        ]);

        $this->agent_id = $user->id;
        $this->status = 'diproses';

        // Kirim notifikasi email ke pelapor
        if ($oldStatus !== 'diproses') {
            $this->ticket->user->notify(new TicketStatusChanged($this->ticket, $oldStatus, 'diproses'));
        }

        session()->flash('message', 'Anda telah mengambil alih tiket ini.');
    }

    // Menyimpan penugasan agen (khusus Admin)
    public function assignAgent()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $this->ticket->update([
            'agent_id' => $this->agent_id ?: null
        ]);

        $this->ticket = $this->ticket->fresh();

        session()->flash('message', 'Agen penanggung jawab berhasil diperbarui.');
    }

    // Memperbarui status tiket (Agen & Admin)
    public function updateStatus()
    {
        $user = auth()->user();
        if ($user->role !== 'agent' && $user->role !== 'admin') {
            abort(403);
        }

        $oldStatus = $this->ticket->status;
        $updateData = ['status' => $this->status];

        // Jika status diubah ke selesai/ditutup, isi closed_at untuk SLA
        if (in_array($this->status, ['selesai', 'ditutup'])) {
            if (is_null($this->ticket->closed_at)) {
                $updateData['closed_at'] = now();
            }
        } else {
            // Jika dikembalikan ke baru/diproses, reset closed_at
            $updateData['closed_at'] = null;
        }

        $this->ticket->update($updateData);
        $this->ticket = $this->ticket->fresh();

        // Kirim notifikasi email ke pelapor jika status berubah
        if ($oldStatus !== $this->status) {
            $this->ticket->user->notify(new TicketStatusChanged($this->ticket, $oldStatus, $this->status));
        }

        session()->flash('message', 'Status tiket berhasil diubah menjadi: ' . ucfirst($this->status));
    }

    public function render()
    {
        // Ambil daftar agen yang bisa ditugaskan (role agent atau admin)
        $agents = User::whereIn('role', ['agent', 'admin'])->get();

        return view('livewire.ticket-show', [
            'agents' => $agents
        ])->layout('layouts.app');
    }
}
