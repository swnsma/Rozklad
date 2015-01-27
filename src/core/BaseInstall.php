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
}