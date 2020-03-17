<?php

namespace App\Http\Controllers;

use App\App;
use App\Helpers\TimeCalculator;
use App\Helpers\TimeStorageApp;
use App\Restrinction;
use Cassandra\Time;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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

    /**
     * Obtener todas las estadisticas de las apps media en dias, semanas y meses
     * @param Request $r
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStatsApps(Request $r){

        $user = $r->user();
        //$apps = App::all();
        $apps = App::select('name_app')->get();
        $times_apps_average = array();

        foreach ($apps as $app) {

           // var_dump($app->name_app);
            $listApps = $user->apps_user()->where('name_app','=',$app->name_app)->get();
           $timeCalc = new TimeCalculator($listApps);
            $total_time_usage_seconds = $timeCalc->totalHours();
            $total_usage_time = Carbon::createFromTimestampUTC($total_time_usage_seconds)->toTimeString();
            $total_usage_time_in_milliseconds  = $total_time_usage_seconds * 1000;

            $day_average = Carbon::createFromTimestampMs($total_usage_time_in_milliseconds / 365)->format('H:i:s.u');
            $week_average = Carbon::createFromTimestampMs($total_usage_time_in_milliseconds / 52)->format('H:i:s.u');
            $month_average = Carbon::createFromTimestampMs($total_usage_time_in_milliseconds / 12)->format('H:i:s.u');

            $apps_time_averages[] = new TimeStorageApp($app['name_app'], $total_usage_time, $day_average, $week_average, $month_average);
        }
        return response()->json($apps_time_averages,200);
    }

    /**
     * Tiempo Total dias anteriores de app especifica
     * @param Request $r
     */
    public function getUseTimeAppPerDay(Request $r,$id){
        $user = $r->user();
        $app_entries = $user->apps_user;
        $apps_entries_by_date = $app_entries->where('id',$id)->groupBy(function($element){
            $new_date = Carbon::parse($element->pivot->date);
            return $new_date->format('Y-m-d');
        });
        $date_apps_per_day = array();
        foreach($apps_entries_by_date as $key => $row){
            $timeCalculator = new TimeCalculator($row);
            $total_use_in_seconds = $timeCalculator->totalHours();
            $total_use_time = Carbon::createFromTimestampUTC($total_use_in_seconds)->toTimeString();
            //sigo por aqui
        }
    }


}
