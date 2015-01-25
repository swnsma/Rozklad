/**
 * Created by Таня on 22.01.2015.
 */
function click_body(){
    $(document).click(function(event){

        ///метод який приховує popup, якщо натиснуто не на pop'api або ж на дні
        //говноКоДЭ
        debugger;
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
    $year.on('input',function(){
        if(this.value.length>4){
            this.value.substr(0, this.value.length - 1);
        }
        sync();
    });
    $year.mask('9999',{placeholder:"---------"});
    $tcalInput.on('input',function(){
        var val=this.value;
        var mas=val.split('-');
        $day.val(mas[0]);
        $month.val(mas[1]);
        $year.val(mas[2]);
    });
}

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



        $calendar.fullCalendar('renderEvent', {
            id: 58,
            title: title,
            start: function(){
                if(month.length!=2){
                    month='0'+month;
                }
                if(day.length!=2){
                    day='0'+day;
                }
                return year+'-'+month+'-'+day+'T'+hourBegin+':'+minutesBegin+':00';
            }(),
            end: function(){
                if(month.length!=2){
                    month='0'+month;
                }
                if(day.length!=2){
                    day='0'+day;
                }
                return year+'-'+month+'-'+day+'T'+hourEnd+':'+minutesEnd+':00';
            }()
        });
        $popup.hide();
        return false;
    });
}


function Calendar_teacher(id,popup){
    Calendar.call(this,id);
    var $popup=$(popup);
    var $calendar = $(id);

    this.option.dayClick=function(date, allDay, jsEvent, view) {
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
    var calendar = new Calendar_teacher('#calendar','#popup');
    debugger;
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
