<?php
require_once  'database.php';
class Base_Install{
    private function  __construct(){
    }
    public static function Run(){
        $buff="0";
        $path='../SQL/install';
        $version=0;
        $query="";
        if(file_exists($path."/version.txt")){
            $file=fopen($path."/version.txt", 'r+');
            $buff=fgets($file, 10);
            fclose($file);
        }
        $dir = opendir($path);
        while( $files=readdir($dir))
        {
            if(preg_match("/install_[0-9]*\.sql/", $files)){
                $buff2=preg_replace("/[^0-9]/", "", $files);
                if($version<$buff2){
                    $version=$buff2;
                }
            }
        }
        $buff2=$version;
        if($buff!=$buff2){
            try{
                $buff=0;
                //переделать
                $DBH=DataBase::getInstance()->DB();//new PDO("sqlite:".FILE."/../SQL/data/rozklad.sqlite");
                do{
                    $buff++;
                    $file=fopen($path.'/install_'.$buff2.'.sql', 'r');
                    while($buff_query=fgets($file, 1000)){
                        if(preg_match('/DROP/', $buff_query)||preg_match('/INSERT/', $buff_query)){
                            $DBH->query($buff_query);
                            if(count_chars($query)){
                                $DBH->query($query);
                                $query="";
                            }
                        }else{
                            $query=$query.$buff_query;
                        }
                    }
                    if($query){
                        $DBH->query($query);
                    }
                    fclose($file);
                }while($buff!=$buff2);
                $file=fopen($path."/version.txt", "w");
                fputs($file, $buff2);
                fclose($file);
            }
            catch(PDOException $e) {
                print 'Error';
            }
        }
    }
    public static function LoadDummy(){
        $f= fopen("../SQL/install/dummy_data.sql", "r");
        $DBH=DataBase::getInstance()->DB();
        //видалення данних
        /* $result = $DBH->query("SELECT * FROM groups");
         var_dump($result->fetchAll());
         $DBH->query("DELETE FROM user WHERE id>0");
         $DBH->query("DELETE FROM groups WHERE id>0");
         $DBH->query("DELETE FROM group_lesson WHERE lesson_id>0");
         $DBH->query("DELETE FROM student_group WHERE group_id>0");
         $DBH->query("DELETE FROM lesson WHERE id>0");*/
        do{
            $query=fgets($f, 1000);
            $DBH->query($query);
        }while($query);
    }
}