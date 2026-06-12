<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\Category;

class CategoryIndex extends Component
{
    public $name = '';
    public $editingCategoryId = null;
    public $editingCategoryName = '';

    protected $rules = [
        'name' => 'required|string|min:3|max:100|unique:categories,name',
    ];

    protected $messages = [
        'name.required' => 'Nama kategori wajib diisi.',
        'name.min' => 'Nama kategori minimal 3 karakter.',
        'name.unique' => 'Nama kategori sudah digunakan.',
    ];

    public function create()
    {
        $this->validate();

        Category::create([
            'name' => $this->name,
        ]);

        $this->name = '';
        $this->dispatch('toast', message: 'Kategori baru berhasil ditambahkan.', type: 'success');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $this->editingCategoryId = $id;
        $this->editingCategoryName = $category->name;
    }

    public function cancelEdit()
    {
        $this->editingCategoryId = null;
        $this->editingCategoryName = '';
        $this->resetValidation();
    }

    public function update()
    {
        $this->validate([
            'editingCategoryName' => 'required|string|min:3|max:100|unique:categories,name,' . $this->editingCategoryId,
        ], [
            'editingCategoryName.required' => 'Nama kategori wajib diisi.',
            'editingCategoryName.min' => 'Nama kategori minimal 3 karakter.',
            'editingCategoryName.unique' => 'Nama kategori sudah digunakan.',
        ]);

        $category = Category::findOrFail($this->editingCategoryId);
        $category->update([
            'name' => $this->editingCategoryName,
        ]);

        $this->editingCategoryId = null;
        $this->editingCategoryName = '';
        $this->dispatch('toast', message: 'Nama kategori berhasil diubah.', type: 'success');
    }

    public function delete($id)
    {
        $category = Category::findOrFail($id);

        // Check if there are tickets belonging to this category
        if ($category->tickets()->exists()) {
            $this->dispatch('toast', message: 'Kategori ini tidak dapat dihapus karena masih digunakan oleh tiket pengaduan.', type: 'error');
            return;
        }

        $category->delete();
        $this->dispatch('toast', message: 'Kategori berhasil dihapus.', type: 'success');
    }

    public function render()
    {
        $categories = Category::withCount('tickets')->get();

        return view('livewire.category-index', [
            'categories' => $categories,
        ])->layout('layouts.app');
    }
}
