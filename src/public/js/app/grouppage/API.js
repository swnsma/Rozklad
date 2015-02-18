var api= {
        getGroupInfo: function(id,successFunction){
            $.ajax({
                url: url + 'app/grouppage/sendGroupInfo/'+id+'/',
                type: 'GET',
                success: function(response) {
                    successFunction(response);
                },
                error: function(xhr) {
                    error(xhr);
                }
            });
        },
        getUsers: function(id,successFunction){
            $.ajax({
                url: url + 'app/grouppage/sendUsers/'+id+'/',
                type: 'GET',
                success: function(response) {
                    successFunction(response);
                },
                error: function(xhr) {
                    error(xhr);
                }
            });
        },
        changeCode: function(id, successFunction){
        $.ajax({
            url: url+'app/grouppage/createInviteCode/'+id+'/',
            type: 'GET',
            success: function(response){
                successFunction(response);
            },
            error: function (xhr){
                alert("Error! "+xhr);
            }
        });
        },
        loadCode: function(id, successFunction){
            $.ajax({
                url: url+'app/grouppage/sendCode/'+id+'/',
                type: 'GET',
                success: function(response){
                    successFunction(response);
                },
                error: function (xhr){
                    alert("Error!");
                }
            })
        },
        renameGroup: function(id, title, successFunction){
            var data={
                title:title
            };
            $.ajax({
            url: url+'app/grouppage/renameGroup/'+id+'/',
            type:'POST',
            data: data,
                success:function(response){
                    successFunction(response);
                },
                error: function(xhr){
                    alert("Error! "+xhr);
                }
            })
        },
        editDesription: function(id, descr, successFunction){
            var data={
                data: descr
            };
        $.ajax({
            url: url+'app/grouppage/editDescription/'+id+'/',
            type: 'POST',
            data:data,
            success: function(response){
                successFunction(response);
            },
            error: function(xhr){
                alert('Error! '+xhr);
            }
        })
    },
    deleteUser:function(id,groupId,successFunction) {
        $.ajax({
            url: url + 'app/grouppage/delUser/'+id+'/'+groupId ,
            type: 'GET',
            success: successFunction (),
            error: function (xhr) {
                alert('Error! ' + xhr);
            }
        })
    },
    restoreUser:function(id,groupId,successFunction) {
        $.ajax({
            url: url + 'app/grouppage/restore/'+groupId+'/'+id ,
            type: 'GET',
            success: successFunction (),
            error: function (xhr) {
                alert('Error! ' + xhr);
            }
        })
    }
};

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