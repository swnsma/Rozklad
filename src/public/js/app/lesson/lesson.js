function ViewModel()
{
    var that = this;
    //data
    that.links=ko.observable('');
    that.homeWorkDescription=ko.observable('Описание домашнего задания');
    that.links=ko.observableArray([ ]);
    that.id=ko.observable('');
    //editing logic
    that.edit=ko.observable(false);
    that.descriptionEdit= ko.observable(false);
    that.linkAdding=ko.observable(false);
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
    that.addLink=function(){
        that.linkAdding(true)
    };

    that.saveLink=function(){
      if( that.linkToAdd().length) {
          that.links.push({name: that.linkToAdd()});
          that.linkAdding(false);
          that.linkToAdd ('');
          that.makeArray()
      }
    };
    that.loadFile=function(formElement){
        $.ajax({
            url: url+'app/lesson/upload/',
            type: 'POST',
            processData:false,
            contentType:false,
            data: new FormData(formElement),
            success: function(response){
                console.log(response);
            },
            error: function(xhr){
                fail(xhr);
            }
        });
    };
     that.makeArray=function(){
        var data={
            description: that.homeWorkDescription(),
            links: that.links()
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
            console.log(incomingData);

            that.homeWorkDescription(incomingData.description);
            that.links(incomingData.links)
        });
    };
}

var viewModel = new ViewModel();
viewModel.activate();

ko.applyBindings(viewModel);










