ko.bindingHandlers.uploadTask = {
    init: function (element, valueAccessor) {
        var value = valueAccessor();
        $(element)
            .on('click', function () {
                input.click();
            })
            .wrap('<div />');
        var form = $('<form/>')
            .attr('enctype', 'multipart/form-data')
            .hide()
            .on('change', function (e) {
                $(element).hide();
                if (e.target.files[0].size < 20971520) {
                    $('.fileValid').show();
                    //that.validationMess("");
                    $.ajax({
                        url: url + 'app/lesson/uploadTask/',
                        type: 'POST',
                        processData: false,
                        contentType: false,
                        data: new FormData(form.get(0)),
                        success: function (response) {

                            response.url = url + 'public/users_files/tasks/' + response.newName;
                            if(response.oldName.length>30){
                                response.oldName=response.oldName.substr(0, 29)+'…';
                            }
                            value.files.push(response);
                            value.save();
                            $(element).show()
                        },
                        error: function (xhr) {
                            alert('Чтото пошло не так. Повторите, пожалуйста загрузку файла!');
                            $(element).show()
                        }
                    });
                }
                else {
                    //element.reset();
                    $('.fileValid').hide();
                    alert("Файл слишком большой");
                    $(element).show()
                }
            })
            .insertAfter(element);
        var input = $('<input />')
            .attr('type', 'file')
            .attr('name', 'file')
            .attr('id', 'file')
            .appendTo(form);
    }
};
ko.bindingHandlers.uploadHomework = {
    init: function (element, valueAccessor) {
        var value = valueAccessor();
        $(element)
            .on('click', function () {
                input.click();
            })
            .wrap('<div />');
        var form = $('<form/>')
            .attr('enctype', 'multipart/form-data')
            .hide()
            .on('change', function (e) {
                if (e.target.files[0].size < 20971520) {
                    console.log(value.userInfo());
                    $('.fileValid').show();
                   $.ajax({
                       url: url + 'app/lesson/uploadhomework/' + value.userInfo()[2] + '/' + value.id(),
                       type: 'POST',
                       processData: false,
                       contentType: false,
                       data: new FormData(form.get(0)),
                       success: function (response) {
                           var obj = {};
                           obj.link  = ko.observable(url + 'public/users_files/homework/' +response.newName);
                           obj.grade = "решение еще не проверено.";
                           console.log(value.homeWork().length);
                           if(value.homeWork().length==0)
                           value.homeWork.push(obj);
                           else{
                               value.homeWork(obj);
                           }
                           viewModel.selfHomeWork(true);
                           viewModel.haveGrade(false);
                       },
                       error: function (xhr) {
                           alert('Чтото пошло не так. Повторите, пожалуйста загрузку файла!');
                           $(element).show()
                       }
                   });
                }
                else {
                    $('.fileValid').hide();
                    alert("Файл слишком большой");
                    $(element).show()
                }

            })
            .insertAfter(element);

        var input = $('<input />')
            .attr('type', 'file')
            .attr('name', 'file')

            .appendTo(form);
    }
};
ko.bindingHandlers.setDeadLine = {
    init: function (element, valueAccessor, ava, viewModel ) {
        $("#deadLine").on("click", function(){
            var d = $("#deadLineSettings");
            if(d.css("display")=="none"){
                d.css("display", "block");
            }
            else{
                d.css("display", "none");
            }
        });
        var value = valueAccessor();
        $(element).click(function () {
            var d=toFormatL(parseInt($("#day").val()));
            var t="";
            var mo=toFormatL(parseInt($("#month").val()));
            var ye=parseInt($("#year").val());
            if(!d||!mo||!ye){
                t="Нет";
            }else{
            if(ye.length>4){
                ye=NaN;
            }
            var h=parseInt($('#hour').val());
            if(!h){
                h=14;
            }else{h=toFormatL(h);}
            var m =parseInt($('#min').val());
            if(!m){
                m='00';
            }else{m=toFormatL(m);}
            if(isNaN(d)||isNaN(mo)||isNaN(ye)||isNaN(h)||isNaN(m)){
                viewModel.deadLineErrorMessage("Дата или время введены в неправильном формате.");
                viewModel.deadLineError(true);
                setTimeout(function(){
                    viewModel.deadLineError(false);
                }, 5000);
            }else{
            var date = new Date(ye, mo-1, d, h, m);
            var today = new Date();
            if(Date.parse(date.toString())<Date.parse(today.toString()))
            {
                viewModel.deadLineErrorMessage("Невозможно установить дедлайн, так как введенная дата уже прошла");
                viewModel.deadLineError(true);
                setTimeout(function(){
                    viewModel.deadLineError(false);
                }, 5000);
                return;
            }
            if(date.getDate()!=d||date.getHours()!=h||date.getFullYear()!=ye||(date.getMonth()!=(mo-1))||date.getMinutes()!=m)
            {
                viewModel.deadLineErrorMessage("Введена несуществующая дата");
                viewModel.deadLineError(true);
                setTimeout(function(){
                    viewModel.deadLineError(false);
                }, 5000);
                return;
            }
            t=d+'-'+mo+'-'+ye+' '+h+':'+m;
            }
            }
            value.deadLine(t);
            $("#deadLineSettings").css("display", "none");
            universalAPI(url+'app/lesson/setDeadLine/'+viewModel.id(), "POST", function(response){
                console.log(response);
            }, function(){
                console.log("Something going wrong!");
            }, {deadline: t});
        })}
};
ko.bindingHandlers.getName={
    init: function (element,valueAccessor){
        var value=valueAccessor();
        value.userInfo.push(value.name);
        value.userInfo.push(value.role);
        value.userInfo.push(value.userId);
    }
};
ko.bindingHandlers.changeTab={
    init: function (element,valueAccessor){
        var value=valueAccessor();
        var tab=value.tab;
        $(element).click(function(){
           switch (tab){
               case 'descr':
                   value.descriptionTab(true);
                   $('.activeTab').removeClass('activeTab');
                   $('.taskButton').addClass('activeTab');

                   break;
               case 'tasks':
                   value.descriptionTab(false);
                   $('.activeTab').removeClass('activeTab');
                   $('.homeworkButton').addClass('activeTab');
                   break;

               default :
                   alert('wrong tab')
           }
        })

    }
};


function ViewModel() {
    var that = this;
    //data
    that.links = ko.observable('');
    that.homeWorkDescription = ko.observable('Описание домашнего задания');
    that.links = ko.observableArray([]);
    that.id = ko.observable('');
    that.files = ko.observableArray([]);
    that.homeWork = ko.observableArray([]);
    that.userInfo=ko.observableArray([]);
    that.selfHomeWork=ko.observable(false);
    that.haveGrade = ko.observable(false);
    that.deadLineErrorMessage= ko.observable("");
    that.deadLineError = ko.observable(false);
    that.day = ko.observable("");
    that.month = ko.observable("");
    that.year = ko.observable("");
    that.hour = ko.observable("");
    that.minute = ko.observable("");
    that.loadScr = ko.observable("load-screen");

    that.descriptionTab=ko.observable(true);



    that.deadLinePass=ko.observable(true);


    //editing logic
    that.edit = ko.observable(false);
    that.descriptionEdit = ko.observable(false);
    that.linkToAdd = ko.observable('');
    that.deadLine = ko.observable(false);
    that.rateStudent=ko.observable(false);
    //editing functions
    that.startEdit = function () {
        that.edit(true)
    };
    that.descriptionEditStart = function () {
        that.descriptionEdit(true);
    };
    that.saveDesc = function () {
        if(that.homeWorkDescription().length!=0) {
            that.descriptionEdit(false);
            that.makeArray();
        }
        else {
            that.homeWorkDescription('Описание домашнего задания');
            that.descriptionEdit(false);
            that.makeArray();
        }
    };
    that.showInput=function(){
        that.rateStudent(true)
    };
    that.saveLink = function (viewModel, event) {
        if (event.charCode == 13) {
            if (that.linkToAdd().length) {
                if(that.linkToAdd().substring(0,7)=='http://'||that.linkToAdd().substring(0,7)=='https:/') {
                    var linkName=that.linkToAdd();
                    if(linkName.length>30){
                        linkName=linkName.substr(0, 29)+'…';
                    }
                    that.links.push({name: that.linkToAdd(), nameLink: linkName});
                    that.linkToAdd('');
                    that.makeArray()
                }
                else
                {
                    var linkName='http://'+that.linkToAdd();
                    if(linkName.length>30){
                        linkName=linkName.substr(0, 29)+'…';
                    }
                    that.links.push({name: 'http://'+that.linkToAdd(), nameLink: linkName});
                    that.linkToAdd('');
                    that.makeArray()
                }
            }
        }
        return true;
    };
    that.deleteLink = function (link) {
        for (var i = 0; i < that.links().length; i++) {
            if (that.links()[i].name == link) {
                that.links.remove(that.links()[i])
            }
        }
        that.makeArray();
    };
    that.deleteFile = function (newName) {
        console.log(newName);
        function sendData() {
            $.ajax({
                url: url + 'app/lesson/deleteFile/',
                type: 'POST',
                data: {
                    data: newName
                },
                success: function (response) {
                    for (var i = 0; i < that.files().length; i++) {
                        if (that.files()[i].newName == newName) {
                            that.files.remove(that.files()[i])
                        }
                    }
                    that.makeArray()
                },
                error: function (xhr) {
                    alert(1)
                }
            });
        }
        sendData(newName)
    };
    that.makeArray = function () {
        var data = {
            description: that.homeWorkDescription(),
            links: that.links(),
            files: that.files()
        };
        var datasend = JSON.stringify(data);
        function sendData() {
            $.ajax({
                url: url + 'app/lesson/changeLessonInfo/' + that.id(),
                type: 'POST',
                data: {
                    data: datasend
                },
                success: function (response) {
                    console.log(response);
                },
                error: function (xhr) {
                    alert('1');
                }
            });

        }
        sendData()
    };

    that.setRate=function(viewModel, event){
        if (event.charCode == 13) {
            //console.log(this.grade)
            var datasend={};
            datasend.grade=this.grade;
            datasend.lessonId=this.id;
            datasend.teacherName=that.userInfo()[0];
            $.ajax({
                url: url + 'app/lesson/setRate/',
                type: 'POST',
                data: {
                    data: datasend
                },
                success: function (response) {
                    if(response.result=='success'){
                       alert('Оценка успешно выставлена')
                    }
                    that.rateStudent(false)
                },
                error: function (xhr) {
                    alert('1');
                }
            });

        }
        return true;
    };
    //method that starts magic
    that.activate = function () {
        var lessonId = window.location.pathname;
        var pos = lessonId.search(/id[0-9]+/);
        lessonId = +lessonId.substr(pos + 2, lessonId.length - pos - 2);
        that.id(lessonId);
        universalAPI(url+'app/lesson/getAll/'+that.id(), 'GET', function(resp){
            var response = resp.deadLine;
            that.deadLine(response.result);
            if(response.result!='Нет'&&response.result.length){
            var date = response.result.replace(/([0-9]*)-([0-9]*)-([0-9]*)/, "$1/$2/$3/");
            response.result=response.result.slice(11, 16);
            var time = response.result.replace(/([0-9]*):([0-9]*)/, "$1/$2");
            var curDate = response.time.replace(/([0-9]*)-([0-9]*)-([0-9]*)/, "$1/$2/$3/");
            response.time = response.time.slice(11, 16);
            var curTime = response.time.replace(/([0-9]*):([0-9]*)/, "$1/$2");
            curTime = curTime.split("/");
            curDate = curDate.split("/");
            date = date.split("/");
            time = time.split("/");
            that.day(date[0]);
            that.month(date[1]);
            that.year(date[2]);
            that.hour(time[0]);
            that.minute(time[1]);
            var today = new Date(curDate[2], curDate[1]-1, curDate[0], curTime[0]-1, curTime[1]);
            var deadLineTime= new Date( date[2], date[1]-1, date[0], time[0], time[1]);
            that.deadLinePass(deadLineTime<Date.parse(today));
            }
            else{
                that.deadLinePass(false);
            }
            response= resp.lessonInfo;
            var incomingData = JSON.parse(response[0].lesson_info);
            if(incomingData!=null){
                that.homeWorkDescription(incomingData.description);

                for(var i=0; i<incomingData.links.length; i++){
                    if(incomingData.links[i].name.length>30){
                        incomingData.links[i].nameLink=incomingData.links[i].name.substr(0, 29)+'…';
                    }else{
                        incomingData.links[i].nameLink=incomingData.links[i].name;
                    }
                }
                that.links(incomingData.links);
                for(var i=0; i<incomingData.files.length; i++){
                    if(incomingData.files[i].oldName.length>30){
                        incomingData.files[i].oldName = incomingData.files[i].oldName.substr(0, 29)+'…';
                    }
                }
                that.files(incomingData.files);
            }
            response = resp.tasks;
            if(that.userInfo()[1]=='student'){
                for (var i = 0; i < response.length; i++){
                    if(response[i].name+' '+response[i].surname==that.userInfo()[0]){
                        that.selfHomeWork(true);
                        console.log(response);
                        var homework = {};
                        homework.link = ko.observable( url + 'public/users_files/homework/' + response[i].link);
                        homework.name = response[i].name + ' ' + response[i].surname;
                        if(response[i].grade){
                            homework.grade = response[i].grade;
                            that.haveGrade(true);
                            homework.teacher = response[i].teacher;
                            homework.time = response[i].time;
                        }else{
                            homework.grade="решение еще не проверено."
                        }
                        that.homeWork(homework);
                    }
                }
            }
            if(that.userInfo()[1]=='teacher') {console.log(response)
                for (var i = 0; i < response.length; i++) {
                    homework = {};
                    if(response[i].fb_id){
                        homework.userPicture='http://graph.facebook.com/' + response[i].fb_id + '/picture?type(square)'
                    }
                    else{
                        homework.userPicture="https://lh5.googleusercontent.com/-qWNMqcca81Y/AAAAAAAAAAI/AAAAAAAAAAA/g6oiN_9k82A/s120-c/photo.jpg"
                    }


                    homework.link = url + 'public/users_files/homework/' + response[i].link;
                    homework.name = response[i].name + ' ' + response[i].surname;
                    homework.grade = ko.observable(response[i].grade);
                    homework.teacher=response[i].teacher;
                    homework.id=response[i].id;
                    that.homeWork.push(homework);
                    console.log(homework)
                }
            }
            that.loadScr('out');
            setTimeout(function(){
                that.loadScr('no')
            }, 500);

        },function(){
            console.log("Something going wrong");
        });
    };
}
function lastVisit(lesson_id) {
    var d = new Date();
    var n = d.toISOString();
    universalAPI(
        url + "app/lesson/setLastVisit",
        "POST",
        function (response) {
            //console.log(response);
        },
        function (response) {
            console.log("error");
        }, {lesson_id: lesson_id, date: n}
    );
}
function toFormatL(number){
    if((number+'').length<2){
        number='0'+number;
    }
    if(number.length>2){
        return +number.substr(0, 2);
    }
    return number;
}

var viewModel = new ViewModel();
var model = {
    viewModel:viewModel,
    commentModel:mod.viewModel
};
function update () {
    lastVisit(viewModel.id());
    setTimeout(update, 60000);
}
$(document).ready(function(){
    viewModel.activate();
    mod.getCurrentUser();
    mod.init();
    ko.applyBindings(model);
    update();
});
