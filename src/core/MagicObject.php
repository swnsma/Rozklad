<?php

require_once DOC_ROOT . 'core/Response.php';

class Magic_Object_Exception extends Exception
{
    public function __construct()
    {
        parent::__construct();
    }
}

abstract class Magic_Object
{
    protected $array_var = Array();

    public function __call($method, $a)
    {
        $method= strtolower(preg_replace("/(.)([A-Z])/", "$1_$2", $method));
        $type=substr($method,0,3);
        $name=substr($method,4);
        switch($type){
            case 'get':
                if (isset($this->array_var[$name])) {
                    return $this->array_var[$name];
                }
                break;
            case 'set':
                if(count($a)==0) {
                    break;
                }
                if(count($a)==1) {
                    $this->array_var[$name] = $a[0];
                } else {
                    $this->array_var[$name] = $a;
                }
                break;
            case 'uns':
                if (isset($this->array_var[$name])) {
                    unset($this->array_var[$name]);
                }
                break;
            case 'has':
                if (isset($this->array_var[$name])) {
                    return true;
                } else {
                    return false;
                }
                break;
            default:
                echo 'invalid prefics';
        }
    }

    public function getProperty()
    {
        $var= &$this->array_var;
        return $var;
    }
}
