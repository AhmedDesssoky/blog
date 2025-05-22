<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register (RegisterRequest $request){
        $user = User::Create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        $data =[
           'token' => $user->createToken('apiCourse')->plainTextToken,
           'name' => $user->name,
           'email' => $user->email,

        ];
        return ApiResponse::sendResponse(201,'Account Created Successfully',$data);
    }

    public function login(LoginRequest $request){

        if(Auth::attempt(['email'=>$request->email , 'password'=>$request->password])){
            $user= Auth::user();
            $data= [
                'token' => $user->createToken('api')->plainTextToken,
                'name' => $user->name,
                'email' => $user->email,
            ];
            return ApiResponse::sendResponse(200,'User Logged In Successfully',$data);

        }else{
            return ApiResponse::sendResponse(401,"This Credentials does't exits",[]);

        }



    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return ApiResponse::sendResponse(200,'Logged out Successfully',[]);
    }

}
