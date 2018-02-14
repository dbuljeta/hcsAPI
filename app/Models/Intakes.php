<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Intakes extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'timeOfIntake'
    ];

    public function pill()
    {
        return $this->belongsTo('App\Models\Pills', 'id_pill');
    }
}
