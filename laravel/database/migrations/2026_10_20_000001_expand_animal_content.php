<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('animals', function (Blueprint $table) {
            $table->string('short_description')->nullable();
            $table->string('kingdom')->nullable();
            $table->string('animal_class')->nullable();
            $table->string('animal_order')->nullable();
            $table->string('family')->nullable();
            $table->string('genus')->nullable();
            $table->text('geographical_range')->nullable();
            $table->text('native_countries')->nullable();
            $table->text('distribution')->nullable();
            $table->string('average_weight')->nullable();
            $table->string('average_height_length')->nullable();
            $table->string('speed')->nullable();
            $table->text('behaviour')->nullable();
            $table->text('reproduction')->nullable();
            $table->text('ecological_role')->nullable();
            $table->text('population_information')->nullable();
            $table->text('major_threats')->nullable();
            $table->text('conservation_efforts')->nullable();
            $table->string('image_alt_text')->nullable();
            $table->text('image_caption')->nullable();
            $table->string('image_credit')->nullable();
            $table->string('image_source')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('canonical_url')->nullable();
            $table->text('social_image')->nullable();
            $table->boolean('noindex')->default(false);
            $table->boolean('nofollow')->default(false);
            $table->boolean('is_homepage_visible')->default(true);
            $table->unsignedInteger('display_order')->default(0);
        });

        Schema::create('animal_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('animal_id')->constrained()->cascadeOnDelete();
            $table->string('image');
            $table->string('alt_text')->nullable();
            $table->text('caption')->nullable();
            $table->string('credit')->nullable();
            $table->string('source')->nullable();
            $table->unsignedInteger('display_order')->default(0);
            $table->timestamps();
        });

        Schema::create('animal_faqs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('animal_id')->constrained()->cascadeOnDelete();
            $table->string('question');
            $table->text('answer');
            $table->unsignedInteger('display_order')->default(0);
            $table->timestamps();
        });

        Schema::create('animal_related_animal', function (Blueprint $table) {
            $table->foreignId('animal_id')->constrained('animals')->cascadeOnDelete();
            $table->foreignId('related_animal_id')->constrained('animals')->cascadeOnDelete();
            $table->primary(['animal_id', 'related_animal_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('animal_related_animal');
        Schema::dropIfExists('animal_faqs');
        Schema::dropIfExists('animal_images');

        Schema::table('animals', function (Blueprint $table) {
            $table->dropColumn([
                'short_description',
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
            ]);
        });
    }
};
