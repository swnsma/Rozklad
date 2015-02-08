/**
 * Created by Таня on 07.02.2015.
 */


function CustomSelect(options) {
    var self = this;

    var valuesss =0;
    var elem = options.elem;
    elem.on('click', '.custom-select-title', onTitleClick);
    elem.on('click', 'li', onOptionClick);

    var isOpen = false;

    // ------ обработчики ------

    function onTitleClick(event) {
        toggle();
    }

    // закрыть селект, если клик вне его
    function onDocumentClick(event) {
        var isInside = $(event.target).closest(elem).length;
        if (!isInside) close();
    }

    function onOptionClick(event) {
        close();
        var name='';
        if($(event.target)[0].tagName==='LI') {
            name = $(event.target).html();
            valuesss= $(event.target).data('value');
        }else{
            name =$(event.target).parent('li').html();
            valuesss= $(event.target).parent('li').data('value');
        }
        var value = $(event.target).data('value');

        //$('#value').text(value);
        self.setValue(name, value);
    }
    // ------------------------

    self.setValue =function(name, value) {

        elem.find('.custom-select-title').html(name);
        $(self).triggerHandler({
            type: 'select',
            name: name,
            value: value
        });

    }
    function toggle() {
        if (isOpen) close()
        else open();
    }
    function open() {
        elem.addClass('custom-select-open');
        $(document).on('click', onDocumentClick);
        isOpen = true;
    }
    function close() {
        elem.removeClass('custom-select-open');
        $(document).off('click', onDocumentClick);
        isOpen = false;
    }
    this.getValue = function(){
        return valuesss;
    }
}
