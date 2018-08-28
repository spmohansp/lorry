<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use App\client; 
use App\staff;
use App\vehicle;
use App\entry;
use Validator;

class clientIncomeController extends Controller
{
    public function addClientIncome(Request $request,$id){
    	$validator = Validator::make($request->all(), [ 
            'date' => 'required|date', 
            'vehicleId' => 'required||exists:vehicles,id', 
            'receivingAmount' => 'required', 
            'discountAmount' => 'required', 
        ]);
		if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
        return $request->all();
        $user = Auth::user(); 
        $data['userId']=$user['id'];
    }
}