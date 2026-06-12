<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\Ticket;
use App\Models\Category;
use App\Models\User;

class TicketEdit extends Component
{
    public Ticket $ticket;
    public $title;
    public $description;
    public $category_id;
    public $priority;
    public $status;
    public $agent_id;

    public function mount(Ticket $ticket)
    {
        $this->ticket = $ticket;
        $user = auth()->user();

        // Check permission
        if ($user->role === 'user') {
            if ($ticket->user_id !== $user->id) {
                abort(403, 'Anda tidak diizinkan mengubah tiket ini.');
            }
            if ($ticket->status !== 'baru') {
                abort(403, 'Tiket yang sudah diproses tidak dapat diubah.');
            }
        } elseif ($user->role === 'agent') {
            if ($ticket->agent_id !== null && $ticket->agent_id !== $user->id) {
                abort(403, 'Tiket ini ditugaskan ke agen lain.');
            }
        }

        // Initialize properties
        $this->title = $ticket->title;
        $this->description = $ticket->description;
        $this->category_id = $ticket->category_id;
        $this->priority = $ticket->priority;
        $this->status = $ticket->status;
        $this->agent_id = $ticket->agent_id;
    }

    public function save()
    {
        $user = auth()->user();
        
        if ($user->role === 'user') {
            if ($this->ticket->status !== 'baru') {
                abort(403);
            }
            $this->validate([
                'title' => 'required|string|min:5|max:255',
                'description' => 'required|string|min:10',
                'category_id' => 'required|exists:categories,id',
                'priority' => 'required|in:low,medium,high',
            ]);

            $this->ticket->update([
                'title' => $this->title,
                'description' => $this->description,
                'category_id' => $this->category_id,
                'priority' => $this->priority,
            ]);
        } elseif ($user->role === 'agent') {
            $this->validate([
                'status' => 'required|in:baru,diproses,selesai,ditutup',
                'priority' => 'required|in:low,medium,high',
            ]);

            $updateData = ['status' => $this->status, 'priority' => $this->priority];
            if (in_array($this->status, ['selesai', 'ditutup'])) {
                if (is_null($this->ticket->closed_at)) {
                    $updateData['closed_at'] = now();
                }
            } else {
                $updateData['closed_at'] = null;
            }

            // Auto-assign to this agent if it was unassigned
            if (is_null($this->ticket->agent_id)) {
                $updateData['agent_id'] = $user->id;
            }

            $this->ticket->update($updateData);
        } elseif ($user->role === 'admin') {
            $this->validate([
                'title' => 'required|string|min:5|max:255',
                'description' => 'required|string|min:10',
                'category_id' => 'required|exists:categories,id',
                'priority' => 'required|in:low,medium,high',
                'status' => 'required|in:baru,diproses,selesai,ditutup',
                'agent_id' => 'nullable|exists:users,id',
            ]);

            $updateData = [
                'title' => $this->title,
                'description' => $this->description,
                'category_id' => $this->category_id,
                'priority' => $this->priority,
                'status' => $this->status,
                'agent_id' => $this->agent_id ?: null,
            ];

            if (in_array($this->status, ['selesai', 'ditutup'])) {
                if (is_null($this->ticket->closed_at)) {
                    $updateData['closed_at'] = now();
                }
            } else {
                $updateData['closed_at'] = null;
            }

            $this->ticket->update($updateData);
        }

        session()->flash('message', 'Tiket pengaduan berhasil diperbarui.');

        return $this->redirect(route('tickets.show', $this->ticket->id), navigate: true);
    }

    public function render()
    {
        $categories = Category::all();
        $agents = User::whereIn('role', ['agent', 'admin'])->get();

        return view('livewire.ticket-edit', [
            'categories' => $categories,
            'agents' => $agents,
        ])->layout('layouts.app');
    }
}
