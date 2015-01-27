function load(){
    function adminViewModel(){
        var self = this;
        self.users = ko.observableArray([]);
        loadUsers(self);
        self.confirm = function (user){
            $.ajax({
                url: 'admin/confirmUser/'+user.id,
                contentType: 'application/json',
                dataType: 'json',
                data: {},
                success: function(response){
                    user.confirmed(true);
                },
                error: function(er) {
                    debugger;
                    console.dir(er);
                    if (er.status==200) {
                        user.confirmed(true);
                    }
                }

            });
        }
        self.unConfirm = function (user){
            $.ajax({
                url: 'admin/unConfirmUser/'+user.id,
                contentType: 'application/json',
                dataType: 'json',
                data: {},
                success: function(response){
                    user.confirmed(false);
                },
                error: function(er) {
                    console.dir(er);
                    if (er.status==200) {
                        user.confirmed(false);
                    }
                }

            });
        }

        function loadUsers(self){
            var users = [];
            $.ajax({
                url: 'admin/getUnconfirmedUsers',
                contentType: 'application/json',
                dataType: 'json',
                data: {},
                success: function(response){
                    for(var i in response){
                        var user ={};
                        user.name = response[i].name+' '+response[i].surname;
                        user.photo = '../../../src/public/img/avatar.png';
                        user.role = response[i].role_id==1?'Студент':'Преподаватель';//TODO: do this switch on server side
                        user.id = response[i].id;
                        user.confirmed = ko.observable(false);

                        users.push(user);
                    }
                    self.users(users);
                },
                error: function(er) {
                    console.dir(er);
                }

            });
        }
    }
    ko.applyBindings(new adminViewModel());
}