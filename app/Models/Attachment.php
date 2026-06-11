<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['ticket_id', 'file_path', 'file_name'])]
class Attachment extends Model
{
    /**
     * Tiket dari lampiran ini.
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
