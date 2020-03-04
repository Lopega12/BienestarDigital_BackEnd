<?php

namespace App\Http\Controllers;

use App\Helpers\Ficheros\Fichero;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function listApps($user){
        $response = array('state' => 500, 'response'=>'');

    }
    public function post(Request $r){
        $fileController = Fichero::getInstance();
       // var_dump($r->fichero);
        $fileController->readFile($r->fichero);


        die();
        return response()->json("hola");
    }
}
