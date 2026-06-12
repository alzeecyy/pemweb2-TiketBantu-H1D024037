<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['title', 'description', 'priority', 'status', 'category_id', 'user_id', 'agent_id', 'sort_order', 'closed_at'])]
class Ticket extends Model
{
    protected $casts = [
        'closed_at' => 'datetime',
    ];

    /**
     * Kategori dari tiket ini.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Pelapor yang membuat tiket ini.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Agen/petugas yang ditugaskan untuk menyelesaikan tiket ini.
     */
    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    /**
     * Komentar/balasan dalam tiket ini.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Lampiran berkas untuk tiket ini.
     */
    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }
}
