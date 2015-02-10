/**
 * Created by Таня on 09.02.2015.
 */
var ajax={
    getFullEventDefault:function(data,successFunction){
        $.ajax({
            url: url+'app/calendar/addFullEventDefault',
            type: 'POST',
            data:data,
            success: function(data){
                successFunction(data)
            },
            error: function(){
                alert('Щось трапилось з завантаженням подій')
            }
        });
    },
    getCurrentUser: function(successFunction){
        $.ajax({
            url: url + 'app/calendar/getUserInfo',
            type: 'GET',
            contentType: 'application/json',
            dataType: 'json',
            success: function(response){
                successFunction(response);
            },
            error: function(er) {
                alert('Не вдалося отримати ваші дані'+er);
            }

        });
    },
    getOurGroups:function(successFunction){
        $.ajax({
            url: url+'app/calendar/getOurGroups/',
            //url: url+'app/calendar/getGroups/',
            contentType: 'application/json',
            dataType: 'json',
            success: function(doc) {
                successFunction(doc)
            },
            error: function(){
                alert('Не вдалося повернути список груп');
            }
        });
    },
    getOurTeacher:function(successFunction){
        $.ajax({
            url: url+'app/calendar/getOurTeacher/',
            //url: url+'app/calendar/getGroups/',
            contentType: 'application/json',
            dataType: 'json',
            success: function(doc) {
                successFunction(doc);
            },
            error: function(){
                alert('Не вдалося віднайти всіх вчителів');
            }
        });
    },
    addFullEventTeacherCurrent:function(data,success){
        $.ajax({
            url: url+'app/calendar/addFullEventTeacherCurrent',
            type:'post',
            data:data,
            success: function(doc) {
                success(doc);
            },
            error: function(){
                alert('Не вдалось завантажити ваші події :(');
            }
        });
    },
    addFullEventTeacherNoCurrent:function(data,success){
        $.ajax({
            url: url+'app/calendar/addFullEventTeacherNoCurrent',
            type:'post',
            data:data,
            success: function(doc) {
                success(doc);
            },
            error: function(){
                alert('Не вдалось завантажити ваші події :(');
            }
        });
    },
    restoreEvent: function(data,success){
        $.ajax({
            url: url + 'app/calendar/restore',
            type: 'POST',
            data:data,
            success: function (date) {
                success(date);
            },
            error: function (er) {
                alert('Так трапилось, але вашу подію не можливо відновити :(');
            }

        });
    },
    addGroupsToLesson:function(data){
        $.ajax({
            url: url+'app/calendar/addGroupsToLesson',
            type: 'post',
            data:data,
            success: function(response){
                if(response.success=='success'){

                }
            },
            error: function(er) {
                alert('Не вдалося запросити групу на Подію');
            }
        });
    },
    deleteGroupFromLesson:function(data){
        $.ajax({
            url: url + 'app/calendar/deleteGroupFromLesson',
            type: 'post',
            data:data,
            success: function(response){
                if(response.success=='success'){

                }
            },
            error: function(er) {
                alert('Не вдалося вилучити групи з події');
            }
        });
    },
    updateEvent:function(data,success){
        $.ajax({
            url: url + 'app/calendar/updateEvent/',
            type: 'POST',
            data:data,
            success: function(id){
                success(id);
            },
            error: function(er) {
                alert('Не вдалося Редагувати подію');
            }

        });
    },
    addEvent:function(data,success){
        $.ajax({
            url: url + 'app/calendar/addEvent/',
            type: 'POST',
            data:data,
            success: function(id){
                success(id);
            },
            error: function(er) {
                alert('Нажаль Подія не була додана');
            }

        });
    },
    delEvent:function(data,success){
        $.ajax({
            url: url + 'app/calendar/delEvent/',
            type: 'POST',
            data:data,
            success: function(id){
                success(id);
            },
            error: function(er) {
                alert('Подя не була видалена');
            }

        });
    }

}
