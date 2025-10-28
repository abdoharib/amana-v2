<?php

namespace App\Http\Controllers;

use App\Http\Requests\KidRequest;
use App\Http\Resources\KidResource;
use App\Models\Kid;
use Illuminate\Http\Request;

class KidController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $PerPage = $request->query('per_page', 10);
        $user = request()->user();
        $kids = KidResource::collection($user->kids()->filter()->latest()->paginate($PerPage));
        return response()->json($kids->response()->getData(true));
    }
   
    public function getKids()
    {
        $kids = Kid::select('id', 'first_name','last_name')->filter()->get(); 
        return response()->json($kids);
    }
    
    public function store(KidRequest $request)
    {
        $data = $request->validated();
        $data['guardian_id'] = $request->input('guardian_id', request()->user()->id);

        // Create the kid record
        $kid = Kid::create($data);

        return response()->json([
            'message' => 'تم إضافة الطفل',
            'kid' => $kid,
        ], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(Kid $kid)
    {
        return response()->json(new KidResource($kid));
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(KidRequest $request, Kid $kid)
    {
        $kid->update($request->validated());

        return response()->json([
            'message' => 'تم تعديل بيانات الطفل',
            'kid' => $kid,
        ]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kid $kid)
    {
        $kid->delete();

        return response()->json(['message' => 'تم مسح بيانات الطفل ']);
    }
}
