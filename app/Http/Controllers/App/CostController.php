<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Cost;
use App\Models\CostCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CostController extends Controller
{

    public function index()
    {
        return inertia('app/cost/Index', [
            'categories' => CostCategory::query()->orderBy('name', 'asc')->get()
        ]);
    }

    public function detail($id = 0)
    {
        return inertia('app/cost/Detail', [
            'data' => Cost::with(['category', 'creator', 'updater'])->findOrFail($id),
        ]);
    }

    public function data(Request $request)
    {
        $orderBy = $request->get('order_by', 'id');
        $orderType = $request->get('order_type', 'desc');
        $filter = $request->get('filter', []);

        $q = Cost::with(['category']);

        if (!empty($filter['search'])) {
            $q->where(function ($q) use ($filter) {
                $q->orWhere('description', 'like', '%' . $filter['search'] . '%');
                $q->orWhere('notes', 'like', '%' . $filter['search'] . '%');
            });
        }

        if (!empty($filter['year']) && $filter['year'] !== 'all') {
            $q->whereYear('datetime', $filter['year']);

            if (!empty($filter['month']) && $filter['month'] !== 'all') {
                $q->whereMonth('datetime', $filter['month']);
            }
        }

        if (!empty($filter['category_id']) && $filter['category_id'] !== 'all') {
            $q->where('category_id', '=', $filter['category_id']);
        }

        $q->orderBy($orderBy, $orderType);

        $items = $q->paginate($request->get('per_page', 10))->withQueryString();

        return response()->json($items);
    }

    public function editor($id = 0)
    {
        $item = $id ? Cost::findOrFail($id) : new Cost(['datetime' => Carbon::now()]);
        $item->amount = abs($item->amount);
        return inertia('app/cost/Editor', [
            'data' => $item,
            'categories' => CostCategory::query()->orderBy('name', 'asc')->get()
        ]);
    }

    public function save(Request $request)
    {
        $validated = $request->validate([
            'id'          => 'nullable|exists:costs,id', // tambahkan ini
            'category_id' => 'required|exists:cost_categories,id',
            'datetime'    => 'required|date',
            'description' => 'required|string|max:100',
            'amount'      => 'required|numeric|min:0.01',
            'notes'       => 'nullable|string|max:255',
        ]);

        $validated['notes'] = $validated['notes'] ?? '';

        try {
            $item = $request->id ? Cost::findOrFail($request->id) : new Cost();
            $item->fill($validated);
            $item->save();
            return redirect(route('app.cost.index'))
                ->with('success', "Biaya Operasional telah disimpan.");
        } catch (\Throwable $e) {
            report($e);
            return back()->withErrors(['error' => 'Gagal menyimpan Biaya Operasional.']);
        }
    }

    public function delete($id)
    {
        $item = Cost::findOrFail($id);
        try {
            $item->delete();

            return response()->json([
                'message' => "Biaya Operasional telah dihapus."
            ]);
        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'message' => 'Gagal menghapus Biaya Operasional.'
            ], 500);
        }
    }

    /**
     * FIXME: Saat ini fungsi belum digunakan
     * Mengekspor daftar kategori ke dalam format PDF atau Excel.
     */
    // public function export(Request $request)
    // {
    //     $items = Cost::orderBy('datetime', 'desc')->get();
    //     $title = 'Daftar Transaksi';
    //     $filename = $title . ' - ' . env('APP_NAME') . Carbon::now()->format('dmY_His');

    //     if ($request->get('format') == 'pdf') {
    //         $pdf = Pdf::loadView('export.cost-list-pdf', compact('items', 'title'));
    //         return $pdf->download($filename . '.pdf');
    //     }

    //     if ($request->get('format') == 'excel') {
    //         $spreadsheet = new Spreadsheet();
    //         $sheet = $spreadsheet->getActiveSheet();

    //         // Tambahkan header
    //         $sheet->setCellValue('A1', 'ID');
    //         $sheet->setCellValue('B1', 'Waktu');
    //         $sheet->setCellValue('C1', 'Pihak');
    //         $sheet->setCellValue('D1', 'Jenis');
    //         $sheet->setCellValue('E1', 'Kategori');
    //         $sheet->setCellValue('F1', 'Jumlah');
    //         $sheet->setCellValue('G1', 'Catatan');

    //         // Tambahkan data ke Excel
    //         $row = 2;
    //         foreach ($items as $item) {
    //             $sheet->setCellValue('A' . $row, $item->id);
    //             $sheet->setCellValue('B' . $row, $item->datetime);
    //             $sheet->setCellValue('C' . $row, $item->party?->name);
    //             $sheet->setCellValue('D' . $row, Cost::Types[$item->type]);
    //             $sheet->setCellValue('E' . $row, $item->category?->name);
    //             $sheet->setCellValue('F' . $row, $item->amount);
    //             $sheet->setCellValue('G' . $row, $item->notes);
    //             $row++;
    //         }

    //         // Kirim ke memori tanpa menyimpan file
    //         $response = new StreamedResponse(function () use ($spreadsheet) {
    //             $writer = new Xlsx($spreadsheet);
    //             $writer->save('php://output');
    //         });

    //         // Atur header response untuk download
    //         $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    //         $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '.xlsx"');

    //         return $response;
    //     }

    //     return abort(400, 'Format tidak didukung');
    // }
}
