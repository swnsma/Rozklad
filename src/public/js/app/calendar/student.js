/**
 * Created by Таня on 23.01.2015.
 */
function Calendar_student(id){
    Calendar.call(this,id);
    var $calendar = $(id);
    $calendar.fullCalendar(this.option);
}
$(document).ready(function() {
    var calendar = new Calendar_student('#calendar');

    //var realTimeUpdate = new RealTimeUpdate();
    //realTimeUpdate.start();
    //calendar.popup();
});
