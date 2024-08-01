<?php

namespace App\Http\Controllers\masters;

use App\Http\Controllers\Controller;
use App\Services\PdfService;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    protected $pdfService;

    public function __construct(PdfService $pdfService)
    {
        $this->pdfService = $pdfService;
    }

    public function generatePdf(Request $request)
    {
        $html = '<h1>Hello World</h1>';
        $output = $request->query('view', 'I');
        return $this->pdfService->generatePdf($html, $output);
    }
}
