<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'location',
        'event_date',
        'event_time',
        'image',
        'status',
        'image_alt_text',
        'image_caption',
        'image_credit',
        'meta_title',
        'meta_description',
        'canonical_url',
        'noindex',
        'is_featured',
        'is_homepage_visible',
        'display_order',
    ];

    protected $casts = [
        'event_date' => 'date',
        'noindex' => 'boolean',
        'is_featured' => 'boolean',
        'is_homepage_visible' => 'boolean',
        'display_order' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $event) {
            $event->slug ??= Str::slug($event->title);
        });

        static::updating(function (self $event) {
            $event->slug ??= Str::slug($event->title);
        });
    }
}
