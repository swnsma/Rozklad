<?php

abstract class Response
{
    public  $string = '';

    public function __construct($string)
    {
        $this->string=$string;
    }

    public function __clone() {}

    public function input($string)
    {
        $this->string = $this->string . $string;
    }

    public function output() {
        echo $this->string;
        $this->string='';
    }
}
?>