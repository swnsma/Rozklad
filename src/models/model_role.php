<?php
class Model_role extends Model {

    public $id;
    public $title;
    function __construct($select=false)
    {
        parent::__construct($select);
    }

    public function fieldsTable(){
        return array(

            'id' => 'Id',
            'title'=>'Title'

        );
    }
}