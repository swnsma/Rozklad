function load(){
    function adminViewModel(){
        var self = this;
        self.users = ko.observableArray([]);
        loadUsers(self);
        var realTimeUpdate = window.setInterval(function(){
            loadUsers(self);
        },500);

        self.confirm = function (user){
            $.ajax({
                url: 'admin/confirmUser/'+user.id,
                success: function(response){
                    user.confirmed(true);
                },
                error: function(er) {
                    console.dir(er);
                    if (er.status==200) {
                        user.confirmed(true);
                    }
                }

            });
        }
        self.unConfirm = function (user){
            $.ajax({
                url: url+"/src/admin/unConfirmUser/"+user.id,
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
            $.ajax({
                url: url+"admin/getUnconfirmedUsers",
                success: function(response){
                    for(var i in response){
                        var user = {};
                        user.name = response[i].name+' '+response[i].surname;
                        user.photo = '../../../src/public/img/avatar.png';
                        user.role = response[i].role_id==1?'Студент':'Преподаватель';//TODO: do this switch on server side
                        user.id = response[i].id;
                        user.confirmed = ko.observable(false);

                        var haveSuchUser = false;
                        var suchUserN;
                        if (self.users()[0]){
                            for (var u in self.users()){
                                if (self.users()[u].id==user.id){
                                    haveSuchUser = true;
                                    suchUserN = u;
                                }
                            }
                        }

                        if (!haveSuchUser){
                            self.users.push(user);
                        } else {
                            self.users()[suchUserN].confirmed(false);
                        }
                    }

                    findConfirmed();
                    function findConfirmed() { //if there are no some users on server side but they are present in self.users() this function will set them as confirmed
                        if (self.users()[0]) {
                            for (var u in self.users()) {
                                var haveSuchUser = false;
                                var suchUserN = null;
                                for (var r in response) {
                                    if (self.users()[u].id == response[r].id) {
                                        haveSuchUser = true;
                                        suchUserN = u;
                                    }
                                }
                                if (!haveSuchUser) {
                                    self.users()[u].confirmed(true);
                                }
                            }
                        }
                    }
                },
                error: function(er) {
                    console.dir(er);
                }

            });
        }
    }
    ko.applyBindings(new adminViewModel());
}