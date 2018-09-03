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
        $finalData['status']='success';
    	$finalData['vehicles'] = vehicle::select("id","modelNumber","vehicleNumber")->where('userId',$user['id'])->get();
    	$finalData['staffs'] = staff::select('id','name','type')->where('userId',$user['id'])->get();
    	return response()->json($finalData);
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
            foreach ($validator->errors()->toArray() as $value) {
                $errData['status']='error';
                $errData['message']=$value[0];
                return response()->json($errData);            
            }
        }
        $data= $request->all();
        $user = Auth::user(); 
        $data['userId']=$user['id'];
    	expense::create($data);
    	$success['status']='success';
        $success['message']='Expense Added Successfully';
        return response()->json($success);  
    }


// GET ALL EXPENSE
    public function getExpense(Request $request){
    	$user = Auth::user(); 
        $success['status']='success';
    	$success['expenses']=expense::select('id','date','expenseType','vehicleId','quantity','staffId','amount','discription')->where('userId',$user['id'])->get();
        return response()->json($success);
    }

// GET 1 EXPENSE DATA
    public function editExpense(expense $id){
        expense::findOrfail($id);
        $success['status']='success';
        $success['staff']=$id;
        return response()->json($success); 
        
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
            foreach ($validator->errors()->toArray() as $value) {
                $errData['status']='error';
                $errData['message']=$value[0];
                return response()->json($errData);            
            }
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
    		$success['status']='success';
            $success['message']='Expense Updated Sucessfully';
            return response()->json($success); 
        }else{
            $success['status']='error';
            $success['message']='Error on update';
            return response()->json($errDatas); 
        }
    }

// DELETE EXPENSE DATA
    public function deleteExpense($id){
    	expense::findOrfail($id);
    	if (expense::where('id', $id)->delete()) {
    		$success['status']='success';
            $success['message']='Expense Deleted Sucessfully';
            return response()->json($success); 
        }else{
            $success['status']='error';
            $success['message']='Error on Delete';
            return response()->json($errDatas);  
        }
    }
}
