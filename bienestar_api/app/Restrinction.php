<?php

namespace App;

use Illuminate\Support\Facades\DB;

class Restrinction
{
    public $id_app,$start_time,$start_finish,$maxTimeUse;
    public $name_app;

    public function __construct($id_app,$start_finish,$start_time,$maxTimeUse)
    {
        $this->id_app = $id_app;
        $this->start_finish =$start_finish;
        $this->start_time = $start_time;
        $this->maxTimeUse = $maxTimeUse;
        $this->name_app = (!is_null(App::find($id_app)))? App::find($id_app)->get('name_app') : "";
    }

    /**
     * @return mixed
     */
    public function getIdApp()
    {
        return $this->id_app;
    }

    /**
     * @param mixed $id_app
     */
    public function setIdApp($id_app): void
    {
        $this->id_app = $id_app;
    }



    /**
     * @return mixed
     */
    public function getStartTime()
    {
        return $this->start_time;
    }

    /**
     * @param mixed $start_time
     */
    public function setStartTime($start_time): void
    {
        $this->start_time = $start_time;
    }

    /**
     * @return mixed
     */
    public function getStartFinish()
    {
        return $this->start_finish;
    }

    /**
     * @param mixed $start_finish
     */
    public function setStartFinish($start_finish): void
    {
        $this->start_finish = $start_finish;
    }

    /**
     * @return mixed
     */
    public function getMaxTimeUse()
    {
        return $this->maxTimeUse;
    }

    /**
     * @param mixed $maxTimeUse
     */
    public function setMaxTimeUse($maxTimeUse): void
    {
        $this->maxTimeUse = $maxTimeUse;
    }

    /**
     * @return string
     */
    public function getNameApp(): string
    {
        return $this->name_app;
    }
    /*public function setNameApp(){
        $this->name_app = (!is_null(App::find($this->id_app)))? App::find($this->id_app)->get('name_app') : "";
    }*/






}
