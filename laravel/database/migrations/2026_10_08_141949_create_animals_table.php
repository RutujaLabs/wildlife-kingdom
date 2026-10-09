<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('animals')) {
            Schema::create('animals', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('habitat_id')->nullable();
                $table->string('name');
                $table->string('scientific_name')->nullable();
                $table->string('slug')->unique();
                $table->string('category')->default('Other');
                $table->text('description')->nullable();
                $table->string('image')->nullable();
                $table->string('conservation_status')->default('Not assessed');
                $table->string('species')->nullable();
                $table->string('diet')->nullable();
                $table->string('lifespan')->nullable();
                $table->text('fun_fact')->nullable();
                $table->boolean('is_featured')->default(false);
                $table->string('habitat')->nullable();
                $table->string('status')->default('published');
                $table->timestamps();
            });

            return;
        }

        if (!Schema::hasColumn('animals', 'status')) {
            Schema::table('animals', function (Blueprint $table) {
                $table->string('status')->default('published')->after('image');
            });
        }

        if (!Schema::hasColumn('animals', 'updated_at')) {
            Schema::table('animals', function (Blueprint $table) {
                $table->timestamp('updated_at')->nullable()->after('created_at');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('animals');
    }
};
