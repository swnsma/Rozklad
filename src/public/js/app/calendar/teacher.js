/**
 * Created by Таня on 22.01.2015.
 */
function click_body(){
    $(document).click(function(){

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
        debugger;
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
        debugger;
    }

    $day.mask('99',{placeholder:"--"});
    $day.on('input',function(){
        debugger;
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

    $month.mask('99',{placeholder:"--"});
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
    $year.mask('9999',{placeholder:"----"});
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
        if(type==='hour'){
            mask.on('input',function(){
                debugger;
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
                if(this.value.length==2){
                    this.value = parseInt(this.value);
                    if(parseInt(this.value)) {
                        this.value=parseInt(this.value);
                        focus.focus();
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
        //маг метод з файла tcal.js , що б зкинути налаштування маленького календарика
        f_tcalCancel();


        var x= allDay.pageX;
        var y = allDay.pageY;
        x=x-$popup.css('width').slice(0,$popup.css('width').length-2)/2;
        $popup.css({
            'left':x,
            'top':y
        });
        debugger;
        click_body();
    };
    $calendar.fullCalendar(this.option);
}
$(document).ready(function() {
    var calendar = new Calendar_teacher('#calendar','#popup');
    syncTcalInput();
    timeIvent();
    //calendar.popup();
});
