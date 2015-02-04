/**
 * Created by Таня on 23.01.2015.
 */

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
                            //debugger;
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
                            //debugger;
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
            tooltipStart: $('#tooltipStart'),
            tooltipEnd: $('#tooltipEnd'),
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
            //debugger;
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
                        'color': '#fff',
                        'fontWeight':'normal'
                    });
                self.jqueryObject.tooltip.tooltip.hide();
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
                        url: url+'app/calendar/addFullEvent'+'/'+start1+'/'+end1,
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
                }
            }
        ]
        //eventClick: function(event, element) {
        //    debugger;
        //    event.title = "CLICKED!";
        //
        //    $('#calendar').fullCalendar('updateEvent', event);
        //
        //},
        //,events: [{
        //        title  : 'event3',
        //        start  : '2015-01-09 12:30:00',
        //        allDay : false // will make the time show
        //    }
        //]


    };

    //дода тултіп
    function toolTip(event, jsEvent, view,thet){

        self.jqueryObject.tooltip.tooltipTitle.text(event.title);

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

        dateStart =  dateStart.getFullYear()+'-'+month+'-'+date+' '+hourStart+':'+minutesStart;


        self.jqueryObject.tooltip.tooltipStart.text(dateStart);

        self.jqueryObject.tooltip.tooltipEnd.text(hour + ':' + minutesEnd);
        self.jqueryObject.tooltip.tooltipAuthor.text(event.name + ' ' + event.surname);


        var XX= jsEvent.offsetX||0;
        var YY=jsEvent.offsetY||0;
        var x = jsEvent.pageX - XX;
        var y = jsEvent.pageY - YY + 17;


        self.jqueryObject.tooltip.tooltip.css({
            'left': x,
            'top': y

        });
        $(thet).css({
            'color': '#000',
            'fontWeight':'bold'
        });

        self.jqueryObject.tooltip.myTooltipGroupList.empty();
        var group=event.group;
        for(var i=0;i<group.length;++i){
            var $selectList =$('<span class="group-list">');
            $selectList.appendTo(self.jqueryObject.tooltip.myTooltipGroupList);
            $selectList.text(group[i].name);
            if(i!==group.length-1){
                $selectList.text(group[i].name+' / ');
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