/**
 * Created by Таня on 23.01.2015.
 */

function normDate(year,month,day,hour,minuts){
    function format(num){
        if((num+'').length==1){
            num='0'+num;
        }
        return num;
    }
    return year+'-'+format(month)+'-'+format(day)+' '+format(hour)+':'+format(minuts)+':00';
}

function Calendar(){
    this.jqueryObject={
        calendar:$('#calendar'),
        popup: {
            typeAction:$('#typeAction'),//тип попапу
            popup: $('#popup'),
            typePopup:$('#eventType'),//Title завдання
            tcal: $('#tcal'),
            tcalInput: $('#tcalInput'),
            start:{
                hour: $('#hourBegin'),
                minutes: $('#minutesBegin')
            },
            end:{
                hour: $('#hourEnd'),
                minutes: $('#minutesEnd')
            },
            day:{
                year:$('#year'),
                month:$('#month'),
                day:$('#day')
            },
            button:{
                delEvent:$('#delEvent'),
                submit:$('#createNewLesson'),
                reset:$('#resetLesson')
            }
        }
    };
    var date = new Date();
    var month=date.getMonth()+1;
    var year= date.getFullYear();

    this.option={
        eventLimit: true, // for all non-agenda views
        firstDay: 1,
        header: {

        },
        monthNames: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
        monthNamesShort: ['Янв.', 'Фев.', 'Март', 'Апр.', 'Май', 'Июнь', 'Июль', 'Авг.', 'Сент.', 'Окт.', 'Ноя.', 'Дек.'],
        dayNames: ["Воскресенье", "Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота"],
        dayNamesShort: ["ВС", "ПН", "ВТ", "СР", "ЧТ", "ПТ", "СБ"],
        timezone:'local',
        buttonText: {
            today: "Сегодня",
            month: "Месяц",
            week: "Неделя",
            day: "День"
        },
        timeFormat: 'H(:mm)',// uppercase H for 24-hour clock
        //handleWindowResize:true,
        //fixedWeekCount:false,

        eventSources: [
            {
                events: function(start, end, timezone, callback) {
                    start=start._d;
                    end=end._d;
                    var start1 = normDate(start.getFullYear(),start.getMonth()+1,start.getDay(),start.getHours(),start.getMinutes());
                    var end1 = normDate(end.getFullYear(),end.getMonth()+1,end.getDay(),end.getHours(),end.getMinutes())
                    debugger;
                    $.ajax({
                        url: url+'app/calendar/addFullEvent'+'/'+start1+'/'+end1,
                        contentType: 'application/json',
                        dataType: 'json',
                        success: function(doc) {
                            debugger;
                            callback(doc);
                            return doc;
                        },
                        error: function(){
                            debugger;
                        }
                    });
                }
            }
        ]
        //eventClick: function(event, element) {
        //    debugger;
        //    event.title = "CLICKED!";
        //
        //    $('#calendar').fullCalendar('updateEvent', event);
        //
        //},
        //,events: [{
        //        title  : 'event3',
        //        start  : '2015-01-09 12:30:00',
        //        allDay : false // will make the time show
        //    }
        //]

    };
}