<?php

class View {
    function __construct() {}

    public function render($name, $data) {
        require __DIR__ . '/../views/' . $name . '.php';
    }
}

?>