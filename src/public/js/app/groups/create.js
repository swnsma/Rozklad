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
    var er1 = $('#error1');
    var er2 = $('#error2');
    var name = el_name.val();
    var descr = el_descr.val();
    el_name.removeClass('error-input');
    el_descr.removeClass('error-input');
    er1.css('display', 'none');
    er2.css('display', 'none');

    if (!name.match(/^[\d+\w+а-яА-Я ]{1,50}$/)) {
        el_name.addClass('error-input');
        er1.css('display', 'block');
        return false;
    }
    if (!descr.match(/^[\(\)\!\?\:\;\.\,\-А-Яа-я \s\S\d+\w+]{1,300}$/)) {
        el_descr.addClass('error-input');
        er2.css('display', 'block');
        return false;
    }

    create_group(new FormData(document.getElementById('create1')), {
        success: function(response) {
            if (response.status == 'group_create') {
                $('#linkNewOnGroup').text(url + 'app/grouppage/id' + response.id).attr('href', url + 'app/grouppage/id' + response.id);
                $('#groupInvite').text(url + 'grouppage/inviteUser/' + response.key);
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