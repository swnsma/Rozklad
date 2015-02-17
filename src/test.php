<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/conf/conf.php';
require __DIR__ . '/lib/google2/src/Google/Client.php';
require __DIR__ . '/lib/google2/src/Google/Service/Calendar.php';
require_once __DIR__ . '/core/DataBase.php';
require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/core/Bootstrap.php';
require_once __DIR__ . '/core/Model.php';
require_once __DIR__ . '/core/View.php';
require_once __DIR__ . '/core/BaseInstall.php';
require_once __DIR__ . '/core/Request.php';
require_once __DIR__ . '/core/Session.php';
require_once __DIR__ . '/module/app/model/lesson_model.php';

$client = new Google_Client();
$client->setClientId(CLIENT_ID_GM);
$client->setClientSecret(CLIENT_SECRET_GM);
$client->setRedirectUri(URL . 'app/loging/login');
$client->setApprovalPrompt(APPROVAL_PROMPT);
$client->setAccessType(ACCESS_TYPE);

session_start();

print_r($_SESSION);



    $this->client->setAccessToken('ya29.HAHmeNv5PrJlHXAoaykGFEzhXAVjXGhvOY9CEs3xQzE94jrj2cFTY5R-Z-_Ad6fCfE7R4f2-sJhc0w');

if ($client->getAccessToken()) {
    echo $client->getAccessToken();
} else {
    echo 122332;
}

$service = new Google_Service_Calendar($client);

$event = new Google_Service_Calendar_Event();
$event->setSummary('Appointment');
$event->setLocation('Somewhere');
$start = new Google_Service_Calendar_EventDateTime();
$start->setDateTime('2011-06-03T10:00:00.000-07:00');
$event->setStart($start);
$end = new Google_Service_Calendar_EventDateTime();
$end->setDateTime('2011-06-03T10:25:00.000-07:00');
$event->setEnd($end);
$createdEvent = $service->events->insert('primary', $event);

echo $createdEvent->getId();

print 23234;
