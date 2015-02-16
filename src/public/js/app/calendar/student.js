/**
 * Created by Таня on 23.01.2015.
 */

function Calendar_student(option){
    Calendar.call(this);
    option.calendar.fullCalendar(this.option);
}
$(document).ready(function() {
    var option={
        calendar: $('#calendar')
    }
    var calendar = new Calendar_student(option);

});
