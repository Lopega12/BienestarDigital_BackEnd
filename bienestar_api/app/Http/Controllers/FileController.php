<?php

namespace App\Http\Controllers;

use App\Helpers\Ficheros\Fichero;
use Illuminate\Http\Request;
use Helpers\Ficheros;

class FileController extends Controller
{

    public function listApps($user){
        $response = array('state' => 500, 'response'=>'');

    }
    public function post(Request $r){
       $fileController = Fichero::getInstance();
      var_dump($r->fichero);
        print_r($fileController->readFile($r->fichero));

        die();
        return response()->json("hola");
    }
}
