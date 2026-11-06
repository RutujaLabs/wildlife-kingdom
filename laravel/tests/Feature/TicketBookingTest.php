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

    public function test_booking_stays_pending_until_admin_verifies_the_submitted_payment_reference(): void
    {
        $ticket = Ticket::create(['ticket_type' => 'Adult', 'price' => 2.00, 'status' => 'active']);
        $submissionToken = (string) Str::uuid();
        $this->withSession(['booking_form_tokens' => [
            $submissionToken => now()->addMinutes((int) config('session.lifetime'))->timestamp,
        ]]);

        $response = $this->postJson(route('bookings.store'), [
            'submission_token' => $submissionToken,
            'ticket_id' => $ticket->id,
            'visit_date' => now()->addDay()->toDateString(),
            'ticket_quantity' => 2,
            'visitor_name' => 'Visitor Example',
            'visitor_email' => 'visitor@example.test',
            'visitor_phone' => '+1 555 0100',
        ]);

        $booking = Booking::query()->latest('id')->firstOrFail();
        $response->assertCreated()->assertJsonPath('paymentUrl', route('bookings.payment.modal', $booking));
        $this->get(route('bookings.payment.modal', $booking))
            ->assertOk()
            ->assertSee('Amount to pay: ₹4.00')
            ->assertSee('UTR / transaction ID');
        $this->assertSame('2.00', $booking->unit_price);
        $this->assertSame('4.00', $booking->total_amount);
        $this->assertSame('pending', $booking->payment_status);

        $this->postJson(route('bookings.payment.process', $booking), ['payment_reference' => 'UTR123456'])
            ->assertOk()
            ->assertJsonPath('confirmationUrl', route('bookings.confirmation.modal', $booking));

        $booking->refresh();
        $this->assertSame('UTR123456', $booking->payment_reference);
        $this->assertSame('pending', $booking->payment_status);
        $this->assertSame('pending', $booking->booking_status);
        $this->assertNull($booking->ticket_number);
        $this->assertNull($booking->verification_token);
        $this->get(route('bookings.confirmation.modal', $booking))
            ->assertOk()
            ->assertSee('Payment verification pending')
            ->assertSee('not confirmed');

        $this->actingAs(User::factory()->create(), 'web')
            ->post(route('admin.bookings.verify-payment', $booking))
            ->assertRedirect(route('admin.bookings.show', $booking));

        $booking->refresh();
        $this->assertSame('paid', $booking->payment_status);
        $this->assertSame('confirmed', $booking->booking_status);
        $this->assertNotNull($booking->ticket_number);
        $this->assertNotNull($booking->verification_token);
        $this->get(route('bookings.confirmation', $booking))
            ->assertOk()
            ->assertSee('Your booking is confirmed')
            ->assertSee('Download PDF ticket');
        $this->get(route('bookings.confirmation.modal', $booking))
            ->assertOk()
            ->assertSee('Unique ticket verification QR code');

        $this->get(route('bookings.ticket.download', $booking))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }

    public function test_booking_modal_uses_the_selected_ticket_and_inr_price(): void
    {
        $tickets = [
            Ticket::create(['ticket_type' => 'Adult', 'price' => 2, 'status' => 'active']),
            Ticket::create(['ticket_type' => 'Child', 'price' => 1, 'status' => 'active']),
            Ticket::create(['ticket_type' => 'Family Pass', 'price' => 3, 'status' => 'active']),
        ];

        foreach ($tickets as $ticket) {
            $this->get(route('bookings.modal', $ticket))
                ->assertOk()
                ->assertSee('Book '.$ticket->ticket_type.' tickets')
                ->assertSee(number_format($ticket->price, 2, '.', ''))
                ->assertDontSee('$');
        }
    }

    public function test_booking_and_payment_fields_are_validated_and_transaction_references_cannot_be_reused(): void
    {
        $ticket = Ticket::create(['ticket_type' => 'Adult', 'price' => 2, 'status' => 'active']);
        $submissionToken = (string) Str::uuid();
        $this->withSession(['booking_form_tokens' => [
            $submissionToken => now()->addMinutes((int) config('session.lifetime'))->timestamp,
        ]]);

        $this->postJson(route('bookings.store'), [
            'submission_token' => $submissionToken,
            'ticket_id' => $ticket->id,
            'visit_date' => now()->subDay()->toDateString(),
            'ticket_quantity' => 21,
            'visitor_name' => '',
            'visitor_email' => 'invalid',
            'visitor_phone' => '',
        ])->assertUnprocessable()
            ->assertJsonValidationErrors([
                'visit_date',
                'ticket_quantity',
                'visitor_name',
                'visitor_email',
                'visitor_phone',
            ]);

        $firstBooking = $this->createVisitorBooking();
        $secondBooking = $this->createVisitorBooking();
        $this->postJson(route('bookings.payment.process', $secondBooking), [
            'payment_reference' => $firstBooking->payment_reference,
        ])->assertUnprocessable()
            ->assertJsonValidationErrors('payment_reference');

        $this->postJson(route('bookings.payment.process', $secondBooking), [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('payment_reference');
    }

    public function test_booking_form_tokens_remain_valid_across_more_than_ten_open_forms(): void
    {
        $ticket = Ticket::create(['ticket_type' => 'Adult', 'price' => 15, 'status' => 'active']);
        $firstForm = $this->get(route('bookings.modal', $ticket))->assertOk();
        $firstToken = $firstForm->viewData('submissionToken');

        for ($i = 0; $i < 12; $i++) {
            $this->get(route('bookings.modal', $ticket))->assertOk();
        }

        $response = $this->post(route('bookings.store'), [
            'submission_token' => $firstToken,
            'ticket_id' => $ticket->id,
            'visit_date' => now()->addDay()->toDateString(),
            'ticket_quantity' => 1,
            'visitor_name' => 'Visitor Example',
            'visitor_email' => 'visitor@example.test',
            'visitor_phone' => '+1 555 0100',
        ]);

        $booking = Booking::firstOrFail();
        $response->assertRedirect(route('bookings.payment', $booking));
        $this->assertDatabaseCount('bookings', 1);
    }

    public function test_expired_form_token_is_rejected_but_existing_booking_submission_remains_idempotent(): void
    {
        $ticket = Ticket::create(['ticket_type' => 'Adult', 'price' => 15, 'status' => 'active']);
        $submissionToken = (string) Str::uuid();
        $payload = [
            'submission_token' => $submissionToken,
            'ticket_id' => $ticket->id,
            'visit_date' => now()->addDay()->toDateString(),
            'ticket_quantity' => 1,
            'visitor_name' => 'Visitor Example',
            'visitor_email' => 'visitor@example.test',
            'visitor_phone' => '+1 555 0100',
        ];
        $this->withSession(['booking_form_tokens' => [
            $submissionToken => now()->addMinutes((int) config('session.lifetime'))->timestamp,
        ]]);
        $this->post(route('bookings.store'), $payload)->assertRedirect();
        $booking = Booking::firstOrFail();

        $this->travel((int) config('session.lifetime') + 1)->minutes();
        $this->withSession(['booking_form_tokens' => []]);
        $this->post(route('bookings.store'), $payload)
            ->assertRedirect(route('bookings.payment', $booking));
        $this->assertDatabaseCount('bookings', 1);

        $newToken = (string) Str::uuid();
        $this->withSession(['booking_form_tokens' => [
            $newToken => now()->subSecond()->timestamp,
        ]]);
        $this->post(route('bookings.store'), array_merge($payload, ['submission_token' => $newToken]))
            ->assertSessionHasErrors([
                'submission_token' => 'This booking form has expired. Please start again.',
            ]);
    }

    public function test_submitting_a_transaction_reference_does_not_fake_payment_success(): void
    {
        $booking = $this->createVisitorBooking();

        $this->assertSame('pending', $booking->payment_status);
        $this->assertSame('pending', $booking->booking_status);
        $this->assertNotNull($booking->payment_reference);
        $this->assertNull($booking->ticket_number);
        $this->assertNull($booking->verification_token);
        $this->get(route('bookings.confirmation', $booking))
            ->assertOk()
            ->assertSee('Payment verification pending')
            ->assertSee('has not yet been verified');
        $this->get(route('bookings.ticket.download', $booking))->assertNotFound();
    }

    public function test_repeated_booking_submission_with_the_same_form_token_creates_only_one_booking(): void
    {
        $ticket = Ticket::create(['ticket_type' => 'Adult', 'price' => 15, 'status' => 'active']);
        $submissionToken = (string) Str::uuid();
        $this->withSession(['booking_form_tokens' => [
            $submissionToken => now()->addMinutes((int) config('session.lifetime'))->timestamp,
        ]]);
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

    public function test_pending_booking_has_no_ticket_and_private_booking_requires_its_session(): void
    {
        $booking = $this->createVisitorBooking();

        $this->assertSame('pending', $booking->payment_status);
        $this->assertNull($booking->ticket_number);
        $this->get(route('bookings.confirmation', $booking))
            ->assertOk()
            ->assertSee('has not yet been verified');
        $this->get(route('bookings.ticket.download', $booking))->assertNotFound();

        $this->withSession(['booking_access' => []])
            ->get(route('bookings.confirmation', $booking))
            ->assertNotFound();
    }

    public function test_verification_does_not_expose_visitor_contact_details_and_ticket_deletion_is_blocked(): void
    {
        $booking = $this->createVisitorBooking();
        $this->actingAs(User::factory()->create(), 'web')
            ->post(route('admin.bookings.verify-payment', $booking));
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
        $this->actingAs(User::factory()->create(), 'web')
            ->post(route('admin.bookings.verify-payment', $booking));
        $booking->refresh();
        $admin = User::factory()->create();

        $this->actingAs($admin, 'web')
            ->get(route('admin.bookings.show', $booking))
            ->assertOk()
            ->assertSee($booking->visitor_email)
            ->assertSee($booking->visitor_phone)
            ->assertSee($booking->payment_reference)
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
        $this->withSession(['booking_form_tokens' => [
            $submissionToken => now()->addMinutes((int) config('session.lifetime'))->timestamp,
        ]]);

        $this->post(route('bookings.store'), [
            'submission_token' => $submissionToken,
            'ticket_id' => $ticket->id,
            'visit_date' => now()->addDay()->toDateString(),
            'ticket_quantity' => 1,
            'visitor_name' => 'Visitor Example',
            'visitor_email' => 'visitor@example.test',
            'visitor_phone' => '+1 555 0100',
        ]);

        $booking = Booking::query()->latest('id')->firstOrFail();
        $this->post(route('bookings.payment.process', $booking), [
            'payment_reference' => 'UTR'.Str::upper(Str::random(12)),
        ]);

        return $booking->fresh();
    }
}
