/**
 * Created by Таня on 16.02.2015.
 */

function Calendar_teacher(myoption){
    Calendar.call(this);

    //зберігається інформацію про юзера
    var userInfo=[];

    //зберігаються всі не заархівіровані групи
    var groups=[];

    //відправляється запит на сервер, який повертає інформацію про поточного юзера, та про список груп
    universalAPI(
        url+'app/calendars/getUserInformationAndOurGroups',
        'post',
        function(data){
            if(data.status==='ok') {
                userInfo = data.user;
                groups = data.group;
            }else if(data.status==='noteacher'){
                alert('Ви не є вчителем. Ви щось коїте дивне');
            }
        },function(error){
            alert('Не вдалось заваднажити ваші дані');
        });

    //безпосереднє завантаження
    this.option.eventSources=[
        {
            events: function(start, end, timezone, callback) {
                loudLesson(start,end,callback,url+'app/calendars/getOurLessonForThisIdTeacherCurrent');
            },
            color: masColor.myEvents.color
        },
        {
            events: function(start, end, timezone, callback) {
                loudLesson(start,end,callback,url+'app/calendars/getOurLessonForThisIdTeacherNoCurrent');
            },
            color: masColor.otherEvents.color,
            textColor:masColor.otherEvents.textColor
        }
    ];



    //рендериться календарик
    myoption.calendar.fullCalendar(this.option);
}
$(document).ready(function() {
    var option={
        calendar: $('#calendar')
    }
    var calendar = new Calendar_teacher(option);

});