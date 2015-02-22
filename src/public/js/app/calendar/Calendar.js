/**
 * Created by Таня on 23.01.2015.
 */
fullEventFor = [];
masColor={
    myEvents:{
        color:'RGB(0,100,160)',
        textColor:'#fff'
    },
    otherEvents:{
        color:'RGBA(0,0,0,0)',
        textColor:'#000'
    },
    delEvent:{
        color:'RGBA(1,0,0,0)',
        textColor:'#aaa'
    }
}

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

    var fullcalendarEvent=[];
    var statusRender=0;
    function RealTimeUpdate(){
        //var interval = 60000;//раз в хвилину оновлення
        var interval = 60000;//раз в хвилину оновлення
        var setTime;
        var operation = function(){
            ajaxParam.realTimeUpdate(self.currentUser,fullcalendarEvent,self.jqueryObject,masColor,interval);
        };
        this.start = function(){

            setTime=setInterval(operation,interval);
        };
        this.stop= function () {
            clearInterval(setTime);

        }
        this.setInterval=function(interval1){
            interval=interval1;
        };
        this.getInterval=function(){
            return interval;
        }
    }


    var self=this;
    self.currentUser;
    self.getCurrentUser=function(){
        universalAPI(
            url + 'app/calendar/getUserInfo',
            'get',
            function(response){
                self.currentUser = response;
            },
            function(){
                alert('Біда');
            }, []);
    };
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
            goToLesson:$('#popupEditGoToLesson'),
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
        height:'auto',
        //contentHeight:'auto',
        fixedWeekCount:false,
        allDaySlot:false,
        //aspectRatio:7,
        firstDay: 1,
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'

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
        axisFormat:'H:mm',
        eventRender:function(event, element) {
            if(statusRender===1){
                fullcalendarEvent=[];
                statusRender=0;
                fullEventFor=[];
            }
            if(event.deleted) {
                fullcalendarEvent.push(event);
            }else {
                fullEventFor.push(event);
            }

            if(event.color!==masColor.delEvent.color) {
                if (event.group) {
                    for (var i = 0; i < event.group.length; ++i) {
                        var $var = $('<span>');
                        $var.text(event.group[i].name[0]);
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
                            'backgroundColor': event.group[i].color,
                            'fontWeight': 'normal',
                            'verticalAlign':'middle',
                            'color':'white'
                        });
                        $(element)/*.find('.fc-time')*/.append($var);

                    }
                }
                if (event.teacher) {
                    var $var = $('<span>');
                    $var.text(event.name[0] + '.' + event.surname);
                    $var.css({
                        'fontSize': '10px',
                        'display': 'inline-block'
                    });
                    $var.appendTo($(element));
                }

            }
            if(event.color===masColor.delEvent.color){
                var $textDeleted =  $(element).find('.fc-title');
                $textDeleted.text('Событие удалено');
                $textDeleted.css({
                    'fontSize':'9px'
                });
                var $link  = $('<span>');
                $link.text('Восстановить');
                $link.addClass('deletedEvent');
                $link.appendTo($(element));

            }

            if(event.lesson_info){
                debugger;
                var a = JSON.parse(event.lesson_info);
                if(a['description'].length!=0||a['links'].length!=0||a['files'].length!=0){
                    debugger;
                    var $screpka = $('<span>');
                    $screpka.appendTo($(element));
                    $screpka.addClass('screpka');
                }
            }

            ///////////////////////////////////

            if(event.newdz) {
                if(event.newdz[0].len>0) {
                    var $conteiner = $('<span>');
                    $conteiner.addClass('book-conteiner');
                    $conteiner.appendTo($(element));

                    var $book = $('<span>');
                    $book.appendTo($conteiner);
                    $book.addClass('book');

                    var $text = $('<span>');
                    $text.text(event.newdz[0].len);
                    $text.addClass('book-text');
                    $text.appendTo($conteiner);
                }
            }



            ///////////////////////////////////

        },
        eventAfterAllRender: function(){
            statusRender=1;
        },
        eventClick: function(calEvent, jsEvent, view){
            window.location= url + 'app/lesson/id'+calEvent.id;
        }
    };


    var a =new RealTimeUpdate();
    this.realTimeUpdate=function(){
        a.start();
    }
    this.realTimeStop = function(){
        a.stop();
    }

}
