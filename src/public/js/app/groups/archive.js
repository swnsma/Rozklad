function ViewModel(){
    var that = this;
    that.groups = ko.observableArray([]);
    that.currentId="";

    that.activate = function(){
        universalAPI(url+'app/groups/getArchiveList', 'GET', function(response){
            that.currentId= response[response.length-1];
            for( var i=0; i< response.length-1; i++){
                var group = new Group(response[i]);
                group.name = ko.observable(response[i].name);
                that.groups.push(group);
            }
            that.groups.sort(function(left, right) { return left.name == right.name ? 0 : (left.name < right.name ? -1 : 1) });
        })
    }
}

function Group(obj){
    this.teacher = obj.teacher_fn+' '+obj.teacher_ln;
    this.imgSrc=url+'public/users_files/images/groups_photo/'+obj.photo;
}
var viewModel = new ViewModel();
viewModel.activate();
ko.applyBindings(viewModel);