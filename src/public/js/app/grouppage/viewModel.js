function ViewModel() {
    var that = this;
    that.groupName = ko.observable('');
    that.teacher = ko.observable('');
    that.description = ko.observable('');
    that.students = ko.observableArray([]);
    that.editGroupName= ko.observable(false);
    that.havePicture=ko.observable(false);
    that.id= ko.observable("");
    that.code= ko.observable("");
    that.imgSrc = ko.observable("");
    that.buffTitle="";
    that.errorTitle= ko.observable("0");
    that.loadScr=ko.observable("load-screen");
    that.focusTitle=function(){
        focusElement("titleInput");
    };
    that.editTitleOpen= function(){
        that.editGroupName(true);
        that.focusTitle();
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
            //that.students.remove(that.students(3))
            location.reload();
        });
    };
    that.dismissStudent=function(userId){
        api.deleteUser(userId,that.id(),function(){
        for(var i=0;i<that.students().length;i++ ) {
            if (that.students()[i].id == userId)
                 {
                    that.students()[i].notDeleted(false)
                 }
            }
        });
    };
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
            that.code(response.code);
        })
    };
    that.restoreUser=function(userId){

        api.restoreUser(userId,that.id(),function() {
            for (var i = 0; i < that.students().length; i++) {
                if (that.students()[i].id == userId) {
                    that.students()[i].notDeleted(true)
                }
            }
        })
    };
    that.activate = function () {

        var groupId = window.location.pathname;
        var pos=groupId.search(/id[0-9]+/);
        groupId= +groupId.substr(pos+2, 2);
        that.id(groupId);
        that.loadScr('out');
        universalAPI(url + 'app/grouppage/sendGroupInfo/'+that.id()+'/', "GET", function(resp){
            var response = resp.info;
            that.groupName(response.name);
            var img = response.img_src ? url + 'public/users_files/images/groups_photo/small_' + response.img_src : url + 'public/users_files/images/default/small_default_group_photo.jpg';
            that.imgSrc(img);
            that.havePicture(true);
            that.teacher(response.teacher);
            that.buffDesc=response.description;
            that.buffTitle=response.name;
            that.code(resp.code);
            response = resp.users;
            for (var i = 0; i < response.length; i++) {
                var student = new Student(response[i]);
                student.notDeleted=ko.observable(true);
                that.students.push(student);
            }

            that.students.sort(function(left, right) { return left.name == right.name ? 0 : (left.name < right.name ? -1 : 1) });
            setTimeout(function(){
                that.loadScr('no')
            }, 300);
        });
    }
}
var Student=function(obj){
    this.name=obj.name;

    if(obj.fb_id) {
        this.fb_account = 'https://www.facebook.com/' + obj.fb_id;
        this.fb_photo = 'http://graph.facebook.com/' + obj.fb_id + '/picture?type(square)';
    }
    else{
        this.fb_account=null;
    }
    this.id=obj.id;
    if(obj.gm_id&&!this.fb_account){
        this.gm_account='https://plus.google.com/u/0/'+obj.gm_id+'/posts';
    }
    else{
        this.gm_account=null;
    }

};
var viewModel = new ViewModel();
viewModel.activate();
function focusElement(id){
    document.getElementById(id).focus();
}
ko.applyBindings(viewModel);