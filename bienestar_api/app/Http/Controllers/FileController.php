<?php

namespace App\Http\Controllers;

use App\App;
use App\Helpers\Ficheros\Fichero;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class FileController extends Controller
{

    public function listApps($user){
        $response = array('state' => 500, 'response'=>'');

    }
    public function post(Request $r){
         $fileController = Fichero::getInstance();
       // var_dump($r->fichero);
        $lines = $fileController->readFile($r->fichero);
        $user = $r->user();
        //var_dump($lines);
        try {
            foreach ($lines as $key => $line){
                $app = App::where('name_app',$line[1])->first();
                if(!is_null($app)) {
                    //Attach sirve para insertar en la tabla intermedia, sin tener que crear el modelo//
                    $user->apps_user()->attach($app->id, [
                        'date' => $line[0],
                        'action' => $line[2],
                        'latitude' => $line[3],
                        'longitude' => $line[4],
                    ]);

                }
            }
            return response()->json("ok", 200);

        }catch (Exception $e){
            return response()->json($e->getMessage(),500);
        }

    }

    /**
     * Carga de nombres de aplicaciones desde fichero csv
     * @param Request $r
     * @return \Illuminate\Http\JsonResponse
     */
    public function insertApp(Request $r){
        $fileController = Fichero::getInstance();
        $lines = $fileController->readFile($r->fichero);
        foreach ($lines as $key=>$line){
            $repeated = App::where('name_app',$line[0])->first();
            if(is_null($repeated)){
                try{
                    $app = new App();
                    $app->name_app = $line[0];
                    $app->logo = $line[1];
                    $app->save();
                }catch (Exception $e){
                    return response()->json($e->getMessage(),500);
                }
            }
        }
    }
}
