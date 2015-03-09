<?php
class Base_Install extends Model
{
    public function __construct()
    {
        $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, 0);
        $currentVersion="0";
        $path=DOC_ROOT.'SQL/install';
        $version=0;
        $query="";
        if(file_exists($path."/version.txt")) {
            $currentVersion = file_get_contents($path."/version.txt");
            $version=$currentVersion;
        }else{
            $query = file_get_contents("SQL/install/drop.sql");
            $this->db->exec($query);
            $query="";
        }
        foreach (glob($path.'/install_*.sql') as $filename){
            if(preg_replace("/[^0-9]/", "", $filename)>$currentVersion){
                $version++;
                $query= $query.file_get_contents($filename);
            }
        }
        if($version!=$currentVersion){
            try{
            file_put_contents($path."/version.txt",$version);
            $this->db->exec($query);
            } catch(PDOException $e) {
                echo 'Error';
            }
        }
        $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, 1);
    }
}