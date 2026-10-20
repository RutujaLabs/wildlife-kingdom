<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('habitats', function (Blueprint $table) {
            $table->string('climate')->nullable();
            $table->string('location')->nullable();
            $table->text('geographical_range')->nullable();
            $table->string('image_alt_text')->nullable();
            $table->text('image_caption')->nullable();
            $table->string('image_credit')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('canonical_url')->nullable();
            $table->boolean('noindex')->default(false);
            $table->boolean('is_homepage_visible')->default(true);
            $table->unsignedInteger('display_order')->default(0);
        });

        Schema::table('events', function (Blueprint $table) {
            $table->string('image_alt_text')->nullable();
            $table->text('image_caption')->nullable();
            $table->string('image_credit')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('canonical_url')->nullable();
            $table->boolean('noindex')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_homepage_visible')->default(true);
            $table->unsignedInteger('display_order')->default(0);
        });

        Schema::table('galleries', function (Blueprint $table) {
            $table->string('image_alt_text')->nullable();
            $table->text('image_caption')->nullable();
            $table->string('image_credit')->nullable();
            $table->string('image_source')->nullable();
            $table->boolean('is_homepage_visible')->default(true);
            $table->unsignedInteger('display_order')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            $table->dropColumn([
                'image_alt_text',
                'image_caption',
                'image_credit',
                'image_source',
                'is_homepage_visible',
                'display_order',
            ]);
        });

        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn([
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
            ]);
        });

        Schema::table('habitats', function (Blueprint $table) {
            $table->dropColumn([
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
            ]);
        });
    }
};
