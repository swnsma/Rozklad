<?php

class RealDelLesson
{
    private static $instance = null;
    private $db;

    private function __construct()
    {
        $this->db = new PDO('sqlite:' . get_include_path() . 'SQL/data/rozklad.sqlite');
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function run()
    {
        $start = date("2014-01-01");
        $start = new DateTime($start);
        $start=$start->format('Y-m-d H:i:s');
        $var =date("Y-m-d H:i:s");
        $var1=new DateTime($var);
        $var1=$var1->modify("-1 day");
        $var1=$var1->format('Y-m-d H:i:s');
        try {
            $this->db->query("DELETE FROM 'lesson' WHERE  update_date BETWEEN '$start' AND '$var1' AND status='2'");
        } catch(PDOException $e) {
            echo $e->getMessage();
            return null;
        }
    }

    static public function getInstance()
    {
        if(is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __clone() {}
}