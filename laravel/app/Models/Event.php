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
    ];

    protected $casts = [
        'event_date' => 'date',
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
