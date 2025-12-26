<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Abonnement extends Model
{
    protected $fillable = [
        'cour_id',
        'nom',
        'duree',
        'prix'
    ];

    public function cour()
    {
        return $this->belongsTo(Cour::class);
    }
}
