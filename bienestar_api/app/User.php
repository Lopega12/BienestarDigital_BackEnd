<?php

namespace App;

use App\Notifications\PasswordResetNotification;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','api_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /***
     * Notificación personalizada para el envio de email de recuperar la contraseña
     * @param string $token
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordResetNotification($token));
    }

    public function apps_user(){
        return $this->belongsToMany('App\App','users_have_apps')->
        withPivot(['action','date','latitude','longitude'])->withTimestamps();
    }

    public function apps_restrinctions(){
        return $this->belongsToMany('App\App','users_restrict_apps')->
        withPivot(['max_use_time','start_time','finish_time'])->withTimestamps();
    }

    public function getDatesFromApps(){
        return $this->belongsToMany('App\App','users_have_apps')->
        withPivot('action')->
        selectRaw('DATE(date) as date_groups,date')->
        withTimestamps();
    }

    public function getLocation(){
        return $this->belongsToMany('App\App','users_have_apps')->
        withPivot(['name','date','longitude','latitude'])->
        select(['name','date','longitude','latitude'])->
        withTimestamps();
    }




}
