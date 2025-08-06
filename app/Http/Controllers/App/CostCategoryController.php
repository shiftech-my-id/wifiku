<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\CostCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CostCategoryController extends Controller
{
    public function index()
    {
        return inertia('app/cost-category/Index');
    }

    public function data(Request $request)
    {
        $orderBy = $request->get('order_by', 'date');
        $orderType = $request->get('order_type', 'desc');
        $filter = $request->get('filter', []);

        $q = CostCategory::query();

        if (!empty($filter['search'])) {
            $q->where(function ($q) use ($filter) {
                $q->where('name', 'like', '%' . $filter['search'] . '%');
            });
        }

        $q->orderBy($orderBy, $orderType);

        $items = $q->paginate($request->get('per_page', 10))->withQueryString();

        return response()->json($items);
    }

    public function duplicate($id)
    {
        $item = CostCategory::findOrFail($id);
        $item->id = null;
        return inertia('app/cost-category/Editor', [
            'data' => $item
        ]);
    }

    public function editor($id = 0)
    {
        $item = $id ? CostCategory::findOrFail($id) : new CostCategory();
        return inertia('app/cost-category/Editor', [
            'data' => $item,
        ]);
    }

    public function save(Request $request)
    {
        $item = $request->id ? CostCategory::findOrFail($request->id) : new CostCategory();

        $validated = $request->validate([
            'name' => [
                'required',
                'max:255',
                Rule::unique('cost_categories', 'name')
                    ->where(fn($query) => $query->where('user_id', auth()->id()))
                    ->ignore($item->id),
            ],
            'description' => 'nullable|max:1000',
        ]);

        $item->fill([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? '',
        ]);

        $item->save();

        $messageKey = $request->id ? 'cost-category-updated' : 'cost-category-created';

        return redirect()
            ->route('app.cost-category.index')
            ->with('success', __("messages.$messageKey", ['name' => $item->name]));
    }

    public function delete($id)
    {
        $item = CostCategory::findOrFail($id);
        $item->delete();

        return response()->json([
            'message' => __('messages.cost-category-deleted', ['name' => $item->name])
        ]);
    }

    /**
     * Mengekspor daftar kategori ke dalam format PDF atau Excel.
     */
    public function export(Request $request)
    {
        $items = CostCategory::orderBy('name', 'asc')->get();
        $title = 'Daftar Kategori Transaksi';
        $filename = $title . ' - ' . env('APP_NAME') . Carbon::now()->format('dmY_His');

        if ($request->get('format') == 'pdf') {
            $pdf = Pdf::loadView('export.cost-category-list-pdf', compact('items', 'title'));
            return $pdf->download($filename . '.pdf');
        }

        if ($request->get('format') == 'excel') {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Tambahkan header
            $sheet->setCellValue('A1', 'No');
            $sheet->setCellValue('B1', 'Nama Kategori');

            // Tambahkan data ke Excel
            $row = 2;
            foreach ($items as $num => $item) {
                $sheet->setCellValue('A' . $row, $num + 1);
                $sheet->setCellValue('B' . $row, $item->name);
                $row++;
            }

            // Kirim ke memori tanpa menyimpan file
            $response = new StreamedResponse(function () use ($spreadsheet) {
                $writer = new Xlsx($spreadsheet);
                $writer->save('php://output');
            });

            // Atur header response untuk download
            $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '.xlsx"');

            return $response;
        }

        return abort(400, 'Format tidak didukung');
    }
}
