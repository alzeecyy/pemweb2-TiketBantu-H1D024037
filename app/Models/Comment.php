<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['ticket_id', 'user_id', 'body'])]
class Comment extends Model
{
    /**
     * Tiket yang dikomentari.
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * User yang menulis komentar ini.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
