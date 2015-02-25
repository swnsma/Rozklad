(function () {

    var months = ['января', 'февраля', 'марта', 'апреля',
        'мая', 'июня', 'июля', 'августа',
        'сентября', 'октября', 'ноября', 'декабря'];
    var objects = {};

    var countMess =0;
    function loadMessages($) {
        universalAPI(
            url + "app/lesson/unreaded",
            "GET",
            function (response) {
                $(".content-wrap").slimScroll({
                    alwaysVisible: true,
                    height: 350
                });
                if(response&&response.length) {
                    proccessLessons(response, $);
                }
            },
            function (error) {
                alert("error: "+error);
            }
        );
    }

    function proccessLessons(lessons, $) {
        for (var i=0;i<lessons.length;i++){
            getAllCommentsForLesson(lessons[i],$);
        }
    }

    function getAllCommentsForLesson(lesson,$) {
                var res = lesson.mess;
                if(res&&res.length){
                    var noneCom = objects.wraper.children("#none-comments");
                    if(noneCom&&noneCom.length){
                        objects.wraper.empty();
                    }

                    var len = res.length;
                    countMess+=len;
                    objects.count.html(countMess);
                    var item =  $(
                        "<div class='item-wrap'>"
                        +"<p class='mess-count'>"+"<b>"+len+"</b>"+getRightForm(len)+"<b>"+'"'+lesson.title+'"'+"</b>"+"</p>"
                        +"<p class='mess-date'>"+"Дата проведения: "+"<b>"+getFormDate(lesson.start)+"</b>"+"</p>"
                        +"</div>").attr("link",url+"app/lesson/id"+lesson.id);
                    objects.wraper.append(
                        item
                    );
                    goLink(item);
                    $("#content-wrap").slimScroll();
                }
    }

    function displayWrap() {
        $("#wrap").toggleClass("display-none");
    }

    function setWrapPos(obj,$) {
        var wrap = jQuery("#wrap"),
            wrapWidth = wrap.width(),
            wrapHeight = wrap.height();
        var offset = obj.offset(),
            top = offset.top,
            left = offset.left;
        wrap.offset({
            top:top+obj.height()+10,
            left:left-wrapWidth*0.75
        });
    }

    function goLink(obj) {
        if(obj.attr("link")){
            obj.on("click",function(){
                var link = $(this).attr("link");
                window.location=link;
            });
        }
    }

    function getRightForm(len) {
        var lenStr = len.toString();
        var ln= lenStr.length;
        var firstDigit = parseInt(lenStr[ln-1]);
        var secondDigit = parseInt(lenStr[ln-2]);

        switch (firstDigit){
            case 1:
                if(secondDigit===1){
                    return " новых сообщений в ";
                }
                return " новoe сообщение в ";

                break;
            case 2:
            case 3:
            case 4:
                return " новых сообщения в ";
                break;
            default:
                return " новых сообщений в ";
                break;
        }
    }

    function getFormDate(dat) {
        var date = getDateOf(dat);
        var days = date.slice(8,10),
            month = parseInt(date.slice(5,7)),
            years = date.slice(0,4);
        return days+" "+months[month-1]+" "+years;
    }

    function getDateOf(date) {
        var needed = date.slice(0,10);
        return needed;
    }

    function setArrowPos(obj) {
        var wrap = jQuery("#wrap"),
            wrapWidth = wrap.width(),
            wrapHeight = wrap.height();
        var after = jQuery(".wrap:after");
        var offset = obj.offset(),
            top = offset.top,
            left = offset.left;
        after.css(
            "left",wrapWidth*0.75+(obj.width()/2)
        );
    }

    var iconClick=function($,that) {
        displayWrap();
        setWrapPos(jQuery(that),$);
        setArrowPos(jQuery(that));
    };

    function init(){
        return {
            wraper:$("#content-wrap"),
            icon:$(".message-icon"),
            count:$(".message-count")
        }
    }
    var main = function($){
        objects=init();
        objects.wraper.append(
            $("<p class='none-comments' id='none-comments'>Нет новых комментариев</p>")
        );

        loadMessages($);

        objects.icon.click(function(){
            iconClick($,this);
        });
        $(window).resize(function(){
            setWrapPos(jQuery(".message-icon"),$);
        });
        $("body").on("click",function(e){
            var targ = e.target;
            var wrap = document.getElementById("wrap");
            var icon = document.getElementById("message-icon");
            if(targ!==wrap&&targ!==icon){
                if(!$(wrap).hasClass("display-none"))
                    $(wrap).addClass("display-none");
            }
        });
    };

    $(document).ready(main);

})();
