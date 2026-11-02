<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_reference',
        'submission_token',
        'ticket_id',
        'visitor_name',
        'visitor_email',
        'visitor_phone',
        'visit_date',
        'ticket_quantity',
        'unit_price',
        'total_amount',
        'payment_status',
        'booking_status',
        'ticket_number',
        'verification_token',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'visit_date' => 'date',
            'ticket_quantity' => 'integer',
            'unit_price' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'paid_at' => 'datetime',
        ];
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }
}
