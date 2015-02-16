/**
 * Created by Таня on 16.02.2015.
 */


function Calendar(){
    this.option={
        fixedWeekCount:false,
        aspectRatio:1.5,
        firstDay: 1,
        header: {
            //left: 'prev,next today',
            //center: 'title',
            //right: 'month,agendaWeek,agendaDay'
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
        eventRender:function(event, element) {
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
            else{
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
        }
    };
}
