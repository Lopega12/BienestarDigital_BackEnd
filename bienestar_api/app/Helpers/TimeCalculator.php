<?php


namespace App\Helpers;
error_reporting(0);

use Illuminate\Support\Carbon;

class TimeCalculator
{
    private $apps, $numApps;
    private static $formatGeneral = 'Y-m-d H:i:s';
    private static $formatDate = 'Y-m-d';

    public function __construct($apps)
    {
        $this->apps = $apps;
        $this->numApps = count($this->apps);
    }

    public function totalHours()
    {
        $total_seconds = 0;
       //print_r($this->apps);
        if ($this->apps[0]->pivot->action == "closes") {
            //Inicializacion de variables//

            $time_diff_till_midnight = 0;
            $date_formated = Carbon::createFromFormat(self::$formatGeneral, $this->apps[0]->pivot->date)->format(self::$formatDate);
            //Fecha sacada de tabla pero a las 00:00:00 significando asi el fin del dia//
            $date_formated_midnight = Carbon::parse($date_formated . '00:00:00');
            $date_hour = Carbon::createFromFormat(self::$formatGeneral, $this->apps[0]->pivot->date);
            //Diferencia de segundos entre la
            $diff_from_midnight_seconds = $date_formated_midnight->diffInSeconds($date_hour);
            $total_seconds += $diff_from_midnight_seconds;
            for ($i = 1; $i <= $this->numApps - 1; $i++) {
                $iguales = true;
                if ($this->apps[$i]->pivot->action == 'opens') {
                    $from_hour = Carbon::createFromFormat('Y-m-d H:i:s', $this->apps[$i]->pivot->date);
                    $from_hour_format = Carbon::createFromFormat('Y-m-d H:i:s', $this->apps[$i]->pivot->date)->format('Y-m-d');
                    $from_hour_format_to_midnight = $from_hour_format . ' 23:59:59';
                    $today_to_midnight = Carbon::parse($from_hour_format_to_midnight);
                    $time_diff_till_midnight = $from_hour->diffInSeconds($today_to_midnight);
                    $total_seconds = $total_seconds + $time_diff_till_midnight;
                    $iguales = false;
                } else if ($this->apps[$i]->pivot->action == "closes") {
                    $total_seconds = $total_seconds - $time_diff_till_midnight;
                    $to_hour = Carbon::createFromFormat('Y-m-d H:i:s', $this->apps[$i]->pivot->date);
                    $iguales = true;
                }
                if ($iguales) {
                    $total_seconds += $from_hour->diffInSeconds($to_hour);
                }

            }
        } else {
            for ($i = 0; $i <= $this->numApps - 1; $i++) {
                $iguales = true;
                $time_diff_till_midnight = 0;
                if ($this->apps[$i]->pivot->action == "opens") {
                    $from_hour = Carbon::createFromFormat('Y-m-d H:i:s', $this->apps[$i]->pivot->date);
                    $from_hour_format = Carbon::createFromFormat('Y-m-d H:i:s', $this->apps[$i]->pivot->date)->format('Y-m-d');
                    $from_hour_format_to_midnight = $from_hour_format . ' 23:59:59';
                    $today_to_midnight = Carbon::parse($from_hour_format_to_midnight);
                    $time_diff_till_midnight = $from_hour->diffInSeconds($today_to_midnight);
                    $total_seconds = $total_seconds + $time_diff_till_midnight;
                    $iguales = false;

                } else if ($this->apps[$i]->pivot->action == "closes") {

                    $total_seconds = $total_seconds - $time_diff_till_midnight;
                    $to_hour = Carbon::createFromFormat('Y-m-d H:i:s', $this->apps[$i]->pivot->date);
                    $iguales = true;

                }

                if ($iguales) {

                    $total_seconds += $from_hour->diffInSeconds($to_hour);

                }
            }
        }

        return $total_usage_time = Carbon::createFromTimestampUTC($total_seconds)->secondsSinceMidnight();
    }
}
