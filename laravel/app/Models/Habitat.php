<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Habitat extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'image',
        'status',
        'climate',
        'location',
        'geographical_range',
        'image_alt_text',
        'image_caption',
        'image_credit',
        'meta_title',
        'meta_description',
        'canonical_url',
        'noindex',
        'is_homepage_visible',
        'display_order',
    ];

    protected function casts(): array
    {
        return [
            'noindex' => 'boolean',
            'is_homepage_visible' => 'boolean',
            'display_order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $habitat) {
            $habitat->slug ??= Str::slug($habitat->name);
        });

        static::updating(function (self $habitat) {
            $habitat->slug ??= Str::slug($habitat->name);
        });
    }

    public function animals()
    {
        return $this->hasMany(Animal::class);
    }
}
