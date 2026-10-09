<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('tickets')) {
            Schema::create('tickets', function (Blueprint $table) {
                $table->id();
                $table->string('ticket_type');
                $table->decimal('price', 8, 2)->default(0);
                $table->text('description')->nullable();
                $table->string('status')->default('active');
                $table->timestamps();
            });

            return;
        }

        if (!Schema::hasColumn('tickets', 'status')) {
            Schema::table('tickets', function (Blueprint $table) {
                $table->string('status')->default('active')->after('description');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
