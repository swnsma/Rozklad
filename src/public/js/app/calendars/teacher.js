/**
 * Created by Таня on 16.02.2015.
 */

function Calendar_teacher(jquery_full_calendar,data){
    Calendar.call(this);
    var self = this;

    this.option.editable=true;
    this.option.dragOpacity=0.8;
    this.option.eventDragStart=function(){
        delPopup();
    };


    this.option.eventClick=function(calEvent, jsEvent, view) {
        if(delPopup()){
            return;
        }
        lastEvent=$(this);
        /*if(calEvent.deleted){
            if(jsEvent.target.className==="deletedEvent") {
                var data={
                    id:calEvent.id
                };
                function success(date){
                    if (date[0].teacher === self.currentUser.id) {
                        date[0].color = masColor.myEvents.color;
                        date[0].textColor = masColor.myEvents.textColor;
                    } else {
                        date[0].color = masColor.otherEvents.color;
                        date[0].textColor= masColor.otherEvents.textColor;
                        date[0].textColor= masColor.otherEvents.textColor;
                    }
                    calEvent.deleted = false;
                    jquery_full_calendar.calendar.fullCalendar('removeEvents', calEvent.id);
                    jquery_full_calendar.calendar.fullCalendar('renderEvent', date[0]);
                }
                ajax.restoreEvent(data,success);
                universalAPI(
                    url + 'app/calendar/restore',
                    'post',
                    success,
                    function(){
                        alert('Відновилось');
                    },
                    data
                )
            }
            return;
        }*/
        var teacher =  new AddTeacherToList(jquery_full_calendar.popupEdit.selectTeacher,{
            id:calEvent.teacher
        },calEvent);
        lastEventColor = $(this).css('backgroundColor');
        originalEvent=calEvent;

        $(this).css({  'backgroundColor':'#07375E' });

        var hourStart = calEvent.start._d.getHours();
        hourStart=toFormat(hourStart);
        var minutesStart = calEvent.start._d.getMinutes();
        minutesStart=toFormat(minutesStart);


        var hourEnd =calEvent.end._d.getHours();
        hourEnd=toFormat(hourEnd);


        var minutesEnd = calEvent.end._d.getMinutes();
        minutesEnd=toFormat(minutesEnd);

        var popupEdit =  jquery_full_calendar.popupEdit;
        popupEdit.listGroup.empty();
        popupEdit.tcalInput.val(calEvent.start._d.getDate()+'-'+ (calEvent.start._d.getMonth()+1)+'-'+calEvent.start._d.getFullYear());
        popupEdit.day.day.val(toFormat(calEvent.start._d.getDate()));
        popupEdit.day.month.val(toFormat(calEvent.start._d.getMonth()+1));
        popupEdit.day.year.val(calEvent.start._d.getFullYear());
        popupEdit.popupEdit.show();
        popupEdit.titleEvent.val(calEvent.title);
        popupEdit.start.hour.val(hourStart);
        popupEdit.start.minutes.val(minutesStart);
        popupEdit.end.hour.val(hourEnd);
        popupEdit.end.minutes.val(minutesEnd);
        popupEdit.goToLesson.on('click',function(){
            window.location=url+'app/lesson/id'+calEvent.id;
        });



        //idUpdate=calEvent.id;

        //orig2=calEvent;
        posPopup(jsEvent);
        var mas=[];
        for(var i =0;i<originalEvent.groups.length;++i){
            mas.push(originalEvent.groups[i]);
        }

        selectGroups = new SetSelect({
            element:jquery_full_calendar.popupEdit.listGroup,
            masGroups:my_date.groups,
            selectElement: mas
        });


    };

    //масив всіх даних
    var my_date = {
        user:data.user,
        groups:data.group,
        teacher:data.teacher
    };

    var originalEvent='';
    var lastteacer='';
    var titleEvent = 'Новое событие ';
    var firstLoud=false;
    var selectGroups;

    var lasSelecrDay, lastEvent,lastEventColor;


    this.option.editable=true;
    this.option.dragOpacity=0.8;

    function AddTeacherToList(jquery_element,selected_obj,event){
        jquery_element.empty();
        lastteacer='';
        function createOption(){

            for(var i = 0;i<my_date.teacher.length;++i){
                var opt = document.createElement('option');
                opt.value = my_date.teacher[i].id;
                opt.innerHTML = my_date.teacher[i].name+' '+my_date.teacher[i].surname;
                if(my_date.teacher[i].id===selected_obj.id){
                    opt.selected=true;
                }
                opt = $(opt);
                opt.appendTo(jquery_element);
            }
            jquery_element.on('change',function(){
                //event.teacher=jquery_element.val();
                lastteacer=jquery_element.val();
            });
        }
        createOption();
        this.getSelectedOption =function(){
            return jquery_element.val();
        }
    }

    //отримання всіх івентів
    this.option.eventSources=[
        {
            events: function(start, end, timezone, callback) {
                start=start._d;
                end=end._d;
                var start1 = normDate(start.getFullYear(),start.getMonth()+1,start.getDay(),start.getHours(),start.getMinutes());
                var end1 = normDate(end.getFullYear(),end.getMonth()+1,end.getDay(),end.getHours(),end.getMinutes());
                universalAPI(
                    url+'app/calendars/getOurEventTeacher',
                    'post',
                    function(doc){
                        if(doc.status==='ok') {
                            var eventForCurrentUser = [];
                            var otherEvent = [];
                            var data = doc.data;
                            function render(events,data){
                                if(events.length!==0&&events[events.length-1].id===data.id) {
                                    events[events.length-1].groups.push({
                                        name:data.group_name,
                                        color:data.group_color,
                                        id:data.group_id
                                    });
                                }
                                else{
                                    data.groups = [];
                                    var groups = data.group_name;
                                    if (groups) {
                                        data.groups.push({
                                            name:data.group_name,
                                            color:data.group_color,
                                            id:data.group_id
                                        });
                                    }
                                    events.push(data);
                                }
                            }
                            for (var i = 0; i < data.length; ++i) {
                                if (data[i].teacher === my_date.user.id) {
                                    render(eventForCurrentUser,data[i]);
                                } else {
                                    render(otherEvent,data[i]);
                                }
                            }
                            callback(eventForCurrentUser);
                            if (!firstLoud) {
                                jquery_full_calendar.calendar.fullCalendar('addEventSource', {
                                    events: function (start, end, timezone, callback) {
                                        callback(otherEvent);
                                    },
                                    color: masColor.otherEvents.color,
                                    textColor: masColor.otherEvents.textColor
                                });
                                firstLoud = true;
                            }
                        }
                    },
                    function(err){
                        alert('Помилка при завантаженні данних');
                    },
                    {
                        start:start1,
                        end:end1
                    }
                )
            },
            color: masColor.myEvents.color
        }
    ];

    //рендер івентів
    this.option.eventRender=function(event, element) {
        if(event.color!==masColor.delEvent.color) {
           showTacherAndGroupsToLesson(event, element);
        }
            if(event.count_no_grade&& event.count_no_grade!=='0') {
                var $conteiner = $('<span>');
                $conteiner.addClass('book-conteiner');
                $conteiner.appendTo($(element));

                var $book = $('<span>');
                $book.appendTo($conteiner);
                $book.addClass('book');

                var $text = $('<span>');
                $text.text(event.count_no_grade);
                $text.addClass('book-text');
                $text.appendTo($conteiner);
            }

    }

    function delPopup(){

        if(jquery_full_calendar.popup.popup.css('display')==='block'||jquery_full_calendar.popupEdit.popupEdit.css('display')==='block'){
            jquery_full_calendar.popup.popup.hide();
            //self.realTimeUpdate();
            jquery_full_calendar.popupEdit.popupEdit.hide();
            if(lasSelecrDay) {
                lasSelecrDay.css({
                    'backgroundColor': 'RGBA(0,0,0,0)'
                });
            }
            if(lastEvent) {
                lastEvent.css({
                    'backgroundColor': lastEventColor
                });
            }

            jquery_full_calendar.popup.listGroups.empty();
            //маг метод з файла tcal.js , що б зкинути налаштування маленького календарика
            f_tcalCancel();
            return 1;
        }
        return 0;
    }

    function posPopup(allDay){
        //self.realTimeStop();
        var x= allDay.pageX;
        var y = allDay.pageY;
        var yminus = y-allDay.clientY;
        var height=document.documentElement.clientHeight;
        var width=document.documentElement.clientWidth;
        var widthPopup=jquery_full_calendar.popup.popup.css('width').slice(0,jquery_full_calendar.popup.popup.css('width').length-2);
        var heightPopup=jquery_full_calendar.popup.popup.css('height').slice(0,jquery_full_calendar.popup.popup.css('height').length-2);


        x=x-(+widthPopup)/2;
        if((y+(+heightPopup)-yminus)>=height){
            y=y-heightPopup-20;
        }

        if(x<=0){
            x=1;
        }else
        if((x+(+widthPopup))>=width){
            x=width-widthPopup;

        }
        jquery_full_calendar.popup.popup.css({
            'left':x,
            'top':y+10
        });
        jquery_full_calendar.popupEdit.popupEdit.css({
            'left':x,
            'top':y+10
        })
    }

    this.option.dayClick=function(date, allDay, jsEvent, view) {
        jquery_full_calendar.popup.button.delEvent.css({'visibility':'hidden'});
        if(delPopup()){

            return;
        }
       var teacherSelect = new AddTeacherToList(jquery_full_calendar.popup.selectTeacher,my_date.user,my_date.user);

        jquery_full_calendar.popup.tcalInput.val(date._d.getDate()+'-'+ (date._d.getMonth()+1)+'-'+date._d.getFullYear());
        jquery_full_calendar.popup.day.day.val(toFormat(date._d.getDate()));
        jquery_full_calendar.popup.day.month.val(toFormat(date._d.getMonth()+1));
        jquery_full_calendar.popup.day.year.val(date._d.getFullYear());
        jquery_full_calendar.popup.popup.show();
        jquery_full_calendar.popup.typePopup.val(titleEvent);
        jquery_full_calendar.popup.start.hour.val('14');
        jquery_full_calendar.popup.start.minutes.val('00');
        jquery_full_calendar.popup.end.hour.val('16');
        jquery_full_calendar.popup.end.minutes.val('00');




        selectGroups = new SetSelect({
            element:jquery_full_calendar.popup.listGroups,
            masGroups:my_date.groups
        });

        //маг метод з файла tcal.js , що б зкинути налаштування маленького календарика
        f_tcalCancel();

        $(this).css({
            'backgroundColor':'#bce8f1'
        });
        lasSelecrDay= $(this);
        posPopup(allDay);
    };


    this.focusDeleted=function(){
        function focusDelete(item){
            var a ='';
            item.on('focus',function(){
                a=this.value;
                this.value='';
            });
            item.on('focusout',function(){
                if(this.value===''){
                    this.value=a;
                }
            })
        }

        function focusDeleteTitle(item){

            item.on('focus',function(){
                if (this.value===titleEvent)
                    this.value='';
            });
            item.on('focusout',function(){
                if(this.value===''){
                    this.value=titleEvent;
                }
            });
        }
        var jq=jquery_full_calendar;
        var popup = jquery_full_calendar.popup;
        focusDeleteTitle(popup.typePopup);
        focusDelete(popup.day.day);
        focusDelete(popup.day.month);
        focusDelete(popup.day.year);
        focusDelete(popup.start.hour);
        focusDelete(popup.start.minutes);
        focusDelete(popup.end.hour);
        focusDelete(popup.end.minutes);

        var popup = jquery_full_calendar.popupEdit;
        //focusDelete(this.jqueryObject.popupEdit.titleEvent);
        focusDelete(popup.day.day);
        focusDelete(popup.day.month);
        focusDelete(popup.day.year);
        focusDelete(popup.start.hour);
        focusDelete(popup.start.minutes);
        focusDelete(popup.end.hour);
        focusDelete(popup.end.minutes);

    };

    //функція яка відповідає за поведення popup
    this.click_body = function(){

        $(document).on('click',function(event){
            var target=event.target;
            var bool=false;
            while(target.tagName!=='BODY') {
                if (target.className === "fc-day-grid-container"||target.className === "fc-widget-content"||target.className === "popup"||target.id==='group_block'||target.id==='tcal'||target.className === "popupEdit") {
                    bool = true;
                    break;
                } else {
                    target = target.parentElement;
                }
            }
            if(!bool){
                delPopup();
            }
        });

    };

    function crosFocus(jquery){
        jquery.focus();
    }

    //синхронизація маленького календарика і поля для ввода дати
    this.syncTcalInput=function(){

        function private(date) {

            function sync() {
                jquery_full_calendar.popup.tcalInput.val(date.day.val() + '-' + date.month.val() + '-' + date.year.val());
                jquery_full_calendar.popupEdit.tcalInput.val(date.day.val() + '-' + date.month.val() + '-' + date.year.val());
            }

            date.day.mask('99');
            date.day.on('input', function () {
                if (this.value > 31) {
                    this.value = 31;
                }
                if (this.value.length == 2) {
                    if (parseInt(this.value) || this.value==='00') {
                        this.value=parseInt(this.value);
                        //this.value = parseInt(this.value);
                        if(this.value==='00'){
                            this.value='01';
                        }
                        this.value=toFormat(this.value);
                        crosFocus(date.month);
                    }

                }
                sync();
            });
            date.month.mask('99');
            date.month.on('input', function () {
                if (this.value > 12) {
                    this.value = 12;
                }
                if (this.value.length == 2) {
                    if (parseInt(this.value) || this.value==='00') {
                        this.value=parseInt(this.value);
                        this.value=toFormat(this.value);
                        crosFocus(date.year);
                    }
                }
                sync();
            });
            date.year.mask('9999');
            date.year.on('input', function () {
                if (this.value.length == 4 ) {
                    if (parseInt(this.value)|| this.value==='0000') {
                        this.value=parseInt(this.value);
                        //toFormat(this.value);
                        crosFocus(jquery_full_calendar.popup.start.hour);
                        crosFocus(jquery_full_calendar.popupEdit.start.hour);
                    }
                }
                sync();
            });

            $tcalInput.on('input', function () {
                var val = this.value;
                var mas = val.split('-');
                date.day.val(toFormat(mas[0]));
                date.month.val(toFormat(mas[1]));
                date.year.val(toFormat(mas[2]));
            });
            $tcalInputEdit.on('input', function () {
                var val = this.value;
                var mas = val.split('-');
                date.day.val(toFormat(mas[0]));
                date.month.val(toFormat(mas[1]));
                date.year.val(toFormat(mas[2]));
            });

        }
        var date = jquery_full_calendar.popupEdit.day;

        private(date);
        date=jquery_full_calendar.popup.day;
        private(date);




    };

    this.resetPopup=function(){
        jquery_full_calendar.popup.button.reset.on('click',function(){
            delPopup();
            return 0;
        });
    }
    //валідація поля дати
    this.timeIvent=function(){
        function maskEndFocus(mask,focus,type){
            mask.mask('99');
            if(type==='hour'){
                mask.on('input',function(){
                    if(this.value>23){
                        this.value=23;
                    }
                    if(this.value.length==2){
                        if(parseInt(this.value) || this.value==='00') {
                            this.value=parseInt(this.value);
                            this.value=toFormat(this.value);
                            crosFocus(focus);
                        }
                    }
                })
            }
            else{
                mask.on('input',function(){
                    if(this.value>59){
                        this.value=59;
                    }
                    if(type!='minutesEnd') {
                        if (this.value.length === 2) {
                            if (parseInt(this.value) || this.value==='00') {
                                this.value=parseInt(this.value);
                                if(mask!=$minutesEnd) {
                                    this.value=toFormat(this.value);
                                    crosFocus(focus);
                                }

                            }
                        }
                    }
                })
            }
        }
        var $hourBegin = jquery_full_calendar.popup.start.hour;
        var $minutesBegin = jquery_full_calendar.popup.start.minutes;
        var $hourEnd = jquery_full_calendar.popup.end.hour;
        var $minutesEnd = jquery_full_calendar.popup.end.minutes;
        maskEndFocus($hourBegin,$minutesBegin,'hour');
        maskEndFocus($minutesBegin,$hourEnd,'minutes');
        maskEndFocus($hourEnd,$minutesEnd,'hour');
        maskEndFocus($minutesEnd,$minutesEnd,'minutesEnd');


        $hourBegin =jquery_full_calendar.popupEdit.start.hour;
        $minutesBegin = jquery_full_calendar.popupEdit.start.minutes;
        $hourEnd = jquery_full_calendar.popupEdit.end.hour;
        $minutesEnd = jquery_full_calendar.popupEdit.end.minutes;
        maskEndFocus($hourBegin,$minutesBegin,'hour');
        maskEndFocus($minutesBegin,$hourEnd,'minutes');
        maskEndFocus($hourEnd,$minutesEnd,'hour');
        maskEndFocus($minutesEnd,$minutesEnd,'minutesEnd');


    };

    this.keyDown=function(){
        $(document).on('keydown',function(e){

            if(e.keyCode===27){
                delPopup();
            }
        });
    };

    function getCaretPos(input) {
        if (input.createTextRange) {
            var range = document.selection.createRange.duplicate();
            range.moveStart('character', -input.value.length);
            return range.text.length;
        } else {
            return input.selectionStart;
        }
    }

    function elementFocus(startElement,element,keyCode){
        startElement.on('keydown',function(e){
            if(e.keyCode===keyCode) {
                switch (keyCode) {
                    case 39:
                        console.log(getCaretPos(startElement[0]));
                        console.log(startElement.val().length);
                        if (getCaretPos(startElement[0]) === startElement.val().length)
                        {
                            crosFocus(element);
                        }
                        break;
                    case 37:
                        if (getCaretPos(startElement[0]) === 0)
                        {
                            crosFocus(element);
                        }
                        break;
                    case 8:
                        if (startElement.val().length === 0)
                        {
                            crosFocus(element);
                        }
                }
            }
        });
    }

    this.focusDate = function(){
        function createFocus(element){
            elementFocus(element.day.day,element.day.month,39);
            elementFocus(element.day.month,element.day.year,39);
            elementFocus(element.day.year,element.start.hour,39);
            elementFocus(element.start.hour,element.start.minutes,39);
            elementFocus(element.start.minutes,element.end.hour,39);
            elementFocus(element.end.hour,element.end.minutes,39);

            elementFocus(element.end.minutes,element.end.hour,37);
            elementFocus(element.end.hour,element.start.minutes,37);
            elementFocus(element.start.minutes,element.start.hour,37);
            elementFocus(element.start.hour,element.day.year,37);
            elementFocus(element.day.year,element.day.month,37);
            elementFocus(element.day.month,element.day.day,37);

            elementFocus(element.end.minutes,element.end.hour,8);
            elementFocus(element.end.hour,element.start.minutes,8);
            elementFocus(element.start.minutes,element.start.hour,8);
            elementFocus(element.start.hour,element.day.year,8);
            elementFocus(element.day.year,element.day.month,8);
            elementFocus(element.day.month,element.day.day,8);
        }

        createFocus(jquery_full_calendar.popup);
        createFocus(jquery_full_calendar.popupEdit);
    }


    function getAddGroups(masGroups){
        var myAddGroups=masGroups;
        var myget=[];
        for(var i=0;i<myAddGroups.length;++i){
            myget.push(myAddGroups[i].idValue);
        }
        return myget;
    }

    function toNormFormGroup(){
        var mas = [];
        var g = selectGroups.getMasGroups();
        for(var i=0;i< g.length;++i){
            mas.push({
                id:g[i].idValue,
                color:g[i].color,
                name:g[i].name
            });
        };
        return mas;
    }

    this.addLesson=function(){

        var newDate = new Date();
        var jqueryObjectPopup  = jquery_full_calendar.popup;
        jqueryObjectPopup.button.submit.on('click',function(){
            //константи
            var title= (jqueryObjectPopup.typePopup.val()||'Новое событие');
            var year=(jqueryObjectPopup.day.year.val()||newDate.getFullYear());
            var month=(jqueryObjectPopup.day.month.val()||newDate.getMonth()+1);
            var day=(jqueryObjectPopup.day.day.val()||newDate.getDate());
            var hourBegin=(jqueryObjectPopup.start.hour.val()||'14');
            var minutesBegin=(jqueryObjectPopup.start.minutes.val()||'00');
            var hourEnd=(jqueryObjectPopup.end.hour.val()||'16');
            var minutesEnd=(jqueryObjectPopup.end.minutes.val()||'00');

            if(jqueryObjectPopup.typePopup.val().length>1000){
                alert('Cлишком много текста:(');
                jqueryObjectPopup.typePopup.val("Новое событие");
                return false;
            }
            if(! /\S/.test( title )){
                title="Новое событие";
            }
            if(+hourEnd<=+hourBegin){
                hourEnd=hourBegin;
                if(+minutesEnd<=+minutesBegin){
                    minutesEnd=+minutesBegin+1;
                    if(+minutesEnd>=60){
                        minutesEnd=0;
                        hourEnd=+hourEnd+1;
                        if(+hourEnd>23){
                            hourBegin=22;
                            hourEnd=23;
                        }
                    }
                }
            }

            function lentghtCom(string){
                if(string.length!=2){
                    return '0'+string;
                }else{
                    return string;
                }
            }

            hourBegin=lentghtCom(hourBegin);
            minutesBegin=lentghtCom(minutesBegin);
            hourEnd=lentghtCom(hourEnd);
            minutesEnd=lentghtCom(minutesEnd);
            var startFun = function(){
                if(month.length!=2){
                    month='0'+month;
                }
                if(day.length!=2){
                    day='0'+day;
                }
                return year+'-'+month+'-'+day+' '+hourBegin+':'+minutesBegin+':00';
            };
            var endFun = function(){
                if(month.length!=2){
                    month='0'+month;
                }
                if(day.length!=2){
                    day='0'+day;
                }
                return year+'-'+month+'-'+day+' '+hourEnd+':'+minutesEnd+':00';
            };

            var teacher = jqueryObjectPopup.selectTeacher.val();

            var name='';
            var surname='';
            for(var i=0;i<my_date.teacher.length;++i){
                if(my_date.teacher[i].id===teacher){
                    name=my_date.teacher[i].name;
                    surname=my_date.teacher[i].surname;
                    break;
                }
            }


            var color =masColor.myEvents.color;
            var textColor =masColor.myEvents.textColor;
            if(teacher!=my_date.user.id){
                color=masColor.otherEvents.color;
                var textColor = masColor.otherEvents.textColor;
            }

            var data={
                title:title,
                start:startFun(),
                end:endFun(),
                teacher:teacher,
                group: getAddGroups(selectGroups.getMasGroups())
            };
            function success(id){
                jquery_full_calendar.calendar.fullCalendar('renderEvent', {
                    id: id.id,
                    title: title,
                    start: startFun(),
                    end: endFun(),
                    allDay: false,
                    teacher: teacher,
                    teacher_name: name,
                    teacher_surname: surname,
                    color:color,
                    groups:toNormFormGroup(),
                    textColor:textColor
                });

            }

            universalAPI(
                url + 'app/calendars/addEvent/',
                'post',
                success,
                function(){
                    alert('Помилка при добаленні');
                },
                data
            );

            delPopup();
            return false;
        });
    };

    function editGroups(originalGroup,addGrops){

        var myAddGroups=[];
        var myDelGroups=[];

        var r={

        }
        if(!originalGroup) {
            originalGroup=[];
        }

        if(originalGroup.length!==0&&addGrops.length!==0){
            for(var i=0;i<addGrops.length;++i){
                for(var j=0;j<originalGroup.length;++j){
                    if(addGrops[i].id===originalGroup[j].id){
                        break;
                    }
                    if(j===originalGroup.length-1){
                        if(addGrops[i].id!=='0') {
                            myAddGroups.push(addGrops[i].id);
                        }
                    }
                }
            }
            for(var i=0;i<originalGroup.length;++i){
                for(var j=0;j<addGrops.length;++j){
                    if(addGrops[j].id===originalGroup[i].id){
                        break;
                    }
                    if(j===addGrops.length-1){
                        myDelGroups.push(originalGroup[i].id);
                    }
                }
            }
        }

        if(originalGroup.length===0){
            for(var i=0;i<addGrops.length;++i){
                if(addGrops[i].id!=='0') {
                    myAddGroups.push(addGrops[i].id);
                }
            }
        }
        if(addGrops.length===0){
            for(var i=0;i<originalGroup.length;++i){
                myDelGroups.push(originalGroup[i].id);
            }
        }


        if(myAddGroups.length===0&&myDelGroups.length===0){
            return;
        }
        if(myAddGroups.length!==0){
            var myget=[];
            for(var i=0;i<myAddGroups.length;++i){
                myget.push(myAddGroups[i]);
            }
            r.add=myget;

        }
        if(myDelGroups.length!==0){
            var myget=[];
            for(var i=0;i<myDelGroups.length;++i){
                myget.push(myDelGroups[i]);
            }
            r.del=myget;
        }
        return r;
    }


    this.editLesson= function(){
        var newDate = new Date();
        var jqueryObjectPopup  = jquery_full_calendar.popupEdit;
        jqueryObjectPopup.button.submit.on('click',function(){
            var idUpdate = originalEvent.id;
            //константи
            var title= (jqueryObjectPopup.titleEvent.val()||'Новое событие');
            var year=(jqueryObjectPopup.day.year.val()||newDate.getFullYear());
            var month=(jqueryObjectPopup.day.month.val()||newDate.getMonth()+1);
            var day=(jqueryObjectPopup.day.day.val()||newDate.getDate());
            var hourBegin=(jqueryObjectPopup.start.hour.val()||'14');
            var minutesBegin=(jqueryObjectPopup.start.minutes.val()||'00');
            var hourEnd=(jqueryObjectPopup.end.hour.val()||'16');
            var minutesEnd=(jqueryObjectPopup.end.minutes.val()||'00');

            if(! /\S/.test( title )){
                title="Новое событие";
            };
            if(+hourEnd<=+hourBegin){
                hourEnd=hourBegin;
                if(+minutesEnd<=+minutesBegin){
                    minutesEnd=+minutesBegin+1;
                    if(+minutesEnd>=60){
                        minutesEnd=0;
                        hourEnd=+hourEnd+1;
                        if(+hourEnd>23){
                            hourBegin=22;
                            hourEnd=23;
                        }
                    }
                }
            }

            function lentghtCom(string){
                if(string.length!=2){
                    return '0'+string;
                }else{
                    return string;
                }
            }

            hourBegin=lentghtCom(hourBegin);
            minutesBegin=lentghtCom(minutesBegin);
            hourEnd=lentghtCom(hourEnd);
            minutesEnd=lentghtCom(minutesEnd);
            var startFun = function(){
                if(month.length!=2){
                    month='0'+month;
                }
                if(day.length!=2){
                    day='0'+day;
                }
                return year+'-'+month+'-'+day+' '+hourBegin+':'+minutesBegin+':00';
            };
            var endFun = function(){
                if(month.length!=2){
                    month='0'+month;
                }
                if(day.length!=2){
                    day='0'+day;
                }
                return year+'-'+month+'-'+day+' '+hourEnd+':'+minutesEnd+':00';
            };
            var urls=0;

            var teacher  = jquery_full_calendar.popupEdit.selectTeacher.val();
            urls=url + 'app/calendar/updateEvent/';

            var nameteacher = '';
            var surnameTeacher = '';
            for(var i=0;i<my_date.teacher.length;++i){
                if(teacher===my_date.teacher[i].id){
                    nameteacher=my_date.teacher[i].name;
                    surnameTeacher=my_date.teacher[i].surname;
                }
            }
            var color =masColor.myEvents.color;
            var textColor = masColor.myEvents.textColor
            if(teacher!=my_date.user.id){
                color=masColor.otherEvents.color;
                textColor = masColor.otherEvents.textColor
            }
            var originalEventGroup = originalEvent.groups;
            if(lastteacer===''){
                lastteacer=originalEvent.teacher;
            }
            var group =editGroups(originalEventGroup,toNormFormGroup());
            var data = {
                title:title,
                start:startFun(),
                end:endFun(),
                id:+idUpdate,
                teacher:lastteacer,
                group:group
            }
            console.log(group);
            function success(id){
                originalEvent.id=idUpdate;
                originalEvent.title=title;
                originalEvent.start=startFun();
                originalEvent.end=endFun();
                originalEvent.teacher=lastteacer;
                originalEvent.surname=surnameTeacher;
                originalEvent.name=nameteacher;
                originalEvent.color=color;
                originalEvent.groups=toNormFormGroup();
                originalEvent.textColor = textColor;
                jquery_full_calendar.calendar.fullCalendar('updateEvent', originalEvent);

            }

            universalAPI(
                url + 'app/calendars/updateEvent/',
                'post',
                success,
                function(){
                    alert('Подія не була відредагована');
                },
                data
            )
            delPopup();
            return false;
        });
    };


    //ініціалізація календаря
    jquery_full_calendar.calendar.fullCalendar(this.option);

}
$(document).ready(function() {
    var option={
        calendar: $('#calendar'),
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
            },
            selectTeacher:$('#selectTeacherEdit')
        },
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
            groupsBlock:$("#groups"),
            selectTeacher:$('#selectTeacher')
        }
    }
    universalAPI(
        url +'app/calendars/getOurInfForTeacher',
    'post',
    function(success){
        if(success.status==='ok'){
            var calendar = new Calendar_teacher(option,success);
            calendar.focusDeleted();

            calendar.editLesson();

            calendar.click_body();
            calendar.syncTcalInput();
            calendar.timeIvent();
            calendar.addLesson();
            //calendar.delLesson();
            //calendar.realTimeUpdate();
            calendar.keyDown();
            calendar.resetPopup();
            calendar.focusDate();
        }
    },
    function(error){

    })

});