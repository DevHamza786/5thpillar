<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadFinancialDataRequest;
use App\Imports\MarketingSheetImport;
use App\Imports\MarketingWorkbookImport;
use App\Models\FinancialData;
use App\Models\FinancialDataUpload;
use Illuminate\Http\Request;
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
        $hajjDataRows = FinancialData::query()->where('product', 'hajj')->count();
        $umrahDataRows = FinancialData::query()->where('product', 'umrah')->count();

        return view('admin.financial-data.index', compact(
            'lastUpload',
            'history',
            'totalDataRows',
            'hajjDataRows',
            'umrahDataRows',
        ));
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

    /**
     * Export planner financial data as CSV for the selected product.
     */
    public function export(Request $request)
    {
        $product = $request->validate([
            'product' => 'required|in:hajj,umrah',
        ])['product'];

        $rows = FinancialData::query()
            ->where('product', $product)
            ->orderBy('age')
            ->orderBy('term')
            ->orderBy('growth_rate')
            ->get();

        $filename = $product.'-planner-data-'.now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, [
                'product',
                'age',
                'term',
                'annual_contribution',
                'growth_rate',
                'takaful_benefit',
                'year_five',
                'year_seven',
                'year_ten',
                'year_fifteen',
                'year_twenty',
                'year_twenty_five',
            ]);

            foreach ($rows as $row) {
                fputcsv($out, [
                    $row->product,
                    $row->age,
                    $row->term,
                    $row->annual_contribution,
                    $row->growth_rate,
                    $row->takaful_benefit,
                    $row->year_five,
                    $row->year_seven,
                    $row->year_ten,
                    $row->year_fifteen,
                    $row->year_twenty,
                    $row->year_twenty_five,
                ]);
            }

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
