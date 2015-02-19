/**
 * Created by Таня on 23.01.2015.
 */

function createListLeson(data,parent){
    parent.empty();
    for(var i =0;i<data.length;++i){
        if(data[i].deadline){
            var $div = $('<div>');
            $div.appendTo(parent);
            $div.attr({
                'id':'lesson'+data[i].id
            })
            $div.on('click',function(){
                window.location=url+'app/lesson/id'+$(this).attr('id').substr(6);
            });
            $div.addClass('lesson-wrapper');

            var $eventTitle = $('<span>');
            $eventTitle.appendTo($div);
            $eventTitle.text(data[i].title);
            $eventTitle.addClass('event-title');

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
            var deadline = data[i].deadline;
            $var.text(deadline.substr(0,deadline.length-3));
            $var.addClass('deadline');
            $var.appendTo($div);


            var $timeTrack = $('<span>');
            var currentData = new Date();
            deadline = new Date(deadline);

            deadline.setFullYear(deadline.getFullYear()-currentData.getFullYear());
            deadline.setMonth(deadline.getMonth()-currentData.getMonth());
            deadline.setDate(deadline.getDate()-currentData.getDate());
            deadline.setHours(deadline.getHours()-currentData.getHours());
            deadline.setMinutes(deadline.getMinutes()-currentData.getMinutes());

            debugger;
            deadline = normDate(deadline.getFullYear(),toFormat(deadline.getMonth()+1),deadline.getDate(),deadline.getHours(),deadline.getMinutes());

            $timeTrack.text(deadline.substr(0,deadline.length-3));
            $timeTrack.attr({
                'id':'timeTrack'+i
            });



            $timeTrack.addClass('timeTrack');
            $timeTrack.appendTo($div);

        }
    }

    setInterval(function(){
        for(var i =0;i<data.length;++i) {
            deadline = $('#timeTrack'+i).text();
            console.log(deadline);
            deadline = new Date(deadline);
            deadline.setMinutes(deadline.getMinutes() - 1);
            deadline = normDate(deadline.getFullYear(), toFormat(deadline.getMonth() + 1), deadline.getDate(), deadline.getHours(), deadline.getMinutes());
            $('#timeTrack'+i).text(deadline.substr(0, deadline.length - 3));
        }
    },1000);
}

function Calendar_student(){
    var event=[];
    Calendar.call(this);
    var self = this;

    var windowEvent=false;

    this.jqueryObject.deadlineTask ={
        deadlineTaskBt:$('#deadlineTaskBt'),
        deadlineTaskContent:$('#deadlineTaskContent'),
        deadlineTaskContentTitle:$('#deadlineTaskContentTitle'),
        deadlineTaskClose: $('#deadlineTaskClose'),
        deadlineTaskContentContent:$('#deadlineTaskContentContent'),
        deadlineTaskResize: $('#deadlineTaskResize')
    }
    this.option.eventSources= [
        {
            events: function(start, end, timezone, callback) {
                start=start._d;
                end=end._d;
                end =  new Date(end);
                end.setMonth(end.getMonth()+1);
                var start1 = normDate(start.getFullYear(),start.getMonth()+1,start.getDay(),start.getHours(),start.getMinutes());
                var end1 = normDate(end.getFullYear(),end.getMonth()+1,end.getDay(),end.getHours(),end.getMinutes());

                universalAPI(
                    url+'app/calendar/addFullEventDefault',
                    'post',
                    function(data){
                        event= data;
                        callback(data);
                        self.masEvent=data;
                        createListLeson(data,self.jqueryObject.deadlineTask.deadlineTaskContentContent);

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

        if(!windowEvent){
            var deadlineTaskContent = self.jqueryObject.deadlineTask.deadlineTaskContent;
            self.jqueryObject.deadlineTask.deadlineTaskContent.css({
                'left':document.documentElement.clientWidth-deadlineTaskContent.css('width').substr(0,deadlineTaskContent.css('width').length-2)-30,
                'top':document.documentElement.clientHeight-deadlineTaskContent.css('height').substr(0,deadlineTaskContent.css('height').length-2)

            });
        }
        windowEvent = true;
        self.jqueryObject.deadlineTask.deadlineTaskContent.show();
    });

    this.jqueryObject.deadlineTask.deadlineTaskClose.on('click',function(){
        self.jqueryObject.deadlineTask.deadlineTaskBt.show();
        self.jqueryObject.deadlineTask.deadlineTaskContent.hide();
    });

    this.dragEvent = function() {
        var drag;
        var clientX;
        var clientY;
        var bool=false;
        this.jqueryObject.deadlineTask.deadlineTaskContentTitle.on('mousedown', function (e) {
            if (e.button === 0)
                drag = self.jqueryObject.deadlineTask.deadlineTaskContent;
            clientX = e.clientX;
            clientY = e.clientY;
        });


        this.jqueryObject.deadlineTask.deadlineTaskContentTitle.on('mouseup', function () {
            drag = null;
        });
        $(document).on('mousemove', function (e) {
            if (drag) {
                drag.css({
                    'left': +drag.css('left').substr(0, drag.css('left').length - 2) + (e.clientX - clientX),
                    'top': +drag.css('top').substr(0, drag.css('top').length - 2) + (e.clientY - clientY)
                });
            }
            clientX = e.clientX;
            clientY = e.clientY;
        });

    }

    //$(".nano").nanoScroller();

    this.resizeDeadline = function(){
        var resize;
        var clientX;
        var clientY;
        var realHeight;
        var realWight;
        this.jqueryObject.deadlineTask.deadlineTaskResize.on('mousedown',function(e) {
            if (e.button === 0) {
                resize = self.jqueryObject.deadlineTask.deadlineTaskContent;
                clientX = e.clientX;
                clientY = e.clientY;
                debugger;
                realWight = +resize.css('width').substr(0, resize.css('width').length - 2);
                realHeight = +resize.css('height').substr(0, resize.css('height').length - 2);

            }
        });
        $(document).on('mouseup', function () {
            resize = null;
        });
        $(document).on('mousemove', function (e) {
            if (resize) {
                var wight = +resize.css('width').substr(0, resize.css('width').length - 2) + (e.clientX - clientX);
                realWight = realWight+ (e.clientX - clientX);
                realHeight = realHeight +(e.clientY - clientY);
                if(realWight<=230){
                    wight = 230;
                }else {
                    wight = realWight;
                }
                var height = +resize.css('height').substr(0, resize.css('height').length - 2) + (e.clientY - clientY);
                if(realHeight<=70){
                    height = 70;
                }else{
                    height=realHeight;
                }
                resize.css({
                    'width': wight,
                    'height': height
                });

                var cont = self.jqueryObject.deadlineTask.deadlineTaskContentContent;

                cont.css({
                    'width': +wight,
                    'height': height-30
                });
            }
            clientX = e.clientX;
            clientY = e.clientY;
        });

    }

    this.jqueryObject.calendar.fullCalendar(this.option);


}
$(document).ready(function() {
    var calendar = new Calendar_student();
    calendar.getCurrentUser();
    calendar.realTimeUpdate();
    calendar.dragEvent();
    calendar.resizeDeadline();

});

