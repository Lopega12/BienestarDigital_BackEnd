<?php

namespace App\Http\Controllers;

use App\App;
use App\Helpers\AppRestrictionManager;
use App\Helpers\TimeCalculator;
use App\Helpers\TimeStorageApp;
use App\Restrinction;
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
     * Eliminar restrinciones
     * @param Request $r
     * @param $restrinction
     * @return \Illuminate\Http\JsonResponse
     */
    public function drop_restrinctions(Request $r,$restrinction){
        try{

          $table = DB::table('users_restrict_apps')->where('id',$restrinction)->delete();
          return response()->json($table,200);
        }catch (\Exception $e){
            return response()->json($e->getMessage(),500);
        }
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
        $date_apps_per_day = [];
        foreach($apps_entries_by_date as $key => $row){
            $timeCalculator = new TimeCalculator($row);
            $total_use_in_seconds = $timeCalculator->totalHours();
            $total_use_time = Carbon::createFromTimestampUTC($total_use_in_seconds)->toTimeString();
            $date_apps_per_day[$key]= $total_use_time;
            
        }
        return response()->json($date_apps_per_day,200);
    }

    /**
     * Obtener la posiciones de las apps utilizadas
     * @param Request $r
     * @return \Illuminate\Http\JsonResponse
     */
public function get_apps_location(Request $r){
    $user = $r->user();
    $apps_names = App::select('name_app')->get();
    $apps_coordinates = [];
    $apps_coordinates_groups = [];

    foreach ($apps_names as $app_name)
    {
        $app_entry = $user->apps_user()->where('name_app', '=', $app_name["name_app"])->latest('date')->first();
        $app_time_storage = new TimeStorageApp();
        $apps_coordinates[] = $app_time_storage->create()->set_coordinates($app_entry->name_app, $app_entry->pivot->latitude, $app_entry->pivot->longitude);

    }

    foreach ($apps_coordinates as $app_coordinates_entry)
    {
        $is_found = false;

        foreach ($apps_coordinates_groups as $new_array_line)
        {
            if($app_coordinates_entry->latitude == $new_array_line->latitude && $app_coordinates_entry->longitude == $new_array_line->longitude){

                $new_array_line->name .= " " . $app_coordinates_entry->name;
                $is_found = true;
                break;

            }

        }

        if($is_found == false){

            $apps_coordinates_groups[] = $app_coordinates_entry;

        }

    }

    return response()->json($apps_coordinates_groups,200);
}

    /**
     * Obtencion de las estadisticas de las apps dada una fecha determinada
     * @param Request $r
     * @param $date
     * @return \Illuminate\Http\JsonResponse
     */
public function getUseAppsByRange(Request $r,$date){
    $user = $r->user();
    $apps = App::all();
    $apps_ranges = [];

    foreach ($apps as $app)
    {
        $app_initial_range = DB::table('users_have_apps')
            ->where('user_id', '=', $user->id)
            ->where('app_id', '=', $app->id)
            ->whereDate('date', '=', $date)
            ->where('action', '=', 'opens')
            ->first();

        $app_finish_range = DB::table('users_have_apps')
            ->where('user_id', '=', $user->id)
            ->where('app_id', '=', $app->id)
            ->whereDate('date', '=', $date)
            ->where('action', '=', 'closes')
            ->orderBy('date', 'desc')
            ->first();

        $app_opens_no_closes = DB::table('users_have_apps')
            ->where('user_id', '=', $user->id)
            ->where('app_id', '=', $app->id)
            ->whereDate('date', '=', $date)
            ->where('action', '=', 'opens')
            ->orderBy('date', 'desc')
            ->first();

        if($app_initial_range == NULL && $app_finish_range == NULL && $app_opens_no_closes == NULL)
        {
            $apps_ranges[] = new Restrinction($app->name_app, "Sin tiempo de uso", "Sin tiempo de uso", "Sin tiempo de uso");

        }else if($app_initial_range == NULL && $app_opens_no_closes == NULL)
        {
            $app_finish_range_hour = \Carbon\Carbon::parse($app_finish_range->date)->format('H:i:s');
            $app_entries = $user->apps_user()->where('name_app', '=', $app->name_app)->whereDate('date', '=', $date)->get();
            $app_time_calculator = new TimeCalculator($app_entries);
            $total_usage_time_in_seconds = $app_time_calculator->totalHours();
            $total_usage_time = Carbon::createFromTimestampUTC($total_usage_time_in_seconds)->toTimeString();
            $apps_user_ranges[] = new Restrinction($app->name_app, "00:00:00", $app_finish_range_hour, $total_usage_time);

        }else{

            if($app_finish_range->date < $app_opens_no_closes->date){

                $app_initial_range_hour = Carbon::parse($app_initial_range->date)->format('H:i:s');
                $app_entries = $user->apps_user()->where('name_app', '=', $app->name_app)->whereDate('date', '=', $date)->get();
                $app_time_calculator = new TimeCalculator($app_entries);
                $total_usage_time_in_seconds = $app_time_calculator->totalHours();
                $total_usage_time = Carbon::createFromTimestampUTC($total_usage_time_in_seconds)->toTimeString();
                $apps_user_ranges[] = new Restrinction($app->name_app, $app_initial_range_hour, "00:00:00", $total_usage_time);

            }else{

                $app_initial_range_hour = Carbon::parse($app_initial_range->date)->format('H:i:s');
                $app_finish_range_hour = Carbon::parse($app_finish_range->date)->format('H:i:s');
                $app_entries = $user->apps_user()->where('name_app', '=', $app->name_app)->whereDate('date', '=', $date)->get();
                $app_time_calculator = new TimeCalculator($app_entries);
                $total_usage_time_in_seconds = $app_time_calculator->totalHours();
                $total_usage_time = Carbon::createFromTimestampUTC($total_usage_time_in_seconds)->toTimeString();
                $apps_user_ranges[] = new Restrinction($app->name_app, $app_initial_range_hour, $app_finish_range_hour, $total_usage_time);

            }
        }
    }

    return response()->json(

        $apps_user_ranges

        , 200);
}

    /**
     * Obtener los detalles de la app seleccionada
     * @param Request $r
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
public function details_app(Request $r,$id){
    try{
        $user = $r->user();
        $app = $user->apps_user()->where('id','=',$id)->first();
        return response()->json(['logo' => $app->logo, 'name'=>$app->name_app],200);
    }catch (\Exception $e){
        return response()->json($e->getMessage(),500);
    }
}
}

