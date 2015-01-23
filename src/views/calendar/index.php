
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Calendar|Rozklad</title>

    <link rel='stylesheet' href='public/js/vendor/fullcalendar-2.2.6/fullcalendar.css' />
    <link rel='stylesheet' href='public/js/vendor/jQuery/jquery-ui.min.css'/>
    <link rel='stylesheet' href='public/css/calendar/common.css'/>


</head>
<body>



<?php
if(Calendar::$role=='teacher')
{
    echo "<div class='popup' id='popup'>
    <div class='content'>
    <h2 class='padding28'> Создать Занятие </h2>
    </div>
    </div>
    <link rel='stylesheet' href='public/css/calendar/popup.css'/>
    ";
}
?>

<div class="calendar" id='calendar'></div>



<script src='public/js/vendor/jQuery/jquery-2.1.1.js'></script>
<script src='public/js/vendor/fullcalendar-2.2.6/moment.min.js'></script>
<script src='public/js/vendor/fullcalendar-2.2.6/fullcalendar.js'></script>


<script src='public/js/app/calendar/Calendar.js'></script>
<?php
if(Calendar::$role=='teacher')
{
    echo "<script src='public/js/app/calendar/teacher.js'></script>";
}else{
    echo "<script src='public/js/app/calendar/student.js'></script>";
}
?>





</body>
</html>