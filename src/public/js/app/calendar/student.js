/**
 * Created by Таня on 23.01.2015.
 */


function Calendar_student(){
    var event=[];
    Calendar.call(this);
    var self = this;

    this.jqueryObject.deadlineTask ={
        deadlineTaskBt:$('#deadlineTaskBt'),
        deadlineTaskContent:$('#deadlineTaskContent'),
        deadlineTaskContentTitle:$('#deadlineTaskContentTitle'),
        deadlineTaskClose: $('#deadlineTaskClose'),
        deadlineTaskContentContent:$('#deadlineTaskContentContent')
    }
    this.option.eventSources= [
        {
            events: function(start, end, timezone, callback) {
                start=start._d;
                end=end._d;
                var start1 = normDate(start.getFullYear(),start.getMonth()+1,start.getDay(),start.getHours(),start.getMinutes());
                var end1 = normDate(end.getFullYear(),end.getMonth()+1,end.getDay(),end.getHours(),end.getMinutes());

                universalAPI(
                    url+'app/calendar/addFullEventDefault',
                    'post',
                    function(data){
                        event= data;
                        callback(data);
                        for(var i =0;i<data.length;++i){
                            if(data[i].deadline){
                                debugger;
                                var $div = $('<div>');
                                $div.appendTo(self.jqueryObject.deadlineTask.deadlineTaskContentContent);

                                $div.css({
                                    'borderBottom':'1px solid #aaa',
                                    'padding':'1px 10px'
                                });

                                var $eventTitle = $('<span>');
                                $eventTitle.appendTo($div);
                                $eventTitle.text(data[i].title);
                                $eventTitle.css({
                                    'color':'#333',
                                    'font':'12px arial,sans-serif',
                                    'fontWeight':'600',
                                    'display':'block',
                                    'margin':'0 0 -5px',
                                    'padding':0
                                });

                                for(var j =0;j<data[i].group.length;++j){
                                    var $var=$('<span>');
                                    $var.appendTo($div);
                                    $var.text(data[i].group[j].name[0]);
                                    $var.css({
                                        'display': 'inline-block',
                                        'width': '10px',
                                        'height': '10px',
                                        'fontSize': '8px',
                                        'textAlign': 'center',
                                        'marginLeft': '2px',
                                        'marginRight':'2px',
                                        'borderRadius': '2px',
                                        'verticalAlign': 'baseline',
                                        'lineHeight':'10px',
                                        'backgroundColor': data[i].group[j].color,
                                        'fontWeight': 'normal',
                                        'verticalAlign':'middle',
                                        'color':'white'
                                    });
                                }
                                if (data[i].teacher) {
                                    var $var = $('<span>');
                                    $var.text(data[i].name[0] + '.' + data[i].surname);
                                    $var.css({
                                        'fontSize': '10px',
                                        'display': 'inline-block',
                                        'lineHeight':'10px'
                                    });
                                    $var.appendTo($div);
                                }

                                var $var = $('<span>');
                                $var.text(data[i].deadline);
                                $var.css({
                                    'fontSize': '10px',
                                    'display': 'block',
                                    'fontWeight':'bold',
                                    'color':'#aaa',
                                    'lineHeight':'10px'
                                });
                                $var.appendTo($div);

                            }
                        }
                    },
                    function(er){
                        alert('Лалала');
                    },
                    {
                        start:start1,
                        end:end1
                    }
                )

            },
            color: 'RGB(0,100,160)'  // an option!
        }
    ]

    this.jqueryObject.deadlineTask.deadlineTaskBt.on('click',function(){
        self.jqueryObject.deadlineTask.deadlineTaskBt.hide();
        self.jqueryObject.deadlineTask.deadlineTaskContent.show();
    });
    this.jqueryObject.deadlineTask.deadlineTaskClose.on('click',function(){
        self.jqueryObject.deadlineTask.deadlineTaskBt.show();
        self.jqueryObject.deadlineTask.deadlineTaskContent.hide();
    });
    var drag;
    var clientX;
    var clientY;
    this.jqueryObject.deadlineTask.deadlineTaskContentTitle.on('mousedown',function(e){
        drag=self.jqueryObject.deadlineTask.deadlineTaskContent;
        clientX= e.clientX;
        clientY= e.clientY;
    });
    this.jqueryObject.deadlineTask.deadlineTaskContentTitle.on('mouseup',function(){
        drag=null;
    });
    $(document).on('mousemove',function(e){
        if(drag) {
            drag.css({
                'right':+drag.css('right').substr(0, drag.css('right').length - 2)-(e.clientX-clientX),
                'bottom':+drag.css('bottom').substr(0, drag.css('bottom').length - 2)-(e.clientY-clientY)
            });
        }
        clientX= e.clientX;
        clientY= e.clientY;
    });


    //$(".nano").nanoScroller();

    this.jqueryObject.calendar.fullCalendar(this.option);


}
$(document).ready(function() {
    var calendar = new Calendar_student();
    calendar.getCurrentUser();
    calendar.realTimeUpdate();

});

