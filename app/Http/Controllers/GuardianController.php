<?php

namespace App\Http\Controllers;

use App\Actions\FileHelper;
use App\Actions\SaveFileHelper;
use App\Actions\storeUser;
use App\Actions\UpdateUser;
use App\Http\Requests\GuardianRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\GuardianResource;
use Illuminate\Http\Request;
use App\Models\Guardian;
use Illuminate\Support\Facades\Storage;

class GuardianController extends Controller
{
    public function index(Request $request)
    {
        $PerPage = $request->query('perPage', 15);
        $guardians = GuardianResource::collection(Guardian::filter()->latest()->paginate($PerPage));
        return response()->json($guardians->response()->getData(true));
    }
   
    public function getGuardians()
    {
        $guardians = Guardian::select('id', 'name')->get(); // Fetch only guardians
    
        return response()->json($guardians);
    }
    
    public function show(Guardian $guardian)
    {
        if (is_null($guardian)) {
            return response()->json(['message' => 'لا يوجد مستخدم'], 404);
        }
        return response()->json(new GuardianResource($guardian));
    }

    public function store(UserRequest $request, storeUser $store)
    {
        $guardian = $store->execute(Guardian::class, $request);
        return response()->json($guardian, 201);
    }

    public function update(UserRequest $request, Guardian $guardian, UpdateUser $updateUser)
    {
        if (is_null($guardian)) {
            return response()->json(['message' => 'لا يوجد مستخدم'], 404);
        }

       $guardian = $updateUser->execute($guardian,$request);
        return response()->json($guardian);
    }

    public function destroy(Guardian $guardian)
    {
        if (is_null($guardian)) {
            return response()->json(['message' => 'لا يوجد مستخدم'], 404);
        }
        $guardian->delete();
        return response()->json(['message' => 'تم مسح المستخدم بنجاح']);
    }
}
