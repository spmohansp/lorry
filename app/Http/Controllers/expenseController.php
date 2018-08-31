<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use App\client; 
use App\staff;
use App\vehicle;
use App\expense;
use Validator;

class expenseController extends Controller
{
	public $successStatus = 200;

//  PASS EXPENSE DATA
    public function expensePassData(Request $request){
    	$user = Auth::user(); 
    	$finalData['vehicles'] = vehicle::select("id","modelNumber","vehicleNumber")->where('userId',$user['id'])->get();
    	$finalData['staffs'] = staff::select('id','name','type')->where('userId',$user['id'])->get();
    	return response()->json(['success',$finalData], $this-> successStatus);
    }


// ADD EXPENSE ENTRY
    public function AddExpense(Request $request){
    	$validator = Validator::make($request->all(), [ 
            'date' => 'required|date', 
            'expenseType' => 'required|in:diesel,salary,extras,checkPost,Toll,bridge,partsCost', 
            'vehicleId' => 'required_if:expenseType,==,diesel|required_without:staffId|exists:vehicles,id', 
            'quantity'=>'required_if:expenseType,==,diesel',
            'staffId' => 'required_if:expenseType,==,salary|required_without:vehicleId|exists:staff,id',
            'amount' => 'required', 
        ]);
		if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
        $data= $request->all();
        $user = Auth::user(); 
        $data['userId']=$user['id'];
    	expense::create($data);
    	return response()->json(['success','Expense Added Sucessfully'], $this-> successStatus); 
    }


// GET ALL EXPENSE
    public function getExpense(Request $request){
    	$user = Auth::user(); 
    	return  expense::where('userId',$user['id'])->get();
    }

// GET 1 EXPENSE DATA
    public function editExpense(expense $id){
        return response()->json(['success',$id], $this-> successStatus); 
        
    }

// UPDATE EXPENSE DATA
    public function updateExpense(Request $request,$id){
    	$validator = Validator::make($request->all(), [ 
            'date' => 'required|date', 
            'expenseType' => 'required|in:diesel,salary,extras,checkPost,Toll,bridge,partsCost', 
            'vehicleId' => 'required_if:expenseType,==,diesel|required_without:staffId|exists:vehicles,id', 
            'quantity'=>'required_if:expenseType,==,diesel',
            'staffId' => 'required_if:expenseType,==,salary|required_without:vehicleId|exists:staff,id',
            'amount' => 'required', 
        ]);
		if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
    	 $data = expense::findOrfail($id);
    	 $data ->date =  request('date');
    	 $data ->expenseType =  request('expenseType');
    	 $data ->vehicleId =  request('vehicleId');
    	 $data ->quantity =  request('quantity');
    	 $data ->staffId =  request('staffId');
    	 $data ->amount =  request('amount');
    	 $data ->discription =  request('discription');
    	if ($data->save()) {
    		return response()->json(['success','Expense Updated Sucessfully'], $this-> successStatus); 
    	}else{
    		return response()->json(['error'], $this-> successStatus); 
    	}
    }

// DELETE EXPENSE DATA
    public function deleteExpense($id){
    	expense::findOrfail($id);
    	if (expense::where('id', $id)->delete()) {
    		return response()->json(['success','Expense Deleted Sucessfully'], $this-> successStatus); 
    	}else{
    		return response()->json(['error'], $this-> successStatus); 
    	}
    }
}
