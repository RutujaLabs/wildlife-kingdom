<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Animal extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'scientific_name',
        'short_description',
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
        'kingdom',
        'animal_class',
        'animal_order',
        'family',
        'genus',
        'geographical_range',
        'native_countries',
        'distribution',
        'average_weight',
        'average_height_length',
        'speed',
        'behaviour',
        'reproduction',
        'ecological_role',
        'population_information',
        'major_threats',
        'conservation_efforts',
        'image_alt_text',
        'image_caption',
        'image_credit',
        'image_source',
        'meta_title',
        'meta_description',
        'canonical_url',
        'social_image',
        'noindex',
        'nofollow',
        'is_homepage_visible',
        'display_order',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'is_homepage_visible' => 'boolean',
            'noindex' => 'boolean',
            'nofollow' => 'boolean',
            'display_order' => 'integer',
        ];
    }

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

    public function images(): HasMany
    {
        return $this->hasMany(AnimalImage::class)->orderBy('display_order')->orderBy('id');
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(AnimalFaq::class)->orderBy('display_order')->orderBy('id');
    }

    public function relatedAnimals(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'animal_related_animal', 'animal_id', 'related_animal_id');
    }
}
