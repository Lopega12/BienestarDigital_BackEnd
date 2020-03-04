<?php

namespace App\Http\Controllers;

use App\App;
use Illuminate\Http\Request;

class AppController extends Controller
{
    public function getAllApps(){
        $response = Array('code'=>200,'response'=>null);
        $response['response'] = App::all(['id','logo','name']);
        return response()->json($response['response'],$response['code']);
    }

}
