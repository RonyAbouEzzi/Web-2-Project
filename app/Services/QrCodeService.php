<?php

namespace App\Services;

use App\Models\ServiceRequest;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

/**
 * QrCodeService
 * Generates a QR code image for a service request.
 * The QR encodes the public tracking URL.
 */
class QrCodeService
{
    public function generate(ServiceRequest $serviceRequest): string
    {
        $url  = route('citizen.track', $serviceRequest->reference_number);
        $dir  = 'qr_codes/' . $serviceRequest->id;
        $file = $dir . '/qr.svg';

        // Use SVG to avoid Imagick/GD dependencies required by PNG rendering.
        $svg = QrCode::format('svg')->size(300)->margin(1)->generate($url);

        Storage::disk('public')->put($file, $svg);

        return $file;
    }
}
