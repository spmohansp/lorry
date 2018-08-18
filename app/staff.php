<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class staff extends Model
{
	// public static $rules = array(
	//     'mobile1' => 'required|unique:staff,mobile1,userId'
	// );
    protected $fillable = ['name','mobile1','mobile2','address','type','licenceNumber','userId' ];

}
