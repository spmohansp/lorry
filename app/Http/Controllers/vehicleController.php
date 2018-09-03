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
            foreach ($validator->errors()->toArray() as $value) {
                $errData['status']='error';
                $errData['message']=$value[0];
                return response()->json($errData);            
            }
        }
       	$data=$request->all();
       	// return $request->documents;
       	$data['documents']=serialize($request->documents);
        $user = Auth::user(); 
        $data['userId']=$user['id'];
        vehicle::create($data);
        $success['status']='success';
        $success['message']='Vehicle Created Successfully';
        return response()->json($success); 
    	return response()->json($success, $this-> successStatus); 
    }

// VIEW ALL VEHICLE DETAILS
    public function getVehicle(Request $request){
    	$user = Auth::user();
        $success['status']='success'; 
    	$success['vehicles']=vehicle::select("id","modelNumber","vehicleNumber","ownerName","documents")->where('userId',$user['id'])->get();
        return response()->json($success);
    }


// VIEW INDIVIDUAL VEHICLE
    public function editVehicle(vehicle $id){
    	vehicle::findOrfail($id);
    	$success['status']='success';
        $success['vehicle']=$id;
        return response()->json($success); 
    }

// UPDATE VEHICLE
	public function updateVehicle(Request $request,$id){
        $validator = Validator::make($request->all(), [ 
            'modelNumber' => 'required', 
            'vehicleNumber' => 'required', 
            'ownerName' => 'required', 
            'documents' => 'required', 
        ]);
        if ($validator->fails()) { 
            foreach ($validator->errors()->toArray() as $value) {
                $errData['status']='error';
                $errData['message']=$value[0];
                return response()->json($errData);            
            }
        }
        $data = vehicle::findOrfail($id);
        $data ->modelNumber =  request('modelNumber');
        $data ->vehicleNumber =  request('vehicleNumber');
        $data ->ownerName =  request('ownerName');
        $data ->documents =  serialize(request('documents'));
        if ($data->save()) {
            $success['status']='success';
            $success['message']='Vehicle Updated Sucessfully';
            return response()->json($success); 
        }else{
            $success['status']='error';
            $success['message']='Error on update';
            return response()->json($errDatas); 
        }
	}

// DELETE VEHICLE
    public function deleteVehicle($id){
    	vehicle::findOrfail($id);
    	if (vehicle::where('id', $id)->delete()) {
    		$success['status']='success';
            $success['message']='Vehicle Deleted Sucessfully';
            return response()->json($success); 
        }else{
            $success['status']='error';
            $success['message']='Error on Delete';
            return response()->json($errDatas);  
        }
    }
}
