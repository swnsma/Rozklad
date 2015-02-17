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
        $id = $_SESSION['id'];
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
//        $data =$this->userInfo;

        $data['title'] = "Calendar|Rozklad";
        $data['groups'] = $this->model->getList();
        $data['name'] = $this->userInfo['name'] . ' ' . $this->userInfo['surname'];
        $data['status'] = $this->userInfo['title'];
        $data['photo'] = 'http://graph.facebook.com/' . $this->userInfo['fb_id'] . '/picture?type=large';
        /*$this->view->renderAllHTML('groups/index',
            $data,
            array('groups/groups.css'));*/
        $this->view->renderHtml('common/head', $data);
        $this->view->renderHtml('common/header', $data);
        $this->view->renderHtml('calendar/index', $data);
//        $this->view->renderHtml('common/footer');
        $this->view->renderHtml('common/foot');

    }

    public function addFullEventDefault()
    {
        if (isset($_POST['start']) && $_POST['end']) {
            $this->model = $this->loadModel('lesson');
            $start = $_POST['start'];
            $end = $_POST['end'];
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

    public function addFullEventTeacherCurrent()
    {
        if (isset($_POST['start']) && isset($_POST['end'])) ;
        {
            $this->model = $this->loadModel('lesson');
            $start = $_POST['start'];
            $end = $_POST['end'];
            $id = $this->model->getOurLessonForThisIdTeacherCurrent($this->userInfo, $start, $end);
            $this->view->renderJson($id);
        }
    }

    public function addFullEventTeacherNoCurrent()
    {
        if (isset($_POST['start']) && isset($_POST['end'])) ;
        {
            $this->model = $this->loadModel('lesson');
            $start = $_POST['start'];
            $end = $_POST['end'];
            $id = $this->model->getOurLessonForThisIdTeacherNoCurrent($this->userInfo, $start, $end);
            $this->view->renderJson($id);
        }
    }

    public function restore()
    {
        if (isset($_POST['id'])) {
            $this->model = $this->loadModel('lesson');
            $id = $_POST['id'];
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
        if (isset($_POST['title']) && isset($_POST['start']) && isset($_POST['end']) && isset($_POST['id']) && isset($_POST['teacher'])) {
            $this->model = $this->loadModel('lesson');
            $title = $_POST['title'];
            $start = $_POST['start'];
            $end = $_POST['end'];
            $id = $_POST['id'];
            $teacherId = $_POST['teacher'];
            $this->model->updateLesson($title, $start, $end, $id, $teacherId);
            if (isset($_POST['group'])) {
                if (isset($_POST['group']['del'])) {
//                    print $_POST['group']['del'];
                    $this->deleteGroupFromLesson($id, $_POST['group']['del']);
                }
                if (isset($_POST['group']['add'])) {
                    $this->addGroupsToLesson($id, $_POST['group']['add']);
                }
            }
            $this->view->renderJson("succeess");
        }
    }

    public function addEvent()
    {
        if (isset($_POST['title']) && isset($_POST['start']) && isset($_POST['end']) && isset($_POST['teacher'])) {
            $this->model = $this->loadModel('lesson');
//            print_r($_POST);
            $title = $_POST['title'];
            $start = $_POST['start'];
            $end = $_POST['end'];
            $teacher = $_POST['teacher'];
            $id = $this->model->addLesson($title, $start, $end, $teacher);

            if ($id == null) {
                echo 'Ошибка';
            } else {
                if (isset($_POST['group'])) {
                    $this->addGroupsToLesson($id, $_POST['group']);
                }
                $this->view->renderJson(array('id' => $id));
            }
        }
    }

    public function delEvent()
    {

        if (isset($_POST['id'])) {
            $this->model = $this->loadModel('lesson');
            $id = $_POST['id'];
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

    public function getGroups(){
        $this->model = $this->loadModel('groups');
//        print $this->userInfo['id'];
        $arr=$this->model->getGroups($this->userInfo['id']);


        $this->view->renderJson($arr);
    }

    //+
    public function getAllGroupsForThisLesson(){
        $request=Request::getInstance();
        $this->model = $this->loadModel('lesson');
        $arr=$this->model->getAllGroupsForThisLesson($request->getParam(0));
        $this->view->renderJson($arr);
    }

    public function import() {
        $client = new Google_Client();
        $client->setClientId(CLIENT_ID_GM);
        $client->setClientSecret(CLIENT_SECRET_GM);
        $gm_token = json_decode(Session::get('gm_token'));
        echo '<pre>';
        print_r($gm_token);
        echo '</pre>';
        $client->setAccessToken(Session::get('gm_token'));
        if ($client->getAccessToken()) {
            echo $client->getAccessToken();
        } else {
            echo 122332;
        }


        $service = new Google_Service_Calendar($client);

        //
        $event = new Google_Service_Calendar_Event();;
        $event->setSummary('Event 1');
        $event->setLocation('Somewhere');
        $start = new Google_Service_Calendar_EventDateTime();
        $start->setDateTime('2013-10-22T19:00:00.000+01:00');
        $start->setTimeZone('Europe/London');
        $event->setStart($start);
        $end = new Google_Service_Calendar_EventDateTime();
        $end->setDateTime('2013-10-22T19:25:00.000+01:00');
        $end->setTimeZone('Europe/London');
        $event->setEnd($end);
        //
        $calendar_id = "sd7h90sdja97sdg9ahd0sa8bd@group.calendar.google.com";
        //
        $new_event = null;
        //
        try {
            $new_event = $service->events->insert('primary', $event);
            //
            $new_event_id= $new_event->getId();
        } catch (Google_ServiceException $e) {
            syslog(LOG_ERR, $e->getMessage());
        }
        //
        $event = $service->events->get($calendar_id, $new_event->getId());
        //
        if ($event != null) {
            echo "Inserted:";
            echo "EventID=".$event->getId();
            echo "Summary=".$event->getSummary();
            echo "Status=".$event->getStatus();
        }
        //...
    }
}