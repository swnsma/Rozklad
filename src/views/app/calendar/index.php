<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Calendar|Rozklad</title>

    <link rel='stylesheet' href='<?php print URL; ?>public/js/vendor/fullcalendar-2.2.6/fullcalendar.css' />
    <link rel='stylesheet' href='<?php print URL; ?>public/js/vendor/jQuery/jquery-ui.min.css'/>
    <link rel='stylesheet' href='<?php print URL; ?>public/css/calendar/common.css'/>
    <link rel='stylesheet' href='<?php print URL; ?>views/app/start_page/bootstrap.css'/>


</head>
<body>



<?php
if(Calendar::$role=='teacher')
{
    echo "<div class='popup' id='popup'>
    <div class='content'>
        <h2 class='padding28'> Создать Занятие </h2>
    </div>

    <form method='post' class='create-lesson'>
        <div class='padding28 inner-wrapper'>
            <span class='tr-create-lesson'>
                <span>Тема: </span>
                <input class='input-create-lesson' placeholder='Новый урок' type='text' id='eventType' name='event_type' value=''>
            </span>
        </div>


        <div class='padding28 inner-wrapper'>
            <div class='tr-create-lesson'>
                <div class='data-ivent'>
                    <input class='input-span width15px' type='text' name='day' id='day' />/<!--
                    --><input class='input-span width15px' type='text' name ='month' id='month' />/<!--
                    --><input class='input-span' type='text' name='year' id='year' /><!--
                    --><input type='text' id='tcalInput' name='date' class='tcal' value='' />
                </div>
                <div class='time-ivent'>
                    <input class='input-span width15px' placeholder='---' type='text' name='hour-begin' id='hourBegin'/>:<!--
                    --><input class='input-span width15px' placeholder='---' type='text' name='minutes-begin' id='minutesBegin'/>-
                    <input class='input-span width15px' placeholder='---' type='text' name='hour-end' id='hourEnd'/>:<!--
                    --><input class='input-span width15px' placeholder='---' type='text' name='minutes-end' id='minutesEnd'/>
                </div>
            </div>
        </div>
        <div class='form-group inner-wrapper'>
            <div class='col-lg-10 col-lg-offset-4'>
                <button id='resetLesson' type='reset' class='btn btn-info'>Отмена</button>
                <button id='createNewLesson' type='submit' class='btn btn-primary'>Создать</button>
            </div>
        </div>
    </form>
</div>
<link rel='stylesheet' type='text/css' href='" .URL ."public/js/app/calendar/tcal/tcal.css' />

    <link rel='stylesheet' href='" . URL . "public/css/calendar/popup.css'/>
    ";
}
?>

<div class="calendar" id='calendar'></div>



<script src='<?php print URL; ?>public/js/vendor/jQuery/jquery-2.1.1.js'></script>
<script src='<?php print URL; ?>public/js/vendor/fullcalendar-2.2.6/moment.min.js'></script>
<script src='<?php print URL; ?>public/js/vendor/fullcalendar-2.2.6/fullcalendar.js'></script>

<script src='<?php print URL; ?>public/js/app/common.js'></script>
<script src='<?php print URL; ?>public/js/app/calendar/Calendar.js'></script>
<?php
if(Calendar::$role=='teacher')
{
    echo "<script type='text/javascript' src='" .URL ."public/js/vendor/jQuery/jquery.mask.js'></script>
    <script src='" . URL . "public/js/app/calendar/teacher.js'></script>
    <script type='text/javascript' src='" .URL ."public/js/app/calendar/tcal/tcal.js'></script>
    ";
}else{
    echo "<script src='" . URL ."public/js/app/calendar/student.js'></script>";
}
?>





</body>
</html>