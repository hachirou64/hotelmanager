<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Reservation;

class PaymentPageTest extends TestCase
{
    /** @test */
    public function payment_page_renders_with_providers_and_summary()
    {
        $reservation = Reservation::first();
        if (! $reservation) {
            $this->markTestSkipped('No reservations in DB; skipping payment page render test.');
        }
        $response = $this->get(route('reservations.pay.form', $reservation));
        $response->assertStatus(200);
        $response->assertSee('Payer la réservation');
        $response->assertSee('MTN');
        $response->assertSee('Moov');
        $response->assertSee('Celtis');
        $response->assertSee((string)number_format($this->app->call(function() use ($reservation) {
            $room = $reservation->room->load('roomType');
            $prix = $room->roomType->prix_base ?? 0;
            $n = \Carbon\Carbon::parse($reservation->date_debut)->diffInDays(\Carbon\Carbon::parse($reservation->date_fin));
            return $prix * $n;
        }, []), 2));
    }
}
