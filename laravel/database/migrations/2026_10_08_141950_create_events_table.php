<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('events')) {
            Schema::create('events', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->string('location')->nullable();
                $table->date('event_date')->nullable();
                $table->string('event_time')->nullable();
                $table->string('image')->nullable();
                $table->string('status')->default('published');
                $table->timestamps();
            });

            return;
        }

        if (!Schema::hasColumn('events', 'status')) {
            Schema::table('events', function (Blueprint $table) {
                $table->string('status')->default('published')->after('image');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
