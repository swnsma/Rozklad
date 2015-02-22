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
            .wrap('<div />')
        var form = $('<form/>')
            .attr('enctype', 'multipart/form-data')
            .hide()
            .on('change', function (e) {
                $(element).hide();
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
                           console.log(response);
                           value.homeWork(response.newName);
                         alert('Домашка загружена успешно')
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
        var value = valueAccessor();
        $(element).click(function () {
            var d=toFormat(parseInt($("#day").val()));
            console.log(d);
            var mo=toFormat(parseInt($("#month").val()));
            console.log(mo);
            var ye=parseInt($("#year").val());
            console.log(ye);
            var t=d+'-'+mo+'-'+ye+' ';
            var h=parseInt($('#hour').val());
            console.log(h);
            if(!h){
                h=14;
            }else{h=toFormat(h);}
            var m =parseInt($('#min').val());
            console.log(m);
            if(!m){
                m='00';
            }else{m=toFormat(m);}
            if(isNaN(d)||isNaN(mo)||isNaN(ye)||isNaN(h)||isNaN(m)){

            }else{
            t+=' '+h;
            t+=':'+m;
            if(t.length<10){
                t="Нет";
            }
            value.deadLine(t);
            universalAPI(url+'app/lesson/setDeadLine/'+viewModel.id(), "POST", function(response){
                console.log(response);
            }, function(){
                console.log("Something going wrong!");
            }, {deadline: t});
            }
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
    //editing logic
    that.edit = ko.observable(false);
    that.descriptionEdit = ko.observable(false);
    that.linkToAdd = ko.observable('');
    that.deadLine = ko.observable(false);
    //editing functions
    that.startEdit = function () {
        that.edit(true)
    };
    that.descriptionEditStart = function () {
        that.descriptionEdit(true);
    };
    that.saveDesc = function () {
        that.descriptionEdit(false);
        that.makeArray()
    };

    that.saveLink = function (viewModel, event) {
        if (event.charCode == 13) {
            if (that.linkToAdd().length) {
                that.links.push({name: that.linkToAdd()});
                that.linkToAdd('');
                that.makeArray()
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
    //method that starts magic
    that.activate = function () {
        var lessonId = window.location.pathname;
        var pos = lessonId.search(/id[0-9]+/);
        lessonId = +lessonId.substr(pos + 2, lessonId.length - pos - 2);
        that.id(lessonId);
        universalAPI(url+'app/lesson/getDeadLine/'+that.id(), 'GET', function(response){
            that.deadLine(response.result);
        },function(){
            console.log("Something going wrong");
        });
        universalAPI(url + 'app/lesson/getLessonInfo/' + that.id(), 'GET', function (response) {
            var incomingData = JSON.parse(response[0].lesson_info);
            that.homeWorkDescription(incomingData.description);
            that.links(incomingData.links);
            that.files(incomingData.files);
        });
        $.ajax({
            url: url + 'app/lesson/getTasks/' + that.id(),
            type: 'GET',
            success: function (response) {
                console.log(response);
                if(that.userInfo()[1]=='student'){
                    for (var i = 0; i < response.length; i++){
                        if(response[i].name+' '+response[i].surname==that.userInfo()[0]){
                            that.selfHomeWork(true);
                            var homework = {};
                            homework.link = url + 'public/users_files/homework/' + response[i].link;
                            homework.name = response[i].name + ' ' + response[i].surname;
                            that.homeWork(homework);
                        }
                    }
                }
                if(that.userInfo()[1]=='teacher') {
                    for (var i = 0; i < response.length; i++) {
                         homework = {};
                         homework.link = url + 'public/users_files/homework/' + response[i].link;
                         homework.name = response[i].name + ' ' + response[i].surname;
                         that.homeWork.push(homework);
                    }
                }
            },
            error: function (xhr) {
                fail(xhr);
            }
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
var viewModel = new ViewModel();
viewModel.activate();
ko.applyBindings(viewModel);
//setInterval(function () {
//    lastVisit(viewModel.id())
//}, 1000);
