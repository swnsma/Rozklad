/**
 * Created by Таня on 22.01.2015.
 */
//наслідується від простого календара // налаштування календара
function Calendar_teacher(){

    var masAction = ['create','edit'];
    var action = masAction[0];

    var idUpdate=0;
    var originalEvent='';
    var orig2='';
    var self=this;
    Calendar.call(this);

    var currentUser;
    function isEmpty( el ){
        return !$.trim(el.html())
    }
    function delPopup(){
        if(self.jqueryObject.popup.popup.css('display')==='block'){
            self.jqueryObject.popup.popup.hide();
            //маг метод з файла tcal.js , що б зкинути налаштування маленького календарика
            f_tcalCancel();
            return 1;
        }
        return 0;
    }

    function posPopup(allDay){
        var x= allDay.pageX;
        var y = allDay.pageY;
        var height=screen.height;
        var width=screen.width;
        var widthPopup=self.jqueryObject.popup.popup.css('width').slice(0,self.jqueryObject.popup.popup.css('width').length-2);
        var heightPopup=self.jqueryObject.popup.popup.css('height').slice(0,self.jqueryObject.popup.popup.css('height').length-2);

        x=x-(+widthPopup)/2;
        if((y+(+heightPopup)+70)>=height){
            y=y-heightPopup;
        }
        self.jqueryObject.popup.popup.css({
            'left':x,
            'top':y
        });
    }
    this.option.dayClick=function(date, allDay, jsEvent, view) {
        self.jqueryObject.popup.button.delEvent.css({'visibility':'hidden'});
        if(delPopup()){
            return;
        }
        self.jqueryObject.popup.tcalInput.val(date._d.getDate()+'-'+ (date._d.getMonth()+1)+'-'+date._d.getFullYear());
        self.jqueryObject.popup.day.day.val(date._d.getDate());
        self.jqueryObject.popup.day.month.val(date._d.getMonth()+1);
        self.jqueryObject.popup.day.year.val(date._d.getFullYear());
        self.jqueryObject.popup.popup.show();
        self.jqueryObject.popup.typePopup.val('');
        self.jqueryObject.popup.start.hour.val('14');
        self.jqueryObject.popup.start.minutes.val('00');
        self.jqueryObject.popup.end.hour.val('16');
        self.jqueryObject.popup.end.minutes.val('00');
        //self.jqueryObject.popup.typeAction.text('Создать событие');
        self.jqueryObject.popup.button.submit.text('Создать');
        action = masAction[0];
        //маг метод з файла tcal.js , що б зкинути налаштування маленького календарика
        f_tcalCancel();

        posPopup(allDay);
    };

    this.option.eventClick=function(calEvent, jsEvent, view) {
        if(delPopup()){
            return;
        }
        if(calEvent.deleted){
            $.ajax({
                url: url + 'app/calendar/restore/' + calEvent.id,
                type: 'POST',
                contentType: 'application/json',
                dataType: 'json',
                success: function(date){
                    calEvent.deleted=false;
                    self.jqueryObject.calendar.fullCalendar( 'removeEvents' ,calEvent.id);
                    self.jqueryObject.calendar.fullCalendar( 'renderEvent' ,date[0]);
                },
                error: function(er) {
                    alert(er);
                }

            });
            return;
        }
        if(currentUser.id!==calEvent.teacher){
            return;
        }
        self.jqueryObject.popup.button.delEvent.css({'visibility':'visible'});
        self.jqueryObject.popup.tcalInput.val(calEvent.start._d.getDate()+'-'+ (calEvent.start._d.getMonth()+1)+'-'+calEvent.start._d.getFullYear());
        self.jqueryObject.popup.day.day.val(calEvent.start._d.getDate());
        self.jqueryObject.popup.day.month.val(calEvent.start._d.getMonth()+1);
        self.jqueryObject.popup.day.year.val(calEvent.start._d.getFullYear());
        self.jqueryObject.popup.popup.show();
        self.jqueryObject.popup.typePopup.val(calEvent.title);
        self.jqueryObject.popup.start.hour.val(calEvent.start._d.getHours());
        self.jqueryObject.popup.start.minutes.val(calEvent.start._d.getMinutes());
        self.jqueryObject.popup.end.hour.val(calEvent.end._d.getHours());
        self.jqueryObject.popup.end.minutes.val(calEvent.end._d.getMinutes());
        //self.jqueryObject.popup.typeAction.text('Редактировать');
        self.jqueryObject.popup.button.submit.text('Сохранить');
        var blockGroup=self.jqueryObject.popup.groupsBlock;
        blockGroup.empty();
        var groups = calEvent.group;

        //if (isEmpty(blockGroup))
        //{
            for(var i=0;i<groups.length;i++){
                blockGroup.append($("<p>"+ groups[i].name+"</p>"));
            }
        //}}
        idUpdate=calEvent.id;
        originalEvent=calEvent;
        orig2=calEvent;
        action = masAction[1];
        //маг метод з файла tcal.js , що б зкинути налаштування маленького календарика
        f_tcalCancel();

        var x= jsEvent.pageX;
        var y = jsEvent.pageY;
        x=x-self.jqueryObject.popup.popup.css('width').slice(0,self.jqueryObject.popup.popup.css('width').length-2)/2;
        self.jqueryObject.popup.popup.css({
            'left':x,
            'top':y
        });

    };
    this.jqueryObject.popup.button.addGroup.on("click",function(e){
        var x= e.clientX;
        var y= e.clientY;
        var block = self.jqueryObject.popup.addGroupBlock;
        var x1=$(this).offset().left+100;
        var y1=$(this).offset().top;
        block.css({
            "top":y1,
            "left":x1,
            "display":"block"});

        if (isEmpty(block))
        {
            for(i=0;i<self.groups.length;i++){
                item=$("<p class='group'>"+self.groups[i].name+"</p>");
                item.attr("id",i+"");
            block.append(item)
            }
       self.initGroupClick();

        }
    });


    this.option.getCurrentUser=function(){
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
    };

    this.initGroupClick=function(){
        $(".group").click(function(){
            debugger;
            var id=self.groups[+$(this).attr("id")].id;
            var ii=+$(this).attr("id");
            var urls = url + 'app/calendar/addGroupToLesson/'+originalEvent.id+"/"+id;
            $.ajax({
                url: urls,
                type: 'GET',
                contentType: 'application/json',
                success: function(response){
                    if(response=='ok'){
                       originalEvent.group.push(self.groups[ii]);
                        self.jqueryObject.popup.groupsBlock.append($("<p>"+self.groups[ii].name+"</p>"));
                    }
                },
                error: function(er) {

                    alert(er);
                }

            });
        });
    };
    self.getCurrentUser=function(){
        var urls = url + 'app/calendar/getUserInfo';
        $.ajax({
            url: urls,
            type: 'GET',
            contentType: 'application/json',
            dataType: 'json',
            success: function(response){
                currentUser=response[0];
                debugger;
                self.getGroups();
            },
            error: function(er) {

                alert(er);
            }

        });
    };
    self.getGroups = function(){
        $.ajax({
            url: url+'app/calendar/getGroups/'+currentUser.id,
            contentType: 'application/json',
            dataType: 'json',
            success: function(doc) {
                self.groups=doc;
            },
            error: function(){

            }
        });
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
        focusDelete(this.jqueryObject.popup.day.day);
        focusDelete(this.jqueryObject.popup.day.month);
        focusDelete(this.jqueryObject.popup.day.year);
        focusDelete(this.jqueryObject.popup.start.hour);
        focusDelete(this.jqueryObject.popup.start.minutes);
        focusDelete(this.jqueryObject.popup.end.hour);
        focusDelete(this.jqueryObject.popup.end.minutes);
    };

    //функція яка відповідає за поведення popup
    this.click_body = function(){
        $(document).click(function(event){
            ///метод який приховує popup, якщо натиснуто не на pop'api або ж на дні
            //говноКоДЭ
            var bool=false;
            var target= event.target;
            if(target.className==='fc-more'){
                bool = false;
            }else{
                if(event.target.id==='popup'||event.target.id==='tcal'|| event.target.id==='tcalNextMonth'||
                    event.target.id==='tcalPrevMonth'){
                    bool=true;
                }else{
                    var teg=$(event.target).parents('#popup')[0];
                    if(teg){
                        bool=true;
                    }
                    teg=$(event.target).parents(".fc-content-skeleton")[0];
                    if(teg){
                        bool=true;
                    }
                    teg=$(event.target).parents("#tcal")[0];
                    if(teg){
                        bool=true;
                    }
                }
            }
            if(!bool){
                var classList = $(event.target)[0].classList;
                for (var i = 0; i < classList.length; ++i) {
                    if (classList[i] === 'fc-day' || classList[i] === 'fc-day-number'||classList[i]==='fc-event-container') {
                        bool = true;

                        break;
                    }
                }
            }
            if(!bool&&event.target.id!=="group_block"&&event.target.id!=="group"&&!$(event.target).hasClass("group")) {
                $('#popup').hide();

                //маг метод з файла tcal.js , що б зкинути налаштування маленького календарика
                f_tcalCancel();
            }

            if(event.target.id!=="add_group"&&event.target.id!=="group_block"&&!$(event.target).hasClass("group")){
                $("#group_block").hide();
            }
        });

    };

    //синхронизація маленького календарика і поля для ввода дати
    this.syncTcalInput=function(){
        var date = self.jqueryObject.popup.day;
        function sync(){
            self.jqueryObject.popup.tcalInput.val(date.day.val()+'-'+date.month.val()+'-'+date.year.val());
        }

        date.day.mask('99',{placeholder:"-----"});
        date.day.on('input',function(){
            if(this.value>31){
                this.value=31;
            }
            if(this.value.length==2){
                if(parseInt(this.value)) {
                    this.value=parseInt(this.value);
                    date.month.focus();
                }

            }
            sync();
        });

        date.month.mask('99',{placeholder:"-----"});
        date.month.on('input',function(){
            if(this.value>12){
                this.value=12;
            }
            if(this.value.length==2){
                if(parseInt(this.value)) {
                    this.value=parseInt(this.value);
                    date.year.focus();
                }
            }
            sync();
        });

        date.year.mask('9999',{placeholder:"---------"});
        date.year.on('input',function(){
            if(this.value.length==4){
                if(parseInt(this.value)) {
                    this.value=parseInt(this.value);
                    self.jqueryObject.popup.start.hour.focus();
                }
            }
            sync();
        });

        $tcalInput.on('input',function(){
            var val=this.value;
            var mas=val.split('-');
            date.day.val(mas[0]);
            date.month.val(mas[1]);
            date.year.val(mas[2]);
        });
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
                        if(parseInt(this.value)) {
                            this.value=parseInt(this.value);
                            focus.focus();
                        }
                    }
                })
            }else{
                mask.on('input',function(){
                    if(this.value>59){
                        this.value=59;
                    }
                    if(mask!=$minutesEnd) {
                        if (this.value.length == 2) {
                            this.value = parseInt(this.value);
                            if (parseInt(this.value)) {
                                this.value = parseInt(this.value);

                                focus.focus();

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
        maskEndFocus($minutesEnd,$minutesEnd,'minutes');


    };

    //додавання нового івента
    this.addLesson=function(){
        var newDate = new Date();
        var jqueryObjectPopup  = self.jqueryObject.popup;
        jqueryObjectPopup.button.submit.on('click',function(){
            //константи
            var title= (jqueryObjectPopup.typePopup.val()||'Новый ивент');
            var year=(jqueryObjectPopup.day.year.val()||newDate.getFullYear());
            var month=(jqueryObjectPopup.day.month.val()||newDate.getMonth()+1);
            var day=(jqueryObjectPopup.day.day.val()||newDate.getDate());
            var hourBegin=(jqueryObjectPopup.start.hour.val()||'14');
            var minutesBegin=(jqueryObjectPopup.start.minutes.val()||'00');
            var hourEnd=(jqueryObjectPopup.end.hour.val()||'16');
            var minutesEnd=(jqueryObjectPopup.end.minutes.val()||'00');

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
            if(action===masAction[0]) {
                urls = url + 'app/calendar/addEvent/' + title + '/' + startFun() + '/' + endFun();
            }else if(action===masAction[1]){
                urls=url + 'app/calendar/updateEvent/' + title + '/' + startFun() + '/' + endFun()+'/'+(+idUpdate);
            }


            $.ajax({
                url: urls,
                type: 'POST',
                contentType: 'application/json',
                dataType: 'json',
                success: function(id){
                    if(action===masAction[0]) {
                        self.masEvent.push({id: id.id,
                            title: title,
                            start: startFun(),
                            end: endFun(),
                            allDay: false});
                        self.jqueryObject.calendar.fullCalendar('renderEvent', {
                            id: id.id,
                            title: title,
                            start: startFun(),
                            end: endFun(),
                            allDay: false,
                            teacher: currentUser.id,
                            name: currentUser.name,
                            surname: currentUser.surname
                        });

                    }else{
                        originalEvent.id=idUpdate;
                        originalEvent.title=title;
                        originalEvent.start=startFun();
                        originalEvent.end=endFun();
                        self.jqueryObject.calendar.fullCalendar('updateEvent', originalEvent);
                    }
                },
                error: function(er) {
                    alert(er);
                }

            });

            self.jqueryObject.popup.popup.hide();
            return false;
        });
    };

    this.delLesson=function(){

        this.jqueryObject.popup.button.delEvent.on('click',function(){
            var urls = url + 'app/calendar/delEvent/' + (+originalEvent.id);
            $.ajax({
                url: urls,
                type: 'POST',
                contentType: 'application/json',
                dataType: 'json',
                success: function(id){
                    //self.jqueryObject.calendar.fullCalendar( 'removeEvents' ,originalEvent.id);
                    originalEvent.title='Возобновить';
                    originalEvent.backgroundColor='#999';
                    //originalEvent.borderColor='#999';
                    originalEvent.deleted=true;
                    debugger;
                    self.jqueryObject.calendar.fullCalendar( 'updateEvent' ,originalEvent);
                },
                error: function(er) {

                    alert(er);
                }

            });
            self.jqueryObject.popup.popup.hide();
        });

    };

    this.keyDown=function(){
        $(document).on('keydown',function(e){
            debugger;
            if(e.keyCode===27){
                delPopup();
            }
        });
    }

}

$(document).ready(function() {
    var calendar = new Calendar_teacher();
    calendar.getCurrentUser();
    calendar.focusDeleted();
    calendar.click_body();
    calendar.syncTcalInput();
    calendar.timeIvent();
    calendar.addLesson();
    calendar.delLesson();
    calendar.realTimeUpdate();
    calendar.keyDown();

    calendar.option.getCurrentUser();

    $('#resetLesson').on('click',function() {
        f_tcalCancel();
        $('#popup').hide();
    });

});