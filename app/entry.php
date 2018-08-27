<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\vehicle;


class entry extends Model
{
    protected $guarded = ['id','created_at','updated_at'];

     public function vechicle_number()
    {
        return $this->hasOne('App\vehicle', 'id', 'vehicleId');
    }
}
