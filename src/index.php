<?php

require_once __DIR__ . '/core/Bootstrap.php';
require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/core/Model.php';
require_once __DIR__ . '/core/View.php';
require_once __DIR__ . '/core/database.php';
require_once __DIR__ . '/core/Request.php';
require_once __DIR__ . '/models/role_model.php';

$app = new Bootstrap();

$select = array(
    'where' => "title = 'student'"
);
//$model = new Model_role($select);
//$model->title ='student';
//$model->save();
//print_r( $model -> getAllRows());

// приклад роботи з базою
//try {
//$db = DataBase::getInstance();
//    print_r($db->query('SELECT * from role')->fetchAll(PDO::FETCH_ASSOC));
//} catch(PDOException $e) {
//    print 'error';
//}

?>