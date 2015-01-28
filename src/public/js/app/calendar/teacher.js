/**
 * Created by Таня on 22.01.2015.
 */
//функція яка відповідає за поведення popup
function click_body(){
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
                if (classList[i] === 'fc-day' || classList[i] === 'fc-day-number') {
                    bool = true;
                    break;
                }
            }
        }
        if(!bool) {
            $('#popup').hide();
            //маг метод з файла tcal.js , що б зкинути налаштування маленького календарика
            f_tcalCancel();
        }
    });
}

//синхронизація маленького календарика і поля для ввода дати
function syncTcalInput(){
    function sync(){
        $tcalInput.val($day.val()+'-'+$month.val()+'-'+$year.val());
    }

    $day.mask('99',{placeholder:"-----"});
    $day.on('input',function(){
        if(this.value>31){
            this.value=31;
        }
        if(this.value.length==2){
            if(parseInt(this.value)) {
                this.value=parseInt(this.value);
                $month.focus();
            }

        }
        sync();
    });

    $month.mask('99',{placeholder:"-----"});
    $month.on('input',function(){
        if(this.value>12){
            this.value=12;
        }
        if(this.value.length==2){
            if(parseInt(this.value)) {
                this.value=parseInt(this.value);
                $year.focus();
            }
        }
        sync();
    });
    $year.mask('9999',{placeholder:"---------"});
    $year.on('input',function(){
        if(this.value.length==4){
            if(parseInt(this.value)) {
                this.value=parseInt(this.value);
                $('#hourBegin').focus();
            }
        }
        sync();
    });

    $tcalInput.on('input',function(){
        var val=this.value;
        var mas=val.split('-');
        $day.val(mas[0]);
        $month.val(mas[1]);
        $year.val(mas[2]);
    });
}

//валідація поля дати
function timeIvent(){
    function maskEndFocus(mask,focus,type){
        mask.mask('99');
        focus.val('');
        if(mask===$hourBegin){
            mask.val('14');
        } else
        if(mask===$hourEnd){
            mask.val('16');
        }else{
            mask.val('00');
        }
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
    var $hourBegin = $('#hourBegin');
    var $minutesBegin = $('#minutesBegin');
    var $hourEnd = $('#hourEnd');
    var $minutesEnd = $('#minutesEnd');
    maskEndFocus($hourBegin,$minutesBegin,'hour');
    maskEndFocus($minutesBegin,$hourEnd,'minutes');
    maskEndFocus($hourEnd,$minutesEnd,'hour');
    maskEndFocus($minutesEnd,$minutesEnd,'minutes');


    //$hourBegin.mask('99');
    //if($hourBegin.val().length==2){
    //    $minutesBegin.val('');
    //    $minutesBegin.focus();
    //}
}

//додавання нового івента
function addLesson(calendar,id,popup){

    var $id = $(id);
    var $calendar= $(calendar);
    var $popup=$(popup);
    var newDate = new Date();
    $id.on('click',function(){

        //константи
        var title= ($('#eventType').val()||'Новый ивент');
        var year=($('#year').val()||newDate.getFullYear());
        var month=($('#month').val()||newDate.getMonth()+1);
        var day=($('#day').val()||newDate.getDate());
        var hourBegin=($('#hourBegin').val()||'14');
        var minutesBegin=($('#minutesBegin').val()||'00');
        var hourEnd=($('#hourEnd').val()||'16');
        var minutesEnd=($('#minutesEnd').val()||'00');


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
        var urls=url+'app/calendar/addEvent/' + title+'/'+startFun()+'/'+endFun();
        $.ajax({
            url: urls,
            type: 'POST',
            contentType: 'application/json',
            dataType: 'json',
            success: function(id){
                $calendar.fullCalendar('renderEvent',{
                    id: id,
                    title: title,
                    start: startFun(),
                    end: endFun(),
                    allDay: false
                });
            },
            error: function(er) {
                alert(er);
            }

        });

        $popup.hide();
        return false;
    });
}

//наслідується від простого календара // налаштування календара
function Calendar_teacher(id,popup){
    Calendar.call(this,id);
    var $popup=$(popup);
    var $calendar = $(id);
    this.option.dayClick=function(date, allDay, jsEvent, view) {
        var moment = $('#calendar').fullCalendar('getDate');
        //заповнення дати, тою датою на юку було натиснуто
        $tcalInput.val(date._d.getDate()+'-'+ (date._d.getMonth()+1)+'-'+date._d.getFullYear());
        $day.val(date._d.getDate());
        $month.val(date._d.getMonth()+1);
        $year.val(date._d.getFullYear());
        $popup.show();
        $('#eventType').val('');
        $('#hourBegin').val('14');
        $('#minutesBegin').val('00');
        $('#hourEnd').val('16');
        $('#minutesEnd').val('00');
        //маг метод з файла tcal.js , що б зкинути налаштування маленького календарика
        f_tcalCancel();

        var x= allDay.pageX;
        var y = allDay.pageY;
        x=x-$popup.css('width').slice(0,$popup.css('width').length-2)/2;
        $popup.css({
            'left':x,
            'top':y
        });
    };

    $calendar.fullCalendar(this.option);
}


$(document).ready(function() {
    var calendar = new Calendar_teacher('#calendar','#popup');

    var realTimeUpdate = new RealTimeUpdate();
    realTimeUpdate.start();
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

    click_body();
    syncTcalInput();
    timeIvent();
    addLesson('#calendar','#createNewLesson','#popup');
    //calendar.popup();
    focusDelete($day);
    focusDelete($month);
    focusDelete($year);

    focusDelete($('#hourBegin'));
    focusDelete($('#minutesBegin'));
    focusDelete($('#hourEnd'));
    focusDelete($('#minutesEnd'));

    $('#resetLesson').on('click',function() {
        f_tcalCancel();
        $('#popup').hide();
    });

});
