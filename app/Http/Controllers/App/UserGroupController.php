<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UserGroupController extends Controller
{
    public function index()
    {
        return inertia('app/user-group/Index');
    }

    public function detail($id = 0)
    {
        // tambahkan jumlah client yang ditangani oleh user-group ini
        return inertia('app/user-group/Detail', [
            'data' => User::findOrFail($id),
        ]);
    }

    public function data(Request $request)
    {
        $orderBy = $request->get('order_by', 'name');
        $orderType = $request->get('order_type', 'asc');
        $filter = $request->get('filter', []);

        // tambahkan jumlah client yang ditangani oleh user-group ini
        $q = User::query();
        $q->orderBy($orderBy, $orderType);

        if (!empty($filter['role'] && $filter['role'] != 'all')) {
            $q->where('role', '=', $filter['role']);
        }

        if (!empty($filter['status']) && ($filter['status'] == 'active' || $filter['status'] == 'inactive')) {
            $q->where('active', '=', $filter['status'] == 'active' ? true : false);
        }

        if (!empty($filter['search'])) {
            $q->where(function ($query) use ($filter) {
                $query->where('name', 'like', '%' . $filter['search'] . '%')
                    ->orWhere('email', 'like', '%' . $filter['search'] . '%');
            });
        }

        $users = $q->paginate($request->get('per_page', 10))->withQueryString();

        return response()->json($users);
    }

    public function duplicate($id)
    {
        $user-group = User::findOrFail($id);
        $user-group->id = null;
        $user-group->created_at = null;
        return inertia('app/user-group/Editor', [
            'data' => $user-group
        ]);
    }

    public function editor($id = 0)
    {
        $user-group = $id ? User::findOrFail($id) : new User();

        if (!$id) {
            $user-group->active = true;
            $user-group->admin = true;
        } else if ($user-group == Auth::user-group()) {
            return redirect(route('app.user-group.index'))->with('warning', 'TIdak dapat mengubah akun anda sendiri.');
        }

        return inertia('app/user-group/Editor', [
            'data' => $user-group,
        ]);
    }

    public function save(Request $request)
    {
        $rules = [
            'name' => 'required|max:255',
            'password' => 'required|min:5|max:40',
            'role' => 'required',
        ];

        $user-group = null;
        $message = '';
        $fields = ['name', 'email', 'role', 'active'];
        $password = $request->get('password');
        if (!$request->id) {
            // email harus unik
            $rules['email'] = "required|alpha_num|max:255|unique:users,email,NULL,id";
            $request->validate($rules);
            $user-group = new User();
        } else {
            // email harus unik, exclude id
            $rules['email'] = "required|alpha_num|max:255|unique:users,email,{$request->id},id";
            if (empty($request->get('password'))) {
                // kalau password tidak diisi, skip validation dan jangan update password
                unset($rules['password']);
                unset($fields['password']);
            }
            $request->validate($rules);
            $user-group = User::findOrFail($request->id);
        }

        if (!empty($password)) {
            $user-group->password = Hash::make($password);
        }
        $user-group->fill($request->only($fields));
        $user-group->save();

        $message = "Pengguna {$user-group->email} telah " . ($request->id ? 'diperbarui' : 'ditambahkan') . '.';

        return redirect(route('app.user-group.index'))->with('success', $message);
    }

    public function delete($id)
    {
        $user-group = User::findOrFail($id);

        if ($user-group->id == Auth::user-group()->id) {
            return response()->json([
                'message' => 'Tidak dapat menghapus akun sendiri!'
            ], 409);
        }

        $user-group->delete();

        return response()->json([
            'message' => "Pengguna {$user-group->email} telah dihapus!"
        ]);
    }

    /**
     * Mengekspor daftar pengguna ke dalam format PDF atau Excel.
     */
    public function export(Request $request)
    {
        $items = User::orderBy('name', 'asc')->get();
        $title = 'Daftar Pengguna';
        $filename = $title . ' - ' . env('APP_NAME') . Carbon::now()->format('dmY_His');

        if ($request->get('format') == 'pdf') {
            $pdf = Pdf::loadView('export.user-group-list-pdf', compact('items', 'title'));
            return $pdf->download($filename . '.pdf');
        }

        if ($request->get('format') == 'excel') {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Tambahkan header
            $sheet->setCellValue('A1', 'No');
            $sheet->setCellValue('B1', 'Username');
            $sheet->setCellValue('C1', 'Nama Lengkap');
            $sheet->setCellValue('D1', 'Status');

            // Tambahkan data ke Excel
            $row = 2;
            foreach ($items as $num => $item) {
                $sheet->setCellValue('A' . $row, $num + 1);
                $sheet->setCellValue('B' . $row, $item->email);
                $sheet->setCellValue('C' . $row, $item->name);
                $sheet->setCellValue('D' . $row, $item->active ? 'Aktif' : 'Tidak Aktif');
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
