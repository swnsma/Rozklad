<?php

class IndexModel extends Model {
    function __construct() {
        parent::__construct();
    }

    public function example() {
        if ($this->check_user()) {
            print $this->getId() . ' - autorized';
        } else {
            print 'no autorized';
        }
    }

}