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

    /**
     * Cek apakah user saat ini bisa melakukan drag-and-drop sort.
     * Hanya admin dan agent yang bisa mengurutkan tiket.
     */
    public function canSort(): bool
    {
        return in_array(auth()->user()->role, ['admin', 'agent']);
    }

    /**
     * Tantangan Khusus Livewire 4: wire:sort drag-and-drop
     * Menerima array urutan baru dari frontend dan menyimpannya ke database.
     */
    public function handleSort($items)
    {
        // Hanya admin/agent yang boleh mengurutkan
        if (! $this->canSort()) {
            return;
        }

        foreach ($items as $item) {
            Ticket::where('id', $item['value'])
                ->update(['sort_order' => $item['order']]);
        }
    }

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
            'canSort' => $this->canSort(),
        ])->layout('layouts.app');
    }
}
