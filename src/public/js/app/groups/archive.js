function ViewModel(){
    var that = this;
    that.groups = ko.observableArray([]);
    that.currentId="";



    that.restore=function(groupId){
        $.ajax({
            url: url+'app/groups/moveToArchive/'+groupId+'/'+0,
            type: 'GET',
            success: function(){
               location.href=url+'app/groups';
            },
            error: function (xhr){
                alert("Error! "+xhr);
            }
        });
    };
    that.activate = function(){
        universalAPI(url+'app/groups/getArchiveList', 'GET', function(response){
            that.currentId= response[response.length-1];
            for( var i=0; i< response.length-1; i++){
                var group = new Group(response[i]);
                group.name = ko.observable(response[i].name);
                that.groups.push(group);
            }
            console.log(response)
            that.groups.sort(function(left, right) { return left.name == right.name ? 0 : (left.name < right.name ? -1 : 1) });
        })
    }
}

function Group(obj){
    this.teacher = obj.teacher_fn+' '+obj.teacher_ln;
    this.imgSrc=obj.photo ? url+'public/users_files/images/groups_photo/'+obj.photo : url+'public/users_files/images/default/default_group_photo.jpg';
    this.groupId=obj.group_id;
}
var viewModel = new ViewModel();
viewModel.activate();
ko.applyBindings(viewModel);

//test ajax



var test=function(){
    $.ajax({
        url: url+'app/groups/moveToArchive/1/0',
        type: 'GET',
        success: function(response){
            console.log(response);
        },
        error: function (xhr){
            alert("Error! "+xhr);
        }
    });
}