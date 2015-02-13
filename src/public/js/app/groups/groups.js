function ViewModel(){
    var that = this;
    that.groups = ko.observableArray([]);
    that.currentId="";
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
                group.description = ko.observable(response[i].description);
                group.archived = ko.observable(response[i].archived==1);
                group.edit = ko.observable(false);
                group.startEditing = function(){
                    this.edit(true);
                };
                group.cancelEditing = function(){
                    this.edit(false);
                };
                group.sendChanges = function(){
                    universalAPI(url+'app/grouppage/renameGroup/'+this.groupId,"POST", function(){}, function(){}, {title:this.name()});
                    universalAPI(url+'app/grouppage/editDescription/'+this.groupId, "POST", function(){}, function(){}, {data:this.description()});
                };
                that.groups.push(group);
            }
            that.groups.sort(function(left, right) { return left.name == right.name ? 0 : (left.name < right.name ? -1 : 1) });
        })
    }
}
function Group(obj){
    this.teacher = obj.teacher_fn+' '+obj.teacher_ln;
    this.imgSrc=url+'public/users_files/images/groups_photo/'+obj.photo;
    this.groupLink=url+'app/grouppage/id'+obj.group_id;
    this.groupId=obj.group_id;
    this.goAway = function(){
        window.location = this.groupLink;
    }
}
var viewModel = new ViewModel();
viewModel.activate();
ko.applyBindings(viewModel);