<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadFinancialDataRequest;
use App\Imports\MarketingSheetImport;
use App\Imports\MarketingWorkbookImport;
use App\Models\FinancialData;
use App\Models\FinancialDataUpload;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class FinancialDataController extends Controller
{
    /**
     * Display the financial data management page.
     */
    public function index()
    {
        $lastUpload = FinancialDataUpload::latest()->first();
        $history = FinancialDataUpload::latest()->take(10)->get();
        $totalDataRows = FinancialData::count();

        return view('admin.financial-data.index', compact('lastUpload', 'history', 'totalDataRows'));
    }

    /**
     * Handle the Excel/CSV file upload and import.
     */
    public function upload(UploadFinancialDataRequest $request)
    {
        try {
            $file = $request->file('file');
            $filename = $file->getClientOriginalName();
            $product = $request->validated('product');
            $ext = strtolower((string) pathinfo($filename, PATHINFO_EXTENSION));

            if ($ext === 'csv' && $product !== 'hajj') {
                return redirect()->back()->withErrors([
                    'file' => __('CSV import is only supported for Hajj data. Choose Hajj or upload an Excel file for Umrah.'),
                ]);
            }

            $defaults = $product === 'umrah'
                ? ['sheet' => 'Format', 'heading_row' => 4]
                : ['sheet' => 'Hajj', 'heading_row' => 1];

            $sheet = $defaults['sheet'];
            $headingRow = $defaults['heading_row'];

            // Store file temporarily
            $path = $file->storeAs('temp-imports', $filename);

            DB::beginTransaction();

            FinancialData::query()->where('product', $ext === 'csv' ? 'hajj' : $product)->delete();

            if ($ext === 'csv') {
                Excel::import(new MarketingSheetImport('hajj', 1), $path);
            } else {
                Excel::import(new MarketingWorkbookImport($product, $headingRow, $sheet), $path);
            }

            $totalRows = FinancialData::query()->where('product', $ext === 'csv' ? 'hajj' : $product)->count();

            // 4. Save upload history
            FinancialDataUpload::create([
                'filename'    => $filename,
                'total_rows'  => $totalRows,
                'uploaded_by' => auth()->user()->name ?? 'Admin',
            ]);

            DB::commit();

            // Clean up temporary file
            Storage::delete($path);

            return redirect()->back()->with('success', [
                'message'   => 'Financial data imported successfully',
                'filename'  => $filename,
                'rows'      => $totalRows,
                'timestamp' => now()->format('Y-m-d H:i:s'),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log error for debugging if needed
            \Log::error('Financial Data Import Error: ' . $e->getMessage());

            return redirect()->back()->withErrors(['file' => 'Import failed: ' . $e->getMessage()]);
        }
    }
}
