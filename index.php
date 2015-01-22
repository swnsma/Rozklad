<?php

require_once 'core/Rozklad.php';
require_once 'core/Controller.php';
require_once 'core/Model.php';
require_once 'core/View.php';
require_once 'core/database.php';
require_once 'models/model_role.php';
$app = new Rozklad();

$select = array(
    'where' => "title = 'student'"
);
$model = new Model_role($select);
$model->title ='student';
$model->save();
//print_r( $model -> getAllRows());

/* приклад роботи з базою
try {
$db = new DataBase();
    print_r($db->query('SELECT * from role')->fetchAll(PDO::FETCH_ASSOC));
} catch(PDOException $e) {
    print 'error';
}
*/


?>