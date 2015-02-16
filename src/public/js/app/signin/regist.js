function ModelRegist(){
    var self=this;
    self.name = ko.observable('');
    self.surname = ko.observable('');
    self.phone = ko.observable('');
    self.gender = ko.observable('');
    self.isChecked=ko.observable(0);
    self.role=ko.observable(0);

    self.rolesName=ko.observableArray([
        {
            itemName:"Студент",
            checked:ko.observable(false)
        },
        {
            itemName:"Учитель",
            checked:ko.observable(false)
        }
    ]);

    self.radioSelectedOptionValue=ko.observable(self.rolesName()[0].itemName);
    self.role=ko.computed(function(){
        return arrayFirstIndexOf(self.rolesName(), function(item) {
            return item.itemName === self.radioSelectedOptionValue();
        });
    });

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
            role:self.role()
        };
        $.ajax({
                url:url + 'app/regist/addUser/',
                type:"POST",
                data:{
                    data:postData
                },
                success:function(response){
                    if(response.result==="registed") {
                        console.log(response.link);
                        $("#btn-success")
                            .prop('disabled', false)
                            .click(function(){
                                window.location=url+response.link;
                            });
                        $("#success")
                            .toggle();
                        $("#regist")
                            .toggle();
                    }
                    else{
                        alert(response.result);
                    }
                },
                error: function (error) {
                    console.log(error);
                    alert('error: block get status');
                }
            }
        );
    };

    self.init=function(){
        getName(self.getName);
        //getRoles(self.getRoles);
    };

    self.getName=function(response){
        self.name(response['firstname']);
        self.surname(response['lastname']);
    };

    self.getRoles=function(response){
        self.rolesName(ko.utils.arrayMap(response,function(item){
            return {
                itemName:item.roleName,
                checked:ko.observable(false)
            };
        }));
    }
}
$(document).ready(function(){
    $("#success").hide();
    $("#btn-success")
        .prop('disabled', true)
    var model=new ModelRegist
    ko.applyBindings(model);
    model.init();
    resetError($("#name"),$("#name_error"));
    resetError($("#surname"),$("#surname_error"));
    resetError($("#phone"),$("#phone_error"));
    $("#reset").on("click",function(){
        var href=$(this).attr("hreff");
        window.location=href;
    });
});
function getName(func){
    ajax('app/regist/getName',func);
}
function getRoles(func){
    ajax('app/regist/getRoles',func);
}

function ajax(url1,func){
    $.ajax({
            url:url + url1,
            type:"GET",
            contentType: 'application/json',
            dataType: 'json',
            success:function(response){
                func(response);
            },
            error: function (error) {
                console.log(error);
                alert('error: block get status');
            }
        }
    );
}
function arrayFirstIndexOf(array, predicate, predicateOwner) {
    for (var i = 0, j = array.length; i < j; i++) {
        if (predicate.call(predicateOwner, array[i])) {
            return i;
        }
    }
    return -1;
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
