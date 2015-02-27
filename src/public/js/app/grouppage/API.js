var api= {

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
                    console.log(xhr);
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

