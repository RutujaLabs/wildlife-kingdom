<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('habitats')) {
            Schema::create('habitats', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->text('short_description')->nullable();
                $table->string('image')->nullable();
                $table->string('status')->default('published');
                $table->timestamps();
            });

            return;
        }

        if (!Schema::hasColumn('habitats', 'short_description')) {
            Schema::table('habitats', function (Blueprint $table) {
                $table->text('short_description')->nullable()->after('description');
            });
        }

        if (!Schema::hasColumn('habitats', 'status')) {
            Schema::table('habitats', function (Blueprint $table) {
                $table->string('status')->default('published')->after('image');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('habitats');
    }
};
