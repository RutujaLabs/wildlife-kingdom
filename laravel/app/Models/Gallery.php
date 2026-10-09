<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'image',
        'category',
        'status',
    ];

    public function getTable()
    {
        return Schema::hasTable('galleries') ? 'galleries' : 'gallery';
    }

    public function usesTimestamps(): bool
    {
        return Schema::hasColumn($this->getTable(), 'created_at')
            && Schema::hasColumn($this->getTable(), 'updated_at');
    }

    protected static function booted(): void
    {
        static::creating(function (self $gallery) {
            $gallery->slug ??= Str::slug($gallery->title);
        });

        static::updating(function (self $gallery) {
            $gallery->slug ??= Str::slug($gallery->title);
        });
    }
}
