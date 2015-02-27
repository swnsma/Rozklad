/**
 * Created by Саша on 24.02.2015.
 */
var popupShown = false;
function toggleExportPopup(){
    if (popupShown){
        $("#exportPopup").hide();
        popupShown = !popupShown;
    } else {
        $("#exportPopup").show();
        popupShown = !popupShown;
    }
}

function loginGoogle(){
    window.location.href = url+'app/loging/login';
}

function exportLesson(lesson, callback){
    $.ajax({
        url: url + 'app/calendar/exportEvent',
        type: 'POST',
        data: lesson,
        success: function(response){
            console.log ('exported');
            callback();
        },
        error: function(e){
            //;
        }
    });
}

function exportAll() {
    var el = document.getElementById('selectCalendar');
    debugger;
    var calendarId = el.options[el.selectedIndex].value;

    var lessons = fullEventFor;
    var counter = 0;

    var success =  document.getElementById('successfullyExported');
    success.style.display = 'none';

    var bar = document.getElementById('exportProgress');
    bar.style.display='block';
    bar.setAttribute('max',lessons.length);
    bar.setAttribute('value','0');

    for (var i =0; i< lessons.length;i++) {
        function callback(){
            counter ++;
            bar.setAttribute('value',counter);
            if (counter >= lessons.length){
                success.style.display = 'block';
                window.setTimeout(hidePopup, 1000);
                function hidePopup(){
                    success.style.display = 'none';
                    bar.style.display='none';
                    $('#exportPopup').hide();
                }
            }
        }
        var lessonId = lessons[i].id;
        var userId = lessons[i].teacher;
        var lesson = {lessonId: lessonId, userId: userId};
        exportLesson({lesson: lesson,calendarId:calendarId}, callback);
    }
}