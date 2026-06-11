<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Ticket;
use App\Models\Category;
use Livewire\WithPagination;

class TicketIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $priority = '';
    public $category_id = '';

    // Reset halaman paginasi jika pencarian atau filter berubah
    public function updatingSearch() { $this->resetPage(); }
    public function updatingStatus() { $this->resetPage(); }
    public function updatingPriority() { $this->resetPage(); }
    public function updatingCategoryId() { $this->resetPage(); }

    public function render()
    {
        $user = auth()->user();
        $query = Ticket::query()->with(['category', 'user', 'agent']);

        // Filter berdasarkan Role
        if ($user->role === 'agent') {
            $query->where(function ($q) use ($user) {
                $q->where('agent_id', $user->id)
                  ->orWhereNull('agent_id');
            });
        } elseif ($user->role === 'user') {
            $query->where('user_id', $user->id);
        }

        // Filter Pencarian (Judul / Deskripsi / Nama Pelapor)
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhereHas('user', function ($uq) {
                      $uq->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        // Filter Status
        if ($this->status) {
            $query->where('status', $this->status);
        }

        // Filter Prioritas
        if ($this->priority) {
            $query->where('priority', $this->priority);
        }

        // Filter Kategori
        if ($this->category_id) {
            $query->where('category_id', $this->category_id);
        }

        // Ambil data tiket dan kategori
        $tickets = $query->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $categories = Category::all();

        return view('livewire.ticket-index', [
            'tickets' => $tickets,
            'categories' => $categories,
        ])->layout('layouts.app');
    }
}
