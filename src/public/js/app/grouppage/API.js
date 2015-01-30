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
        }
};

var Student=function(obj){
    this.name=obj.name;
    this.fb_account='https://www.facebook.com/profile.php?id='+obj.user_id;
    this.fb_photo='http://graph.facebook.com/'+obj.user_id+'/picture?type=large';
};