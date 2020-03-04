<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function create_restrinction(Request $r){
        $request_user = $r->user;
        if(isset($r->user,$r->id_app)){
            //Pendiente
        }
    }
}
