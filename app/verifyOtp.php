<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class verifyOtp extends Model
{
    protected $fillable = ['mobile','otp'];
}
