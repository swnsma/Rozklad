<?php
class Base_Install extends Model{
    public function __construct(){
        parent::__construct();
        $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, 0);
        $buff="0";
        $path='SQL/install';
        $version=0;
        if(file_exists($path."/version.txt")){
            $buff = file_get_contents($path."/version.txt");
        }else{
            $query = file_get_contents("SQL/install/drop.sql");
            $this->db->exec($query);
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
                do{
                    $buff++;
                    $query = file_get_contents($path.'/install_'.$buff.'.sql');
                    $this->db->exec($query);
        }while($buff!=$buff2);
         file_put_contents($path."/version.txt",$buff2 );
        }
         catch(PDOException $e) {
             print 'Error';
         }
     }
    $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, 1);
    }
}