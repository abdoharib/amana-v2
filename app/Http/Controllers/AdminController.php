<?php

namespace App\Http\Controllers;

use App\Actions\storeUser;
use App\Actions\UpdateUser;
use App\Http\Requests\UserRequest;
use App\Http\Resources\AdminResource;
use App\Models\Admin;
use Illuminate\Http\Request;


class AdminController extends Controller
{
    public function index(Request $request)
    {
        $PerPage = $request->query('perPage', 15);
        $admins = AdminResource::collection(Admin::filter()->latest()->paginate($PerPage));
        return response()->json($admins->response()->getData(true));
    }

    public function show(Admin $admin)
    {
        if (is_null($admin)) {
            return response()->json(['message' => 'لا يوجد مستخدم'], 404);
        }
        return response()->json($admin);
    }

    public function store(UserRequest $request, storeUser $store)
    {
        $admin = $store->execute(Admin::class, $request);
        return response()->json($admin, 201);
    }

    public function update(UserRequest $request, Admin $admin, UpdateUser $updateUser)
    {
        if (is_null($admin)) {
            return response()->json(['message' => 'لا يوجد مستخدم'], 404);
        }

       $admin = $updateUser->execute($admin,$request);
        return response()->json($admin);
    }

    public function destroy(Admin $admin)
    {
        if (is_null($admin)) {
            return response()->json(['message' => 'لا يوجد مستخدم'], 404);
        }
        $admin->delete();
        return response()->json(['message' => 'تم مسح المستخدم بنجاح']);
    }
}
