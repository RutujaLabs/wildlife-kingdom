<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_reference')->unique();
            $table->uuid('submission_token')->unique();
            $table->foreignId('ticket_id')
                ->constrained('tickets')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->string('visitor_name', 150);
            $table->string('visitor_email', 254);
            $table->string('visitor_phone', 32);
            $table->date('visit_date');
            $table->unsignedSmallInteger('ticket_quantity');
            $table->decimal('unit_price', 8, 2);
            $table->decimal('total_amount', 10, 2);
            $table->string('payment_status', 20)->default('pending');
            $table->string('booking_status', 20)->default('pending');
            $table->string('ticket_number')->nullable()->unique();
            $table->uuid('verification_token')->nullable()->unique();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index('visitor_email');
            $table->index('visit_date');
            $table->index('payment_status');
            $table->index('booking_status');
            $table->index(['visit_date', 'booking_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
