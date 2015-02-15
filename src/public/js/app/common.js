var url =window.location.origin +'/src/';

function universalAPI(urla, type, success, fail, data, processData, contentType){
    $.ajax({
        url: urla,
        type: type,
        processData: processData,
        contentType: contentType,
        data: data,
        success: function(response){
            success(response);
        },
        error: function(xhr){
        fail(xhr);
    }
    });

}

function include(arr,obj) {
    return (arr.indexOf(obj) != -1);
}


if (window.location.hash && window.location.hash == '#_=_') {
    window.location.hash = '';
}