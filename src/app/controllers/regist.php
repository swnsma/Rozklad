<?php
class Regist extends Controller
{
    private $model;

    public function __constructor()
    {
        parent::__construct();
    }

    public function index()
    {
        header('Content-type: text/html; charset=utf-8');
        $this->view->renderHtml("regist/index");
    }

    public function addUser()
    {
        $data=Request::getPost('data');
        $name =$data['name'];
        $surname =$data['surname'];
        $phone =$data['phone'];
        $role =$data['role'];

        $this->model=$this->loadModel("regist");
        $fb_ID='';
        if(Session::has('fb_ID')){
            $fb_ID=Session::get('fb_ID');
        }
        $gm_ID='';
        if(Session::has('gm_ID')){
            $gm_ID=Session::get('gm_ID');
        }
        $email=Session::get('email');
        if(!$email||count($email)<1){
            $this->view->renderJson(array('result'=> "error: hasn't email"));
            exit;
        }
        $existUserFb=0;
        $existUserGm=0;

        if($fb_ID) {
            $existUserFb = $this->model->checkUserFB($fb_ID);
        }
        if($gm_ID) {
            $existUserGm = $this->model->checkUserGM($gm_ID);
        }

        if((!$existUserFb)&&(!$existUserGm)) {
            $reg=$this->model->addUser($name, $surname, $phone,$role, $fb_ID,$gm_ID,$email);
            if($reg){
                if(Session::has('fb_ID')){
                    $this->model=$this->loadModel("user");
                    $id=$this->model->getIdFB(Session::get("fb_ID"));
                    Session::set('id', $id);
                }
                if(Session::has('gm_ID')){
                    $this->model=$this->loadModel("user");
                    $id=$this->model->getIdGM(Session::get("gm_ID"));
                    Session::set('id',$id);
                }
                if($role=='0')
                {
                    Session::set('status',"ok");
                }
                else{
                    Session::set('status',"unconfirmed");
                }
                $link='app/calendar';
                if(Session::has('unusedLink')){
                    $link=Session::get('unusedLink');
                    Session::uns('unusedLink');
                }
                $this->view->renderJson(array('result'=>'registed', 'link'=>$link)) ;

            }else{
                $this->view->renderJson(array('result'=> "not_registed"));
            }
        } else{
            $this->view->renderJson(array('result'=> "has_user"));
        }
    }

    public function getName()
    {
        $this->view->renderJson(
            [
                'firstname'=>Session::get('firstname'),
                'lastname'=>Session::get('lastname')
            ]);
    }

    public function getRoles()
    {
        $this->model=$this->loadModel("regist");
        $arr=$this->model->getRoles();
        $this->view->renderJson($arr);
    }
}