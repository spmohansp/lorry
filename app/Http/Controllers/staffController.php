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
            return response()->json(['error'=>$validator->errors()], 401);            
        }
        $data=$request->all();
        $user = Auth::user(); 
        $data['userId']=$user['id'];
    	staff::create($data);
    	return response()->json(['success','Staff Added Sucessfully'], $this-> successStatus); 
    }

// VIEW ALL STAFFS
    public function getStaff(Request $request){
    	$user = Auth::user(); 
    	return  staff::select('id','name','mobile1','mobile2','address','type','licenceNumber')->where('userId',$user['id'])->get();
    }

// VIEW INDIVIDUAL STAFF
    public function editStaff(staff $id){
    	staff::findOrfail($id);
    	return $id;
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
            return response()->json(['error'=>$validator->errors()], 401);            
        }
     	$data = staff::findOrfail($id);
        $data ->name =  request('name');
        $data ->mobile1 =  request('mobile1');
        $data ->mobile2 =  request('mobile2');
        $data ->address =  request('address');
        $data ->type =  request('type');
        $data ->licenceNumber =  request('licenceNumber');

    	if ($data->save()) {
    		return response()->json(['success','Staff Updated Sucessfully'], $this-> successStatus); 
    	}else{
    		return response()->json(['error'], $this-> successStatus); 
    	}
    }

// DELETE STAFF
    public function deleteStaff($id){
    	staff::findOrfail($id);
    	if (staff::where('id', $id)->delete()) {
    		return response()->json(['success','Staff Deleted Sucessfully'], $this-> successStatus); 
    	}else{
    		return response()->json(['error'], $this-> successStatus); 
    	}
    }
}
