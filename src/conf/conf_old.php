<?php
require_once __DIR__ . '/../core/database.php';
class BaseInstall{
    private function  __construct(){
    }
    public static function Run(){
        $buff="0";
        $buff2="";
        $path='../SQL/install';
        $query="";
        if(file_exists($path."/version.txt")){
            $file=fopen($path."/version.txt", 'r+');
            $buff=fgets($file, 10);
            fclose($file);
        }
        if(file_exists($path."/last_version.txt")){
            $file=fopen($path."/last_version.txt", 'r+');
            $buff2=fgets($file, 10);
            fclose($file);
        }
        if($buff==$buff2){
            try{
                $buff=0;
                //переделать
                $DBH=DataBase::getInstance()->DB();//new PDO("sqlite:".DOCUMENT_ROOT."/../SQL/data/rozklad.sqlite");
                do{
                    $buff++;
                    $file=fopen($path.'/install_'.$buff2.'.sql', 'r');
                    while($buff_query=fgets($file, 1000)){
                        if(preg_match('/DROP/', $buff_query)){
                            $DBH->query($buff_query);
                            if(count_chars($query)){
                                $DBH->query($query);
                                $query="";
                            }
                        }else{
                            $query=$query.$buff_query;
                        }
                    }
                    fclose($file);
                }while($buff!=$buff2);
                $file=fopen($path."version.txt", "w");
                fputs($file, $buff2);
                fclose($file);
            }
            catch(PDOException $e) {
                print 'Error';
            }
        }
    }
}