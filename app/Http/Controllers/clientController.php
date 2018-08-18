<?php

namespace App\Http\Controllers;
use App\client; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Validator;
class clientController extends Controller
{
	public $successStatus = 200;

// ADD CLIENT  
    public function addClient(Request $request){
        $user = Auth::user(); 
    	$validator = Validator::make($request->all(), [ 
            'name' => 'required', 
            'mobile' => 'required|min:10|max:10', 
            'address' => 'required',
        ]);
		if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }

        $data=$request->all();
        $data['userId']=$user['id'];
    	client::create($data);
    	return response()->json(['success','Client Added Sucessfully'], $this-> successStatus); 
    }

// GET ALL CLIENTS
    public function getClient(Request $request){
    	$user = Auth::user(); 
    	return  client::select('id','name','mobile','address','type')->where('userId',$user['id'])->get();
    }

// GET SINGLE CLIENT FOR EDIT
    public function editClient(client $id){
    	client::findOrfail($id);
    	return $id;
    }

// UPDATE CLIENT DETAIL
    public function updateClient(Request $request,$id){
    	$validator = Validator::make($request->all(), [ 
            'name' => 'required', 
            'mobile' => 'required|min:10|max:10', 
            'address' => 'required',
        ]);
		if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }

       	$data = client::findOrfail($id);
        $data ->name =  request('name');
        $data ->mobile =  request('mobile');
        $data ->address =  request('address');
    	if ($data->save()) {
    		return response()->json(['success','Client Updated Sucessfully'], $this-> successStatus); 
    	}else{
    		return response()->json(['error'], $this-> successStatus); 
    	}

    }

// DELETE CLIENT DETAIL
    public function deleteClient($id){
    	client::findOrfail($id);
    	if (client::where('id', $id)->delete()) {
    		return response()->json(['success','Client Deleted Sucessfully'], $this-> successStatus); 
    	}else{
    		return response()->json(['error'], $this-> successStatus); 
    	}
    }

}
