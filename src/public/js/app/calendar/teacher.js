/**
 * Created by Таня on 22.01.2015.
 */


function remove(elem) {
    return elem.parentNode ? elem.parentNode.removeChild(elem) : elem;
}


//наслідується від простого календара // налаштування календара
Array.prototype.remove = function(from, to) {
    var rest = this.slice((to || from) + 1 || this.length);
    this.length = from < 0 ? this.length + from : from;
    return this.push.apply(this, rest);
};
function Calendar_teacher(){

    Calendar.call(this);

    var titleEvent = 'Новое событие ';
    var self=this;
    self.jqueryObject.popup.selectTeacher=$('#selectTeacher');
    self.jqueryObject.popupEdit.selectTeacher=$('#selectTeacherEdit');


    var idUpdate=0;
    var originalEvent=''; //останій івент на який було натиснуто
    var orig2='';
    var lastDate;
    var lasSelecrDay;

    var lastEvent;
    var lastEventColor;


    var groups=[]; //всі групи
    var selectGroups;

    var ourteacher=[];//всі вчителі

    function AddTeacherToList(jquery_element,selected_obj,event){

        function createOption(){
            jquery_element.empty();
            for(var i = 0;i<ourteacher.length;++i){
                var opt = document.createElement('option');
                opt.value = ourteacher[i].id;
                opt.innerHTML = ourteacher[i].name+' '+ourteacher[i].surname;
                if(ourteacher[i].id===selected_obj.id){
                    opt.selected=true;
                }
                opt = $(opt);
                opt.appendTo(jquery_element);
            }
            jquery_element.on('change',function(){
                event.teacher=jquery_element.val();
            });
        }

        createOption();
        this.getSelectedOption =function(){
            return jquery_element.val();
        }


    }


    //всі групи
    (function(){
        function success(doc){
            groups=doc;
        };
        ajax.getOurGroups(success);
    })();

    //добавлення всіх вчителів
    (function(){
        function success(doc){
            ourteacher=doc;
        };
        ajax.getOurTeacher(success);
    })();



    //функція яка відповідає за зникнення popup'iв
    function delPopup(){

        if(self.jqueryObject.popup.popup.css('display')==='block'||self.jqueryObject.popupEdit.popupEdit.css('display')==='block'){
            self.jqueryObject.popup.popup.hide();
            self.realTimeUpdate();
            self.jqueryObject.popupEdit.popupEdit.hide();
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

            self.jqueryObject.popup.listGroups.empty();
            //маг метод з файла tcal.js , що б зкинути налаштування маленького календарика
            f_tcalCancel();
            return 1;
        }
        return 0;
    }

    //'cursor': 'pointer' по івентах
    self.option.eventMouseover=function(event, jsEvent, view){
        if(!event.deleted) {
            $(this).css({
                'cursor': 'pointer'
            });
        }
    }

    //функція яка відповідає за появленя popup'ів
    function posPopup(allDay){
        self.realTimeStop();
        var x= allDay.pageX;
        var y = allDay.pageY;
        var yminus = y-allDay.clientY;
        var height=document.documentElement.clientHeight;
        var width=document.documentElement.clientWidth;
        var widthPopup=self.jqueryObject.popup.popup.css('width').slice(0,self.jqueryObject.popup.popup.css('width').length-2);
        var heightPopup=self.jqueryObject.popup.popup.css('height').slice(0,self.jqueryObject.popup.popup.css('height').length-2);


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
        self.jqueryObject.popup.popup.css({
            'left':x,
            'top':y+10
        });
        self.jqueryObject.popupEdit.popupEdit.css({
            'left':x,
            'top':y+10
        })
    }

    this.option.eventSources=[
        {
            events: function(start, end, timezone, callback) {
                start=start._d;
                end=end._d;
                var start1 = normDate(start.getFullYear(),start.getMonth()+1,start.getDay(),start.getHours(),start.getMinutes());
                var end1 = normDate(end.getFullYear(),end.getMonth()+1,end.getDay(),end.getHours(),end.getMinutes());
                universalAPI(
                    url+'app/calendar/addFullEventTeacher',
                    'post',
                    function(doc){
                        self.masEvent=doc;
                        callback(self.masEvent['current']);
                        self.jqueryObject.calendar.fullCalendar('addEventSource',{
                            events:function(start, end, timezone, callback){
                                callback(self.masEvent['no']);
                            },
                            color: masColor.otherEvents.color,
                            textColor:masColor.otherEvents.textColor
                        });
                    },
                    function(err){
                        alert('Помилка при завантаженні');
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

    this.option.editable=true;
    this.option.dragOpacity=0.8;

    this.option.eventDragStart=function(){
        delPopup();
    };

    var eventResize = function( event, delta, revertFunc, jsEvent, ui, view){
        if(!event.deleted) {
            var start = new Date(event.start);
            //start.setDate(start.getDate()+delta._data.days);
            start = normDate(start.getFullYear(), toFormat(start.getMonth() + 1), start.getDate(), start.getHours(), start.getMinutes());

            var end = new Date(event.end);
            //end.setDate(end.getDate()+delta._data.days);
            end = normDate(end.getFullYear(), toFormat(end.getMonth() + 1), end.getDate(), end.getHours(), end.getMinutes());
            universalAPI(
                url + 'app/calendar/eventDrop',
                'post',
                function (data) {
                    if (data.status !== 'ok') {
                        alert('щось трапилось дивне');
                    } else {
                        var myevent = {
                            start: start,
                            end: end,
                            teacher: event.teacher,
                            id: event.id,
                            name: event.name,
                            surname: event.surname,
                            group: event.group,
                            title: event.title,
                            color: (function () {
                                if (self.currentUser.id === event.teacher) {
                                    return masColor.myEvents.color;
                                } else {
                                    return masColor.otherEvents.color;
                                }
                            })(),
                            textColor: (function () {
                                if (self.currentUser.id === event.teacher) {
                                    return masColor.myEvents.textColor;
                                } else {
                                    return masColor.otherEvents.textColor;
                                }
                            })()
                        }
                        self.jqueryObject.calendar.fullCalendar('removeEvents', event.id);
                        self.jqueryObject.calendar.fullCalendar('renderEvent', myevent);
                    }
                },
                function () {
                    alert('Помилка');
                },
                {
                    start: start,
                    end: end,
                    id: event.id
                }
            )
        }
    }
    this.option.eventDrop=function( event, delta, revertFunc, jsEvent, ui, view ){
        //delPopup();
        eventResize(event, delta, revertFunc, jsEvent, ui, view);
    };
    this.option.eventResize = function(event, delta, revertFunc, jsEvent, ui, view){
        eventResize(event, delta, revertFunc, jsEvent, ui, view);
    }
    this.option.dayClick=function(date, allDay, jsEvent, view) {
        self.jqueryObject.popup.button.delEvent.css({'visibility':'hidden'});
        if(delPopup()){

            return;
        }
        var teacherSelect = new AddTeacherToList(self.jqueryObject.popup.selectTeacher,self.currentUser,self.currentUser);

        self.jqueryObject.popup.tcalInput.val(date._d.getDate()+'-'+ (date._d.getMonth()+1)+'-'+date._d.getFullYear());
        self.jqueryObject.popup.day.day.val(toFormat(date._d.getDate()));
        self.jqueryObject.popup.day.month.val(toFormat(date._d.getMonth()+1));
        self.jqueryObject.popup.day.year.val(date._d.getFullYear());
        self.jqueryObject.popup.popup.show();
        self.jqueryObject.popup.typePopup.val(titleEvent);
        self.jqueryObject.popup.start.hour.val('14');
        self.jqueryObject.popup.start.minutes.val('00');
        self.jqueryObject.popup.end.hour.val('16');
        self.jqueryObject.popup.end.minutes.val('00');

        //var b = new CreateSelect( self.jqueryObject.popup.listGroups);
        selectGroups = new SetSelect({
            element:self.jqueryObject.popup.listGroups,
            masGroups:groups
        });
        //self.jqueryObject.popup.typeAction.text('Создать событие');
        self.jqueryObject.popup.button.submit.text('Создать');
        //маг метод з файла tcal.js , що б зкинути налаштування маленького календарика
        f_tcalCancel();

        $(this).css({
            'backgroundColor':'#bce8f1'
        });
        lasSelecrDay= $(this);
        posPopup(allDay);
    };

    this.option.eventClick=function(calEvent, jsEvent, view) {
        if(delPopup()){
            return;
        }
        lastEvent=$(this);
        if(calEvent.deleted){
            if(jsEvent.target.className=="deletedEvent") {
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
                    self.jqueryObject.calendar.fullCalendar('removeEvents', calEvent.id);
                    self.jqueryObject.calendar.fullCalendar('renderEvent', date[0]);
                }
                ajax.restoreEvent(data,success);
            }
            return;
        }
        var teacher =  new AddTeacherToList(self.jqueryObject.popupEdit.selectTeacher,{
            id:calEvent.teacher
        },calEvent);
        lastEventColor = $(this).css('backgroundColor');
        originalEvent=calEvent;

        $(this).css({  'backgroundColor':'#07375E' });

        var hourStart = calEvent.start._d.getHours();
        hourStart=toFormat(hourStart);
        var minutesStart = calEvent.start._d.getMinutes();
        minutesStart=toFormat(minutesStart);


        debugger;
        var hourEnd =calEvent.end._d.getHours();
        hourEnd=toFormat(hourEnd);


        var minutesEnd = calEvent.end._d.getMinutes();
        minutesEnd=toFormat(minutesEnd);

        self.jqueryObject.popupEdit.listGroup.empty();
        self.jqueryObject.popupEdit.tcalInput.val(calEvent.start._d.getDate()+'-'+ (calEvent.start._d.getMonth()+1)+'-'+calEvent.start._d.getFullYear());
        self.jqueryObject.popupEdit.day.day.val(toFormat(calEvent.start._d.getDate()));
        self.jqueryObject.popupEdit.day.month.val(toFormat(calEvent.start._d.getMonth()+1));
        self.jqueryObject.popupEdit.day.year.val(calEvent.start._d.getFullYear());
        self.jqueryObject.popupEdit.popupEdit.show();
        self.jqueryObject.popupEdit.titleEvent.val(calEvent.title);
        self.jqueryObject.popupEdit.start.hour.val(hourStart);
        self.jqueryObject.popupEdit.start.minutes.val(minutesStart);
        self.jqueryObject.popupEdit.end.hour.val(hourEnd);
        self.jqueryObject.popupEdit.end.minutes.val(minutesEnd);
        self.jqueryObject.popupEdit.goToLesson.on('click',function(){
            window.location=url+'app/lesson/id'+calEvent.id;
        });



        idUpdate=calEvent.id;

        orig2=calEvent;
        posPopup(jsEvent);
        var mas=[];
        for(var i =0;i<originalEvent.group.length;++i){
            mas.push(originalEvent.group[i]);
        }

        selectGroups = new SetSelect({
            element:self.jqueryObject.popupEdit.listGroup,
            masGroups:groups,
            selectElement: mas
        });


    };



    //моя функція
    function getAddGroups(masGroups){
        var myAddGroups=masGroups;
        var myget=[];
        for(var i=0;i<myAddGroups.length;++i){
            myget.push(myAddGroups[i].idValue);
        }
        return myget;
    }

    self.getCurrentUser=function(){
        function success(response){
            self.currentUser=response;
            return response;
        }
        ajax.getCurrentUser(success);

    };

    this.jqueryObject.calendar.fullCalendar(this.option);

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
        focusDeleteTitle(this.jqueryObject.popup.typePopup);
        focusDelete(this.jqueryObject.popup.day.day);
        focusDelete(this.jqueryObject.popup.day.month);
        focusDelete(this.jqueryObject.popup.day.year);
        focusDelete(this.jqueryObject.popup.start.hour);
        focusDelete(this.jqueryObject.popup.start.minutes);
        focusDelete(this.jqueryObject.popup.end.hour);
        focusDelete(this.jqueryObject.popup.end.minutes);

        //focusDelete(this.jqueryObject.popupEdit.titleEvent);
        focusDelete(this.jqueryObject.popupEdit.day.day);
        focusDelete(this.jqueryObject.popupEdit.day.month);
        focusDelete(this.jqueryObject.popupEdit.day.year);
        focusDelete(this.jqueryObject.popupEdit.start.hour);
        focusDelete(this.jqueryObject.popupEdit.start.minutes);
        focusDelete(this.jqueryObject.popupEdit.end.hour);
        focusDelete(this.jqueryObject.popupEdit.end.minutes);

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
        jquery.on('change',function(){
            jquery.focus(function(){
                setTimeout(function(){
                    jquery.select();
                },1);
            });
        });
    }

    //синхронизація маленького календарика і поля для ввода дати
    this.syncTcalInput=function(){

        function private(date) {

            function sync() {
                self.jqueryObject.popup.tcalInput.val(date.day.val() + '-' + date.month.val() + '-' + date.year.val());
                self.jqueryObject.popupEdit.tcalInput.val(date.day.val() + '-' + date.month.val() + '-' + date.year.val());
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
                        crosFocus(self.jqueryObject.popup.start.hour);
                        crosFocus(self.jqueryObject.popupEdit.start.hour);
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
        var date = self.jqueryObject.popupEdit.day;

        private(date);
        date=self.jqueryObject.popup.day;
        private(date);




    };

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
                                    crosFocus(focus);
                                }

                            }
                        }
                    }
                })
            }
        }
        var $hourBegin = self.jqueryObject.popup.start.hour;
        var $minutesBegin = self.jqueryObject.popup.start.minutes;
        var $hourEnd = self.jqueryObject.popup.end.hour;
        var $minutesEnd = self.jqueryObject.popup.end.minutes;
        maskEndFocus($hourBegin,$minutesBegin,'hour');
        maskEndFocus($minutesBegin,$hourEnd,'minutes');
        maskEndFocus($hourEnd,$minutesEnd,'hour');
        maskEndFocus($minutesEnd,$minutesEnd,'minutesEnd');


        $hourBegin = self.jqueryObject.popupEdit.start.hour;
        $minutesBegin = self.jqueryObject.popupEdit.start.minutes;
        $hourEnd = self.jqueryObject.popupEdit.end.hour;
        $minutesEnd = self.jqueryObject.popupEdit.end.minutes;
        maskEndFocus($hourBegin,$minutesBegin,'hour');
        maskEndFocus($minutesBegin,$hourEnd,'minutes');
        maskEndFocus($hourEnd,$minutesEnd,'hour');
        maskEndFocus($minutesEnd,$minutesEnd,'minutesEnd');


    };

    //моя функція
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
    this.editLesson= function(){
        var newDate = new Date();
        var jqueryObjectPopup  = self.jqueryObject.popupEdit;
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

            var teacher  = self.jqueryObject.popupEdit.selectTeacher.val();
            urls=url + 'app/calendar/updateEvent/' + title + '/' + startFun() + '/' + endFun()+'/'+(+idUpdate)+'/'+teacher;

            var nameteacher = '';
            var surnameTeacher = '';
            for(var i=0;i<ourteacher.length;++i){
                if(teacher===ourteacher[i].id){
                    nameteacher=ourteacher[i].name;
                    surnameTeacher=ourteacher[i].surname;
                }
            }
            var color =masColor.myEvents.color;
            var textColor = masColor.myEvents.textColor
            if(teacher!=self.currentUser.id){
                color=masColor.otherEvents.color;
                textColor = masColor.otherEvents.textColor
            }
            var originalEventGroup = originalEvent.group;
            var data = {
                title:title,
                start:startFun(),
                end:endFun(),
                id:+idUpdate,
                teacher:teacher,
                group:editGroups(originalEventGroup,toNormFormGroup())
            }
            function success(id){

                originalEvent.id=idUpdate;
                originalEvent.title=title;
                originalEvent.start=startFun();
                originalEvent.end=endFun();
                originalEvent.teacher=teacher;
                originalEvent.surname=surnameTeacher;
                originalEvent.name=nameteacher;
                originalEvent.color=color;
                originalEvent.group=toNormFormGroup();
                originalEvent.textColor = textColor;

                self.jqueryObject.calendar.fullCalendar('updateEvent', originalEvent);

            }
            ajax.updateEvent(data,success);
            delPopup();
            return false;
        });
    };
    //додавання нового івента
    this.addLesson=function(){
        var newDate = new Date();
        var jqueryObjectPopup  = self.jqueryObject.popup;
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
            for(var i=0;i<ourteacher.length;++i){
                if(ourteacher[i].id===teacher){
                    name=ourteacher[i].name;
                    surname=ourteacher[i].surname;
                    break;
                }
            }


            var color =masColor.myEvents.color;
            var textColor =masColor.myEvents.textColor;
            if(teacher!=self.currentUser.id){
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
                //self.masEvent.push({id: id.id,
                //    title: title,
                //    start: startFun(),
                //    end: endFun(),
                //    allDay: false,
                //    teacher:teacher});
                self.jqueryObject.calendar.fullCalendar('renderEvent', {
                    id: id.id,
                    title: title,
                    start: startFun(),
                    end: endFun(),
                    allDay: false,
                    teacher: teacher,
                    name: name,
                    surname: surname,
                    color:color,
                    group:toNormFormGroup(),
                    textColor:textColor
                });

            }
            ajax.addEvent(data,success);

           delPopup();
            return false;
        });
    };

    this.delLesson=function(){

        this.jqueryObject.popupEdit.button.deleted.on('click',function(){
            var data={
                id:+originalEvent.id
            }
            function success(id){
                originalEvent.color= masColor.delEvent.color;
                originalEvent.textColor = masColor.delEvent.textColor;
                originalEvent.deleted=true;
                for(var i =0;i<self.masEvent.length;++i){
                    if(+self.masEvent[i].id===+originalEvent.id){
                        self.masEvent[i].deleted=true;
                        break;
                    }
                }
                self.jqueryObject.calendar.fullCalendar( 'updateEvent' ,originalEvent);

            }
            ajax.delEvent(data,success)
            delPopup();
        });

    };

    this.keyDown=function(){
        $(document).on('keydown',function(e){

            if(e.keyCode===27){
                delPopup();
            }
        });
    };


    this.resetPopup=function(){
        self.jqueryObject.popup.button.reset.on('click',function(){
            delPopup();
            return 0;
        });

    }

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

        createFocus(self.jqueryObject.popup);
        createFocus(self.jqueryObject.popupEdit);
    }
}

$(document).ready(function() {
    var calendar = new Calendar_teacher();
    calendar.getCurrentUser();
    calendar.focusDeleted();
    calendar.editLesson();
    calendar.click_body();
    calendar.syncTcalInput();
    calendar.timeIvent();
    calendar.addLesson();
    calendar.delLesson();
    calendar.realTimeUpdate();
    calendar.keyDown();
    calendar.resetPopup();
    calendar.focusDate();
});