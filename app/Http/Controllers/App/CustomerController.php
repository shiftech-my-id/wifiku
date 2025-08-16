<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CustomerController extends Controller
{
    public function index()
    {
        return inertia('app/customer/Index', [
            'products' => Product::where('active', '=', true)->orderBy('name')->get()
        ]);
    }

    public function detail($id = 0)
    {
        return inertia('app/customer/Detail', [
            'data' => Customer::with(['product', 'creator', 'updater'])->findOrFail($id),
        ]);
    }

    public function data(Request $request)
    {
        $orderBy = $request->get('order_by', 'name');
        $orderType = $request->get('order_type', 'asc');
        $filter = $request->get('filter', []);

        $q = Customer::with(['product:id,name,price']);

        if (!empty($filter['search'])) {
            $q->where(function ($q) use ($filter) {
                $search = $filter['search'];

                // 1. Coba cocokkan pola lengkap "CST-angka"
                if (preg_match('/^CST-(\d+)$/i', $search, $matches)) {
                    $customerId = (int) $matches[1];
                    $q->where('id', $customerId);
                    return; // Hentikan eksekusi, karena sudah menemukan kecocokan
                }

                // 2. Jika bukan pola lengkap, cek apakah string adalah angka saja
                if (is_numeric($search)) {
                    $customerId = (int) $search;
                    $q->where('id', $customerId);
                    return; // Hentikan eksekusi
                }

                // 3. Jika bukan kedua pola di atas, lakukan pencarian teks normal
                $q->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                    $q->orWhere('id_card_number', 'like', '%' . $search . '%');
                    $q->orWhere('email', 'like', '%' . $search . '%');
                    $q->orWhere('phone', 'like', '%' . $search . '%');
                    $q->orWhere('wa', 'like', '%' . $search . '%');
                    $q->orWhere('address', 'like', '%' . $search . '%');
                    $q->orWhere('notes', 'like', '%' . $search . '%');
                });
            });
        }

        if (!empty($filter['status']) && (in_array($filter['status'], ['active', 'inactive']))) {
            $q->where('active', '=', $filter['status'] == 'active' ? true : false);
        }

        if (!empty($filter['product_id']) && $filter['product_id'] !== 'all') {
            $q->where('product_id', '=', $filter['product_id']);
        }

        $q->orderBy($orderBy, $orderType);

        $items = $q->paginate($request->get('per_page', 10))->withQueryString();

        return response()->json($items);
    }

    public function duplicate($id)
    {
        $item = Customer::findOrFail($id);
        $item->id = null;
        $item->customer_id = Customer::getNextCustomerId(Auth::user()->company_id);
        $item->created_at = null;
        return inertia('app/customer/Editor', [
            'data' => $item,
        ]);
    }

    public function editor($id = 0)
    {
        $item = $id ? Customer::findOrFail($id) : new Customer(['active' => true]);
        return inertia('app/customer/Editor', [
            'data' => $item,
            'products' => Product::where('active', '=', true)->orderBy('name')->get()
        ]);
    }

    public function save(Request $request)
    {
        $validated =  $request->validate([
            'name'              => 'required|string|max:255',
            'id_card_number'    => 'nullable|string|max:100',
            'email'             => 'nullable|email|max:255',
            'phone'             => 'nullable|string|max:50',
            'wa'                => 'nullable|string|max:50',
            'address'           => 'nullable|string|max:500',
            'active'            => 'required|boolean',
            'balance'           => 'nullable|numeric|min:0',
            'installation_date' => 'nullable|date',
            'lat_long'          => 'nullable|string|max:100',
            'notes'             => 'nullable|string',
            'product_id'        => 'nullable|exists:products,id',
        ]);

        $item = !$request->id ? new Customer() : Customer::findOrFail($request->post('id', 0));

        if (!$request->id) {
            $validated['customer_id'] = Customer::getNextCustomerId(Auth::user()->company_id);
        }

        // TODO: tambahkan ke log aktivasi layanan

        $item->fill($validated);
        $item->save();

        return redirect(route('app.customer.detail', ['id' => $item->id]))
            ->with('success', "Pelanggan $item->name telah disimpan.");
    }

    public function delete($id)
    {
        $item = Customer::findOrFail($id);
        $item->delete();

        return response()->json([
            'message' => "Pelanggan $item->name telah dihapus."
        ]);
    }

    /**
     * Mengekspor daftar pihak ke dalam format PDF atau Excel.
     */
    public function export(Request $request)
    {
        $items = Customer::orderBy('name', 'asc')->get();

        $title = 'Daftar Pelanggan';
        $filename = $title . ' - ' . env('APP_NAME') . Carbon::now()->format('dmY_His');

        if ($request->get('format') == 'pdf') {
            $pdf = Pdf::loadView('export.customer-list-pdf', compact('items', 'title'))
                ->setPaper('a4', 'landscape');
            return $pdf->download($filename . '.pdf');
        }

        if ($request->get('format') == 'excel') {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Tambahkan header
            $sheet->setCellValue('A1', 'No');
            $sheet->setCellValue('B1', 'Jenis');
            $sheet->setCellValue('C1', 'Nama');
            $sheet->setCellValue('D1', 'Telepon');
            $sheet->setCellValue('E1', 'Alamat');
            $sheet->setCellValue('F1', 'Status');
            $sheet->setCellValue('G1', 'Utang / Piutang (Rp)');
            $sheet->setCellValue('H1', 'Catatan');

            // Tambahkan data ke Excel
            $row = 2;
            foreach ($items as $num => $item) {
                $sheet->setCellValue('A' . $row, $num + 1);
                $sheet->setCellValue('B' . $row, $item->type);
                $sheet->setCellValue('C' . $row, $item->name);
                $sheet->setCellValue('D' . $row, $item->phone);
                $sheet->setCellValue('E' . $row, $item->address);
                $sheet->setCellValue('F' . $row, $item->active ? 'Aktif' : 'Tidak Aktif');
                $sheet->setCellValue('G' . $row, $item->balance);
                $sheet->setCellValue('H' . $row, $item->notes);
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
