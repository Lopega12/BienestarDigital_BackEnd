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
         $file = preg_split("'\n'",$file);
         $header = array_shift($file);
         return $file;
     }
}
