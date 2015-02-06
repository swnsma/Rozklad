function create_group(data, func) {
    $.ajax({
        url: url + 'app/groups/createNewGroup',
        type: 'POST',
        dataType: 'json',
        data: data,
        success: func.success,
        error: func.error,
        processData: false,
        contentType: false
    });
}

$('#createButton').click(function() {
    var el_name = $('#inputName');
    var el_descr = $('#inputDesc');
    var name = el_name.val();
    var descr = el_descr.val();
    el_name.removeClass('error-input');
    el_descr.removeClass('error-input');

    if (!name.match(/^[\d+\w+]{1,50}$/)) {
        el_name.addClass('error-input');
        return false;
    }
    if (!descr.match(/^[\(\)\!\?\:\;\.\, \s\S\d+\w+]{1,300}$/)) {
        el_descr.addClass('error-input');
        return false;
    }

    create_group(new FormData(document.getElementById('create1')), {
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