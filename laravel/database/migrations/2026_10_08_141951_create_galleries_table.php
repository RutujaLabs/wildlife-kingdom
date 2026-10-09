<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('galleries')) {
            Schema::create('galleries', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->string('image')->nullable();
                $table->string('category')->default('wildlife');
                $table->string('status')->default('published');
                $table->timestamps();
            });

            return;
        }

        if (!Schema::hasColumn('galleries', 'status')) {
            Schema::table('galleries', function (Blueprint $table) {
                $table->string('status')->default('published')->after('category');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('galleries');
    }
};
