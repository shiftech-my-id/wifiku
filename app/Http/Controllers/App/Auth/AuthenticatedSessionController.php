<?php

namespace App\Http\Controllers\App\Auth;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthenticatedSessionController extends Controller
{
    /**
     * Menampilkan form login atau memproses permintaan login.
     *
     * @param Request $request
     * @return \Inertia\Response|\Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        if ($request->getMethod() === 'GET') {
            return inertia('app/auth/Login');
        }

        $validator = Validator::make($request->all(), [
            'company_code'  => 'required|string',
            'username'      => 'required|string',
            'password'      => 'required|string',
        ], [
            'company_code.required' => 'Kode Perusahaan harus diisi.',
            'username.required'     => 'Username harus diisi.',
            'password.required'     => 'Kata sandi harus diisi.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $company = Company::where('code', $request->company_code)->first();

        if (!$company) {
            $validator->errors()->add('username', 'Username atau kata sandi salah untuk perusahaan ini!');
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
            'company_id' => $company->id,
        ];

        if (!Auth::attempt($credentials, $request->has('remember'))) {
            $validator->errors()->add('username', 'Username atau kata sandi salah untuk perusahaan ini!');
            return redirect()->back()->withInput()->withErrors($validator);
        }


        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->active) {
            $validator->errors()->add('username', 'Akun Anda tidak aktif. Silakan hubungi penyedia layanan!');
            Auth::logout(); // Logout user yang tidak aktif
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $user->setLastLogin();
        $user->setLastActivity('Login');

        $request->session()->regenerate();
        return redirect(route('app.dashboard'));
    }

    public function logout(Request $request)
    {
        $this->_logout($request);
        return redirect('/')->with('success', 'Anda telah logout.');
    }

    private function _logout(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user) {
            $user->setLastActivity('Logout');
        }
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
