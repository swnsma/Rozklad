/**
 * Created by Sasha on 28.01.2015.
 */
function header_load(){
    function getCurrentUser(){
        $.ajax({
            url: 'admin/getUnconfirmedUsers',//???????
            success: function(response){
                var photo = document.getElementById('activeUserPhoto');
                debugger;
            },
            error: function(er) {
                debugger;
                console.dir(er);
            }

        });
    }
}

function logout(){

}