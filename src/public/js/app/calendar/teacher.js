/**
 * Created by Таня on 22.01.2015.
 */
function Calendar_teacher(id,popup){
    Calendar.call(this,id);
    debugger;
    var $popup=$(popup);
    var $calendar = $(id);
    this.option.dayClick=function(date, allDay, jsEvent, view) {
            debugger;
            $popup.show();
            var x= allDay.pageX;
            var y = allDay.pageY;
            debugger;
            x=x-$popup.css('width').slice(0,$popup.css('width').length-2)/2;
            $popup.css({
                'left':x,
                'top':y
            });
        click_body();
    };
    $calendar.fullCalendar(this.option);
}
$(document).ready(function() {
    var calendar = new Calendar_teacher('#calendar','#popup');
    //calendar.popup();
});
