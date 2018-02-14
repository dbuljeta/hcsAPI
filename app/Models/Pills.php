<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pills extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name', 'description', 'numberOfIntakes'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'id_user');
    }

    public function intakes()
    {
        return $this->hasMany('App\Models\Intakes', 'id_pill', 'id');
    }
}
