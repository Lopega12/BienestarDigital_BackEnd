<?php


namespace App\Helpers;


use Illuminate\Support\Carbon;

class TimeCalculator
{
    private $apps,$numApps;
    private static $formatGeneral = 'Y-m-d H:i:s';
    private static $formatDate = 'Y-m-d';
    public function __construct($apps)
    {
        $this->apps = $apps;
        $this->numApps = count($this->apps);
    }

    public function totalHours(){
        $total_seconds = 0;
       // print_r($this->apps);
        /*if($this->apps[0]->pivot_action == "closes"){
            $date_formated = Carbon::createFromFormat(self::$formatGeneral,$this->apps[0]->pivot_date)->format(self::$formatDate);
            //Fecha sacada de tabla pero a las 00:00:00 significando asi el fin del dia//
            $date_formated_midnight = Carbon::parse($date_formated. '00:00:00');
            $date_hour =Carbon::createFromFormat(self::$formatGeneral,$this->apps[0]->pivot_date);
            //Diferencia de segundos entre la
            $diff_from_midnight_seconds = $date_formated_midnight->diffInSeconds($date_hour);
        }*/

    }
}
