<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class TicketBookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_ticket_page_only_lists_active_ticket_types(): void
    {
        Ticket::create(['ticket_type' => 'Adult', 'price' => 25, 'status' => 'active']);
        Ticket::create(['ticket_type' => 'Retired', 'price' => 10, 'status' => 'inactive']);

        $this->get(route('front.tickets.index'))
            ->assertOk()
            ->assertSee('Adult')
            ->assertDontSee('Retired');
    }

    public function test_admin_can_manage_ticket_types_without_deleting_booking_dependencies(): void
    {
        $this->actingAs(User::factory()->create(), 'web')
            ->get(route('admin.tickets.index'))
            ->assertOk()
            ->assertSee('Add Ticket');

        $this->get(route('admin.tickets.create'))->assertOk();
        $this->post(route('admin.tickets.store'), [
            'ticket_type' => 'Adult',
            'price' => '20.00',
            'description' => 'General admission.',
            'status' => 'active',
        ])->assertRedirect(route('admin.tickets.index'));

        $ticket = Ticket::firstOrFail();
        $this->get(route('admin.tickets.edit', $ticket))->assertOk();
        $this->put(route('admin.tickets.update', $ticket), [
            'ticket_type' => 'Adult updated',
            'price' => '22.50',
            'description' => 'Updated admission.',
            'status' => 'inactive',
        ])->assertRedirect(route('admin.tickets.index'));

        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'ticket_type' => 'Adult updated',
            'price' => '22.50',
            'status' => 'inactive',
        ]);

        $this->delete(route('admin.tickets.destroy', $ticket))
            ->assertRedirect(route('admin.tickets.index'));
        $this->assertDatabaseMissing('tickets', ['id' => $ticket->id]);
    }

    public function test_booking_uses_database_price_and_successful_test_payment_issues_ticket(): void
    {
        $ticket = Ticket::create(['ticket_type' => 'Family', 'price' => 27.50, 'status' => 'active']);
        $submissionToken = (string) Str::uuid();
        $this->withSession(['booking_form_tokens' => [$submissionToken => true]]);

        $this->post(route('bookings.store'), [
            'submission_token' => $submissionToken,
            'ticket_id' => $ticket->id,
            'visit_date' => now()->addDay()->toDateString(),
            'ticket_quantity' => 3,
            'visitor_name' => 'Visitor Example',
            'visitor_email' => 'visitor@example.test',
            'visitor_phone' => '+1 555 0100',
            'price' => 0.01,
        ])->assertRedirect();

        $booking = Booking::firstOrFail();
        $this->assertSame('27.50', $booking->unit_price);
        $this->assertSame('82.50', $booking->total_amount);
        $this->assertSame('pending', $booking->payment_status);

        $this->post(route('bookings.payment.process', $booking), ['test_outcome' => 'success'])
            ->assertRedirect(route('bookings.confirmation', $booking));

        $booking->refresh();
        $this->assertSame('paid', $booking->payment_status);
        $this->assertSame('confirmed', $booking->booking_status);
        $this->assertNotNull($booking->ticket_number);
        $this->assertNotNull($booking->verification_token);

        $this->get(route('bookings.ticket.download', $booking))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }

    public function test_failed_payment_never_issues_ticket_and_cannot_be_replayed_as_success(): void
    {
        $booking = $this->createVisitorBooking();

        $this->post(route('bookings.payment.process', $booking), ['test_outcome' => 'failure'])
            ->assertRedirect(route('bookings.confirmation', $booking));
        $this->post(route('bookings.payment.process', $booking), ['test_outcome' => 'success'])
            ->assertRedirect(route('bookings.confirmation', $booking));

        $booking->refresh();
        $this->assertSame('failed', $booking->payment_status);
        $this->assertSame('failed', $booking->booking_status);
        $this->assertNull($booking->ticket_number);
        $this->assertNull($booking->verification_token);
        $this->get(route('bookings.ticket.download', $booking))->assertNotFound();
    }

    public function test_repeated_booking_submission_with_the_same_form_token_creates_only_one_booking(): void
    {
        $ticket = Ticket::create(['ticket_type' => 'Adult', 'price' => 15, 'status' => 'active']);
        $submissionToken = (string) Str::uuid();
        $this->withSession(['booking_form_tokens' => [$submissionToken => true]]);
        $payload = [
            'submission_token' => $submissionToken,
            'ticket_id' => $ticket->id,
            'visit_date' => now()->addDay()->toDateString(),
            'ticket_quantity' => 1,
            'visitor_name' => 'Visitor Example',
            'visitor_email' => 'visitor@example.test',
            'visitor_phone' => '+1 555 0100',
        ];

        $this->post(route('bookings.store'), $payload)->assertRedirect();
        $firstReference = Booking::firstOrFail()->booking_reference;
        $this->post(route('bookings.store'), $payload)->assertRedirect();

        $this->assertDatabaseCount('bookings', 1);
        $this->assertDatabaseHas('bookings', [
            'submission_token' => $submissionToken,
            'booking_reference' => $firstReference,
        ]);
    }

    public function test_cancelled_booking_has_no_confirmed_ticket_and_private_booking_requires_its_session(): void
    {
        $booking = $this->createVisitorBooking();

        $this->post(route('bookings.payment.process', $booking), ['test_outcome' => 'cancel']);
        $booking->refresh();
        $this->assertSame('cancelled', $booking->payment_status);
        $this->assertNull($booking->ticket_number);

        $this->withSession(['booking_access' => []])
            ->get(route('bookings.confirmation', $booking))
            ->assertNotFound();
    }

    public function test_verification_does_not_expose_visitor_contact_details_and_ticket_deletion_is_blocked(): void
    {
        $booking = $this->createVisitorBooking();
        $this->post(route('bookings.payment.process', $booking), ['test_outcome' => 'success']);
        $booking->refresh();

        $this->get(route('tickets.verify', $booking->verification_token))
            ->assertOk()
            ->assertSee('Ticket verified')
            ->assertDontSee($booking->visitor_name)
            ->assertDontSee($booking->visitor_email)
            ->assertDontSee($booking->visitor_phone);

        $this->actingAs(User::factory()->create(), 'web')
            ->delete(route('admin.tickets.destroy', $booking->ticket))
            ->assertRedirect(route('admin.tickets.index'))
            ->assertSessionHas('error');
    }

    public function test_booking_administration_requires_authentication_and_supports_search_filters(): void
    {
        $booking = $this->createVisitorBooking();

        $this->get(route('admin.bookings.index'))->assertRedirect(route('admin.login'));

        $this->actingAs(User::factory()->create(), 'web')
            ->get(route('admin.bookings.index', [
                'q' => 'Visitor Example',
                'visit_date' => $booking->visit_date->toDateString(),
                'payment_status' => 'pending',
                'booking_status' => 'pending',
            ]))
            ->assertOk()
            ->assertSee($booking->booking_reference)
            ->assertSee($booking->visitor_email);
    }

    public function test_admin_can_view_qr_download_ticket_and_cancel_a_confirmed_booking(): void
    {
        $booking = $this->createVisitorBooking();
        $this->post(route('bookings.payment.process', $booking), ['test_outcome' => 'success']);
        $booking->refresh();
        $admin = User::factory()->create();

        $this->actingAs($admin, 'web')
            ->get(route('admin.bookings.show', $booking))
            ->assertOk()
            ->assertSee($booking->visitor_email)
            ->assertSee('Ticket verification QR code');

        $this->get(route('admin.bookings.ticket', $booking))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');

        $this->patch(route('admin.bookings.status', $booking), ['booking_status' => 'cancelled'])
            ->assertRedirect(route('admin.bookings.show', $booking));
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'booking_status' => 'cancelled',
            'payment_status' => 'paid',
        ]);
        $this->get(route('tickets.verify', $booking->verification_token))->assertNotFound();
        $this->get(route('admin.bookings.ticket', $booking))->assertNotFound();
    }

    private function createVisitorBooking(): Booking
    {
        $ticket = Ticket::create(['ticket_type' => 'Adult', 'price' => 15, 'status' => 'active']);
        $submissionToken = (string) Str::uuid();
        $this->withSession(['booking_form_tokens' => [$submissionToken => true]]);

        $this->post(route('bookings.store'), [
            'submission_token' => $submissionToken,
            'ticket_id' => $ticket->id,
            'visit_date' => now()->addDay()->toDateString(),
            'ticket_quantity' => 1,
            'visitor_name' => 'Visitor Example',
            'visitor_email' => 'visitor@example.test',
            'visitor_phone' => '+1 555 0100',
        ]);

        return Booking::firstOrFail();
    }
}
