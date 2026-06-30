<?php

namespace App\Http\Controllers;

use App\Support\PublicPath;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PdfEmbedController extends Controller
{
    public function show(string $file): BinaryFileResponse
    {
        $relativePath = PublicPath::resolveAllowedAssetPdfPath($file);
        if ($relativePath === null) {
            throw new NotFoundHttpException('Invalid PDF path.');
        }

        $absolutePath = public_path($relativePath);
        if (! is_file($absolutePath)) {
            throw new NotFoundHttpException('PDF not found.');
        }

        $filename = basename($relativePath);

        return response()->file($absolutePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$filename.'"',
        ]);
    }
}
