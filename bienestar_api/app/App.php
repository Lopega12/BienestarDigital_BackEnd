<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class App extends Model
{
    public function users(){
        //Funcion pivot recupera campos de la tabla intermedia, en este caso seria la tabla intermedia entre Apps y Users.
        return $this->belongsToMany('App\User','users_have_apps')->
        withPivot(['action','date','longitude','latitude'])->as('app')->withTimestamps();
    }
    public function user_restrictions(){
        return $this->belongsToMany('App\App','users_restrict_apps')->
        withPivot(['max_use_time','start_time','finish_time'])->
        withTimestamps();
    }
}
