<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Ticket;
use App\Models\Category;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class TicketIndex extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public $search = '';

    #[Url(except: '')]
    public $status = '';

    #[Url(except: '')]
    public $priority = '';

    #[Url(except: '')]
    public $category_id = '';

    #[Url(except: '')]
    public $assigned = '';

    // Reset halaman paginasi jika pencarian atau filter berubah
    public function updatingSearch() { $this->resetPage(); }
    public function updatingStatus() { $this->resetPage(); }
    public function updatingPriority() { $this->resetPage(); }
    public function updatingCategoryId() { $this->resetPage(); }
    public function updatingAssigned() { $this->resetPage(); }

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

        // Skip re-render karena DOM sudah diatur oleh SortableJS di client
        $this->skipRender();
    }

    public function render()
    {
        $user = auth()->user();
        $query = Ticket::query()->with(['category', 'user', 'agent']);

        // Filter berdasarkan Role
        if ($user->role === 'agent') {
            if ($this->assigned === '1') {
                // "Tugas Saya": hanya tiket yang ditugaskan ke agen ini
                $query->where('agent_id', $user->id);
            } else {
                // "Tiket": tampilkan tiket agen ini ATAU tiket yang belum memiliki agen penanggung jawab (agar bisa diklaim)
                $query->where(function ($q) use ($user) {
                    $q->where('agent_id', $user->id)
                      ->orWhereNull('agent_id');
                });
            }
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

        // Hitung statistik berdasarkan role/visibilitas user saat ini
        $metricsQuery = Ticket::query();
        if ($user->role === 'agent') {
            if ($this->assigned === '1') {
                $metricsQuery->where('agent_id', $user->id);
            } else {
                $metricsQuery->where(function ($q) use ($user) {
                    $q->where('agent_id', $user->id)
                      ->orWhereNull('agent_id');
                });
            }
        } elseif ($user->role === 'user') {
            $metricsQuery->where('user_id', $user->id);
        }

        $allTicketsCount = $metricsQuery->count();
        $baruCount = (clone $metricsQuery)->where('status', 'baru')->count();
        $prosesCount = (clone $metricsQuery)->where('status', 'diproses')->count();
        $selesaiCount = (clone $metricsQuery)->where('status', 'selesai')->count();
        $ditutupCount = (clone $metricsQuery)->where('status', 'ditutup')->count();

        $slaSuccess = 100;
        if ($allTicketsCount > 0) {
            $resolvedCount = $selesaiCount + $ditutupCount;
            $slaSuccess = round(($resolvedCount / $allTicketsCount) * 100);
        }

        return view('livewire.ticket-index', [
            'tickets' => $tickets,
            'categories' => $categories,
            'canSort' => $this->canSort(),
            'baruCount' => $baruCount,
            'prosesCount' => $prosesCount,
            'selesaiCount' => $selesaiCount,
            'slaSuccess' => $slaSuccess,
        ])->layout('layouts.app');
    }
}
