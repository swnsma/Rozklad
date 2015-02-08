/**
 * Created by Таня on 08.02.2015.
 */

var masSelect=[
    {
        id:'1',
        name:'Name1',
        color:'#111'
    },
    {
        id: '2',
        name: 'Name2',
        color: '#222'
    },
    {
        id:'3',
        name:'Name3',
        color:'#333'
    },
    {
        id:'4',
        name:'Name4',
        color:'#444'
    }
];

function SetSelect(option){
    //масив всіх елементів, з яких буде будуватися група селектів
    var groups = option.masGroups;

    //батьківський елемент всіх селектів
    var element = option.element;

    //номер наступного селекта
    var numberSelect=0;

    //кількість селектів
    var lenthSelect=0;

    var nameSelect='GroupSelect';

    var selectOption = []

    var clickLi = function (){
        if(lenthSelect!==groups.length) {
            CreateSelect();
        }
    }
    function createOption(group,parent){
        for(var i = 0;i<group.length;++i){
            var $li = $('<li>');
            $li.appendTo(parent);
            $li.attr({'data-value':group[i].id});

            var $spanColor = $('<span>');
            $spanColor.appendTo($li);
            $spanColor.css({
                'backgroundColor':group[i].color,
                'width':'10px',
                'height':'10px',
                'display':'inline-block',
                'marginRight':'3px',
                'borderRadius':'2px'
            });
            var $spanText = $('<span>');
            $spanText.appendTo($li);
            $spanText.text(group[i].name);

            $li.on('click',clickLi);
        }
    }
    function CreateSelect(){
        lenthSelect++;

        //ствоерння селекта
        var $select = $('<div>');
        debugger;
        var name = nameSelect+''+numberSelect;
        $select.attr({
            'id':name
        });
        $select.appendTo($(element));
        $select.addClass('custom-select');
        numberSelect++;

        //створення заголовку селекта
        var $title =$('<span>');
        $title.addClass('custom-select-title');
        $title.appendTo($select);

        //створення контейнера для опцій
        var $ul = $('<ul>');
        $ul.appendTo($select);
        $ul.addClass('custom-select-options');

        createOption(groups,$ul);
        var animalSelect = new CustomSelect({
            elem: $('#'+name)
        });
        animalSelect.setValue('Добавить групу','-1');


    }
    CreateSelect();


}