
function loadMessages($){
    universalAPI(
        url+"app/lesson/unreadedMessages",
        "GET",
        function(response){
            $(".content-wrap").slimScroll({
                alwaysVisible: true,
                height: 300
            });
            proccessLessons(response,$);
        },
        function(error){
            alert("error");
        }
    );
}

 function proccessLessons(lessons,$){
     for(var i=0;i<lessons.length;i++){
         getAllCommentsForThread(lessons[i],$);
     }
 }
 function getAllCommentsForThread(lesson,$){
     var urlThread=url+"app/lesson/id"+lesson.id;
     $.ajax({
         url:"https://disqus.com/api/3.0/threads/listPosts.json",
         data: {api_key:disqusPublicKey,forum:disqusShortname,thread:"link:"+urlThread,since:lesson.last_visit,order:"asc"},
         type:"GET",
         success:function(response){
             console.log(response);
             var res = response.response;

             if(res.length){
                 var item =  $("<div class='item-wrap'>"
                 +response.response.length+" нових повідомлень у \""
                 +lesson.title+"\"</div>").attr("link",url+"app/lesson/id"+lesson.id);
                 $("#content-wrap").append(
                    item
                 );
                 goLink(item);
                 $(".content-wrap").slimScroll();
             }
         },
         error:function(response){
             //alert("error");
             console.log(response);
         }
     });
 }
var iconClick=function($,that){
    displayWrap();
    setWrapPos(jQuery(that),$);
};
var main = function($){
    loadMessages($);
    $(".message-icon").click(function(){
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


function scroll(){
    $(".content-wrap").customScrollbar();
}
 function displayWrap(){
    $("#wrap").toggleClass("display-none");
 }

 function setWrapPos(obj,$){
     var wrap = jQuery("#wrap"),
         wrapWidth = wrap.width(),
         wrapHeight = wrap.height();
     debugger;
     var offset = obj.offset(),
         top = offset.top,
         left = offset.left;
     wrap.offset({
         top:top+obj.height(),
         left:left-wrapWidth*0.75
     });
 }

 function goLink(obj){
     if(obj.attr("link")){
         obj.on("click",function(){
             var link = $(this).attr("link");
             window.location=link;
         });
     }
 }
$(document).ready(main);
(function($){

})(jQuery);