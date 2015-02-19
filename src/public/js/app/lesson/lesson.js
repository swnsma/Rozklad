ko.bindingHandlers.uploadTask = {
    init: function(element, valueAccessor){
        var value = valueAccessor();
        $(element)
            .on('click',function(){
                input.click();
            })
            .wrap('<div />');

        var form = $('<form/>')
            .attr('enctype', 'multipart/form-data')
            .hide()
            .on('change', function(e){

                if(e.target.files[0].size<20971520) {
                    $('.fileValid').show();
                    //that.validationMess("");
                    $.ajax({
                        url: url + 'app/lesson/uploadTask/',
                        type: 'POST',
                        processData: false,
                        contentType: false,
                        data: new FormData(form.get(0)),
                        success: function (response) {
                           // form.reset();
                            response.url = url + 'public/users_files/tasks/' + response.newName;
                            value.files.push(response);
                            value.save();
                        },
                        error: function (xhr) {
                            alert('pp')
                        }
                    });
                }
                else{
                    //element.reset();
                    $('.fileValid').hide();
                    alert("Файл слишком большой");
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
    init: function(element, valueAccessor){
        var value = valueAccessor();
        var  studentId=value.studentId;
        var studentName=value.studentName;

        $(element)
            .on('click',function(){
                input.click();
            })
            .wrap('<div />')

        var form = $('<form/>')
            .attr('enctype', 'multipart/form-data')
            .hide()
            .on('change', function(e){

                if(e.target.files[0].size<20971520) {
                    $('.fileValid').show();
                    $.ajax({
                        url: url + 'app/lesson/uploadhomework/'+studentId+'/'+value.id(),
                        type: 'POST',
                        processData: false,
                        contentType: false,
                        data: new FormData(form.get(0)),
                        success: function (response) {
                            console.log(response);
                            value.homeWork(response.newName);

                        },
                        error: function (xhr) {
                            alert('pp')
                        }
                    });
                }
                else{
                $('.fileValid').hide();
                    alert("Файл слишком большой");
                }

            })
            .insertAfter(element);

        var input = $('<input />')
            .attr('type', 'file')
            .attr('name', 'file')

            .appendTo(form);
    }
};
function ViewModel()
{
    var that = this;
    //data
    that.links=ko.observable('');
    that.homeWorkDescription=ko.observable('Описание домашнего задания');
    that.links=ko.observableArray([ ]);
    that.id=ko.observable('');
    that.files=ko.observableArray([]);
    that.homeWork=ko.observableArray([]);
    //editing logic
    that.edit=ko.observable(false);
    that.descriptionEdit= ko.observable(false);
    that.linkToAdd = ko.observable('');

    //editing functions
    that.startEdit=function(){
        that.edit(true)
    };
    that.descriptionEditStart=function(){
        that.descriptionEdit(true);
    };
    that.saveDesc=function(){
        that.descriptionEdit(false);
        that.makeArray()
    };

    that.saveLink=function(){
      if( that.linkToAdd().length) {
          that.links.push({name: that.linkToAdd()});
          that.linkToAdd ('');
          that.makeArray()
      }
    };
    that.deleteLink=function(link){
        for(var i =0;i<that.links().length;i++){
            if(that.links()[i].name==link){
                that.links.remove(that.links()[i])
            }
        }
        that.makeArray();
    };
    that.deleteFile=function(newName){
        console.log(newName);
        function sendData() {
            $.ajax({
                url: url + 'app/lesson/deleteFile/',
                type: 'POST',
                data: {
                    data: newName
                },
                success: function (response) {

                       for(var i =0;i<that.files().length;i++){
                           if(that.files()[i].newName==newName){
                               that.files.remove(that.files()[i])
                           }
                       }
                       that.makeArray()
                },
                error: function (xhr) {
                    fail(xhr);
                }
            });
        }
        sendData(newName)
    };
       that.makeArray=function(){
        var data={
            description: that.homeWorkDescription(),
            links: that.links(),
            files:that.files()
        };

        var datasend=JSON.stringify(data);
        function sendData(){
            $.ajax({
                url: url+'app/lesson/changeLessonInfo/'+that.id(),
                type: 'POST',
                data:{
                    data:datasend
                },
                success: function(response){
                    console.log(response);
                },
                error: function(xhr){
                    fail(xhr);
                }
            });

        }
        sendData()
    };

    //method that starts magic
    that.activate = function () {
        var lessonId = window.location.pathname;
        var pos=lessonId.search(/id[0-9]+/);
        lessonId= +lessonId.substr(pos+2, 2);
        that.id(lessonId);
        universalAPI(url+'app/lesson/getLessonInfo/'+that.id(), 'GET', function(response){
        var incomingData= JSON.parse(response[0].lesson_info);
            that.homeWorkDescription(incomingData.description);
            that.links(incomingData.links);
            that.files(incomingData.files);
        });
        $.ajax({
            url: url+'app/lesson/getTasks/'+that.id(),
            type: 'GET',
            success: function(response){
                for(var i =0;i<response.length;i++)
                {
                    var homework={};
                    homework.urls =  url+'public/users_files/homework/'+response[i].link;
                    homework.name=response[i].name+' '+response[i].surname;

                    that.homeWork.push(homework);

                }
                console.log(that.homeWork())
            },
            error: function(xhr){
                fail(xhr);
            }
        });







    };
}

var viewModel = new ViewModel();
viewModel.activate();
ko.applyBindings(viewModel);







