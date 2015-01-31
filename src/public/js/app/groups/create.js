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
            if (response.status == 'group_create') {
                $('#linkNewOnGroup').text(url + 'grouppage/' + response.id);
                $('#groupInvite').text('invite' + response.key);
                $('#afterCreate').css('display', 'block');
                $('#formCreate').css('display', 'none')
            } else {
                alert(response.status);
            }
        },
        error: function() {
            alert('error');
        }
    });
    return false;
});