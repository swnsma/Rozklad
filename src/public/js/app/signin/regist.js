function ModelRegist(){
    var self=this;
    self.name = ko.observable('');
    self.surname = ko.observable('');
    self.phone = ko.observable('');
    self.gender = ko.observable('');
    self.isChecked=ko.observable(0);
    self.rolesName=[
        {itemName:"Студент"},
        {itemName:"Вчитель"}
    ];
    self.role = ko.observable();
    self.validName=function(){
        resetError($("#name"),$("#name_error"));
        var  number=self.name();
        if(number.length===0){
            showError($("#name"),$("#name_error"),"Введите имя");
            return 0;
        }
        return 1;
    };
    self.validSurname=function(){
        resetError($("#surname"),$("#surname_error"));
        var  number=self.surname();
        if(number.length===0){
            showError($("#surname"),$("#surname_error"),"Введите имя");
            return 0;
        }
        return 1;
    };
    self.validPhone=function(){
        resetError($("#phone"),$("#phone_error"));
        var  number=self.phone()
        if(number.length===0){
            showError($("#phone"),$("#phone_error"),"Введите номер");
            return 0;
        }
        if(number.match(/[^0-9]/g)){
            showError($("#phone"),$("#phone_error"),"Только цифры");
            return 0;
        }
        if(number.length>9){
            showError($("#phone"),$("#phone_error"),"Не больше девяти цифр");
            return 0;
        }
        return 1;
    };


    self.checkValidPhone=ko.computed(function(){
        var  number=self.phone();
        if(number.match(/[^0-9]/g)){
            showError($("#phone"),$("#phone_error"),"Только цифры");
            return 0;
        }
        if(number.length>9){
            showError($("#phone"),$("#phone_error"),"Не больше девяти цифр");
            return 0;
        }
        resetError($("#phone"),$("#phone_error"));
        return 1;
    });
    //self.isAble=function(){
    //    return self.validPhone()&&self.validSurname()&&self.validName();
    //}
    self.ckeckValidName=ko.computed(function(){
        if(self.name()){
            resetError($("#name"),$("#name_error"));
            self.isChecked(0);
        }
    });
    self.ckeckValidSurname=ko.computed(function(){
        if(self.surname()){
            resetError($("#surname"),$("#surname_error"));
            self.isChecked(0);
        }
    });
    self.sendInfo = function(){

        var check=1;
        if(!self.validSurname()){
            //self.isChecked(1);
            check=0;
        }
        if(!self.validName()){
            //self.isChecked(1);
            check=0;
        }
        if(!self.validPhone()){
            self.isChecked(1);
            check=0;
        }
        if(!check)return;

        var postData= {
            name:self.name(),
            surname:self.surname(),
            phone:self.phone(),
            role:self.roleIndex()
        };
        $.ajax({
                url:'http://localhost/src/app/regist/addUser/'+postData.name+'/'+postData.surname+'/'+postData.phone+'/'+postData.role+'/',
                type:"GET",
                success:function(response){
                    if(response==="registed") {
                        $("#btn-success")
                            .prop('disabled', false);
                        $("#success")
                            .toggle();
                        $("#regist")
                            .toggle();
                    }
                    else{
                        alert(response);
                    }
                },
                error: function (error) {
                    console.log(error);
                    alert('error: block get status');
                }
            }
        );
    };
    self.roleIndex=ko.computed(function(){
        if(self.role()===self.rolesName[0]){
            return 0;
        }
        else {
            return 1;
        }
    })
}
$(document).ready(function(){
    $("#success").hide();
    $("#btn-success")
        .prop('disabled', true)
        .click(function(){
            window.location=url+"app/calendar";
        });
    debugger;
    ko.applyBindings(new ModelRegist);
    resetError($("#name"),$("#name_error"));
    resetError($("#surname"),$("#surname_error"));
    resetError($("#phone"),$("#phone_error"));
    $("#reset").on("click",function(){
        var href=$(this).attr("hreff");
        window.location=href;
    });
});

ko.bindingHandlers.check={
    init:function(element, valueAccessor, allBindings, viewModel){
        viewModel.name();
    },
    update:function(element, valueAccessor, allBindings, viewModel) {
        //    var data=valueAccessor()();
        //    var value=$(element).val();
        //    resetError($(element),$(data.errorBlock));
        //    var  number=self.phone()
        //    if(number.length!==0){
        //        return 1
        //    }
        //    else{
        //        showError($(element),$(+data.errorBlock),data)
        //        return 0;
        //    }
        //    return 1;
        //
        //}
    }
}
function showError(input,contMass,error){
    input.css("border","1px solid red");
    input.css("color","1px solid red");
    contMass.html(error);
}
function resetError(input,contMass){
    input.css("border","1px solid #bbb");
    input.css("color","1px solid black");
    contMass.html('');
}