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
        if($buff<$buff2){
            try{
                $DBH=DataBase::getInstance()->DB();
                do{
                    $buff++;
                    $file=fopen($path.'/install_'.$buff.'.sql', 'r');
                    while($buff_query=fgets($file, 10000)){
                        $DBH->query($buff_query);
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
        $f= fopen("SQL/install/dummy_data.sql", "r");
        $DBH=DataBase::getInstance()->DB();
        do{
            $query=fgets($f, 10000);
            $DBH->query($query);
        }while($query);
    }
}