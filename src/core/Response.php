<?php
/**
 * Created by PhpStorm.
 * User: Таня
 * Date: 22.01.2015
 * Time: 23:56
 */
abstract class Response{
    public  $string = '';
    public function __construct($string){
        $this->string=$string;
    }
    public function __clone(){

    }
    public function input($string){
        $this->string = $this->string . $string;
    }
    public function output(){
        echo $this->string;
        $this->string='';
    }
}
?>