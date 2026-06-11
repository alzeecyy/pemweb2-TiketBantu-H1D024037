<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name', 'slug'])]
class Category extends Model
{
    /**
     * Tiket yang tergolong dalam kategori ini.
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
