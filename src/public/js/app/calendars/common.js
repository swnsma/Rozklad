/**
 * Created by Таня on 16.02.2015.
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

//функція яка приймає час початку та кінця подій які потрібно завантажаити, та по URL відправляє
//запит на API та визиває callback при респонсі
function loudLesson(start,end,callback,url){
    start=start._d;
    end=end._d;
    var start1 = normDate(start.getFullYear(),start.getMonth()+1,start.getDay(),start.getHours(),start.getMinutes());
    var end1 = normDate(end.getFullYear(),end.getMonth()+1,end.getDay(),end.getHours(),end.getMinutes());
    universalAPI(
        url,
        'post',
        function(data){
            if(data.status==='ok'){
                callback(data.data);
            }
        },
        function(){
            alert(';(');
        },
        {
            start:start1,
            end:end1
        })
}

//добавлення до івента вчителя та групи
function showTacherAndGroupsToLesson(event, element){
    if (event.groups) {
        for (var i = 0; i < event.groups.length; ++i) {
            var $var = $('<span>');
            $var.text(event.groups[i].name[0]);
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
                'backgroundColor': event.groups[i].color,
                'fontWeight': 'normal',
                'verticalAlign':'middle',
                'color':'white'
            });
            $(element)/*.find('.fc-time')*/.append($var);

        }
    }
    if (event.teacher) {
        var $var = $('<span>');
        $var.text(event.teacher_name[0] + '.' + event.teacher_surname);
        $var.css({
            'fontSize': '10px',
            'display': 'inline-block'
        });
        $var.appendTo($(element));
    }
}
