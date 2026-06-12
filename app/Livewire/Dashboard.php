<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\Ticket;
use App\Models\Category;

class Dashboard extends Component
{
    public function render()
    {
        $user = auth()->user();
        $data = [];
        $query = Ticket::query();

        if ($user->role === 'agent') {
            $query->where('agent_id', $user->id);
        } elseif ($user->role === 'user') {
            $query->where('user_id', $user->id);
        }

        // Statistik (Berlaku untuk semua role, namun angkanya sesuai scope query)
        $data['totalTickets'] = (clone $query)->count();
        $data['baruCount'] = (clone $query)->where('status', 'baru')->count();
        $data['prosesCount'] = (clone $query)->where('status', 'diproses')->count();
        $data['selesaiCount'] = (clone $query)->where('status', 'selesai')->count();
        $data['ditutupCount'] = (clone $query)->where('status', 'ditutup')->count();

        // Agent specific stats
        if ($user->role === 'agent') {
            $data['assignedTicketsCount'] = $data['totalTickets'];
            $data['highPriorityCount'] = (clone $query)->where('priority', 'high')->count();
            $data['unresolvedCount'] = (clone $query)->whereIn('status', ['baru', 'diproses'])->count();
        }

        // Data Tiket Berdasarkan Prioritas (Untuk Bagian Bawah Dashboard)
        $data['urgentTickets'] = (clone $query)->where('priority', 'high')
            ->whereNotIn('status', ['selesai', 'ditutup'])
            ->with(['user', 'category', 'agent'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        $data['mediumTickets'] = (clone $query)->where('priority', 'medium')
            ->whereNotIn('status', ['selesai', 'ditutup'])
            ->with(['user', 'category', 'agent'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        $data['lowTickets'] = (clone $query)->where('priority', 'low')
            ->whereNotIn('status', ['selesai', 'ditutup'])
            ->with(['user', 'category', 'agent'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('livewire.dashboard', $data)->layout('layouts.app');
    }
}
