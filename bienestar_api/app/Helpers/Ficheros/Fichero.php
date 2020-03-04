<?php
//app/Helpers/Ficheros/Fichero.php
namespace App\Helpers\Ficheros;
class Fichero{
    private static $instance;
     private function __construct()
     {

     }
     public static function getInstance(){
         if(!self::$instance instanceof self){
             self::$instance = new self();
         }
         return self::$instance;
     }

    /**
     * @param $file This parameter should be contain a string of all lines in the file.
     */
     public function readFile($file){
        // $file = explode("'\n'",$file);
         if(isset($r->fichero->pathName)){
            //aqui va lectura de fichero desde php
         }else{
             $lines = Array();
             $file = explode("\n",$file);
             $header = array_shift($file);
             foreach ($file as $line){
                 array_push($lines,str_getcsv($line));
             }

         }

         return $lines;
     }
}
