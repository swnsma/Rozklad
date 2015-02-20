 function ModelHeader(){

 }

 function proccessLessons(lessons){
     for(var i=0;i<lessons.length;i++){
         getAllCommentsForThread(lessons[i]);
     }
 }
 function getAllCommentsForThread(lesson){
     var urlThread=url+"app/lesson/id"+lesson.id;
     $.ajax({
         url:"https://disqus.com/api/3.0/threads/listPosts.json",
         data: { api_key:disqusPublicKey,forum:disqusShortname,thread:"link:"+urlThread,since:lesson.last_visit,order:"asc"},
         type:"GET",
         success:function(response){
             debugger;
             alert("success"+" "+response.response.length);
             console.log(response);
         },
         error:function(response){
             alert("error");
             console.log(response);
         }
     });
 }