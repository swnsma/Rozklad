<?php
/**
 * Created by PhpStorm.
 * User: Таня
 * Date: 22.01.2015
 * Time: 23:56
 */
class Magic_Object_Exception extends Exception {
    public function __construct(){
        parent::__construct();
    }
}
abstract class Magic_Object
{
    protected $array_var = Array();

    public function __construct()
    {

    }
    public function __call($method, $a)
    {
        $type=substr($method,0,3);
        $name=substr($method,3);
        switch($type){
            case 'get':
                if (isset($this->array_var[$name]))
                {
                    return $this->array_var[$name];
                }
                break;
            case 'set':
                if(count($a)==0){
                    break;
                }
                if(count($a)==1){
                    $this->array_var[$name] = $a[0];
                }else{
                    $this->array_var[$name] = $a;
                }
                break;
            case 'uns':
                if (isset($this->array_var[$name]))
                {
                    unset($this->array_var[$name]);
                }
                break;
            case 'has':
                if (isset($this->array_var[$name]))
                {
                    return true;
                }
                else{
                    return false;
                }
                break;
            default:
                throw new Magic_Object_Exception('MagicObjectExeption');
        }
    }

    public function  getProperty(){
        return $this->array_var;
    }
}

?>