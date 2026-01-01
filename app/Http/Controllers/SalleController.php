<?php
namespace App\Http\Controllers;
use App\Models\Salle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SalleController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => 200,
            'data' => Salle::with('cours')->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'capacite' => 'required|integer|min:1',
            'img' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', 
        ]);

        if ($request->hasFile('img')) {
            $path = $request->file('img')->store('images', 'public');
            $validated['img'] = $path;
        }

        $salle = Salle::create($validated);
        return response()->json([
            'status' => 201,
            'message' => 'Salle created successfully',
            'data' => $salle
        ], 201);
    }

    public function show(Salle $salle)
    {
        return response()->json([
            'status' => 200,
            'data' => $salle->load('cours')
        ]);
    }

    public function update(Request $request, Salle $salle)
    {
        $validated = $request->validate([
            'nom' => 'sometimes|string|max:255',
            'address' => 'sometimes|string|max:255',
            'capacite' => 'sometimes|integer|min:1',
            'img' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:20480', 
        ]);

        if ($request->hasFile('img')) {
            if ($salle->img && Storage::disk('public')->exists($salle->img)) {
                Storage::disk('public')->delete($salle->img);
            }
            $path = $request->file('img')->store('images', 'public');
            $validated['img'] = $path;
        }

        $salle->update($validated);
        return response()->json([
            'status' => 200,
            'message' => 'Salle updated successfully',
            'data' => $salle
        ]);
    }

    public function destroy(Salle $salle)
    {
        if ($salle->img && Storage::disk('public')->exists($salle->img)) {
            Storage::disk('public')->delete($salle->img);
        }
        $salle->delete();
        return response()->json([
            'status' => 200,
            'message' => 'Salle deleted successfully'
        ]);
    }
}