<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cour extends Model
{
    protected $fillable = [
        'coach_id',
        'nom',
        'horaire_deb',
        'horaire_fin',
        'capacite',
        'salle_id',
    ];

    public function salle()
    {
        return $this->belongsTo(Salle::class);
    }

    public function abonnement()
    {
        return $this->hasMany(Abonnement::class);
    } 
}
