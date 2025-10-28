<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVaccinationRequest;
use App\Http\Requests\UpdateVaccinationRequest;
use App\Models\Vaccination;
use Illuminate\Http\Request;

class VaccinationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vaccinations = request()->user()->vaccinations()->filter()->latest()->paginate(10);
        return response()->json($vaccinations);
    }
    public function stats()
    {
        $total = request()->user()->vaccinations()->count();

        $completed =request()->user()->vaccinations()->where('status', 'منجزة')->count();
        $upcoming = request()->user()->vaccinations()->where('status', 'قادمة')->count();
        $missed = request()->user()->vaccinations()->where('status', 'فائتة')->count();

        return response()->json([
            'total' => $total,
            'completed' => $completed,
            'upcoming' => $upcoming,
            'missed' => $missed,
            'percentages' => [
                'completed' => $total ? round(($completed / $total) * 100) : 0,
                'upcoming' => $total ? round(($upcoming / $total) * 100) : 0,
                'missed' => $total ? round(($missed / $total) * 100) : 0,
            ]
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVaccinationRequest $request)
    {

        $data = $request->validated();
        $data['guardian_id'] = request()->user()->id;

        $vaccination = request()->user()->vaccinations()->create($data);

        return response()->json([
            'message' => 'تم إضافة التطعيم',
            'vaccination' => $vaccination,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Vaccination $vaccination)
    {
        return response()->json($vaccination);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVaccinationRequest $request, Vaccination $vaccination)
    {

        $vaccination->update($request->validated());

        return response()->json([
            'message' => 'تم تحديث التطعيم',
            'vaccination' => $vaccination,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vaccination $vaccination)
    {
        $vaccination->delete();

        return response()->json([
            'message' => 'تم حذف التطعيم',
        ]);
    }
}
