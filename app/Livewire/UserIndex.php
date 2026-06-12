<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserIndex extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public $search = '';

    #[Url(except: '')]
    public $roleFilter = '';

    // Create Form Properties
    public $name = '';
    public $email = '';
    public $password = '';
    public $role = 'user';
    public $showCreateModal = false;

    // Edit Form Properties
    public $editingUserId = null;
    public $editingName = '';
    public $editingEmail = '';
    public $editingRole = '';
    public $showEditModal = false;

    public function updatingSearch() { $this->resetPage(); }
    public function updatingRoleFilter() { $this->resetPage(); }

    public function openCreateModal()
    {
        $this->resetValidation();
        $this->reset(['name', 'email', 'password', 'role']);
        $this->role = 'user';
        $this->showCreateModal = true;
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
    }

    public function createUser()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:admin,agent,user',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'role.required' => 'Role wajib dipilih.',
        ]);

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => $this->role,
        ]);

        $this->showCreateModal = false;
        $this->dispatch('toast', message: 'User berhasil ditambahkan.', type: 'success');
    }

    public function openEditModal($id)
    {
        $this->resetValidation();
        $user = User::findOrFail($id);
        $this->editingUserId = $id;
        $this->editingName = $user->name;
        $this->editingEmail = $user->email;
        $this->editingRole = $user->role;
        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
    }

    public function updateUser()
    {
        $this->validate([
            'editingName' => 'required|string|max:255',
            'editingEmail' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->editingUserId),
            ],
            'editingRole' => 'required|string|in:admin,agent,user',
        ], [
            'editingName.required' => 'Nama wajib diisi.',
            'editingEmail.required' => 'Email wajib diisi.',
            'editingEmail.unique' => 'Email sudah digunakan oleh user lain.',
            'editingRole.required' => 'Role wajib dipilih.',
        ]);

        $user = User::findOrFail($this->editingUserId);
        
        // Prevent admin from changing their own role to something else
        if ($user->id === auth()->id() && $this->editingRole !== 'admin') {
            $this->dispatch('toast', message: 'Anda tidak dapat mengubah role Anda sendiri.', type: 'error');
            return;
        }

        $user->update([
            'name' => $this->editingName,
            'email' => $this->editingEmail,
            'role' => $this->editingRole,
        ]);

        $this->showEditModal = false;
        $this->dispatch('toast', message: 'User berhasil diperbarui.', type: 'success');
    }

    public function deleteUser($id)
    {
        if ($id === auth()->id()) {
            $this->dispatch('toast', message: 'Anda tidak dapat menghapus akun Anda sendiri.', type: 'error');
            return;
        }

        $user = User::findOrFail($id);
        
        // Check if user has tickets as reporter or agent
        if ($user->tickets()->exists() || $user->assignedTickets()->exists()) {
            $this->dispatch('toast', message: 'User tidak dapat dihapus karena memiliki riwayat tiket pengaduan.', type: 'error');
            return;
        }

        $user->delete();
        $this->dispatch('toast', message: 'User berhasil dihapus.', type: 'success');
    }

    public function render()
    {
        $query = User::query();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->roleFilter) {
            $query->where('role', $this->roleFilter);
        }

        $users = $query->orderBy('name', 'asc')->paginate(10);

        return view('livewire.user-index', [
            'users' => $users
        ])->layout('layouts.app');
    }
}
