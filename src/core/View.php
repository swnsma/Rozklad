<?php

class View {
    function __construct() {}

    public function render($name) {
        require __DIR__ . '/../views/' . $name . '.php';
    }
}

?>