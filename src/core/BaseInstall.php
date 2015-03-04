<?php
class Base_Install extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, 0);
        $currentVersion="0";
        $path=DOC_ROOT.'SQL/install';
        $version=0;
        if(file_exists($path."/version.txt")) {
            $currentVersion = file_get_contents($path."/version.txt");
        }else{
            $query = file_get_contents("SQL/install/drop.sql");
            $this->db->exec($query);
        }
        $dir = opendir($path);
        while( $files=readdir($dir)) {
            if(preg_match("/install_[0-9]*\.sql/", $files)) {
                $lastVersion=preg_replace("/[^0-9]/", "", $files);
                if($version<$lastVersion) {
                    $version=$lastVersion;
                }
            }
        }
        $lastVersion=$version;
        if($currentVersion<$lastVersion) {
            try {
                do {
                    $currentVersion++;
                    $query = file_get_contents($path.'/install_'.$currentVersion.'.sql');
                    $this->db->exec($query);
                } while($currentVersion!=$lastVersion);
                file_put_contents($path."/version.txt",$lastVersion );
            } catch(PDOException $e) {
                print 'Error';
            }
        }
        $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, 1);
    }
}