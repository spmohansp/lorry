<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use App\client; 
use App\staff;
use App\vehicle;
use App\entry;
use Validator;

class entryController extends Controller
{
	public $successStatus = 200;

// PASS ENTRY DATA
    public function entryPassData(Request $request){
    	$user = Auth::user(); 
         $locationData=array();
         $loadType=array();
	 // Location 
    	$entryTable = entry::select('locationFrom as location','locationTo','loadType')->where('userId',$user['id'])->get();
        $finalData['status']='success';
        if (!empty($entryTable)) {
        	foreach ($entryTable as $key => $value) {
        		$locationData[]=$value['location'];
        		$locationData[]=$value['locationTo'];
        		$loadType[]=$value['loadType'];
        	}
        	 $finalData['locations'] = array_unique($locationData);  //LOCATION DATA
        	 $finalData['loadTypes'] = array_unique($loadType); // LOAD TYPE
        }
	 // Staff 
    	$staff = staff::select('id','name','type')->where('userId',$user['id'])->get();
    	foreach ($staff as $key => $value) {
    		$finalData['staffs'][$value['type']][]=$value;
    	}
    	$finalData['vehicles'] = vehicle::select("id","modelNumber","vehicleNumber")->where('userId',$user['id'])->get();
    	$finalData['clients'] = client::select('id','name')->where('userId',$user['id'])->get();
    	return response()->json($finalData, $this-> successStatus);
    }

// ADD ENTRY
    public function addEntry(Request $request){
    	$validator = Validator::make($request->all(), [ 
            'dateFrom' => 'required|date', 
            'dateTo' => 'required|date', 
            'vehicleId' => 'required|exists:vehicles,id', 
            'clientId' => 'required|exists:clients,id', 
            'driverId' => 'required_without:cleanerId|exists:staff,id',
            'cleanerId' => 'required_without:driverId|exists:staff,id', 
            'startKm' => 'required', 
            'endKm' => 'required', 
            'total' => 'required', 
            'locationFrom' => 'required', 
            'locationTo' => 'required', 
            'billAmount' => 'required', 
            'advance' => 'required', 
            'balance' => 'required', 
        ]);
		if ($validator->fails()) { 
            foreach ($validator->errors()->toArray() as $value) {
                $errData['status']='error';
                $errData['message']=$value[0];
                return response()->json($errData);            
            }
        }
        $data=$request->all();
        $user = Auth::user(); 
        $data['userId']=$user['id'];
        entry::create($data);
    	$success['status']='success';
        $success['message']='Entry Added Successfully';
        return response()->json($success);  
    }

// VIEW ALL ENTRY
    public function getEntry(Request $request){
    	$user = Auth::user(); 
        $success['status']='success';
    	$success['entries']=  entry::where('userId',$user['id'])->get();
        return response()->json($success);
    }

// EDIT ENTRY 
    public function editEntry(entry $id){
        entry::findOrfail($id);
        $success['status']='success';
        $success['staff']=$id;
        return response()->json($success); 
    }

// UPDATE ENTRY
    public function updateEntry(Request $request,$id){
    	$validator = Validator::make($request->all(), [ 
            'dateFrom' => 'required|date', 
            'dateTo' => 'required|date', 
            'vehicleId' => 'required|exists:vehicles,id', 
            'clientId' => 'required|exists:clients,id', 
            'driverId' => 'required_without:cleanerId|exists:staff,id',
            'cleanerId' => 'required_without:driverId|exists:staff,id',
            'startKm' => 'required', 
            'endKm' => 'required', 
            'total' => 'required', 
            'locationFrom' => 'required', 
            'locationTo' => 'required', 
            'billAmount' => 'required', 
            'advance' => 'required', 
            'balance' => 'required', 
        ]);
		if ($validator->fails()) { 
            foreach ($validator->errors()->toArray() as $value) {
                $errData['status']='error';
                $errData['message']=$value[0];
                return response()->json($errData);            
            }
        }
        $data = entry::findOrfail($id);
        $data ->dateFrom =  request('dateFrom');
        $data ->dateTo =  request('dateTo');
        $data ->vehicleId =  request('vehicleId');
        $data ->clientId =  request('clientId');
        $data ->cleanerId =  request('cleanerId');
        $data ->driverId =  request('driverId');
        $data ->managerId =  request('managerId');
        $data ->startKm =  request('startKm');
        $data ->endKm =  request('endKm');
        $data ->total =  request('total');
        $data ->locationFrom =  request('locationFrom');
        $data ->locationTo =  request('locationTo');
        $data ->loadType =  request('loadType');
        $data ->ton =  request('ton');
        $data ->billAmount =  request('billAmount');
        $data ->comission =  request('comission');
        $data ->upLift =  request('upLift');
        $data ->downLift =  request('downLift');
        $data ->advance =  request('advance');
        $data ->balance =  request('balance');
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

// DELETE ENTRY 
    public function deleteEntry($id){
    	entry::findOrfail($id);
    	if (entry::where('id', $id)->delete()) {
    		$success['status']='success';
            $success['message']='Entry Deleted Sucessfully';
            return response()->json($success); 
        }else{
            $success['status']='error';
            $success['message']='Error on Delete';
            return response()->json($errDatas);  
        }
    }
}
