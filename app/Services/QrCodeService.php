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
        $file = $dir . '/qr.png';

        $png = QrCode::format('png')->size(300)->generate($url);

        Storage::disk('public')->put($file, $png);

        return $file;
    }
}
