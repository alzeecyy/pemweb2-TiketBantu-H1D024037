<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketStatusChanged extends Notification
{
    use Queueable;

    public function __construct(
        public Ticket $ticket,
        public string $oldStatus,
        public string $newStatus
    ) {}

    /**
     * Channel notifikasi: email
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Konten email notifikasi
     */
    public function toMail(object $notifiable): MailMessage
    {
        $ticketId = '#TKT-' . str_pad($this->ticket->id, 5, '0', STR_PAD_LEFT);
        $statusLabel = [
            'baru' => 'Baru',
            'diproses' => 'Diproses',
            'selesai' => 'Selesai',
            'ditutup' => 'Ditutup',
        ];

        $oldLabel = $statusLabel[$this->oldStatus] ?? $this->oldStatus;
        $newLabel = $statusLabel[$this->newStatus] ?? $this->newStatus;

        return (new MailMessage)
            ->subject("Status Tiket {$ticketId} Diperbarui — {$newLabel}")
            ->greeting("Halo, {$notifiable->name}!")
            ->line("Status tiket Anda telah diperbarui.")
            ->line("**Tiket:** {$ticketId} — {$this->ticket->title}")
            ->line("**Status Lama:** {$oldLabel}")
            ->line("**Status Baru:** {$newLabel}")
            ->action('Lihat Detail Tiket', url('/tickets/' . $this->ticket->id))
            ->line('Terima kasih telah menggunakan TiketBantu!');
    }
}
