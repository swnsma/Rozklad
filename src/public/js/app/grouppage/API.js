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
            $.ajax({
                url: url+'app/grouppage/renameGroup/'+id+'/'+title+'/',
            type:'GET',
                success:function(response){
                    successFunction(response);
                },
                error: function(xhr){
                    alert("Error! "+xhr);
                }
            })
        },
        editDesription: function(id, descr, successFunction){
        $.ajax({
            url: url+'app/grouppage/editDescription/'+id+'/'+descr+'/',
            type: 'GET',
            success: function(response){
                successFunction(response);
            },
            error: function(xhr){
                alert('Error! '+xhr);
            }
        })
    }
};

var Student=function(obj){
    this.name=obj.name;
    this.fb_account='https://www.facebook.com/profile.php?id='+obj.user_id;
    this.fb_photo='http://graph.facebook.com/'+obj.user_id+'/picture?type=large';
};