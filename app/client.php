<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class client extends Model
{
     protected $fillable = ['name','mobile','address','type','userId'];
}
