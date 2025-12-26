<?php

namespace App\Http\Controllers;

use App\Models\Cour;
use App\Models\Abonnement;
use Illuminate\Http\Request;

class CourController extends Controller
{
    public function index($coach_id)
    {
        $cours = Cour::with(['salle', 'abonnement'])
            ->where('coach_id', $coach_id)
            ->get();

        return response()->json([
            'status' => 200,
            'data' => $cours
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'coach_id' => 'required|exists:users,id',
            'salle_id' => 'required|exists:salles,id',
            'nom' => 'required|string|max:255',
            'horaire_deb' => 'required|date_format:H:i',
            'horaire_fin' => 'required|date_format:H:i|after:horaire_deb',
            'capacite' => 'required|integer|min:1',
            'abonnement_nom' => 'required|string|max:255',
            'abonnement_prix' => 'required|numeric|min:0',
            'abonnement_duree' => 'required|integer|min:1',
        ]);

        $cours = Cour::create([
            'salle_id' => $validated['salle_id'],
            'nom' => $validated['nom'],
            'horaire_deb' => $validated['horaire_deb'],
            'horaire_fin' => $validated['horaire_fin'],
            'capacite' => $validated['capacite'],
        ]);

        $abonnement = Abonnement::create([
            'cour_id' => $cours->id,
            'nom' => $validated['abonnement_nom'],
            'prix' => $validated['abonnement_prix'],
            'duree' => $validated['abonnement_duree'],
        ]);

        return response()->json([
            'status' => 201,
            'message' => 'Cours and Abonnement created successfully',
            'data' => [
                'cours' => $cours,
                'abonnement' => $abonnement
            ]
        ], 201);
    }

    public function show(Cour $cour)
    {
        return response()->json([
            'status' => 200,
            'data' => $cour->load(['salle','abonnement'])
        ]);
    }

    public function update(Request $request, Cour $cour)
    {
        $validated = $request->validate([
            'coach_id' => 'required|exists:users,id',
            'salle_id' => 'sometimes|exists:salles,id',
            'nom' => 'sometimes|string|max:255',
            'horaire_deb' => 'sometimes|date_format:H:i',
            'horaire_fin' => 'sometimes|date_format:H:i|after:horaire_deb',
            'capacite' => 'sometimes|integer|min:1',
            'abonnement_nom' => 'sometimes|string|max:255',
            'abonnement_prix' => 'sometimes|numeric|min:0',
            'abonnement_duree' => 'sometimes|integer|min:1',
        ]);

        $cour->update($validated);

        if ($cour->abonnement) {
            $abonnementData = [];
            if (isset($validated['abonnement_nom'])) $abonnementData['nom'] = $validated['abonnement_nom'];
            if (isset($validated['abonnement_prix'])) $abonnementData['prix'] = $validated['abonnement_prix'];
            if (isset($validated['abonnement_duree'])) $abonnementData['duree'] = $validated['abonnement_duree'];

            if (!empty($abonnementData)) {
                $cour->abonnement->update($abonnementData);
            }
        }

        return response()->json([
            'status' => 200,
            'message' => 'Cours and related Abonnement updated successfully',
            'data' => [
                'cours' => $cour->load('abonnement')
            ]
        ]);
    }


    public function destroy(Cour $cour)
    {
        $cour->abonnement()->delete();

        $cour->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Cours and related Abonnement deleted successfully'
        ]);
    }
}
