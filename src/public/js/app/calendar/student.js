/**
 * Created by Таня on 23.01.2015.
 */
function Calendar_student(){
    Calendar.call(this);
    this.jqueryObject.calendar.fullCalendar(this.option);
}
$(document).ready(function() {
    var calendar = new Calendar_student();
    calendar.realTimeUpdate();

});
