<?php

namespace App\Http\Controllers;

use App\Models\Salle;
use Illuminate\Http\Request;

class SalleController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => 200,
            'data' => Salle::all()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'capacite' => 'required|integer|min:1',
        ]);

        Salle::create($validated);

        return response()->json([
            'status' => 201,
            'message' => 'Salle created successfully',
        ], 201);
    }

    public function show(Salle $salle)
    {
        return response()->json([
            'status' => 200,
            'data' => $salle
        ]);
    }

    public function update(Request $request, Salle $salle)
    {
        $validated = $request->validate([
            'nom' => 'sometimes|string|max:255',
            'address' => 'sometimes|string|max:255',
            'capacite' => 'sometimes|integer|min:1',
        ]);

        $salle->update($validated);

        return response()->json([
            'status' => 200,
            'message' => 'Salle updated successfully',
        ]);
    }

    public function destroy(Salle $salle)
    {
        $salle->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Salle deleted successfully'
        ]);
    }
}
