<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use App\vehicle;
use Validator;

class vehicleController extends Controller
{

// ADD VEHICLE 
    public function addVehicle(Request $request){
    	$validator = Validator::make($request->all(), [ 
            'modelNumber' => 'required', 
            'vehicleNumber' => 'required', 
            'ownerName' => 'required', 
            'documents' => 'required', 
        ]);
		if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
       	return $data=$request->all();
        $user = Auth::user(); 
        $data['userId']=$user['id'];
        vehicle::create($data);
    	return response()->json(['success','Staff Added Sucessfully'], $this-> successStatus); 
    }
}
