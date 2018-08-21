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
	 // Location 
    	$entryTable = entry::select('locationFrom as location','locationTo','loadType')->where('userId',$user['id'])->get();
    	foreach ($entryTable as $key => $value) {
    		$locationData[]=$value['location'];
    		$locationData[]=$value['locationTo'];
    		$loadType[]=$value['loadType'];
    	}
    	 $finalData['location'] = array_unique($locationData);  //LOCATION DATA
    	 $finalData['loadType'] = array_unique($loadType); // LOAD TYPE
	 // Staff 
    	$staff = staff::select('id','name','type')->where('userId',$user['id'])->get();
    	foreach ($staff as $key => $value) {
    		$finalData['staff'][$value['type']][]=$value;
    	}
    	$finalData['vehicle'] = vehicle::select("id","modelNumber","vehicleNumber")->where('userId',$user['id'])->get();
    	$finalData['client'] = client::select('id','name')->where('userId',$user['id'])->get();
    	return response()->json(['success',$finalData], $this-> successStatus);
    }

// ADD ENTRY
    public function addEntry(Request $request){
    	$validator = Validator::make($request->all(), [ 
            'dateFrom' => 'required', 
            'dateTo' => 'required', 
            'vehicleId' => 'required|exists:vehicles,id', 
            'clientId' => 'required|exists:clients,id', 
            'driverId' => 'required|exists:staff,id', 
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
            return response()->json(['error'=>$validator->errors()], 401);            
        }
        $data=$request->all();
        $user = Auth::user(); 
        $data['userId']=$user['id'];
        entry::create($data);
    	return response()->json(['success','Entry Added Sucessfully'], $this-> successStatus); 
    }

// VIEW ALL ENTRY
    public function getEntry(Request $request){
    	$user = Auth::user(); 
    	return  entry::where('userId',$user['id'])->get();
    }

// EDIT ENTRY 
    public function editEntry(entry $id){
	    	return $id;
    }

// UPDATE ENTRY
    public function updateEntry(Request $request,$id){
    	$validator = Validator::make($request->all(), [ 
            'dateFrom' => 'required', 
            'dateTo' => 'required', 
            'vehicleId' => 'required|exists:vehicles,id', 
            'clientId' => 'required|exists:clients,id', 
            'driverId' => 'required|exists:staff,id', 
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
            return response()->json(['error'=>$validator->errors()], 401);            
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
    		return response()->json(['success','Entry Updated Sucessfully'], $this-> successStatus); 
    	}else{
    		return response()->json(['error'], $this-> successStatus); 
    	}
    }

// DELETE ENTRY 
    public function deleteEntry($id){
    	entry::findOrfail($id);
    	if (entry::where('id', $id)->delete()) {
    		return response()->json(['success','Entry Deleted Sucessfully'], $this-> successStatus); 
    	}else{
    		return response()->json(['error'], $this-> successStatus); 
    	}
    }
}
