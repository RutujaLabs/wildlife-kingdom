<?php

namespace App\Services;

use App\Models\Booking;
use Dompdf\Dompdf;
use Dompdf\Options;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\SvgWriter;
use Illuminate\Http\Response;
use LogicException;

class TicketDocument
{
    public function qrDataUri(Booking $booking): string
    {
        if (! $booking->verification_token) {
            throw new LogicException('A verified booking is required to generate a ticket QR code.');
        }

        $verificationUrl = route('tickets.verify', $booking->verification_token);
        $qrCode = new QrCode(
            data: $verificationUrl,
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 240,
            margin: 8,
            foregroundColor: new Color(11, 47, 34),
            backgroundColor: new Color(255, 255, 255),
        );

        return (new SvgWriter)->write($qrCode, options: [
            SvgWriter::WRITER_OPTION_EXCLUDE_XML_DECLARATION => true,
        ])->getDataUri();
    }

    public function download(Booking $booking): Response
    {
        if ($booking->payment_status !== 'paid' || $booking->booking_status !== 'confirmed') {
            abort(404);
        }

        $options = new Options;
        $options->setDefaultFont('DejaVu Sans');
        $options->setIsRemoteEnabled(false);

        $logoPath = base_path('../assets/images/logo/wildlife-kingdom-header.jpg');
        if (! is_file($logoPath)) {
            throw new LogicException('The Wildlife Kingdom logo is required to generate a ticket PDF.');
        }

        $pdf = new Dompdf($options);
        $pdf->loadHtml(view('front.bookings.ticket', [
            'booking' => $booking->loadMissing('ticket'),
            'qrDataUri' => $this->qrDataUri($booking),
            'logoDataUri' => 'data:image/jpeg;base64,'.base64_encode(file_get_contents($logoPath)),
        ])->render());
        $pdf->setPaper('A4');
        $pdf->render();

        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="wildlife-kingdom-'.$booking->ticket_number.'.pdf"',
            'Cache-Control' => 'private, no-store, max-age=0',
        ]);
    }
}
