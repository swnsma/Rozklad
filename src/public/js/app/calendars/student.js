/**
 * Created by Таня on 16.02.2015.
 */

function Calendar_student(options){
    Calendar.call(this);
    this.option.eventSources=[
        {
            events: function(start, end, timezone, callback) {
                //функція знаходться в файлі common.js
                loudLesson(start,end,callback,url+'app/calendars/getOurLessonForThisIdStudent');
            },
            color: masColor.myEvents.color
        }
    ];
    options.calendar.fullCalendar(this.option);
}
$(document).ready(function() {
    var option={
        calendar: $('#calendar')
    }
    var calendar = new Calendar_student(option);

});