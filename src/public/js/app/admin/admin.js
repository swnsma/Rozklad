function load(){
    function adminViewModel(){
        var self = this;
        self.users = ko.observableArray([]);
        getTeachers(self);

        self.confirm = function (user){
            $.ajax({
                url: url+"app/admin/confirmUser/"+user.id,
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
                url: url+"app/admin/unConfirmUser/"+user.id,
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

        self.deleteUser = function (user){
            $.ajax({
                url: url+"app/admin/deleteUser/"+user.id,
                success: function(response){
                    user.deleted(true);
                },
                error: function(er) {
                    console.dir(er);
                }
            });
        }

        self.recoverUser = function (user){
            $.ajax({
                url: url+"app/admin/recoverUser/"+user.id,
                success: function(response){
                    user.deleted(false);
                },
                error: function(er) {
                    console.dir(er);
                }
            });
        }


        function loadUsers(self){
            $.ajax({
                url: url+"app/admin/getTeachers",
                success: function(response){
                    for(var i in response){
                        var user = {};
                        user.name = response[i].name+' '+response[i].surname;
                        user.photo = response[i].photo;
                        user.role = response[i].title;
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

        function getTeachers(self){
            $.ajax({
                url: url+"app/admin/getTeachers",
                success: function(response){
                    for(var i in response){
                        var user = {};
                        user.name = response[i].name+' '+response[i].surname;
                        user.photo = response[i].photo;
                        user.role = response[i].title;
                        user.id = response[i].id;
                        user.confirmed = ko.observable(true);
                        user.deleted = ko.observable(false);
                        if(response[i].unc_id){
                            user.confirmed(false);
                        }
                        self.users.push(user);
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