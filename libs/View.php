<?php

class View {
    function __construct() {}

    public function render($name) {
        require 'views/' . $name . '.php';
    }
}


?>