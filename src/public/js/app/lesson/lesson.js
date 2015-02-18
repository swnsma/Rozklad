function ViewModel()
{
    var that = this;
    //data
    that.links=ko.observable('');
    that.homeWorkDescription=ko.observable('Описание домашнего задания');
    that.links=ko.observableArray([ ]);
    that.id=ko.observable('');
    that.files=ko.observableArray([]);
    that.validationMess=ko.observable('');


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
    ko.bindingHandlers.loadFile={
        init:function(element, valueAccessor, allBindings,currentContext,  viewModel) {
            $(element).change(function(){

                //сделать хайд пока не загрузится

                if(element.firstChild.nextElementSibling.files[0].size<20971520) {
                    $('.fileValid').show();
                    that.validationMess("");
                    $.ajax({
                        url: url + 'app/lesson/upload/',
                        type: 'POST',
                        processData: false,
                        contentType: false,
                        data: new FormData(element),
                        success: function (response) {
                            element.reset();
                            response.url = url + 'public/users_files/tasks/' + response.newName;
                            that.files.push(response);
                            that.makeArray();
                        },
                        error: function (xhr) {
                           alert('pp')
                        }
                    });
                }
            else{
                    element.reset();
                    $('.fileValid').hide();
                    that.validationMess("Файл слишком большой");
                }
            })
        }
    };
    //method that starts magic
    that.activate = function () {
        var lessonId = window.location.pathname;
        var pos=lessonId.search(/id[0-9]+/);
        lessonId= +lessonId.substr(pos+2, 2);
        that.id(lessonId);
        universalAPI(url+'app/lesson/getLessonInfo/'+that.id(), 'GET', function(response){
        var incomingData= JSON.parse(response[0].lesson_info);
            console.log(incomingData);
            that.homeWorkDescription(incomingData.description);
            that.links(incomingData.links);
            that.files(incomingData.files);
        });
    };
}

var viewModel = new ViewModel();
viewModel.activate();
ko.applyBindings(viewModel);










