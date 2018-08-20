<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use App\vehicle;
use Validator;

class vehicleController extends Controller
{
	public $successStatus = 200;

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
       	$data=$request->all();
       	// return $request->documents;
       	$data['documents']=serialize($request->documents);
        $user = Auth::user(); 
        $data['userId']=$user['id'];
        vehicle::create($data);
    	return response()->json(['success','Vehicle Added Sucessfully'], $this-> successStatus); 
    }

// VIEW ALL VEHICLE DETAILS
    public function getVehicle(Request $request){
    	$user = Auth::user(); 
    	return  vehicle::select("id","modelNumber","vehicleNumber","ownerName","documents")->where('userId',$user['id'])->get();
    }


// VIEW INDIVIDUAL VEHICLE
    public function editVehicle(vehicle $id){
    	vehicle::findOrfail($id);
    	return $id;
    }

// UPDATE VEHICLE
	public function updateVehicle(Request $request,$id){
		return $request;
	}

// DELETE VEHICLE
    public function deleteVehicle($id){
    	vehicle::findOrfail($id);
    	if (vehicle::where('id', $id)->delete()) {
    		return response()->json(['success','Vehicle Deleted Sucessfully'], $this-> successStatus); 
    	}else{
    		return response()->json(['error'], $this-> successStatus); 
    	}
    }
}
