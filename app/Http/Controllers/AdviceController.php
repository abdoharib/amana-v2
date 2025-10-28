<?php

namespace App\Http\Controllers;

use App\Actions\FileHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdviceRequest;
use App\Http\Requests\StoreAdviceRequest;
use App\Http\Requests\UpdateAdviceRequest;
use App\Http\Resources\AdviceCollection;
use App\Http\Resources\AdviceResource;
use App\Models\Advice;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdviceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $PerPage = $request->query('perPage', 15);
        /** @var User $user */
        $user = request()->user();
        $advices = AdviceResource::collection($user->advices()->filter()->latest()->paginate($PerPage));
        return $advices->response();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdviceRequest $request)
    {
        /** @var User $user */
        $userId = $request->guardian_id ?? auth()->id(); // Use guardian_id if provided, otherwise authenticated user
    
        $user = User::findOrFail($userId); // Ensure the user exists
        $advice = $user->advices()->create($request->validated());
    
        return (new AdviceResource($advice))->response()->setStatusCode(201);
    }
    
    /**
     * Display the specified resource.
     */
    public function show(Advice $advice)
    {
        return new AdviceResource($advice->load(['guardian', 'kid']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AdviceRequest $request, Advice $advice)
    {
        $advice->update($request->validated());

        return new AdviceResource($advice);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Advice $advice)
    {
        $advice->delete();

        return response()->noContent();
    }
}
