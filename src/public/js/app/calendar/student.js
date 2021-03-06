/**
 * Created by Таня on 23.01.2015.
 */

function compare(left,right){
    return   (new Date(left.deadline))-(new Date(right.deadline))  ;
}
function createTextTimer(text, timer,parent){
    var $timer = $('<span>');
    $timer.text(timer);

    parent.append($timer);

    var $text = $('<span>');
    $text.text(text+' ');
    $text.css({
        'fontSize':'8px',
        'color':'#555'
    });

    parent.append($text);
}
var time;
function CreateListLeson(data,parent){

    data.sort(compare);
    var color = '#a20';
    var masTime = [];
    parent.empty();
    var masId=[];
    for(var i =0;i<data.length;++i){
        if(data[i].deadline){

            var deadline = data[i].deadline;
            var currentData = new Date();
            var day =deadline.substr(0,2);
            var month = deadline.substr(3,2);

            var year= function(){
                var ret='';
                for(var i =6; i<deadline.length;++i){
                    if(deadline[i]===' '){
                        break;
                    }else{
                        ret=ret+deadline[i];
                    }
                }
                return ret;
            };
            var deadlinePrint = year()+'/'+month+'/'+day+' '+deadline.substr(deadline.length-5);
            deadline = new Date(deadlinePrint);
            var r = deadline-currentData;
            if(r>0) {
                var $div = $('<div>');
                $div.appendTo(parent);
                $div.attr({
                    'id': 'lesson' + data[i].id
                })
                $div.on('click', function () {
                    window.location = url + 'app/lesson/id' + $(this).attr('id').substr(6);
                });
                $div.addClass('lesson-wrapper');

                var $eventTitle = $('<span>');
                $eventTitle.appendTo($div);
                $eventTitle.text(data[i].title);
                $eventTitle.addClass('event-title');

                for (var j = 0; j < data[i].group.length; ++j) {
                    var $var = $('<span>');
                    $var.appendTo($div);
                    $var.text(data[i].group[j].name[0]);
                    $var.css({
                        'display': 'inline-block',
                        'width': '10px',
                        'height': '10px',
                        'fontSize': '8px',
                        'textAlign': 'center',
                        'marginLeft': '2px',
                        'marginRight': '2px',
                        'borderRadius': '2px',
                        'verticalAlign': 'baseline',
                        'lineHeight': '10px',
                        'backgroundColor': data[i].group[j].color,
                        'fontWeight': 'normal',
                        'verticalAlign': 'middle',
                        'color': 'white'
                    });
                }
                if (data[i].teacher) {
                    var $var = $('<span>');
                    $var.text(data[i].name[0] + '.' + data[i].surname);
                    $var.css({
                        'fontSize': '10px',
                        'display': 'inline-block',
                        'lineHeight': '10px'
                    });
                    $var.appendTo($div);
                }

                var $var = $('<span>');
                var deadlinePrint=deadlinePrint.replace('/','-').replace('/','-');
                $var.text(deadlinePrint);
                $var.addClass('deadline');
                $var.appendTo($div);


                var $timeTrack = $('<span>');


                var minutes = r / 1000 / 60;
                minutes = parseInt(minutes);

                var hour = 0;
                if (minutes >= 60) {
                    hour = minutes / 60;
                    hour = parseInt(hour);
                    minutes = minutes % 60;
                    minutes = parseInt(minutes);
                }
                var day = 0;
                if (hour >= 24) {
                    day = hour / 24;
                    day = parseInt(day);
                    hour = hour % 24;
                    hour = parseInt(hour);
                }

                masTime[data[i].id] = {
                    day: day,
                    hour: hour,
                    minutes: minutes,
                    content: $div
                };
                if (day < 0) {
                    $div.empty();
                }
                $timeTrack.attr({
                    'id': 'timeTrack' + data[i].id
                });

                masId.push(data[i].id);


                $timeTrack.addClass('timeTrack');
                $timeTrack.appendTo($div);


                if (day >= 0) {
                    $timeTrack.empty();
                    var textDay ='дней';
                    var textHour ='часов';
                    var textMinutes = 'минут';
                    if(day===1){
                        textDay='день';
                        textHour='час';
                        textMinutes='минута';
                    }else if(day>=2&&day<=4){
                        textDay='дня'
                        textHour='часа';
                        textMinutes='минуты';
                    }
                    createTextTimer('d',parseInt(day),$timeTrack );
                    createTextTimer('h',parseInt(hour),$timeTrack );
                    createTextTimer('m',parseInt(minutes),$timeTrack );
                    if(data[i].estimate.length!=0){
                        if(data[i].estimate[0].grade) {
                            var $var = $('<span>');
                            $var.addClass('img-checkmark');
                            $var.appendTo($eventTitle);
                        }else{
                            var $var = $('<span>');
                            $var.addClass('img-yellow-checkmark');
                            $var.appendTo($eventTitle);
                        }
                    }
                    //$timeTrack.text(' ' + parseInt(day) + 'дней ' + toFormat(parseInt(hour)) + 'часов ' + toFormat(parseInt(minutes))+'минут');
                } else {
                    $timeTrack.text('');
                    $div.empty();
                }
            }

        }
    }

    this.start=function(){
        time=setInterval(function(){
            for(var i =0;i<masId.length;++i) {
                deadline = masTime[masId[i]];
                if(deadline['day']>=0) {
                    deadline['minutes']--;
                    if (deadline['minutes'] < 0) {
                        deadline['minutes'] = 59;
                        deadline['hour']--;
                        deadline['hour'] = parseInt(deadline['hour']);
                        if (deadline['hour'] < 0) {
                            deadline['hour'] = 23;
                            deadline['day']--;
                            deadline['day'] = parseInt(deadline['day']);
                            if (deadline['day'] < 0) {
                                deadline['content'].empty();
                            }
                        }

                    }
                    $('#timeTrack'+masId[i]).empty();
                    createTextTimer('d',deadline['day'],$('#timeTrack'+masId[i]));
                    createTextTimer('h',toFormat(deadline['hour']),$('#timeTrack'+masId[i]));
                    createTextTimer('m',toFormat(deadline['minutes']),$('#timeTrack'+masId[i]));
                    //$('#timeTrack'+masId[i]).text(''+deadline['day'] +'' + toFormat(deadline['hour']) + ':' + toFormat(deadline['minutes']));
                }else{
                    $('#timeTrack'+masId[i]).text('');
                    deadline['content'].empty();
                }
            }
        },60000);
    }
    this.stop=function(){
        if(time) {
            clearInterval(time);
        }
    }


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
                        var a = new CreateListLeson(data,self.jqueryObject.deadlineTask.deadlineTaskContentContent);
                        a.stop();
                        a.start();


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
            color: masColor.myEvents.color  // an option!
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
        var bool = false;
        self.jqueryObject.deadlineTask.deadlineTaskContentTitle.on('mousedown', function (e) {
                if (e.button === 0)
                    drag = self.jqueryObject.deadlineTask.deadlineTaskContent;
                clientX = e.clientX;
                clientY = e.clientY;
        });


        self.jqueryObject.deadlineTask.deadlineTaskContentTitle.on('mouseup', function () {
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


    $(window).on('resize',function(e){
        var clientHeight=document.documentElement.clientHeight;
        var clientWight = document.documentElement.clientWidth;

        var drag = self.jqueryObject.deadlineTask.deadlineTaskContent;
        var left = +drag.css('left').substr(0, drag.css('left').length - 2)-drag.css('width').substr(0, drag.css('width').length - 2);
        if(left<0){
            left=0;
        }
        if( ((+drag.css('left').substr(0, drag.css('left').length - 2))+(+drag.css('width').substr(0, drag.css('width').length - 2) ))>clientWight ){
            drag.css({
                'left':left
            })
        }
        var top  =+drag.css('top').substr(0, drag.css('top').length - 2)-drag.css('height').substr(0, drag.css('height').length - 2);
        if(top<0){
            top =0;
        }
        if( ((+drag.css('top').substr(0, drag.css('top').length - 2))+(+drag.css('height').substr(0, drag.css('height').length - 2) ))>clientHeight ){
            drag.css({
                'top':top
            })
        }
    });


}
$(document).ready(function() {
    var calendar = new Calendar_student();
    calendar.getCurrentUser();
    //calendar.realTimeUpdate();
    calendar.dragEvent();
    calendar.resizeDeadline();

});

