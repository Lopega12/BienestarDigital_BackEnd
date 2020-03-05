<?php

namespace App\Http\Controllers;

use App\Restrinction;
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
}
