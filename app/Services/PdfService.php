<?php

namespace App\Services;

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfService
{
    protected $dompdf;

    public function __construct()
    {
        // Initialize dompdf
        $options = new Options();
        $options->set('defaultFont', 'Helvetica');
        $this->dompdf = new Dompdf($options);
    }

    public function generatePdf($html, $output = 'I')
    {
        // Load HTML content
        $this->dompdf->loadHtml($html);

        // (Optional) Set paper size and orientation
        $this->dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $this->dompdf->render();

        // Output the generated PDF (I for inline display, D for download)
        return $this->dompdf->stream('document.pdf', ['Attachment' => $output === 'D']);
    }
}
