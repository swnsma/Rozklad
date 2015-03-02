<?php

class GreenElephant extends Controller
{
    public function getPhoto()
    {
        echo URL . 'public/img/ge/' . rand(1, 6) . '.png';
    }
}