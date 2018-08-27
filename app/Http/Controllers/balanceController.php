<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use App\client; 
use App\staff;
use App\vehicle;
use App\entry;
use Validator;
use DB;

class balanceController extends Controller
{
     public function ClientIncomeBalance(Request $request,$id){
    	$user = Auth::user(); 
    	$entryBalanceData =entry::groupBy('vehicleId')->selectRaw('vehicleId, sum(balance) as balance')->where('userId',$user['id'])->Where('clientId', $id)->get();
    	// return $entryBalance = DB::table('entries')->where('userId',$user['id'])->Where('clientId', $id)->select('vehicleId',DB::raw('SUM(balance) as balanceAmount'))->groupBy('vehicleId')->get();   
    	foreach ($entryBalanceData as $key => $value) {
    		$data =  entry::find($value->vehicleId)->vechicle_number;
    		$balanceDatas[$key]['vehicleId']=$data['id'];
    		$balanceDatas[$key]['vehicleNumber']=$data['vehicleNumber'];
    		$balanceDatas[$key]['balance']=$value['balance'];
    	}
    	 return $balanceDatas;
    }
}
