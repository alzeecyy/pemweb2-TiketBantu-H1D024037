<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Ticket;
use App\Models\Category;
use App\Models\Attachment;
use Livewire\WithFileUploads;

class TicketCreate extends Component
{
    use WithFileUploads;

    public $title = '';
    public $description = '';
    public $category_id = '';
    public $priority = 'low';
    public $attachment; // Untuk upload berkas lampiran

    protected $rules = [
        'title' => 'required|string|min:5|max:255',
        'description' => 'required|string|min:10',
        'category_id' => 'required|exists:categories,id',
        'priority' => 'required|in:low,medium,high',
        'attachment' => 'nullable|file|max:5120', // Maksimal file 5MB
    ];

    protected $messages = [
        'title.required' => 'Judul pengaduan wajib diisi.',
        'title.min' => 'Judul minimal 5 karakter.',
        'description.required' => 'Deskripsi masalah wajib diisi.',
        'description.min' => 'Deskripsi minimal 10 karakter.',
        'category_id.required' => 'Pilih kategori terlebih dahulu.',
        'category_id.exists' => 'Kategori yang dipilih tidak valid.',
        'priority.required' => 'Pilih tingkat prioritas.',
        'priority.in' => 'Prioritas tidak valid.',
        'attachment.file' => 'Lampiran harus berupa file.',
        'attachment.max' => 'Ukuran file lampiran maksimal 5MB.',
    ];

    public function save()
    {
        $this->validate();

        // Buat tiket baru
        $ticket = Ticket::create([
            'title' => $this->title,
            'description' => $this->description,
            'category_id' => $this->category_id,
            'priority' => $this->priority,
            'status' => 'baru',
            'user_id' => auth()->id(),
            'sort_order' => Ticket::max('sort_order') + 1, // Untuk wire:sort drag-and-drop
        ]);

        // Simpan lampiran jika ada file yang diunggah
        if ($this->attachment) {
            $originalName = $this->attachment->getClientOriginalName();
            $path = $this->attachment->store('attachments', 'public');

            Attachment::create([
                'ticket_id' => $ticket->id,
                'file_path' => $path,
                'file_name' => $originalName,
            ]);
        }

        session()->flash('message', 'Tiket pengaduan berhasil dibuat.');

        return $this->redirect(route('tickets.index'), navigate: true);
    }

    public function render()
    {
        $categories = Category::all();

        return view('livewire.ticket-create', [
            'categories' => $categories
        ])->layout('layouts.app');
    }
}
