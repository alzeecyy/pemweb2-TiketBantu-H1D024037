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
    public $priority;

    public function mount(Ticket $ticket)
    {
        // Muat relasi
        $this->ticket = $ticket->load(['category', 'user', 'agent', 'attachments']);
        $this->status = $ticket->status;
        $this->agent_id = $ticket->agent_id;
        $this->priority = $ticket->priority;

        // Cek hak akses melihat detail tiket
        $user = auth()->user();
        if ($user->role === 'user' && $this->ticket->user_id !== $user->id) {
            abort(403, 'Anda tidak diizinkan melihat tiket ini.');
        }
        if ($user->role === 'agent' && !is_null($this->ticket->agent_id) && $this->ticket->agent_id !== $user->id) {
            abort(403, 'Anda tidak diizinkan melihat tiket agen lain.');
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

        $this->ticket = $this->ticket->fresh(['category', 'user', 'agent', 'attachments']);

        $this->agent_id = $user->id;
        $this->status = 'diproses';

        // Kirim notifikasi email ke pelapor
        if ($oldStatus !== 'diproses') {
            $this->ticket->user->notify(new TicketStatusChanged($this->ticket, $oldStatus, 'diproses'));
        }

        $this->dispatch('toast', message: 'Anda telah mengambil alih tiket ini.', type: 'success');
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

        $this->ticket = $this->ticket->fresh(['category', 'user', 'agent', 'attachments']);

        $this->dispatch('toast', message: 'Agen penanggung jawab berhasil diperbarui.', type: 'success');
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
        $this->ticket = $this->ticket->fresh(['category', 'user', 'agent', 'attachments']);

        // Kirim notifikasi email ke pelapor jika status berubah
        if ($oldStatus !== $this->status) {
            $this->ticket->user->notify(new TicketStatusChanged($this->ticket, $oldStatus, $this->status));
        }

        $this->dispatch('toast', message: 'Status tiket berhasil diubah menjadi: ' . ucfirst($this->status), type: 'success');
    }

    // Memperbarui prioritas tiket (Agen & Admin)
    public function updatePriority()
    {
        $user = auth()->user();
        if ($user->role !== 'agent' && $user->role !== 'admin') {
            abort(403);
        }

        $this->validate([
            'priority' => 'required|in:low,medium,high',
        ]);

        $this->ticket->update(['priority' => $this->priority]);
        $this->ticket = $this->ticket->fresh(['category', 'user', 'agent', 'attachments']);

        $this->dispatch('toast', message: 'Prioritas tiket berhasil diubah menjadi: ' . ucfirst($this->priority), type: 'success');
    }

    // Hapus tiket (Admin only - soft delete)
    public function deleteTicket()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $this->ticket->delete();

        session()->flash('message', 'Tiket pengaduan berhasil dihapus.');

        return $this->redirect(route('tickets.index'), navigate: true);
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
