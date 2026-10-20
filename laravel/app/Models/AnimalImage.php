<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnimalImage extends Model
{
    protected $fillable = [
        'image',
        'alt_text',
        'caption',
        'credit',
        'source',
        'display_order',
    ];

    public function animal(): BelongsTo
    {
        return $this->belongsTo(Animal::class);
    }
}
