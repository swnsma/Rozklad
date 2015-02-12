//var url = 'http://rozklad.loc:83/src/';
//var url ='http://rozklad:10000/src/';
var url =window.location.origin +'/';
function universalAPI(urla, type, success, fail, data){
    $.ajax({
        url: urla,
        type: type,
        data: data,
        success: function(response){
            success(response);
        },
        error: function(xhr){
        fail(xhr);
    }
    });

}


if (window.location.hash && window.location.hash == '#_=_') {
    window.location.hash = '';
}