<?php

class CommentModel extends Model {
    function __construct() {
        parent::__construct();
    }

    public function index($id){
        $arr=$this->selectTree($id,0);
        return $arr;
    }

    private function selectTree($lesson_id,$pid){
        try{
            $query=<<<Q
select
 c.id as com_id,
 c.pid,
 c.lesson_id,
 c.date,
 c.text,
 u.id as user_id,
 u.name,
 u.surname,
 u.fb_id,
 u.gm_id
 from comment as c
 inner join user as u on c.user_id=u.id
  where c.lesson_id='$lesson_id' and c.pid='$pid' and status = 1
Q;

            $arr=$this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
            for($i=0;$i<count($arr);$i++){
                $id_child=$arr[$i]['com_id'];
                $children=$this->selectTree($lesson_id,$id_child);
                if(count($children)===0){
                    $children=NULL;
                }
                $arr[$i]['CHILDREN']=$children;
                if($arr[$i]["gm_id"]){
                    $arr[$i]["gm_photo"]=$this->getGooglePhotoByGId($arr[$i]["gm_id"]);
                }

            }
            return $arr;
        }
        catch(PDOException $e) {
            echo $e->getMessage();
            return null;
        }
    }

    private function getGooglePhotoByGId($g_id){
        $apiKey = "AIzaSyBZxhxAn-PyWms-8yYb33kiRgO4cFi8o1Y";
        $url = "https://www.googleapis.com/plus/v1/people/$g_id?fields=image%2Furl&key=$apiKey";
        $res =file_get_contents($url);
        $link = json_decode($res)->image->url;
        return $link;
    }

    public function addComment($data){

        $pid=$data['pid'];
        $user_id=Session::get('id');
        $text=$data['text'];
        $date=$data['date'];
        $lesson_id=$data['lesson_id'];
//
        try{

            $this->db->query("insert into comment (pid ,user_id,text,date,lesson_id,status) values('$pid','$user_id','$text','$date','$lesson_id',1)");
            $last=$this->db->lastInsertId();
            $query=<<<Q
select
 c.id as com_id,
 c.pid,
 c.lesson_id,
 c.date,
 c.text,
 u.id as user_id,
 u.name,
 u.surname,
 u.fb_id,
 u.gm_id
 from comment as c
 inner join user as u on c.user_id=u.id
  where c.id='$last' and status = 1
Q;
            $arr=$this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
            if($arr[0]["gm_id"]){
                $arr[0]["gm_photo"]=$this->getGooglePhotoByGId($arr[0]["gm_id"]);
            }
            return $arr[0];
        }
        catch(PDOException $e) {
            echo $e->getMessage();
            return null;
        }
    }
    public function removeComment($id){

        try{
            $this->removeChildren($id);
            $this->db->query("update 'comment' set status=2 where id='$id'");
            return 1;
        }
        catch(PDOException $e) {
            echo $e->getMessage();
            return null;
        }
    }
    public function removeChildren($pid){
        try{
            $query=<<<Q
select
 id
 from comment
 where pid='$pid' and status = 1
Q;
            $arr=$this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
            for($i=0;$i<count($arr);$i++){
                $this->removeChildren($arr[$i]['id']);
            }
            $query1=<<<Q
update comment set status = 2 where
pid='$pid'
Q;
            $this->db->query($query1)->fetchAll(PDO::FETCH_ASSOC);
        }
        catch(PDOException $e) {
            echo $e->getMessage();
            return null;
        }
    }
}