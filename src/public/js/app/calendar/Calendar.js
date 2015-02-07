/**
 * Created by Таня on 23.01.2015.
 */
function getHexRGBColor(color)
{
    color = color.replace(/\s/g,"");
    var aRGB = color.match(/^rgb\((\d{1,3}[%]?),(\d{1,3}[%]?),(\d{1,3}[%]?)\)$/i);

    if(aRGB)
    {
        color = '';
        for (var i=1;  i<=3; i++) color += Math.round((aRGB[i][aRGB[i].length-1]=="%"?2.55:1)*parseInt(aRGB[i])).toString(16).replace(/^(.)$/,'0$1');
    }
    else color = color.replace(/^#?([\da-f])([\da-f])([\da-f])$/i, '$1$1$2$2$3$3');

    return color;
}

function getRgbaHexColor(color){
    var mas={
        0:0,
        1:1,
        2:2,
        3:3,
        4:4,
        5:5,
        6:6,
        7:7,
        8:8,
        9:9,
        a:10,
        b:11,
        c:12,
        d:13,
        e:14,
        f:15
    };
    function to10(p){
        return (mas[p[0]]*16+(mas[p[1]])*1);
    }
    function lighting(R){
        R=R+100;
        return R;
    }
    var R=0;
    var G=0;
    var B=0;
    var A=0;
    R =color.substr(0,2);
    G =color.substr(2,2);
    B =color.substr(4,2);
    R=to10(R);
    G=to10(G);
    B=to10(B);
    R=lighting(R);
    G=lighting(G);
    B=lighting(B);

    return 'RGB('+R+','+G+','+B+')';

}
   function  getRgbaRgbColor(color){
       var colorHex = getHexRGBColor(color);
       var colorRgba = getRgbaHexColor(colorHex);
       return colorRgba;
   }

function toFormat(number){
    if((number+'').length!=2){
        number='0'+number;

    }
    return number;
}
function normDate(year,month,day,hour,minuts){
    function format(num){
        if((num+'').length==1){
            num='0'+num;
        }
        return num;
    }
    return year+'-'+format(month)+'-'+format(day)+' '+format(hour)+':'+format(minuts)+':00';
}

function Calendar(){
    function RealTimeUpdate(){
        //var interval = 60000;//раз в хвилину оновлення
        var interval = 60000;//раз в хвилину оновлення
        var setTime;
        var operation = function(){
            $.ajax({
                url: url+'app/calendar/getRealTimeUpdate/'+interval/1000,
                type: 'POST',
                contentType: 'application/json',
                dataType: 'json',
                success: function(date){
                    for(var i=0;i<date.length;++i){
                        if((+date[i].status)===2&&date[i].teacher===currentUser.id){
                            for(var j=0;j<self.masEvent.length;++j){
                                if(date[i].id===self.masEvent[j].id){
                                    if(self.masEvent[j].deleted){
                                        break;
                                    }else{
                                        self.jqueryObject.calendar.fullCalendar('removeEvents',date[i].id);
                                    }
                                }
                            }
                            continue;
                        }
                        if((+date[i].status)===2) {
                            self.jqueryObject.calendar.fullCalendar('removeEvents',+date[i].id);

                        }else{
                            for(var j =0;j<self.masEvent.length;++j){

                                if( (+date[i].id)===(+self.masEvent[j].id)){
                                    self.jqueryObject.calendar.fullCalendar('removeEvents',date[i].id);
                                    //self.jqueryObject.calendar.fullCalendar('renderEvent',date[i]);
                                    self.masEvent.push(date[i]);
                                    break;
                                }
                            }
                        }
                        if(+date[i].status!=2) {
                            self.jqueryObject.calendar.fullCalendar('renderEvent', date[i]);
                            self.masEvent.push(date[i]);
                        }
                    }
                },
                error: function(er) {
                    alert(er)
                }

            });
        };
        this.start = function(){
            setTime=setInterval(operation,interval);
        };
        this.setInterval=function(interval1){
            interval=interval1;
        };
        this.getInterval=function(){
            return interval;
        }
    }
    var currentUser;

    var self=this;
    this.masEvent=[];
    this.groups=[];
    this.jqueryObject={
        calendar:$('#calendar'),
        popup: {
            typeAction:$('#typeAction'),//тип попапу
            popup: $('#popup'),
            typePopup:$('#eventType'),//Title завдання
            tcal: $('#tcal'),
            tcalInput: $('#tcalInput'),
            start:{
                hour: $('#hourBegin'),
                minutes: $('#minutesBegin')
            },
            end:{
                hour: $('#hourEnd'),
                minutes: $('#minutesEnd')
            },
            day:{
                year:$('#year'),
                month:$('#month'),
                day:$('#day')
            },
            button:{
                delEvent:$('#delEvent'),
                submit:$('#createNewLesson'),
                reset:$('#resetLesson'),
                addGroup:$("#add_group")
            },
            listGroups:$('#listGroups'),
            addGroupBlock:$("#group_block"),
            groupsBlock:$("#groups")
        },
        tooltip:{
            tooltip:$('#tooltip'),
            tooltipTitle: $('#tooltipTitle'),
            tooltipStart: $('#tooltipDate'),
            tooltipEnd: $('#tooltipTimeStartEnd'),
            tooltipAuthor: $('#tooltipAuthor'),
            myTooltipGroupList:$('#myTooltipGroupList')
        },
        popupEdit:{
            tcalInput: $('#tcalInputEdit'),
            popupEdit:$('#popupEdit'),
            titleEvent:$('#titleEventEdit'),
            start:{
                hour:$('#hourBeginEdit'),
                minutes:$('#minutesBeginEdit')
            },
            end:{
                hour:$('#hourEndEdit'),
                minutes:$('#minutesEndEdit')
            },
            day:{
                day:$('#dayEdit'),
                month:$('#monthEdit'),
                year:$('#yearEdit')
            },
            listGroup:$('#listGroupsEdit'),
            button:{
                deleted:$('#resetLessonEdit'),
                submit:$('#createNewLessonEdit')
            }
        }
    };

    var date = new Date();
    var month=date.getMonth()+1;
    var year= date.getFullYear();

    this.option={
        //selectable:true,
        //eventLimit: true, // for all non-agenda views
        firstDay: 1,
        header: {
            //left: 'prev,next today',
            //center: 'title',
            //right: 'month,agendaWeek,agendaDay'

        },
        monthNames: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
        monthNamesShort: ['Янв.', 'Фев.', 'Март', 'Апр.', 'Май', 'Июнь', 'Июль', 'Авг.', 'Сент.', 'Окт.', 'Ноя.', 'Дек.'],
        dayNames: ["Воскресенье", "Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота"],
        dayNamesShort: ["ВС", "ПН", "ВТ", "СР", "ЧТ", "ПТ", "СБ"],
        timezone:'local',
        buttonText: {
            today: "Сегодня",
            month: "Месяц",
            week: "Неделя",
            day: "День"
        },
        timeFormat: 'H:mm',// uppercase H for 24-hour clock
        //handleWindowResize:true,
        //fixedWeekCount:false,
        eventMouseover:function(event, jsEvent, view){
            if(!event.group&&!event.deleted){
                $.ajax({
                    url: url + 'app/calendar/getAllGroupsForThisLesson/' + event.id,
                    type: 'POST',
                    contentType: 'application/json',
                    dataType: 'json',
                    success: function(date){
                        event.group=date;
                        toolTip(event,jsEvent,view,this);
                    },
                    error: function(er) {
                        alert(er);
                    }

                });
            }
            else
            if(!event.deleted) {
                toolTip(event, jsEvent, view,this);
            }


        },
        eventMouseout:function(event, jsEvent, view){
            if(event.deleted!=true)
            {
                    $(this).css({
                        //'color': '#fff',
                        'fontWeight':'normal'
                    });
                self.jqueryObject.tooltip.tooltip.hide();
            }

        },
        eventRender:function(event, element) {
            if(event.group){
                for(var i=0;i<event.group.length;++i){

                    var $var = $('<span>');
                    $var.text(event.group[i].name[0]);
                    $var.css({
                        'display': 'inline-block',
                        'width': '8px',
                        'height': '8px',
                        'fontSize': '8px',
                        'textAlign': 'center',
                        'marginLeft': '2px',
                        'verticalAlign': 'baseline',
                        'backgroundColor': event.group[i].color,
                        'fontWeight': 'normal'
                    });
                    $(element).find('.fc-time').append($var);

                }
            }
        },
        eventSources: [
            {
                events: function(start, end, timezone, callback) {
                    start=start._d;
                    end=end._d;
                    var start1 = normDate(start.getFullYear(),start.getMonth()+1,start.getDay(),start.getHours(),start.getMinutes());
                    var end1 = normDate(end.getFullYear(),end.getMonth()+1,end.getDay(),end.getHours(),end.getMinutes());

                    $.ajax({
                        url: url+'app/calendar/addFullEventDefault'+'/'+start1+'/'+end1,
                        contentType: 'application/json',
                        dataType: 'json',
                        success: function(doc) {
                            self.masEvent=doc;
                            callback(doc);
                            return doc;
                        },
                        error: function(){

                        }
                    });
                },
                color: 'RGB(0,100,160)'  // an option!
            }
        ]
    };

    //дода тултіп
    function toolTip(event, jsEvent, view,thet){

        var events = event.title;
        if(events.length>=78){
            events = events.substr(0, 78) + '...';
        }

        var backColor = ( event.color || event.source.color );
        var hex = getRgbaRgbColor(backColor);
        self.jqueryObject.tooltip.tooltip.css({
            'backgroundColor':hex
        });
        self.jqueryObject.tooltip.tooltipTitle.text(events);


        var dateEnd = new Date(event.end);
        var dateStart = new Date(event.start);
        var minutesEnd = dateEnd.getMinutes();
        var month = dateStart.getMonth()+1;
        var date = dateStart.getDate();
        var hourStart = dateStart.getHours();
        var minutesStart = dateStart.getMinutes();
        var hour = dateEnd.getHours();

        month=toFormat(month);
        date=toFormat(date);
        hourStart=toFormat(hourStart);
        minutesEnd=toFormat(minutesEnd);
        minutesStart=toFormat(minutesStart);
        hour=toFormat(hour);

        dateStart =  dateStart.getFullYear()+'-'+month+'-'+date+' ';

        self.jqueryObject.tooltip.tooltipStart.text(dateStart);

        self.jqueryObject.tooltip.tooltipEnd.text(hourStart+':'+minutesStart+'-'+hour + ':' + minutesEnd);
        var author = event.name + ' ' + event.surname;
        if(author.length>=78){
            author = author.substr(0, 78) + '...';
        }
        self.jqueryObject.tooltip.tooltipAuthor.text(author);

        var XX= jsEvent.offsetX||0;
        var YY=jsEvent.offsetY||0;
        var x = jsEvent.pageX - XX;
        var y = jsEvent.pageY - YY + 17;

        self.jqueryObject.tooltip.tooltip.css({
            'left': x,
            'top': y

        });
        $(thet).css({
            //'color': '#000',
            'fontWeight':'bold'
        });

        self.jqueryObject.tooltip.myTooltipGroupList.empty();
        var group=event.group;
        for(var i=0;i<group.length;++i){
            var $selectList =$('<span class="group-list">');
            $selectList.appendTo(self.jqueryObject.tooltip.myTooltipGroupList);
            $selectList.text(group[i].name);
            if(i!==group.length-1){
                $selectList.text(group[i].name+',');
            }
        }
        self.jqueryObject.tooltip.tooltip.show();
    }

    (this.option.getCurrentUser=function(){
        var urls = url + 'app/calendar/getUserInfo';
        $.ajax({
            url: urls,
            type: 'GET',
            contentType: 'application/json',
            dataType: 'json',
            success: function(response){
                currentUser=response;
                return response;
            },
            error: function(er) {

                alert(er);
            }

        });
    })();

    this.realTimeUpdate=function(){
        var a =new RealTimeUpdate();
        a.start();
    }


}