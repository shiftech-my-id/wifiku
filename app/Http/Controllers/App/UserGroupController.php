<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\UserGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserGroupController extends Controller
{
    public function index()
    {
        return inertia('app/user-group/Index');
    }

    public function data(Request $request)
    {
        $orderBy = $request->get('order_by', 'name');
        $orderType = $request->get('order_type', 'desc');
        $filter = $request->get('filter', []);

        $q = UserGroup::query();

        if (!empty($filter['search'])) {
            $q->where(function ($q) use ($filter) {
                $q->where('name', 'like', '%' . $filter['search'] . '%');
                $q->orWhere('description', 'like', '%' . $filter['search'] . '%');
            });
        }

        $q->orderBy($orderBy, $orderType);

        $items = $q->paginate($request->get('per_page', 10))->withQueryString();
        return response()->json($items);
    }

    public function duplicate($id)
    {
        $item = UserGroup::findOrFail($id);
        $item->id = null;
        return inertia('app/user-group/Editor', [
            'data' => $item
        ]);
    }

    public function editor($id = 0)
    {
        $item = $id ? UserGroup::findOrFail($id) : new UserGroup();
        return inertia('app/user-group/Editor', [
            'data' => $item,
        ]);
    }

    public function save(Request $request)
    {
        $item = $request->id
            ? UserGroup::where('company_id', Auth::user()->company->id)->findOrFail($request->id)
            : new UserGroup();

        $validated = $request->validate([
            'name' => [
                'required',
                'max:255',
                Rule::unique('user_groups', 'name')
                    ->where(fn($query) => $query->where('company_id', Auth::user()->company->id))
                    ->ignore($item->id, 'id'), // Lebih eksplisit
            ],
            'description' => 'nullable|max:1000',
        ]);
        $item->fill([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? '',
        ]);

        $item->save();

        return redirect()
            ->route('app.user-group.index')
            ->with('success', "Grup pengguna $item->name telah disimpan.");
    }

    public function delete($id)
    {
        $item = UserGroup::findOrFail($id);
        $item->delete();

        return response()->json([
            'message' => "Grup pengguna telah dihapus $item->name"
        ]);
    }
}
