<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\TicketDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AdminBookingController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:254'],
            'visit_date' => ['nullable', 'date_format:Y-m-d'],
            'payment_status' => ['nullable', 'in:pending,paid,failed,cancelled'],
            'booking_status' => ['nullable', 'in:pending,confirmed,failed,cancelled'],
        ]);

        $bookings = Booking::query()
            ->with('ticket')
            ->when($filters['q'] ?? null, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    if (ctype_digit($search)) {
                        $query->where('id', $search)
                            ->orWhere('booking_reference', 'like', '%'.$search.'%');
                    } else {
                        $query->where('booking_reference', 'like', '%'.$search.'%');
                    }

                    $query->orWhere('visitor_name', 'like', '%'.$search.'%')
                        ->orWhere('visitor_email', 'like', '%'.$search.'%');
                });
            })
            ->when($filters['visit_date'] ?? null, fn ($query, $date) => $query->whereDate('visit_date', $date))
            ->when($filters['payment_status'] ?? null, fn ($query, $status) => $query->where('payment_status', $status))
            ->when($filters['booking_status'] ?? null, fn ($query, $status) => $query->where('booking_status', $status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.bookings.index', compact('bookings', 'filters'));
    }

    public function show(Booking $booking, TicketDocument $ticketDocument)
    {
        $booking->load('ticket');
        $qrDataUri = $booking->verification_token
            ? $ticketDocument->qrDataUri($booking)
            : null;

        return view('admin.bookings.show', compact('booking', 'qrDataUri'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'booking_status' => ['required', 'in:confirmed,cancelled'],
        ]);

        DB::transaction(function () use ($booking, $validated) {
            $lockedBooking = Booking::query()->lockForUpdate()->findOrFail($booking->getKey());

            if ($validated['booking_status'] === 'confirmed') {
                if ($lockedBooking->payment_status !== 'paid' || $lockedBooking->booking_status === 'cancelled') {
                    throw ValidationException::withMessages([
                        'booking_status' => 'Only successfully paid bookings can be confirmed.',
                    ]);
                }

                $lockedBooking->booking_status = 'confirmed';
                $lockedBooking->save();

                return;
            }

            if (in_array($lockedBooking->booking_status, ['pending', 'confirmed'], true)) {
                $lockedBooking->booking_status = 'cancelled';
                $lockedBooking->save();
            }
        });

        return redirect()
            ->route('admin.bookings.show', $booking)
            ->with('success', 'Booking status updated.');
    }

    public function downloadTicket(Booking $booking, TicketDocument $ticketDocument)
    {
        return $ticketDocument->download($booking);
    }
}
