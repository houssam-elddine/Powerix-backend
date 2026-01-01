<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inscirption extends Model
{
    protected $fillable = [
        'date_inscription','etat','cour_id','abonnement_id','client_id'
    ];

    public function cour()
    {
        return $this->belongsTo(Cour::class);
    }

    public function abonnement()
    {
        return $this->belongsTo(Abonnement::class);
    }

    public function client()
    {
        return $this->belongsTo(User::class);
    }
}
