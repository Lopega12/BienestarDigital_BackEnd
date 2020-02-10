<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LoginUserController extends Controller
{
    /**
     * Login users in BienestarDigital
     * @param Request $r
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function login(Request $r)
    {
        $status = Array('code' => 404, 'info' => 'Not Found');
        $user = User::where('email', $r->email)->first();
        if (!empty($user) && Hash::check($r->password, $user->password)) {
            $status['code'] = 200;
            $status['info'] = $user;
            return $status;
            }else{
            $status['code'] = 403;
        }
        return response()->json($status);
    }

    /**
     * Register in app BienestarDigital
     * @param Request $r
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $r){
        $status = Array('code'=>400,'user'=>"");
        $user = User::where('email',$r->email)->first();

        if(!empty($user)){
            $status['code'] = 304;
        }else{
            $user_token = Str::random(60);
            $user = User::create([
                'name' =>$r->name,
                'email'=> $r->email,
                'password' => Hash::make($r->password),
                'api_token' =>hash('sha256',$user_token)
            ]);
            $status['code'] = 200;
            $status['user'] = [$user,$user_token];
        }
        return response()->json($status);
    }
    public function resetPassword(){

    }



}
