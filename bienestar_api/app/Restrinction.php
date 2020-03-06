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
        $this->name_app = (!is_null(App::find($id_app)))? App::find($id_app)->name_app : "";
    }






}
