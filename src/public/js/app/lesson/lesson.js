function ViewModel()
{
    var that = this;
    //data
    that.links=ko.observable('');
    that.homeWorkDescription=ko.observable('Описание домашнего задания');
    that.links=ko.observableArray([ ]);

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
        //тут буде збереження в  базі даних

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
    that.makeArray=function(){
        var data={
            description: that.homeWorkDescription(),
            links: that.links()
        };
        var datasend=JSON.stringify(data)

        function sendData(){
            $.ajax({
                url: url+'app/lesson/changeLessonInfo/1/'+datasend,
                type: 'POST',

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


    };
}

var viewModel = new ViewModel();
viewModel.activate();

ko.applyBindings(viewModel);










//приклад запиту до метода опису

universalAPI(url+'app/lesson/getLessonInfo/1', 'GET', function(response){console.log(response)});


