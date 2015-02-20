 function ModelHeader(){
        var self=this;
        self.unreadedLessons = ko.observableArray([]);
        self.init=function(){
            universalAPI(
                url+"app/lesson/unreadedMessages",
                "GET",
                function(response){
                    proccessLessons(response);
                },
                function(error){
                    alert("error");
                }
            )
        }
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
             console.log(response);
         },
         error:function(response){
             alert("error");
             console.log(response);
         }
     });
 }

 var model=new ModelHeader();
 model.init();
 ko.applyBindings(model);
