/**
 * Created by Таня on 12.02.2015.
 */

ajaxParam={
    realTimeUpdate:function(currentUser,fullcalendarEvent,jqueryObject,masColor,interval){
        var urla=url+'app/calendar/getRealTimeUpdate/'+interval/100;
        var type = 'GET';
        var data={

        };
        function success(date){
            if(currentUser.title==='student') {
                for (var i = 0; i < date.length; ++i) {
                    if (+date[i].status === 1) {
                        date[i].color=masColor.myEvents.color;
                        date[i].textColor=masColor.myEvents.textColor;
                        jqueryObject.calendar.fullCalendar('removeEvents', date[i].id);
                        jqueryObject.calendar.fullCalendar('renderEvent', date[i]);
                    }
                    if(+date[i].status===2){
                        jqueryObject.calendar.fullCalendar('removeEvents', date[i].id);
                    }
                }

            }
            if(currentUser.title==='teacher'){
                for (var i = 0; i < date.length; ++i){

                    if(+date[i].teacher===+currentUser.id) {
                        date[i].color = masColor.myEvents.color;
                        date[i].textColor = masColor.myEvents.textColor;
                    }else{
                        date[i].color = masColor.otherEvents.color;
                        date[i].textColor = masColor.otherEvents.textColor;
                    }

                    if (+date[i].status === 1) {
                        jqueryObject.calendar.fullCalendar('removeEvents', date[i].id);
                        jqueryObject.calendar.fullCalendar('renderEvent', date[i]);
                    }
                    if (+date[i].status === 2) {
                        var bool=false;
                        for(var j=0;j<fullcalendarEvent.length;++j){
                            if(+fullcalendarEvent[j].id===+date[i].id){
                                bool=true;
                            }
                        }
                        if(!bool) {
                            jqueryObject.calendar.fullCalendar('removeEvents', date[i].id);
                        }
                    }
                }
            }
        };

        function fail(ex){

        };

        universalAPI(urla, type, success, fail, data);
    },
    getCurrentUser: function(currentUser){
        var type = 'Get';
        function success(response){
            currentUser=response;
            return response;
        };
        var fail=function(er) {
            alert('Не вдалося отримати ваші дані'+er);
        }
        universalAPI(urla, type, success, fail, []);
    }
}
