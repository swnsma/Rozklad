
 universalAPI(
     url+"app/lesson/unreadedMessages",
     "GET",
     function(response){
         proccessLessons(response);
     },
     function(error){
         alert("error");
     }
 );
 function proccessLessons(lessons){
     for(var i=0;i<lessons.length;i++){
         getAllCommentsForThread(lessons[i]);
     }
 }
 function getAllCommentsForThread(lesson){
     var urlThread=url+"app/lesson/id"+lesson.id;
     $.ajax({
         url:"https://disqus.com/api/3.0/threads/listPosts.json",
         data: {api_key:disqusPublicKey,forum:disqusShortname,thread:"link:"+urlThread,since:lesson.last_visit,order:"asc"},
         type:"GET",
         success:function(response){
             console.log(response);
             $("#wrap").append($("<p>"+response.response.length+"</p>"));
         },
         error:function(response){
             alert("error");
             console.log(response);
         }
     });
 }
 $(document).ready(function(){
     $(".message-icon").click(function(){
         displayWrap();
         setWrapPos($(this));
     });
     $(window).resize(function(){
         setWrapPos($(".message-icon"));
     })
 });

 function displayWrap(){
    $("#wrap").toggleClass("display-none");
 }

 function setWrapPos(obj){
     var wrap = $("#wrap"),
         wrapWidth = wrap.width(),
         wrapHeight = wrap.height();
     var offset = obj.offset(),
         top = offset.top,
         left = offset.left;
     wrap.offset({
         top:top+obj.height(),
         left:left-wrapWidth*0.75
     });

 }
