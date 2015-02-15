
function ViewModel(){
    var that = this;
    that.groups = ko.observableArray([]);
    that.currentId="";
    that.loadScreen = ko.observable(true);
    that.loadScr = ko.observable("load-screen");
    that.archivate=function(obj){
                $.ajax({
                       url: url+'app/groups/moveToArchive/'+obj.groupId+'/'+1,
                        type: 'GET',
                        success: function(){
                           for(var i =0;i<that.groups().length;i++){
                                  if(that.groups()[i].groupId==obj.groupId){
                                          that.groups()[i].archived(true);
                                      }
                               }
                        },
                    error: function (xhr){
                            alert("Error! "+xhr);
                        }
                });
        };
    that.activate = function(){
        universalAPI(url+'app/groups/getGroupList', 'GET', function(response){
            that.currentId= response[response.length-1];
            for( var i=0; i< response.length-1; i++){
                var group = new Group(response[i]);
                group.host = ko.observable(that.currentId==response[i].teacher_id);
                group.name = ko.observable(response[i].name);
                group.description = ko.observable(response[i].descr);
                group.archived = ko.observable(response[i].archived==1);
                group.edit = ko.observable(false);
                group.errorTitle= ko.observable("");
                group.errorDesc = ko.observable("");
                group.buffName = "";
                group.buffDesc= "";
                group.sending = ko.observable(false);
                group.file = ko.observable("Ничего не выбрано (Max - 4mb)");
                group.fileError=ko.observable("");
                group.deArchivate = function(){
                    var those=this;
                    universalAPI(url+'app/groups/moveToArchive/'+those.groupId+'/'+0, "GET", function(){
                        those.archived(false);
                    });

                };
                group.fileStatus = function(file){
                    if(!file){
                        this.file("Ничего не выбрано");
                        console.log("hi1")
                    }
                    else{
                        this.file(file.name);
                        if(!file.type.match(/image.*/)){
                            this.fileError("Неверное расширение файла!");
                        }else{
                            if( file.size> 4 * 1024 * 1024){
                                this.fileError("Файл должен быть не более 4 мб")
                            }else{
                            this.fileError("");
                            }
                        }
                    }
                };
                group.imgSrc= ko.observable(response[i].photo ? url+'public/users_files/images/groups_photo/'+response[i].photo : url+'public/users_files/images/default/default_group_photo.jpg');
                group.startEditing = function(){
                    this.buffName = this.name();
                    this.buffDesc = this.description();
                    this.edit(true);
                };
                group.cancelEditing = function(){
                    this.errorDesc("");
                    this.errorTitle("");
                    this.fileError("");
                    this.file("Ничего не выбрано (Max - 4mb)");
                    this.name(this.buffName);
                    this.description(this.buffDesc);
                    this.edit(false);
                };
                group.sendChanges = function(){
                    var those = this;
                    those.errorTitle("");
                    those.errorDesc("");
                    var desc = those.description().trim();
                    var title = those.name().trim();
                    if(!desc){those.errorDesc("Поле не может быть пустым");}

                    if(!title){those.errorTitle("Поле не может быть пустым!");}

                    if(those.errorDesc()||those.errorTitle()||those.fileError()){return;}

                    if(title==those.buffName){those.name("");}

                    if(desc==those.buffDesc){those.description("");}

                    if($("#photo", "#"+those.groupId).val())
                    {
                        those.sending(true);
                    universalAPI(url+'app/grouppage/changeImage/'+those.groupId,
                        "POST",
                        function(response){
                            those.description(desc);
                            those.name(title);
                            if(response.errormess){
                                those.errorTitle(response.errormess);
                                those.sending(false);
                            }else{
                            those.imgSrc( url+'public/users_files/images/groups_photo/'+response.result);
                            those.file("Ничего не выбрано (Max - 4mb)");
                            those.sending(false);
                            those.edit(false);
                            }
                        },
                        function(xhr){  },
                        new FormData(document.getElementById(those.groupId)),
                        false,
                        false
                    );
                    }else{
                        those.sending(true);
                        if(title==those.buffName&&desc==those.buffDesc){
                            those.description(desc);
                            those.name(title);
                            those.sending(false);
                            those.edit(false);
                        }else{
                        universalAPI(url+'app/grouppage/renameGroup/'+those.groupId,"POST",
                            function(response){
                                those.description(desc);
                                those.name(title);
                                those.sending(false);
                                if(response.errormess){
                                    those.errorTitle(response.errormess);
                                }else{
                                those.edit(false);
                                }
                            }, function(){}, {title:those.name(), data:those.description()});
                        }
                    }
                };
                that.groups.push(group);
            }
            that.loadScr("out");
            setInterval(function(){
                that.loadScr("no");
            }, 600)

        })
    }
}
function Group(obj){
    this.teacher = obj.teacher_fn+' '+obj.teacher_ln;
    this.groupLink=url+'app/grouppage/id'+obj.group_id;
    this.groupId=obj.group_id;
    this.goAway = function(){
        window.location = this.groupLink;
    }
}

var viewModel = new ViewModel();
viewModel.activate();
ko.applyBindings(viewModel);


