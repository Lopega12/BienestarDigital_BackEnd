<?php

namespace App\Http\Controllers;

use App\App;
use App\Restrinction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppController extends Controller
{
    public function getAllApps(){
        $response = Array('code'=>200,'response'=>null);
        $response['response'] = App::all(['id','logo','name_app']);
        return response()->json($response['response'],$response['code']);
    }
    public function get_restrinctions(Request $r){
        $user = $r->user();
        $result = DB::table('users_restrict_apps')->
        where('user_id',$user->id)->
        get(['user_id','app_id','max_use_time','start_time','finish_time']);
        $restrinctions = Array();
        foreach ($result as $value){
            array_push($restrinctions, new Restrinction($value->app_id,$value->finish_time,$value->start_time,$value->max_use_time));
        }
        return response()->json($restrinctions,200);
    }
    


}
