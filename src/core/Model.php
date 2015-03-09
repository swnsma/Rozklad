<?php
require_once DOC_ROOT . 'core/MagicObject.php';

abstract class Model extends Magic_Object
{
    protected $data=[];

    public function __get($name){

        if($name === "db"){

            if(isset($this->data['db'])&&!empty($this->data['db'])){
                return $this->data['db'];
            }
            else{
                $this->data['db'] = DataBase::getInstance()->DB();
                return $this->data['db'];
            }
        }
    }
}