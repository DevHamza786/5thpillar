<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\WebsiteBackupService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BackupController extends Controller
{
    public function index(WebsiteBackupService $backups): View
    {
        return view('admin.backups.index', [
            'backups' => $backups->listBackups(),
        ]);
    }

    public function store(WebsiteBackupService $backups): RedirectResponse
    {
        try {
            $result = $backups->create();
        } catch (\Throwable $e) {
            return back()->withErrors(['backup' => $e->getMessage()]);
        }

        return back()->with('status', __('Backup created: :name (:size)', [
            'name' => $result['name'],
            'size' => $result['size_label'],
        ]));
    }

    public function download(string $filename, WebsiteBackupService $backups): BinaryFileResponse
    {
        return response()->download($backups->downloadPath($filename));
    }

    public function destroy(string $filename, WebsiteBackupService $backups): RedirectResponse
    {
        try {
            $backups->delete($filename);
        } catch (\Throwable $e) {
            return back()->withErrors(['backup' => $e->getMessage()]);
        }

        return back()->with('status', __('Backup deleted.'));
    }
}
