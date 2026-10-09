<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Animal extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'scientific_name',
        'category',
        'description',
        'habitat_id',
        'image',
        'status',
        'slug',
        'species',
        'diet',
        'lifespan',
        'conservation_status',
        'fun_fact',
        'is_featured',
        'habitat',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $animal) {
            $animal->slug ??= Str::slug($animal->name);
        });

        static::updating(function (self $animal) {
            $animal->slug ??= Str::slug($animal->name);
        });
    }

    public function habitat()
    {
        return $this->belongsTo(Habitat::class);
    }
}
