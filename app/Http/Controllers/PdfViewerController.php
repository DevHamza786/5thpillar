<?php

namespace App\Http\Controllers;

use App\Support\PublicPath;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PdfViewerController extends Controller
{
    public function show(string $file): View
    {
        $relativePath = PublicPath::resolveAllowedAssetPdfPath($file);
        if ($relativePath === null) {
            throw new NotFoundHttpException('Invalid PDF path.');
        }

        $absolutePath = public_path($relativePath);
        if (! is_file($absolutePath)) {
            throw new NotFoundHttpException('PDF not found.');
        }

        $title = pathinfo($relativePath, PATHINFO_FILENAME);
        $title = str_replace(['-', '_'], ' ', $title);

        return view('pdf.viewer', [
            'title' => $title,
            'pdfUrl' => route('pdf-embed.show', ['file' => $relativePath]),
        ]);
    }
}
