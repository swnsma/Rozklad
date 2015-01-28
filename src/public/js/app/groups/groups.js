(function(){
    'use strict';
    var $ = jQuery;
    $.fn.extend({
        filterTable: function(){
            return this.each(function(){
                $(this).on('keyup', function(e){
                    $('.filterTable_no_results').remove();
                    var $this = $(this), search = $this.val().toLowerCase(), target = $this.attr('data-filters'), $target = $(target), $rows = $target.find('tbody tr');
                    if(search == '') {
                        $rows.show();
                    } else {
                        $rows.each(function(){
                            var $this = $(this);
                            $this.text().toLowerCase().indexOf(search) === -1 ? $this.hide() : $this.show();
                        })
                        if($target.find('tbody tr:visible').size() === 0) {
                            var col_count = $target.find('tr').first().find('td').size();
                            var no_results = $('<tr class="filterTable_no_results"><td colspan="'+col_count+'">No results found</td></tr>')
                            $target.find('tbody').append(no_results);
                        }
                    }
                });
            });
        }
    });
    $('[data-action="filter"]').filterTable();
})(jQuery);

$(function(){
    // attach table filter plugin to inputs
    $('[data-action="filter"]').filterTable();

    $('.container').on('click', '.panel-heading span.filter', function(e){
        var $this = $(this),
            $panel = $this.parents('.panel');

        $panel.find('.panel-body').slideToggle();
        if($this.css('display') != 'none') {
            $panel.find('.panel-body input').focus();
        }
    });
   // $('[data-toggle="tooltip"]').tooltip();
});


function GroupList() {
    (function getList(success, error) {
        $.ajax({
            url: url + 'app/groups/listGroup',
            type: 'GET',
            success: function(response) {
                success(response);
            },
            error: function(xhr) {
                error(xhr);
            }
        });
    })(renderGroup, function(error) {
        alert(error.responseText);
    });

    function renderGroup(groups) {
        var table = $('#task-table > tbody');
        for(var i in groups) {
            var g = groups[i];
            var tr = $('<tr>').html('<td>' + (+i+1) +
                '</td><td><a href="#/'+ g.group_id + '">' + g.name
                + '</a></td><td>' + g.teacher_name + '</td><td>16 березня</td>');
        }
        table.append(tr);
    }

}


$(document).ready(function() {
   var list = new GroupList();
});