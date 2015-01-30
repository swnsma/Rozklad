/**
 * Created by Таня on 23.01.2015.
 */

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
                            continue;
                        }
                        if((+date[i].status)===2) {
                            self.jqueryObject.calendar.fullCalendar('removeEvents',date[i].id);
                            continue;
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
                        self.jqueryObject.calendar.fullCalendar('renderEvent', date[i]);
                        self.masEvent.push(date[i]);
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
                reset:$('#resetLesson')
            }
        },
        tooltip:{
            tooltip:$('#tooltip'),
            tooltipTitle: $('#tooltipTitle'),
            tooltipStart: $('#tooltipStart'),
            tooltipEnd: $('#tooltipEnd'),
            tooltipAuthor: $('#tooltipAuthor')

        }
    };
    var date = new Date();
    var month=date.getMonth()+1;
    var year= date.getFullYear();

    this.option={
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
            //event.backgroundColor='#004';
            //self.jqueryObject.calendar.fullCalendar('updateEvent',event);

            if(!event.deleted) {
                self.jqueryObject.tooltip.tooltipTitle.text(event.title);
                self.jqueryObject.tooltip.tooltipStart.text(event.start._i);
                var dateEnd = new Date(event.end);
                var minutes = dateEnd.getMinutes();
                if ((minutes + '').length != 2) {
                    minutes = '0' + minutes;
                }
                var hour = dateEnd.getHours();
                if ((hour + '').length != 2) {
                    hour = '0' + hour;
                }
                self.jqueryObject.tooltip.tooltipEnd.text(hour + ':' + minutes);
                self.jqueryObject.tooltip.tooltipAuthor.text(event.name + ' ' + event.surname);

                var XX= jsEvent.offsetX||0;
                var YY=jsEvent.offsetY||0;
                var x = jsEvent.clientX - XX;
                var y = jsEvent.clientY + YY + 10;


                self.jqueryObject.tooltip.tooltip.css({
                    'left': x,
                    'top': y

                });
                $(this).css({
                    'background': '#004'
                });
                self.jqueryObject.tooltip.tooltip.show();
            }



        },
        eventMouseout:function(event, jsEvent, view){
            if(event.deleted!=true)
            {
                $(this).css({
                    'background': '#029acf'
                });
                //event.backgroundColor='';
                //self.jqueryObject.calendar.fullCalendar('updateEvent',event);
                self.jqueryObject.tooltip.tooltip.hide();
                //$(this).css({
                //    'background':'#029acf'
                //});
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


    };

    (this.option.getCurrentUser=function(){
        var urls = url + 'app/calendar/getUserInfo';
        $.ajax({
            url: urls,
            type: 'GET',
            contentType: 'application/json',
            dataType: 'json',
            success: function(response){
                currentUser=response[0];
                return response[0];
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