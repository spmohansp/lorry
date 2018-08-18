<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User; 
use App\verifyOtp; 
use Illuminate\Support\Facades\Auth; 
use Validator;
use DB;

class UserController extends Controller
{
    public $successStatus = 200;
// REGISTER USER
	public function register(Request $request) { 
        $validator = Validator::make($request->all(), [ 
            'transportName' => 'required', 
            'mobile' => 'required|unique:users|min:10|max:10', 
            'address' => 'required', 
        ]);
		if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
		$input = $request->all(); 
		$user = User::create($input); 
		$success['token'] =  $user->createToken('Mohan')-> accessToken; 
		$success['transportName'] =  $user->transportName;
		return response()->json(['success'=>$success], $this-> successStatus); 
    }

// LOGIN USER
	public function login(Request $request){ 
		$validator = Validator::make($request->all(), [ 
            'mobile' => 'required|min:10|max:10', 
        ]);
		if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
		// $success['otp'] = rand(1000, 9999);  //generate random otp number
		 $success['otp']=1234;
		 $success['mobile']=request('mobile');
		 $finalData['mobile']=request('mobile');
		 verifyOtp::create($success); 
		return response()->json(['success' => $success], $this-> successStatus); 
		// return response()->json(['success' => $finalData], $this-> successStatus); 
	}

// VALIDATE OTP
	public function validateLogin(Request $request) {
		$otpData = verifyOtp::where('mobile',request('mobile'))->latest()->first();
		if ($otpData['otp']==request('otp')) {
			$userData= user::where('mobile',request('mobile'))->first();
			if (!empty($userData)) {
				$success['token']=$userData->createToken('Mohan')-> accessToken; 
				$success['transportName'] =  $userData->transportName;
				verifyOtp::where('mobile',request('mobile'))->latest()->first()->delete();
				return response()->json(['success' => $success], $this-> successStatus);
			}else{
				$success['status']="User Not Register Yet";
				$success['mobile']=request('mobile');
				verifyOtp::where('mobile',request('mobile'))->latest()->first()->delete();
				return response()->json(['success'=>$success], $this-> successStatus); 
			}
		}else{
			return response()->json(['error'=>'Enter Valid OTP'], 401); 
		}
	}

// LOGOUT
	public function logout(Request $request){
		if (Auth::check()) {
	        Auth::user()->token()->revoke();
	        return response()->json(['success' =>'logout_success'],200); 
	    }else{
	        return response()->json(['error' =>'api.something_went_wrong'], 500);
	    }

	}

// GET ALL USER DETAIL TEST
    public function details() { 
        $user = Auth::user(); 
        return response()->json(['success' => $user], $this-> successStatus); 
    }
}
