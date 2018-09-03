<?php

namespace App\Http\Controllers;
use App\client; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Validator;
class clientController extends Controller
{
	public $successStatus = 200;
    // public $success['status'] = 'success';

// ADD CLIENT  
    public function addClient(Request $request){
        $user = Auth::user(); 
    	$validator = Validator::make($request->all(), [ 
            'name' => 'required', 
            'mobile' => 'required|min:10|max:10', 
            'address' => 'required',
        ]);
		if ($validator->fails()) { 
            foreach ($validator->errors()->toArray() as $value) {
                $errData['status']='error';
                $errData['message']=$value[0];
                return response()->json($errData);            
            }
        }
        // CHECK CLIENT MOBILE ALREADY EXITS OR NOT
        $clientData=client::where([['userId',$user['id']],['mobile',$request->mobile]])->first();
        if(!empty($clientData->mobile)){
            $errData['status']='error';
            $errData['message']='Client Already Added';
            return response()->json($errData); 
        }

        $data=$request->all();
        $data['userId']=$user['id'];
    	client::create($data);
        $success['status']='success';
        $success['message']='Client Created Successfully';
    	return response()->json($success); 
    }

// GET ALL CLIENTS
    public function getClient(Request $request){
    	$user = Auth::user(); 
        $success['status']='success';
        $success['clients'] = client::select('id','name','mobile','address','type')->where('userId',$user['id'])->get();
        return response()->json($success); 
    }

// GET SINGLE CLIENT FOR EDIT
    public function editClient(client $id){
    	client::findOrfail($id);
        $success['status']='success';
        $success['client']=$id;
    	return response()->json($success); 
    }

// UPDATE CLIENT DETAIL
    public function updateClient(Request $request,$id){
    	$validator = Validator::make($request->all(), [ 
            'name' => 'required', 
            'mobile' => 'required|min:10|max:10', 
            'address' => 'required',
        ]);
		if ($validator->fails()) { 
            foreach ($validator->errors()->toArray() as $value) {
                $errData['status']='error';
                $errData['message']=$value[0];
                return response()->json($errData);            
            }
        }
       	$data = client::findOrfail($id);
        $data ->name =  request('name');
        $data ->mobile =  request('mobile');
        $data ->address =  request('address');
    	if ($data->save()) {
            $success['status']='success';
            $success['message']='Client Updated Sucessfully';
    		return response()->json($success); 
    	}else{
             $success['status']='error';
             $success['message']='Error on update';
    		return response()->json($errDatas); 
    	}
    }

// DELETE CLIENT DETAIL
    public function deleteClient($id){
    	client::findOrfail($id);
    	if (client::where('id', $id)->delete()) {
    		$success['status']='success';
            $success['message']='Client Deleted Sucessfully';
            return response()->json($success); 
    	}else{
    		$success['status']='error';
            $success['message']='Error on Delete';
            return response()->json($errDatas); 
    	}
    }

}
