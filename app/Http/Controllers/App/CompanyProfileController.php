<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class CompanyProfileController
 *
 * Controller ini bertanggung jawab untuk mengelola profil perusahaan,
 * termasuk menampilkan dan memperbarui informasi seperti nama, telepon, dan alamat perusahaan.
 * Akses ke controller ini dibatasi hanya untuk peran admin.
 */
class CompanyProfileController extends Controller
{
    /**
     * Menampilkan form profil perusahaan.
     *
     * @return \Inertia\Response
     */
    public function edit()
    {
        $company = Company::findOrFail(Auth::user()->company_id);
        return inertia('app/company-profile/Edit', [
            'company' => $company
        ]);
    }

    /**
     * Memperbarui informasi profil perusahaan.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request): \Illuminate\Http\RedirectResponse
    {
        $company = Company::findOrFail(Auth::user()->company_id);

        $rules = [
            'name' => 'required|string|min:2|max:100',
            'owner_name' => 'required|string|min:2|max:100',
            // Regex untuk nomor telepon bisa sangat kompleks. Pastikan regex ini sesuai kebutuhan.
            // Jika memungkinkan, gunakan library validasi telepon atau validasi yang lebih sederhana.
            'phone' => 'nullable|string|regex:/^(\+?\d{1,4})?[\s.-]?\(?\d{1,4}\)?[\s.-]?\d{1,4}[\s.-]?\d{1,9}$/|max:40',
            'address' => 'nullable|string|max:1000', // Tambahkan 'nullable' dan 'string'
        ];

        $company->fill($request->validate($rules));
        $company->save();

        Auth::user()->setLastActivity('Memperbarui profil perusahaan');

        // Mengembalikan respons dengan pesan sukses
        return back()->with('success', 'Profil perusahaan berhasil diperbarui.');
    }
}
