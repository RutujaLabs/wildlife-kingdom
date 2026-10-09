<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('enquiries')) {
            Schema::create('enquiries', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email');
                $table->string('phone')->nullable();
                $table->string('subject')->nullable();
                $table->text('message');
                $table->string('status')->default('new');
                $table->timestamps();
            });

            return;
        }

        if (!Schema::hasColumn('enquiries', 'status')) {
            Schema::table('enquiries', function (Blueprint $table) {
                $table->string('status')->default('new')->after('message');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('enquiries');
    }
};
