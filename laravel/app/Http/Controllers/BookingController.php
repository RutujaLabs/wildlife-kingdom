<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Ticket;
use App\Services\TicketDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    public function tickets()
    {
        $tickets = Ticket::query()
            ->where('status', 'active')
            ->orderBy('price')
            ->get();

        return view('front.tickets.index', compact('tickets'));
    }

    public function create(Ticket $ticket)
    {
        abort_unless($ticket->status === 'active', 404);

        $tickets = Ticket::query()
            ->where('status', 'active')
            ->orderBy('price')
            ->get();
        $submissionToken = (string) Str::uuid();
        $formTokens = session()->get('booking_form_tokens', []);
        $formTokens[$submissionToken] = true;
        session()->put('booking_form_tokens', array_slice($formTokens, -10, null, true));

        return view('front.bookings.create', compact('ticket', 'tickets', 'submissionToken'));
    }

    public function store(Request $request)
    {
        $tokenData = $request->validate([
            'submission_token' => ['required', 'uuid'],
        ]);
        $submissionToken = $tokenData['submission_token'];
        $formTokens = session()->get('booking_form_tokens', []);

        if (! is_array($formTokens) || ! isset($formTokens[$submissionToken])) {
            throw ValidationException::withMessages([
                'submission_token' => 'This booking form has expired. Please start again.',
            ]);
        }

        $existingBooking = Booking::query()
            ->where('submission_token', $submissionToken)
            ->first();

        if ($existingBooking) {
            $this->grantVisitorAccess($existingBooking);

            return $this->bookingRedirect($existingBooking);
        }

        $validated = $request->validate([
            'ticket_id' => ['required', 'integer', 'exists:tickets,id'],
            'visit_date' => ['required', 'date', 'after_or_equal:today'],
            'ticket_quantity' => ['required', 'integer', 'min:1', 'max:20'],
            'visitor_name' => ['required', 'string', 'max:150'],
            'visitor_email' => ['required', 'email', 'max:254'],
            'visitor_phone' => ['required', 'string', 'max:32'],
        ]);

        $booking = DB::transaction(function () use ($validated, $submissionToken) {
            $ticket = Ticket::query()
                ->whereKey($validated['ticket_id'])
                ->lockForUpdate()
                ->first();

            if (! $ticket || $ticket->status !== 'active') {
                throw ValidationException::withMessages([
                    'ticket_id' => 'This ticket type is no longer available.',
                ]);
            }

            $unitPriceCents = (int) round((float) $ticket->price * 100);
            $totalCents = $unitPriceCents * $validated['ticket_quantity'];

            return Booking::query()->firstOrCreate(
                ['submission_token' => $submissionToken],
                [
                    'booking_reference' => 'WK-'.Str::upper(Str::random(12)),
                    'ticket_id' => $ticket->id,
                    'visitor_name' => $validated['visitor_name'],
                    'visitor_email' => $validated['visitor_email'],
                    'visitor_phone' => $validated['visitor_phone'],
                    'visit_date' => $validated['visit_date'],
                    'ticket_quantity' => $validated['ticket_quantity'],
                    'unit_price' => number_format($unitPriceCents / 100, 2, '.', ''),
                    'total_amount' => number_format($totalCents / 100, 2, '.', ''),
                    'payment_status' => 'pending',
                    'booking_status' => 'pending',
                ]
            );
        });

        $this->grantVisitorAccess($booking);

        return $this->bookingRedirect($booking);
    }

    public function payment(Booking $booking)
    {
        $this->authorizeVisitor($booking);

        if ($booking->payment_status !== 'pending') {
            return redirect()->route('bookings.confirmation', $booking);
        }

        return view('front.bookings.payment', compact('booking'));
    }

    public function processPayment(Request $request, Booking $booking)
    {
        $this->authorizeVisitor($booking);

        $validated = $request->validate([
            'test_outcome' => ['required', 'in:success,failure,cancel'],
        ]);

        DB::transaction(function () use ($booking, $validated) {
            $lockedBooking = Booking::query()->lockForUpdate()->findOrFail($booking->getKey());

            if ($lockedBooking->payment_status !== 'pending' || $lockedBooking->booking_status !== 'pending') {
                return;
            }

            if ($validated['test_outcome'] === 'success') {
                $lockedBooking->forceFill([
                    'payment_status' => 'paid',
                    'booking_status' => 'confirmed',
                    'ticket_number' => 'WK-T-'.Str::upper(Str::random(12)),
                    'verification_token' => (string) Str::uuid(),
                    'paid_at' => now(),
                ])->save();

                return;
            }

            $cancelled = $validated['test_outcome'] === 'cancel';
            $lockedBooking->forceFill([
                'payment_status' => $cancelled ? 'cancelled' : 'failed',
                'booking_status' => $cancelled ? 'cancelled' : 'failed',
            ])->save();
        });

        return redirect()->route('bookings.confirmation', $booking);
    }

    public function confirmation(Booking $booking)
    {
        $this->authorizeVisitor($booking);
        $booking->load('ticket');

        return view('front.bookings.confirmation', compact('booking'));
    }

    public function downloadTicket(Booking $booking, TicketDocument $ticketDocument)
    {
        $this->authorizeVisitor($booking);

        return $ticketDocument->download($booking);
    }

    public function verify(string $verificationToken)
    {
        $booking = Booking::query()
            ->with('ticket')
            ->where('verification_token', $verificationToken)
            ->first();

        $valid = $booking
            && $booking->payment_status === 'paid'
            && $booking->booking_status === 'confirmed';

        return response()->view('front.bookings.verify', [
            'valid' => $valid,
            'booking' => $valid ? $booking : null,
        ], $valid ? 200 : 404);
    }

    private function authorizeVisitor(Booking $booking): void
    {
        $access = session()->get('booking_access', []);

        abort_unless(is_array($access) && isset($access[$booking->getKey()]), 404);
    }

    private function grantVisitorAccess(Booking $booking): void
    {
        $access = session()->get('booking_access', []);
        $access[$booking->getKey()] = true;
        session()->put('booking_access', $access);
    }

    private function bookingRedirect(Booking $booking)
    {
        return $booking->payment_status === 'pending'
            ? redirect()->route('bookings.payment', $booking)
            : redirect()->route('bookings.confirmation', $booking);
    }
}
