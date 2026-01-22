<?php

namespace App\Http\Controllers;

use App\Models\Inscirption;
use Illuminate\Http\Request;

class InscirptionController extends Controller
{
    public function index($client_id = null)
    {
        $query = Inscirption::with(['client' ,'cour', 'abonnement']); 

        if ($client_id) {
            $query->where('client_id', $client_id);
        }

        $inscriptions = $query->get();

        return response()->json([
            'status' => 200,
            'data' => $inscriptions
        ]);
        
    }


public function store(Request $request)
{
    $data = $request->validate([
        'client_id' => 'required|exists:users,id',
        'cour_id' => 'required|exists:cours,id',
        'abonnement_id' => 'required|exists:abonnements,id',
        'date_inscription' => 'required|date',
    ]);

    $exists = Inscirption::where('client_id', $data['client_id'])
        ->where('cour_id', $data['cour_id'])
        ->first();

    if ($exists) {
        return response()->json([
            'status' => 409,
            'message' => 'Already registered in this course'
        ], 409);
    }

    $data['etat'] = 'en attente';

    Inscirption::create($data);

    return response()->json([
        'status' => 201,
        'message' => 'Inscription created successfully'
    ], 201);
}


    public function show(Inscirption $inscirption)
    {
        return response()->json(
            $inscirption->load(['cour','abonnement'])
        );
    }

    public function update(Request $request, Inscirption $inscirption)
    {
        $data = $request->validate([
            'etat' => 'required|in:en attente,sans payée,valider,annuler'
        ]);

        $inscirption->update($data);

        return response()->json($inscirption);
    }

    public function destroy(Inscirption $inscirption)
    {
        $inscirption->delete();
        return response()->json(['message' => 'Inscirption supprimée']);
    }

    public function indexByCoach()
    {
        $inscriptions = Inscirption::with([
                'cour.salle',
                'cour.abonnement',
                'client'
            ])
            ->whereHas('cour', function ($query) {
                $query->where('coach_id', auth()->id());
            })
            ->where('etat', 'valider')           
            ->get();

        return response()->json([
            'status' => 200,
            'data' => $inscriptions
        ]);
    }

}
