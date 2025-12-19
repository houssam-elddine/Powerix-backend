<?php

namespace App\Http\Controllers;

use App\Models\Abonnement;
use Illuminate\Http\Request;

class AbonnementController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => 200,
            'data' => Abonnement::all()
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cour_id' => 'required|exists:cours,id',
            'nom' => 'required|string|max:255',
            'prix' => 'required|numeric|min:0',
            'duree' => 'required|integer|min:1'
        ]);

        $abonnement = Abonnement::create($validated);

        return response()->json([
            'status' => 201,
            'message' => 'Abonnement created successfully',
            'data' => $abonnement
        ], 201);
    }

    public function show(Abonnement $abonnement)
    {
        return response()->json([
            'status' => 200,
            'data' => $abonnement
        ], 200);
    }

    public function update(Request $request, Abonnement $abonnement)
    {
        $validated = $request->validate([
            'cour_id' => 'sometimes|exists:cours,id',
            'nom' => 'sometimes|string|max:255',
            'prix' => 'sometimes|numeric|min:0',
            'duree' => 'sometimes|integer|min:1'
        ]);

        $abonnement->update($validated);

        return response()->json([
            'status' => 200,
            'message' => 'Abonnement updated successfully',
            'data' => $abonnement
        ], 200);
    }

    public function destroy(Abonnement $abonnement)
    {
        $abonnement->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Abonnement deleted successfully'
        ], 200);
    }
}
