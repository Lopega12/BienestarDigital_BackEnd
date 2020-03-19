<?php

namespace App\Http\Controllers;

use App\Restrinction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /*public function create_restrinction(Request $r){
        $request_user = $r->user;
        if(isset($r->user,$r->id_app)){
            //Pendiente
        }
    }*/
    /**
     * Guardar restrinciones en base de datos
     * @param Request $r peticion post con time_finish,start_time y maxTime
     * @param $id_app parametro pasado por url
     * @return \Illuminate\Http\JsonResponse
     */
    public function save_restriction(Request $r,$id_app){
        $restrinction = new Restrinction($id_app,$r->time_finish,$r->start_time,$r->maxTime);
        $user = $r->user();
            $app = $user->apps_restrinctions->where('id',$restrinction->getIdApp())->first();
            if(!is_null($app)){
                try{
                    $app->pivot->max_use_time = $restrinction->getMaxTimeUse();
                    $app->pivot->start_time = $restrinction->getStartTime();
                    $app->pivot->finish_time = $restrinction->getStartFinish();
                    $app->save();
                }catch(\Exception $e){
                    return response()->json($e->getMessage(),500);
                }
            }else{
                try{
                    $user->apps_restrinctions()->attach($restrinction->getIdApp(),[
                        'max_use_time' => $restrinction->getMaxTimeUse(),
                        'start_time' => $restrinction->getStartTime(),
                        'finish_time' => $restrinction->getStartFinish()
                    ]);
                }catch(\Exception $e){
                    return response()->json($e->getMessage(),500);
                }

            }

        return response()->json("ok",200);
    }

    /**
     * Tiempo de uso total
     */
    public function use_time_apps(Request $r,$id){
        $user = $r->user();
        $app_entries = $user->apps_user()->wherePivot('app_id', $id)->get();
        $app_entry = $user->apps_user->wherePivot('app_id', $id)->first();
        $app_entries_lenght = count($app_entries);
        $total_time_in_seconds = 0;

        for ($x = 0; $x <= $app_entries_lenght - 1; $x++) {

            $have_both_hours = false;

            if($app_entries[$x]->pivot->event == "opens")
            {
                $from_hour = Carbon::createFromFormat('Y-m-d H:i:s', $app_entries[$x]->pivot->date);

            }else{

                $to_hour = Carbon::createFromFormat('Y-m-d H:i:s', $app_entries[$x]->pivot->date);
                $have_both_hours = true;

            }

            if($have_both_hours)
            {
                $total_time_in_seconds += $from_hour->diffInSeconds($to_hour);

            }
        }

        $total_usage_time = Carbon::createFromTimestampUTC($total_time_in_seconds)->toTimeString();

        return response()->json([

            "app_name" => $app_entry->name,
            "total_usage_time" => $total_usage_time,

        ],200);
    }
}
