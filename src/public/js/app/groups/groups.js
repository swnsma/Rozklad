function ViewModel(){
    var that = this;
    that.groups = ko.observableArray([]);
    that.currentId="";
    that.startEdit=function(name){
        for(var i=0; i<that.groups().length; i++){
            if (that.groups()[i].name==name){
                that.groups()[i].edit(true);
            }
        }

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
                group.edit = ko.observable();
                that.groups.push(group);
            }
            that.groups.sort(function(left, right) { return left.name == right.name ? 0 : (left.name < right.name ? -1 : 1) });
        })
    }
}
function Group(obj){
    //this.name = obj.name;
    //this.description=obj.descr;
    this.teacher = obj.teacher_fn+' '+obj.teacher_ln;
    this.imgSrc=url+'public/users_files/images/groups_photo/'+obj.photo;
    this.groupLink=url+'app/grouppage/id'+obj.group_id;
    this.goAway = function(){
        window.location = this.groupLink;
    }
}
var viewModel = new ViewModel();
viewModel.activate();
ko.applyBindings(viewModel);