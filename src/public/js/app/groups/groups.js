function ViewModel(){
    var that = this;
    that.groups = ko.observableArray([]);
    that. activate = function(){
        universalAPI(url+'app/groups/getGroupList', 'GET', function(response){
            for( var i=0; i< response.length; i++){
                var group = new Group(response[i]);
                that.groups.push(group);
            }
            that.groups.sort(function(left, right) { return left.name == right.name ? 0 : (left.name < right.name ? -1 : 1) });
        for(var j=0; j<2; j++){
            console.log(that.groups()[j]);
        }
        })
    }
}
function Group(obj){
    this.name = obj.name;
    this.description=obj.descr;
    this.teacher = obj.teacher_fn+' '+obj.teacher_ln;
    this.imgSrc=url+'public/users_files/images/groups_photo/'+obj.photo;
    this.groupLink=url+'app/grouppage/id'+obj.group_id;
}
var viewModel = new ViewModel();
viewModel.activate();
ko.applyBindings(viewModel);