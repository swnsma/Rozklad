<?php

class Base_Install{
    private function  __construct(){
    }
    public static function DesolationBase(){
        $f= fopen("SQL/install/drop.sql", "r");
        $DBH=DataBase::getInstance()->DB();
        do{
            $query=fgets($f, 10000);
            $DBH->query($query);
        }while($query);
    }
    public static function Run(){
        $buff="0";
        $path='SQL/install';
        $version=0;
        $query="";
        if(file_exists($path."/version.txt")){
            $file=fopen($path."/version.txt", 'r+');
            $buff=fgets($file, 10);
            fclose($file);
        }else{
            Base_Install::DesolationBase();
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
                //переделать
                $DBH=DataBase::getInstance()->DB();
                do{
                    $buff++;
                    $file=fopen($path.'/install_'.$buff.'.sql', 'r'); print_r($buff);
                    while($buff_query=fgets($file, 10000)){
                        echo $buff_query;
                        echo"<br/>";
                        $DBH->query($buff_query);
                    }
                    print_r($query);
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
        $f= fopen("SQL/install/dummy_data.sql", "r");
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
            $query=fgets($f, 10000);
            $DBH->query($query);
        }while($query);
    }
}