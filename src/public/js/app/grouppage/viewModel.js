function ViewModel() {
    var that = this;
    that.groupName = ko.observable('');
    that.teacher = ko.observable('');
    that.description = ko.observable('');
    that.students = ko.observableArray([]);

    that.activate = function () {
        var groupId = window.location.hash.substr(1);
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
           console.log(that.students())
        });



    }
}


var viewModel = new ViewModel();
viewModel.activate();

ko.applyBindings(viewModel);