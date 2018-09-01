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
            'mobile' => 'required|unique:users|min:10|max:10', 
            'transportName' => 'required', 
            'address' => 'required', 
        ]);
		if ($validator->fails()) { 
            foreach ($validator->errors()->toArray() as $value) {
                // $errData['message'][]=$value[0];
                $errData['status']='error';
                $errData['message']=$value[0];
            	return response()->json($errData);            
            }
        }
		$input = $request->all(); 
		$user = User::create($input); 
		$success['status']='success';
		$success['token'] =  $user->createToken('Mohan')-> accessToken; 
		$success['transportName'] =  $user->transportName;
		return response()->json($success, $this-> successStatus); 
    }

// LOGIN USER
	public function login(Request $request){ 
		$validator = Validator::make($request->all(), [ 
            'mobile' => 'required|min:10|max:10', 
        ]);
		if ($validator->fails()) { 
            foreach ($validator->errors()->toArray() as $value) {
               	$errData['status']='error';
                $errData['message']=$value[0];
            	return response()->json($errData);            
            }
        }
		// $success['otp'] = rand(1000, 9999);  //generate random otp number
		$success['status']='success';
		$success['mobile']=request('mobile');
		$success['otp']=1234;
		$finalData['status']='success';
		$finalData['mobile']=request('mobile');
		verifyOtp::create($success); 
		return response()->json( $success, $this-> successStatus); 
		// return response()->json(['success' => $finalData], $this-> successStatus); 
	}

// VALIDATE OTP
	public function validateLogin(Request $request) {
		$validator = Validator::make($request->all(), [ 
            'otp' => 'required', 
            'mobile' => 'required|min:10|max:10', 
        ]);
		if ($validator->fails()) { 
            foreach ($validator->errors()->toArray() as $value) {
               	$errData['status']='error';
                $errData['message']=$value[0];
            	return response()->json($errData);            
            }
        }

		$otpData = verifyOtp::where('mobile',request('mobile'))->latest()->first();
		if ($otpData['otp']==request('otp')) {
			$userData= user::where('mobile',request('mobile'))->first();
			if (!empty($userData)) {
				$success['status']='success';
				$success['token']=$userData->createToken('Mohan')-> accessToken; 
				$success['transportName'] =  $userData->transportName;
				verifyOtp::where('mobile',request('mobile'))->latest()->first()->delete();
				return response()->json($success, $this-> successStatus);
			}else{
				$success['status']='success';
				$success['message']="User Not Register Yet";
				$success['mobile']=request('mobile');
				verifyOtp::where('mobile',request('mobile'))->latest()->first()->delete();
				return response()->json($success, $this-> successStatus); 
			}
		}else{
			$error['status']='success';
			$error['message']='Enter Valid OTP';
			return response()->json($error); 
		}
	}

// LOGOUT
	public function logout(Request $request){
		if (Auth::check()) {
	        Auth::user()->token()->revoke();
	        $success['status']='success';
	        $success['message']='Logout Success';
	        return response()->json($success,200); 
	    }else{
	    	$error['message']='api.something_went_wrong';
	        return response()->json($error, 500);
	    }

	}

// GET ALL USER DETAIL TEST
    public function details() { 
        $user = Auth::user(); 
        return response()->json(['success' => $user], $this-> successStatus); 
    }
}
