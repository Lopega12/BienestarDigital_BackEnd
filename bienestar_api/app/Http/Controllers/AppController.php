<?php

namespace App\Http\Controllers;

use App\App;
use App\Helpers\TimeCalculator;
use App\Restrinction;
use Cassandra\Time;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppController extends Controller
{
    public function getAllApps(){
        $response = Array('code'=>200,'response'=>null);
        $response['response'] = App::all(['id','logo','name_app']);
        return response()->json($response['response'],$response['code']);
    }

    /**
     * Obtencion de todas las restrinciones que tiene un usuario
     * @param Request $r
     * @return \Illuminate\Http\JsonResponse
     */
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

    public function getStatsApps(Request $r){

        $user = $r->user();
        //$apps = App::all();
        $apps = App::select('name_app')->get();
        $times_apps_average = array();

        foreach ($apps as $app) {
           // var_dump($app->name_app);
            $listApps = $user->apps_user()->where('name_app','=',$app->name_app)->get();
            print_r($listApps->filliable);
           // $timeCalc = new TimeCalculator($listApps);
            //$total_time_usage_seconds = $timeCalc->totalHours();
        }
        //return response()->json($apps,200);
    }



}
