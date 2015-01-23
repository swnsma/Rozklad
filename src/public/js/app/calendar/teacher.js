/**
 * Created by Таня on 22.01.2015.
 */
function click_body(){
    $(document).click(function (event) {
        var boolClass=false;
        debugger;
        if(event.target.id==='popup'){
            debugger;
            boolClass=true;
        }
        if($(event.target).parents('#popup')){
            var b= $(event.target).parents('#popup');
            if(b[0]){
                boolClass=true;
            }
        }
        else {
            var teg =$(event.target).parents(".fc-content-skeleton")[0];
            if(teg) {
                debugger;
                var classList = teg.classList;
                for (var i = 0; i < classList.length; ++i) {
                    if (classList[i] === 'fc-content-skeleton') {
                        boolClass = true;
                        break;
                    }
                }
            }
        }
        if(!boolClass) {
            var classList = $(event.target)[0].classList;
            for (var i = 0; i < classList.length; ++i) {
                if (classList[i] === 'fc-day' || classList[i] === 'fc-day-number') {
                    boolClass = true;
                    break;
                }
            }
        }
        debugger;
        if(event.target.className==='fc-more'){
            boolClass = false;
        }
        if(!boolClass ) {
            $('#popup').hide();
        }

    });
}

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
