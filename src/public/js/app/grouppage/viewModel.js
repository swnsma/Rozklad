function ViewModel() {
    var that = this;
    that.groupName = ko.observable('');
    that.teacher = ko.observable('');
    that.description = ko.observable('');
    that.students = ko.observableArray([]);
    that.editGroupName= ko.observable(false);
    that.editDescription= ko.observable(false);
    that.id= ko.observable("");
    that.code= ko.observable("");
    that.buffDesc="";
    that.buffTitle="";
    that.errorDesc=ko.observable("0");
    that.errorTitle= ko.observable("0");
    that.editDescOpen=function(){
        that.editDescription(true);
    };
    that.editTitleOpen= function(){
        that.editGroupName(true);
    };
    that.saveDesc=function(){
      var buff= that.description().trim();
        if(buff){
            if(buff!=that.buffDesc){
            api.editDesription(that.id(), buff, function(response){
                if(response){
                    that.editDescription(false);
                }
                else {
                    that.errorDesc("2");
                }
            });
                that.buffDesc=buff;
            }
            else{
                that.editDescription(false);
            }
            that.errorDesc("0");
        }
        else {
            that.errorDesc("1");
        }
        };
    that.saveTitle=function(){
        var buff= that.groupName().trim();
        if(buff){
            if(buff!=that.buffTitle){
            api.renameGroup(that.id(), buff, function(response){
                if(response){
                    that.editGroupName(false);
                }
                else{
                    that.errorTitle("2");
                }
            });
                that.buffTitle=buff;
            }
            else{
                that.editGroupName(false);
            }
            that.errorTitle("0");
        }
        else {
            that.errorTitle("1");
        }
    };
    that.deleteUser=function(userId){
        api.deleteUser(userId,that.id(),function(){
           // that.students.remove(that.students(3))
            location.reload();
        });
    };

    that.dismissStudent=function(userId){
        api.deleteUser(userId,that.id(),function(){
            that.students.remove(function(item) { return item.id == userId});
        });
    };
    that.errorDescMessage = ko.computed(function(){
        switch(that.errorDesc()){
            case "1":
                return "Поле не может быть пустым";
            case "2":
                return "Ошибка соединения с сервером";
            default:
                return "";
        }
    });
    that.errorTitleMessage = ko.computed(function(){
        switch(that.errorTitle()){
            case "1":
                return "Поле не может быть пустым";
            case "2":
                return "Ошибка соединения с сервером";
            default:
                return "";
        }
    });
    that.getCode = ko.computed(function(){
        return url+'app/grouppage/inviteUser/'+that.code();
    });
    that.changeCode= function(){
        api.changeCode(that.id(),function (response){
            console.log(response);
            that.code(response.code);
        })
    };
    that.activate = function () {
        var groupId = window.location.pathname;
        var pos=groupId.search(/id[0-9]+/);
        groupId= +groupId.substr(pos+2, 2);
        that.id(groupId);
        api.getGroupInfo(groupId, function (response) {
            that.groupName(response.name);
            that.teacher(response.teacher);
            that.description(response.description)
            that.buffDesc=response.description;
            that.buffTitle=response.name;
        });
        api.getUsers(groupId, function (response) {
            for (var i = 0; i < response.length; i++) {
                var student = new Student(response[i]);
                that.students.push(student)
            }
            console.log(that.students());
        });
        api.loadCode(groupId, function (response){
            that.code(response.code);
        });
    }
}
var viewModel = new ViewModel();
viewModel.activate();

ko.applyBindings(viewModel);