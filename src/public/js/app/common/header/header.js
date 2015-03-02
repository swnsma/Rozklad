(function () {

    if (!Array.prototype.find) {
        Array.prototype.find = function(predicate) {
            if (this == null) {
                throw new TypeError('Array.prototype.find called on null or undefined');
            }
            if (typeof predicate !== 'function') {
                throw new TypeError('predicate must be a function');
            }
            var list = Object(this);
            var length = list.length >>> 0;
            var thisArg = arguments[1];
            var value;

            for (var i = 0; i < length; i++) {
                value = list[i];
                if (predicate.call(thisArg, value, i, list)) {
                    return value;
                }
            }
            return undefined;
        };
    }

    var months = ['января', 'февраля', 'марта', 'апреля',
        'мая', 'июня', 'июля', 'августа',
        'сентября', 'октября', 'ноября', 'декабря'];
    var objects = {};
    var countMess = 0;
    var currentCoef = 1;
    var lessons = [];
    var lastTimeUpdate = 0;
    var humanFriendlyDate = new HumanFriendlyDate();

    function getMaxOfArray(numArray) {
        return Math.max.apply(null, numArray);
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

    function getDateNow(){
        return Date.now() / 1000 | 0;
    }

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
                    lastTimeUpdate= getDateNow();
                    lessons = response;
                    proccessLessons(response, $);
                    //starRealTime($)
                }
            },
            function (error) {
                alert("error: "+error);
            }
        );
    }

    function realtimeUpdate($){
        universalAPI(
            url + "app/lesson/realTimeUpdate",
            "POST",
            function (response) {
                if(response&&response.length){
                    countMess=0;
                    objects.wraper.empty();
                    checkLessonsBeforeUpdate(response);
                    updateLessons(response);
                    proccessLessons(lessons, $);
                    lastTimeUpdate = getDateNow();
                    setTimeout(function(){
                        realtimeUpdate($)
                    },5000);
                }
            },
            function (error) {
                console.log(error);
            }
            ,{
                since:parseInt(lastTimeUpdate)
            }
        );
    }

    function proccessLessons(lessons, $) {
        for (var i=0;i<lessons.length;i++){
            getAllCommentsForLesson(lessons[i],$)
        }
    }

    function getAllCommentsForLesson(lesson,$){
                var res = lesson.mess;
                if(res&&res.length){
                    var noneCom = objects.wraper.children("#none-comments");
                    if(noneCom&&noneCom.length){
                        objects.wraper.empty();
                        objects.count.css("display","block");
                    }
                    var len = res.length;
                    for(var i = 0;i<len;i++){
                        if(res[i].status === "1"){
                            countMess++;
                        }
                    }
                    if(countMess===0){
                        objects.count.css("display","none");
                    }
                    else{
                        objects.count.css("display","block");
                    }
                    var countToStringLen=countMess.toString().length;
                    if(countToStringLen>currentCoef){
                        currentCoef=countToStringLen;
                        objects.count.width(objects.count.width()*currentCoef*0.6);
                    }
                    objects.count.html(countMess);

                    var maxDate = getMaxOfArray(res.map(function(item){
                        return item[0];
                    }));

                    var item =  $(
                        "<div class='item-wrap'>"
                        +"<p class='mess-count'>"+"<b>"+countMess+"</b>"+getRightForm(countMess)+"<b>"+'"'+lesson.title+'"'+"</b>"+"</p>"
                        +"<p class='mess-date'>"+"Дата проведения: "+"<b>"+getFormDate(lesson.start)+"</b>"+"</p>"
                        +"<p class='mess-last-mess'>"+humanFriendlyDate.getDateRus(maxDate)+"</p>"
                        +"</div>").attr("link",url+"app/lesson/id"+lesson.id);
                    objects.wraper.append(
                        item
                    );
                    goLink(item);
                    $("#content-wrap").slimScroll();
                }
    }

    function checkLessonsBeforeUpdate(response){
        var len = response.length,
            item = null;


        for(var i = 0;i<len;i++){
            var lenMes = response[i].mess.length;
            while(item=response[i].mess.find(function(element,i,arr){
                return element.status==="2";
            })){
                var ind = response[i].mess.indexOf(item);
                response[i].mess.splice(ind,1);
                lessons[i].mess.splice(ind,1);
            }
        }
    }
    function updateLessons(response){
        var len = response.length;
        for(var i = 0;i<len;i++){
            var lenMes = response[i].mess.length;
            for(var j = 0;j< lenMes;j++){
                lessons[i].mess.unshift(response[i].mess[j])
            }
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
                if(secondDigit===1){
                    return " новых сообщений в ";
                }
                return " новых сообщения в ";
                break;
            default:
                return " новых сообщений в ";
                break;
        }
    }

    function getFormDate(dat) {
        var date = getOnlyDate(dat);
        var days = date.slice(8,10),
            month = parseInt(date.slice(5,7)),
            years = date.slice(0,4);
        return days+" "+months[month-1]+" "+years;
    }

    function getOnlyDate(date) {
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

    function starRealTime($){
        setTimeout(function(){
            realtimeUpdate($)
        },5000);
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
            if(targ===wrap||targ===icon){
                return false;
            }
            while(targ!==this){
                if(targ===wrap){
                    return false;
                }
                targ=targ.parentNode;
            }
            if(!$(wrap).hasClass("display-none"))
                $(wrap).addClass("display-none");
        });
    };

    $(document).ready(main);

})();