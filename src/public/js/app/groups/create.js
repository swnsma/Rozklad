function create_group(data, func) {
    $.ajax({
        url: url + 'app/groups/createNewGroup',
        type: 'POST',
        dataType: 'json',
        data: data,
        success: func.success,
        error: func.error
    });
}


$('#create').click(function() {
    var el_name = $('#inputName');
    var el_descr = $('#inputDesc');
    var name = el_name.val();
    var descr = el_descr.val();
    create_group({
        name: name,
        descr: descr
    }, {
        success: function(response) {
            console.log(response);
        },
        error: function() {
            alert('error');
        }
    });
    return false;
});