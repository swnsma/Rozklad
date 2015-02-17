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

    this.option.editable=true;
    this.option.dragOpacity=0.8;
    this.option.eventDrop=function( event, delta, revertFunc, jsEvent, ui, view ){
        var start  = new Date(event.start);
        //start.setDate(start.getDate()+delta._data.days);
        start= normDate(start.getFullYear(),toFormat(start.getMonth()+1),start.getDate(),start.getHours(),start.getMinutes());

        var end = new Date(event.end);
        //end.setDate(end.getDate()+delta._data.days);
        end= normDate(end.getFullYear(),toFormat(end.getMonth()+1),end.getDate(),end.getHours(),end.getMinutes());
        universalAPI(
            url+'app/calendars/eventDrop',
            'post',
            function(data){
                if(data.status!=='ok'){
                    alert('щось трапилось дивне');
                }else{
                    var myevent={
                        start: start,
                        end: end,
                        teacher: event.teacher,
                        id:event.id,
                        name:event.name,
                        surname:event.surname,
                        group:event.group,
                        title:event.title,
                        color: (function(){
                            if(userInfo.id===event.teacher){
                                return masColor.myEvents.color;
                            }else{
                                return masColor.otherEvents.color;
                            }
                        })(),
                        textColor: (function(){
                            if(userInfo.id===event.teacher){
                                return masColor.myEvents.textColor;
                            }else{
                                return masColor.otherEvents.textColor;
                            }
                        })()
                    }
                    myoption.calendar.fullCalendar('removeEvents', event.id);
                    myoption.calendar.fullCalendar('renderEvent',myevent);
                }
            },
            function(){
                alert('Помилка');
            },
            {
                start:start,
                end:end,
                id:event.id
            }

        )
    };


    this.option.dayClick=function(){

    }
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