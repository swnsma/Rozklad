function ModelRegist(){
    var self=this;
    self.name = ko.observable('');
    self.surname = ko.observable('');
    self.phone = ko.observable('');
    self.gender = ko.observable('');
    self.email = ko.observable();
    self.rolesName=["Студент","Вчитель"];
    self.role = ko.observable(self.rolesName[0]);

    self.sendInfo = function(){
        var postData= {
            name:self.name(),
            surname:self.surname(),
            phone:self.phone(),
            role:self.roleIndex()
        };
        $.ajax({
            url: url + 'app/regist/index/'+postData.name+'/'+postData.surname+'/'+postData.phone+'/'+postData.roleIndex+'/',
            type:"GET",
            success:function(response){
                console.log(response);
                window.location=url+'app/calendar/';
            },
            error: function (error) {
                console.log(error);
                alert('error: block get status');
            }
        });
    }
    self.roleIndex=ko.computed(function(){
        if(self.role()===self.rolesName[0])
            return 0;
        else return 1;
    })
}
$(document).ready(function(){
    ko.applyBindings(new ModelRegist);
});