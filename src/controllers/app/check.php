<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 26.01.2015
 * Time: 19:33
 */
class Check extends Controller
{

   public $fbuser;
    public function __constructor(){
        $appId = '1536442079974268'; //Facebook App ID
        $appSecret = '1d75987fcb8f4d7abc1a34287f9601cf'; // Facebook App Secret
        $facebook = new Facebook(array(
            'appId' => $appId,
            'secret' => $appSecret,
        ));
        $fbuser = $facebook->getUser();
    }
    public function index(){
        print_r($this->fbuser);
    }
}
?>