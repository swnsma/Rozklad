<?php

class IndexModel extends Model
{
    public function example()
    {
        if ($this->check_user()) {
            print $this->getId() . ' - autorized';
        } else {
            print 'no autorized';
        }
    }
}