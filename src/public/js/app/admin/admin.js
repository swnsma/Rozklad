function load(){
    function adminViewModel(){
        var self = this;
        self.users = ko.observableArray([]);
        loadUsers(self);
        self.confirm = function (user){
            user.confirmed(true);
        }
        self.unConfirm = function (user){
            user.confirmed(false);
        }

        function loadUsers(self){
            var users=[{name:'Андрей Морозов',role: 'Пеподаватель',confirmed: ko.observable(false), photo: '../../../public/img/avatar.png'},
                {name:'Артем Сердюк',role: 'Пеподаватель',confirmed: ko.observable(false), photo: '../../../public/img/avatar.png'},
                {name:'Андрей Дребот',role: 'Пеподаватель',confirmed: ko.observable(false), photo: '../../../public/img/avatar.png'},
                {name:'Славик',role: 'Пеподаватель',confirmed: ko.observable(false), photo: '../../../public/img/avatar.png'}];
            self.users(users);
        }
    }
    ko.applyBindings(new adminViewModel());
}