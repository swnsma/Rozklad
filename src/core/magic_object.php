<?php
/**
 * Created by PhpStorm.
 * User: Таня
 * Date: 22.01.2015
 * Time: 23:56
 */
abstract class MagicObject{
    protected $array_var= Array();
    protected $array_function= Array();
    public function __construct(){

    }
    public function __clone(){

    }
    public function __set($var, $val){
        $this->array_var[$var] = $val;
    }
    public function __get($var){
        if(isset($this->array_var[$var])){
            return $this->array_var[$var];
        }
    }
    public function __call($m, $a) {
        $this->array_function[$m] = $a;
    }
}
?>