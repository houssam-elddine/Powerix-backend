<?php

namespace App\Http\Controllers;

use App\Models\Cour;
use App\Models\Abonnement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CourController extends Controller
{
    public function index($coach_id = null)
    {
        $query = Cour::with(['salle', 'abonnement']); // تغيير إلى abonnement (hasMany)

        if ($coach_id) {
            $query->where('coach_id', $coach_id);
        }

        $cours = $query->get();

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
            'img' => 'required|image|mimes:jpeg,png,jpg,gif|max:20480',
            'abonnements' => 'required|array|min:1', // array من الاشتراكات
            'abonnements.*.nom' => 'required|string|max:255',
            'abonnements.*.prix' => 'required|numeric|min:0',
            'abonnements.*.duree' => 'required|integer|min:1',
        ]);

        // تحميل الصورة
        if ($request->hasFile('img')) {
            $path = $request->file('img')->store('images', 'public');
            $validated['img'] = $path;
        }

        // إنشاء الدورة
        $cour = Cour::create([
            'coach_id' => $validated['coach_id'],
            'salle_id' => $validated['salle_id'],
            'nom' => $validated['nom'],
            'horaire_deb' => $validated['horaire_deb'],
            'horaire_fin' => $validated['horaire_fin'],
            'capacite' => $validated['capacite'],
            'img' => $validated['img'],
        ]);

        // إنشاء عدة اشتراكات
        foreach ($validated['abonnements'] as $abonnementData) {
            Abonnement::create([
                'cour_id' => $cour->id,
                'nom' => $abonnementData['nom'],
                'prix' => $abonnementData['prix'],
                'duree' => $abonnementData['duree'],
            ]);
        }

        // تحميل الدورة مع الاشتراكات
        $cour->load('abonnement');

        return response()->json([
            'status' => 201,
            'message' => 'Cours and Abonnements created successfully',
            'data' => $cour
        ], 201);
    }

    public function show(Cour $cour)
    {
        return response()->json([
            'status' => 200,
            'data' => $cour->load(['salle', 'abonnement'])
        ]);
    }

    public function update(Request $request, Cour $cour)
    {
        $validated = $request->validate([
            'coach_id' => 'sometimes|exists:users,id',
            'salle_id' => 'sometimes|exists:salles,id',
            'nom' => 'sometimes|string|max:255',
            'horaire_deb' => 'sometimes|date_format:H:i',
            'horaire_fin' => 'sometimes|date_format:H:i|after:horaire_deb',
            'capacite' => 'sometimes|integer|min:1',
            'img' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'abonnements' => 'sometimes|array',
            'abonnements.*.id' => 'sometimes|exists:abonnements,id|cour_id,' . $cour->id,
            'abonnements.*.nom' => 'required_with:abonnements|string|max:255',
            'abonnements.*.prix' => 'required_with:abonnements|numeric|min:0',
            'abonnements.*.duree' => 'required_with:abonnements|integer|min:1',
        ]);

        // تحديث الصورة
        if ($request->hasFile('img')) {
            if ($cour->img && Storage::disk('public')->exists($cour->img)) {
                Storage::disk('public')->delete($cour->img);
            }
            $path = $request->file('img')->store('images', 'public');
            $validated['img'] = $path;
        }

        $cour->update($validated);

        // تحديث الاشتراكات إذا وجدت
        if (isset($validated['abonnements'])) {
            foreach ($validated['abonnements'] as $abData) {
                if (isset($abData['id'])) {
                    Abonnement::where('id', $abData['id'])->where('cour_id', $cour->id)->update([
                        'nom' => $abData['nom'],
                        'prix' => $abData['prix'],
                        'duree' => $abData['duree'],
                    ]);
                }
            }
        }

        $cour->load('abonnement');

        return response()->json([
            'status' => 200,
            'message' => 'Cours and Abonnements updated successfully',
            'data' => $cour
        ]);
    }

    public function destroy(Cour $cour)
    {
        if ($cour->img && Storage::disk('public')->exists($cour->img)) {
            Storage::disk('public')->delete($cour->img);
        }

        // حذف كل الاشتراكات المرتبطة
        $cour->abonnement()->delete();
        $cour->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Cours and related Abonnements deleted successfully'
        ]);
    }
}