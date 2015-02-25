<?php
/**
 * Created by PhpStorm.
 * User: Саша
 * Date: 25.02.2015
 * Time: 12:12
 */

class GreenElephant extends Controller{
    public function getPhoto(){
        echo URL . 'public/img/ge/' . rand(1, 6) . '.png';
    }
}