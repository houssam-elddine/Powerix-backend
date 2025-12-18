<?php

namespace App\Http\Controllers;

use App\Models\Cour;
use Illuminate\Http\Request;

class CourController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => 200,
            'data' => Cour::with('salle')->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'horaire_deb' => 'required|date_format:H:i',
            'horaire_fin' => 'required|date_format:H:i|after:horaire_deb',
            'capacite' => 'required|integer|min:1',
            'salle_id' => 'required|exists:salles,id',
        ]);

        $cours = Cour::create($validated);

        return response()->json([
            'status' => 201,
            'message' => 'Cours created successfully',
            'data' => $cours
        ], 201);
    }

    public function show(Cour $cour)
    {
        return response()->json([
            'status' => 200,
            'data' => $cour->load('salle')
        ]);
    }

    public function update(Request $request, Cour $cour)
    {
        $validated = $request->validate([
            'nom' => 'sometimes|string|max:255',
            'horaire_deb' => 'sometimes|date_format:H:i',
            'horaire_fin' => 'sometimes|date_format:H:i|after:horaire_deb',
            'capacite' => 'sometimes|integer|min:1',
            'salle_id' => 'sometimes|exists:salles,id',
        ]);

        $cour->update($validated);

        return response()->json([
            'status' => 200,
            'message' => 'Cours updated successfully',
            'data' => $cour
        ]);
    }

    public function destroy(Cour $cour)
    {
        $cour->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Cours deleted successfully'
        ]);
    }
}
