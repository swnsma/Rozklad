/**
 * Created by Таня on 23.01.2015.
 */
function click_body(){
    $(document).click(function (event) {
        debugger;
        var boolClass=false;
        debugger;
        if(event.target.id==='popup'){
            debugger;
            boolClass=true;
        }
        else {
            var teg =$(event.target).parents(".fc-content-skeleton")[0];
            if(teg) {
                debugger;
                var classList = teg.classList;
                for (var i = 0; i < classList.length; ++i) {
                    if (classList[i] === 'fc-content-skeleton') {
                        boolClass = true;
                        break;
                    }
                }
            }
        }
        if(!boolClass) {
            var classList = $(event.target)[0].classList;
            for (var i = 0; i < classList.length; ++i) {
                if (classList[i] === 'fc-day' || classList[i] === 'fc-day-number') {
                    boolClass = true;
                    break;
                }
            }
        }
        debugger;
        if(event.target.className==='fc-more'){
            boolClass = false;
        }
        if(!boolClass ) {
            $('#popup').hide();
        }

    });
}

function Calendar(id){
    debugger;
    var calendar = $(id);
    var popupp=$(popup);
    this.option= {
        editable: true,
        eventLimit: true, // for all non-agenda views
        firstDay: 1,
        header: {

        },
        monthNames: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
        monthNamesShort: ['Янв.', 'Фев.', 'Март', 'Апр.', 'Май', 'Июнь', 'Июль', 'Авг.', 'Сент.', 'Окт.', 'Ноя.', 'Дек.'],
        dayNames: ["Воскресенье", "Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота"],
        dayNamesShort: ["ВС", "ПН", "ВТ", "СР", "ЧТ", "ПТ", "СБ"],
        buttonText: {
            today: "Сегодня",
            month: "Месяц",
            week: "Неделя",
            day: "День"
        },
        defaultDate: '2014-11-12',
        timeFormat: 'H(:mm)', // uppercase H for 24-hour clock
    };

}