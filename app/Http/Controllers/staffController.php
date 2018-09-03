<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use App\staff;
use Validator;

class staffController extends Controller{
	public $successStatus = 200;

// ADD STAFFS
    public function addStaff(Request $request){      
    	$validator = Validator::make($request->all(), [ 
            'name' => 'required', 
            'mobile1' => 'required|min:10|max:10', 
            'mobile2' => 'min:10|max:10', 
            'address' => 'required', 
            'type' => 'required|in:manager,driver,cleaner', 
            'licenceNumber' => 'required', 
        ]);
		if ($validator->fails()) { 
            foreach ($validator->errors()->toArray() as $value) {
                $errData['status']='error';
                $errData['message']=$value[0];
                return response()->json($errData);            
            }
        }
        // CHECK STAFF MOBILE ALREADY EXITS OR NOT
        $user = Auth::user(); 
        $staffData=staff::where([['userId',$user['id']],['mobile1',$request->mobile1]])->first();
        if(!empty($staffData->mobile1)){
            $errData['status']='error';
            $errData['message']='Staff Already Added';
            return response()->json($errData); 
        }
        $data=$request->all();
        $data['userId']=$user['id'];
    	staff::create($data);
    	$success['status']='success';
        $success['message']='Staff Created Successfully';
        return response()->json($success); 
    }

// VIEW ALL STAFFS
    public function getStaff(Request $request){
    	$user = Auth::user(); 
        $success['status']='success';
        $success['staffs']=staff::select('id','name','mobile1','mobile2','address','type','licenceNumber')->where('userId',$user['id'])->get();
        return response()->json($success);
    }

// VIEW INDIVIDUAL STAFF
    public function editStaff(staff $id){
    	staff::findOrfail($id);
        $success['status']='success';
        $success['staff']=$id;
        return response()->json($success); 
    }

// UPDATE STAFF
    public function updateStaff(Request $request,$id){
    	$validator = Validator::make($request->all(), [ 
            'name' => 'required', 
            'mobile1' => 'required|min:10|max:10', 
            'mobile2' => 'min:10|max:10', 
            'address' => 'required', 
            'type' => 'required|in:manager,driver,cleaner', 
            'licenceNumber' => 'required', 
        ]);
		if ($validator->fails()) { 
            foreach ($validator->errors()->toArray() as $value) {
                $errData['status']='error';
                $errData['message']=$value[0];
                return response()->json($errData);            
            }
        }
     	$data = staff::findOrfail($id);
        $data ->name =  request('name');
        $data ->mobile1 =  request('mobile1');
        $data ->mobile2 =  request('mobile2');
        $data ->address =  request('address');
        $data ->type =  request('type');
        $data ->licenceNumber =  request('licenceNumber');
    	if ($data->save()) {
    		$success['status']='success';
            $success['message']='Staff Updated Sucessfully';
            return response()->json($success); 
    	}else{
    		$success['status']='error';
            $success['message']='Error on update';
            return response()->json($errDatas); 
    	}
    }

// DELETE STAFF
    public function deleteStaff($id){
    	staff::findOrfail($id);
    	if (staff::where('id', $id)->delete()) {
    		$success['status']='success';
            $success['message']='Staff Deleted Sucessfully';
            return response()->json($success); 
    	}else{
    		$success['status']='error';
            $success['message']='Error on Delete';
            return response()->json($errDatas);  
    	}
    }
}
