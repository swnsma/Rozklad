function ViewModel() {
    var that = this;
    that.groupName = ko.observable('');
    that.teacher = ko.observable('');
    that.description = ko.observable('');
    that.students = ko.observableArray([]);
    that.editGroupName= ko.observable("true");
    that.editDescription= ko.observable("true");
    that.id= ko.observable("");
    that.code= ko.observable("");
    that.getCode = ko.computed(function(){
        return url+'app/grouppage/inviteUser/'+that.code();
    });
    that.changeCode= function(){
        api.changeCode(that.id(),function (response){
            console.log(response);
            that.code(response.code);
        })
    }
    that.activate = function () {
        var groupId = window.location.pathname;
        var pos=groupId.search(/id[0-9]+/);
        groupId= +groupId.substr(pos+2, 2);
        that.id(groupId);
        api.getGroupInfo(groupId, function (response) {
            that.groupName(response.name);
            that.teacher(response.teacher);
            that.description(response.description)
        });
        api.getUsers(groupId, function (response) {
            for (var i = 0; i < response.length; i++) {
                var student = new Student(response[i]);
                that.students.push(student)
            }
        });
        api.loadCode(groupId, function (response){
            that.code(response.code);
        });
    }
}
var viewModel = new ViewModel();
viewModel.activate();

ko.applyBindings(viewModel);