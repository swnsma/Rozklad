<?php
include '../core/database.php';
function baseConfig(){
    $buff="0";
    $buff2="";
    $path='../SQL/install';
    $query="";
    $file="";
    $files=scandir($path);
    if(file_exists($path."/version.txt")){
        $file=fopen($path."/version.txt", 'r+');
        $buff=fgets($file, 10);
        fclose($file);
    }
    if(($buff2=preg_grep('/install_[0-9]*\.sql/',$files))){
        //ну что поделать, говнокодим.
        foreach($buff2 as $file_name);
        $buff2=preg_replace("/[^0-9]/", '',$file_name);
    }
    if($buff==$buff2){
        $file=fopen($path."/version.txt", 'w+');
        fputs($file, $buff2);
        fclose($file);
        $file=fopen($path.'/install_'.$buff2.'.sql', 'r');
        try{
        $DBH=new PDO("sqlite: ../SQL/data/rozklad.sqlite");
            while($buff=fgets($file, 1000)){
                if(preg_match('/DROP/', $buff)){
                    $DBH->query($buff);
                    if(count_chars($query)){
                        $DBH->query($query);
                        $query="";
                    }
                }else{
                    $query=$query.$buff;
                }
            }
            $DBH=null;
        }
        catch(PDOException $e) {
            print 'Error';
        }
    }
}