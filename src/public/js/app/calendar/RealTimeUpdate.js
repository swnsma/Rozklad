/**
 * Created by Таня on 28.01.2015.
 */

function RealTimeUpdate(){
    var interval = 60000;//раз в хвилину оновлення

    var setTime;
    var operation = function(){
        $.ajax({
            url: url+'app/calendar/getRealTimeUpdate/'+interval/1000,
            type: 'POST',
            contentType: 'application/json',
            dataType: 'json',
            success: function(date){
                debugger;
                for(var i=0;i<date.length;++i){
                    $('#calendar').fullCalendar('renderEvent',{
                        id: date[i].id,
                        title: date[i].title,
                        start: date[i].start,
                        end: date[i].end
                    });
                }
            },
            error: function(er) {
                alert(er)
            }

        });
    }
    this.start = function(){
        setTime=setInterval(operation,interval);
    };
    this.setInterval=function(interval1){
        interval=interval1;
    }
    this.getInterval=function(){
        return interval;
    }
}