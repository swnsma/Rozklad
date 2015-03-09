<?php

require DOC_ROOT . '/lib/google2/src/Google/Client.php';
require DOC_ROOT . '/lib/google2/src/Google/Service/Calendar.php';

class Calendar extends Controller
{
    private $userInfo;
    private $role = 'teacher';

    public function __construct()
    {
        parent::__construct();
        $id = Session::get('id');
        if ($id === null) {
            $this->logout();
        }
        $this->model = $this->loadModel('user');
        $this->userInfo = $this->model->getCurrentUserInfo($id);
        if ($this->userInfo === null) {
            $this->logout();
        }
    }

    public function getUserInfo()
    {
        $this->view->renderJson($this->userInfo);
    }

    public function index()
    {
        $this->model = $this->loadModel('lesson');
        $data['title'] = "Calendar|Rozklad";
        $data['groups'] = $this->model->getList();
        $data['name'] = $this->userInfo['name'] . ' ' . $this->userInfo['surname'];
        $data['status'] = $this->userInfo['title'];
        $data['photo'] = 'http://graph.facebook.com/' . $this->userInfo['fb_id'] . '/picture?type=large';
        $data['currentPage']=$this->getClassName();
        if (Session::has('gm_ID')){
            $data['googleCalendars']=$this->model->getGoogleCalendarList();
        }

        $this->view->renderHtml('common/head', $data);
        $this->view->renderHtml('common/header', $data);
        if($data['status']==='student') {
            $this->view->renderHtml('calendar/deadlinetask', $data);
        }else{
            $this->view->renderHtml('calendar/popup', $data);
        }
        $this->view->renderHtml('calendar/index', $data);
        $this->view->renderHtml('common/foot');

}

    public function addFullEventDefault()
    {
        $start = Request::getPost('start');
        $end = Request::getPost('end');
        if (isset($start) && isset($end)) {
            $this->model = $this->loadModel('lesson');
            $id = $this->model->getOurLessonForThisIdStudent($this->userInfo, $start, $end);
            $this->view->renderJson($id);
        }
    }

    public function getOurGroups()
    {
        $this->model = $this->loadModel('groups');
        $arr = $this->model->getOurGroups($this->userInfo['id']);
        $this->view->renderJson($arr);
    }

    public function getOurTeacher()
    {
        $this->model = $this->loadModel('user');
        $date = $this->model->getOurTeacher();
        $this->view->renderJson($date);
    }

    public function addFullEventTeacher()
    {
        $start = Request::getPost('start');
        $end = Request::getPost('end');
        if (isset($start) && isset($end)) ;
        {
            $this->model = $this->loadModel('lesson');
            $id['current'] = $this->model->getOurLessonForThisIdTeacherCurrent($this->userInfo, $start, $end);
            $id['no']=$this->model->getOurLessonForThisIdTeacherNoCurrent($this->userInfo, $start, $end);
            $this->view->renderJson($id);
        }
    }

    public function restore()
    {
        $id = Request::getPost('id');
        if (isset($id)) {
            $this->model = $this->loadModel('lesson');
            $date = $this->model->restore($id);
            $this->view->renderJson($date);
        }
    }

    private function addGroupsToLesson($lessonId, $groupId)
    {
        $this->model = $this->loadModel('grouplesson');
        for ($i = 0; $i < count($groupId); ++$i) {
            $this->model->addGroupToLesson($lessonId, $groupId[$i]);
        }
    }

    private function deleteGroupFromLesson($lessonId, $groupId)
    {
        $this->model = $this->loadModel("lesson");
        for ($i = 0; $i < count($groupId); ++$i) {
            $success = $this->model->deleteGroupFromLesson($lessonId, $groupId[$i]);
        }
//        $this->view->renderJson(Array('success' => $success));

    }

    public function updateEvent()
    {
        $title = Request::getPost('title');
        $start = Request::getPost('start');
        $end = Request::getPost('end');
        $id = Request::getPost('id');
        $teacherId = Request::getPost('teacher');
        if (isset($title) && isset($start) && isset($end) && isset($id) && isset($teacherId)) {
            $this->model = $this->loadModel('lesson');
            $this->model->updateLesson($title, $start, $end, $id, $teacherId);
            $group = Request::getPost('group');
            if (isset($group)) {
                if (isset($group['del'])) {
//                    print $group['del'];
                    $this->deleteGroupFromLesson($id, $group['del']);
                }
                if (isset($group['add'])) {
                    $this->addGroupsToLesson($id, $group['add']);
                }
            }
            $this->view->renderJson("succeess");
        }
    }

    public function addEvent()
    {
        $title = Request::getPost('title');
        $start = Request::getPost('start');
        $end = Request::getPost('end');
        $teacher = Request::getPost('teacher');
        if (isset($title) && isset($start) && isset($end) && isset($teacher)) {
            $this->model = $this->loadModel('lesson');
//            print_r($_POST);
            $id = $this->model->addLesson($title, $start, $end, $teacher);

            if ($id == null) {
                echo 'Ошибка';
            } else {
                $group = Request::getPost('group');
                if (isset($group)) {
                    $this->addGroupsToLesson($id, $group);
                }
                $this->view->renderJson(array('id' => $id));
            }
        }
    }

    public function delEvent()
    {
        $id = Request::getPost('id');
        if (isset($id)) {
            $this->model = $this->loadModel('lesson');
            $this->model->delEvent($id);
            $this->view->renderJson("success");
        }
    }

    public function getRealTimeUpdate()
    {
        $this->model = $this->loadModel('lesson');
        $interval = Request::getInstance()->getParam(0);
        $id = $this->model->getRealTimeUpdate($interval, $this->userInfo);
        $this->view->renderJson($id);
    }

    public function getGroups()
    {
        $this->model = $this->loadModel('groups');
//        print $this->userInfo['id'];
        $arr=$this->model->getGroups($this->userInfo['id']);


        $this->view->renderJson($arr);
    }

    public function getAllGroupsForThisLesson()
    {
        $request=Request::getInstance();
        $this->model = $this->loadModel('lesson');
        $arr=$this->model->getAllGroupsForThisLesson($request->getParam(0));
        $this->view->renderJson($arr);
    }

    public function eventDrop()
    {
        if($this->userInfo['title']==='teacher') {
            $start =  Request::getPost('start');
            $end =  Request::getPost('end');
            $idlesson =  Request::getPost('id');
            if(isset($start)&&isset($end)&&isset($id)) {

                $this->model = $this->loadModel('lesson');


                if($this->model->eventDrop($idlesson, $start, $end)) {
                    $id['status']='ok';
                    $this->view->renderJson($id);
                } else {
                    $id['status']='notOk';
                    $this->view->renderJson($id);
                }
            } else {
                $returns['status'] = 'problem';
                $this->view->renderJson($returns);
            }
        } else {
            $returns['status'] = 'noteacher';
            $this->view->renderJson($returns);
        }
    }

    public function import()
    {
        $client = new Google_Client();
        $client->setApplicationName("Rozklad");
        $client->setClientId(CLIENT_ID_GM);
        $client->setClientSecret(CLIENT_SECRET_GM);
        $client->setRedirectUri(URL . "app/loging/login");
        $client->setApprovalPrompt(APPROVAL_PROMPT);
        $client->setAccessType(ACCESS_TYPE);
        $client->setAccessToken(Session::get('token'));
        $service = new Google_Service_Calendar($client);

        $event = new Google_Service_Calendar_Event();
        $event->setSummary('Event 1');
        $event->setLocation('Somewhere');
        $start = new Google_Service_Calendar_EventDateTime();;
        $start->setDateTime('2015-02-17T19:00:00.000+01:00');
        $start->setTimeZone('Europe/London');
        $event->setStart($start);
        $end = new Google_Service_Calendar_EventDateTime();
        $end->setDateTime('2015-02-17T20:00:00.000+01:00');
        $end->setTimeZone('Europe/London');
        $event->setEnd($end);
        $calendar_id = "myrozklad@gmail.com";
        $new_event = null;
        try {
            $new_event = $service->events->insert($calendar_id, $event);
            //
            $new_event_id= $new_event->getId();
        } catch (Google_ServiceException $e) {
            syslog(LOG_ERR, $e->getMessage());
        }
        $event = $service->events->get($calendar_id, $new_event->getId());
        if ($event != null) {
            echo "Inserted:";
            echo "EventID=".$event->getId();
            echo "Summary=".$event->getSummary();
            echo "Status=".$event->getStatus();
        }
    }

    public function exportEvent()
    {
        $this->model = $this->loadModel('lesson');
        $this->model->exportEvent( Request::getPost('lesson')['lessonId'], Request::getPost('lesson')['userId'], Request::getPost('calendarId'));
    }

    public function getGoogleCalendarList()
    {
        echo( json_encode($this->model->getGoogleCalendarList()) );
    }

    public function exportPopup()
    {
        $this->model = $this->loadModel('lesson');
        $data = [];
        if (Session::has('gm_ID')) {
            $data['googleCalendars']=$this->model->getGoogleCalendarList();
        }
        $this->view->renderHtml('calendar/exportPopup',$data);
    }
}