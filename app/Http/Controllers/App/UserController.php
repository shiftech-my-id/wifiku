<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        return inertia('app/user/Index', [
        'groups' => UserGroup::query()->orderBy('name')->get()
        ]);
    }

    public function detail($id = 0)
    {
        // tambahkan jumlah client yang ditangani oleh user ini
        return inertia('app/user/Detail', [
            'data' => User::with(['group'])->findOrFail($id),
        ]);
    }

    public function data(Request $request)
    {
        $orderBy = $request->get('order_by', 'name');
        $orderType = $request->get('order_type', 'asc');
        $filter = $request->get('filter', []);

        // tambahkan jumlah client yang ditangani oleh user ini
        $q = User::with(['group']);
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
                    ->orWhere('username', 'like', '%' . $filter['search'] . '%');
            });
        }

        $users = $q->paginate($request->get('per_page', 10))->withQueryString();

        // Transform the collection to include the group name
        $users->getCollection()->transform(function ($user) {
            $user->group = $user->group?->name;
            return $user;
        });

        return response()->json($users);
    }

    public function duplicate($id)
    {
        $user = User::findOrFail($id);
        $user->id = null;
        $user->created_at = null;
        return inertia('app/user/Editor', [
            'data' => $user
        ]);
    }

    public function editor($id = 0)
    {
        $user = $id ? User::findOrFail($id) : new User();

        if (!$id) {
            $user->active = true;
            $user->admin = true;
        } else if ($user == Auth::user()) {
            return redirect(route('app.user.index'))->with('warning', 'TIdak dapat mengubah akun anda sendiri.');
        }

        return inertia('app/user/Editor', [
            'data' => $user,
            'groups' => UserGroup::query()->orderBy('name')->get()
        ]);
    }

    public function save(Request $request)
    {
        $isNew = empty($request->id);
        $companyId = auth()->user()->company_id;

        $rules = [
            'name'     => 'required|max:255',
            'username' => [
                'required',
                'alpha_num',
                'max:255',
                Rule::unique('users', 'username')
                    ->where('company_id', $companyId)
                    ->ignore($request->id),
            ],
            'active'   => 'required|boolean',
            'group_id' => 'nullable|exists:user_groups,id',
        ];

        if ($isNew) {
            $rules['password'] = 'required|min:5|max:40';
        } elseif ($request->filled('password')) {
            $rules['password'] = 'min:5|max:40';
        }

        $validated = $request->validate($rules);

        $user = $isNew ? new User() : User::findOrFail($request->id);

        // Kalau update dan password kosong, buang supaya tidak mengisi ulang
        if (!$request->filled('password')) {
            unset($validated['password']);
        }

        $user->fill($validated);
        $user->company_id = $companyId;
        $user->save();

        $message = "Pengguna {$user->username} telah " . ($isNew ? 'ditambahkan' : 'diperbarui') . '.';

        return redirect()->route('app.user.index')->with('success', $message);
    }


    public function delete($id)
    {
        $user = User::findOrFail($id);

        if ($user->id == Auth::user()->id) {
            return response()->json([
                'message' => 'Tidak dapat menghapus akun sendiri!'
            ], 409);
        }

        $user->delete();

        return response()->json([
            'message' => "Pengguna {$user->email} telah dihapus!"
        ]);
    }
}
