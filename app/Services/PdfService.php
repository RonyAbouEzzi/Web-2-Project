<?php

namespace App\Services;

use App\Models\ServiceRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

/**
 * PdfService
 * Auto-generates PDF documents for certificates, receipts, and approval letters.
 * Uses barryvdh/laravel-dompdf.
 */
class PdfService
{
    public function generateReceipt(ServiceRequest $req): string
    {
        return $this->generate('pdf.receipt', $req, 'receipt');
    }

    public function generateApprovalLetter(ServiceRequest $req): string
    {
        return $this->generate('pdf.approval', $req, 'approval');
    }

    public function generateCertificate(ServiceRequest $req): string
    {
        return $this->generate('pdf.certificate', $req, 'certificate');
    }

    private function generate(string $view, ServiceRequest $req, string $type): string
    {
        $req->load(['citizen', 'service', 'office.municipality']);

        // Pass as 'serviceRequest' to match all PDF Blade template variable names
        $pdf  = Pdf::loadView($view, ['serviceRequest' => $req]);
        $path = "pdfs/{$req->id}/{$type}-{$req->reference_number}.pdf";

        Storage::disk('private')->put($path, $pdf->output());

        return $path;
    }

    public function stream(string $path, string $filename)
    {
        return response()->streamDownload(function () use ($path) {
            echo Storage::disk('private')->get($path);
        }, $filename, ['Content-Type' => 'application/pdf']);
    }
}
