<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salle extends Model
{
    protected $fillable = [
        'nom',
        'img',
        'address', 
        'capacite'
    ];

    public function cours()
    {
        return $this->hasMany(Cour::class);
    }
}
