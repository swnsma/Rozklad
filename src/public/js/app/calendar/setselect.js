/**
 * Created by Таня on 08.02.2015.
 */

function SetSelect(option){
    var self = this;
    //масив всіх елементів, з яких буде будуватися група селектів
    var groups = option.masGroups;

    //батьківський елемент всіх селектів
    var element = option.element;

    var selectElement =option.selectElement;

    //номер наступного селекта
    var numberSelect=0;

    //кількість селектів
    var lenthSelect=0;

    var nameSelect='GroupSelect';

    var selectOption = [];

    var masCreateSelect=[];

    var noSelect =0;


    //копіювання елементів з groups
    (function(){
        for(var i =0;i<groups.length;++i){
            masCreateSelect.push(groups[i]);
        }
    })()

    var clickLi = function (color,idValue,idSelect,name){
        var bool=false;
        for(var i=0;i<selectOption.length;++i){
            if(selectOption[i].idSelect===idSelect){
                bool=true;
                for(var j=0;j<masCreateSelect.length;++j){
                    if(masCreateSelect[j].id===idValue){
                        masCreateSelect.splice(j,1);
                    }
                };
                if(idValue!==selectOption[i].idValue) {
                    masCreateSelect.push({
                        id: selectOption[i].idValue,
                        color: selectOption[i].color,
                        name: selectOption[i].name
                    });
                }
                selectOption[i]={
                    color:color,
                    idValue:idValue,
                    idSelect:idSelect,
                    name:name
                }
                break;
            }
        }
        if(!bool) {
            noSelect--;
            for(var j=0;j<masCreateSelect.length;++j){
                if(masCreateSelect[j].id===idValue){
                    masCreateSelect.splice(j,1);
                    break;
                }
            }
            selectOption.push({
                color: color,
                idValue: idValue,
                idSelect: idSelect,
                name: name
            });
        }
        if(lenthSelect!==groups.length&&!bool &&noSelect!=1) {
            createSelect();
        }
    }

    var createSpanColorAndText = function(parent,group){
        var $spanColor = $('<span class="color">');
        $spanColor.appendTo(parent);
        $spanColor.css({
            'backgroundColor':group.color,
            'width':'10px',
            'height':'10px',
            'display':'inline-block',
            'marginRight':'3px',
            'borderRadius':'2px',
            'fontSize':'10px',
            'color':'white',
            'textAlign':'center'
        });
        $spanColor.text(group.name[0]);
        var $spanText = $('<span class="text">');
        $spanText.appendTo(parent);
        $spanText.text(group.name);
    }
    function createOption(group,parent){
        $(parent).empty();
        for(var i = 0;i<group.length;++i){
            var $li = $('<li>');
            $li.appendTo(parent);
            $li.attr({'data-value':group[i].id});

            createSpanColorAndText($li,group[i]);
            $li.on('click',function(){
                clickLi($(this).find('.color').css('backgroundColor'),$(this).attr('data-value'),parent.parent().attr('id'),$(this).find('.text').text());
            });
        }
    }

    function createSelect(){
        lenthSelect++;
        noSelect++;
       //створення контейнера для селекта
        var $div = $('<div>');
        $div.appendTo($(element));

        //ствоерння селекта
        var $select = $('<div>');
        var name = nameSelect+''+numberSelect;
        $select.attr({
            'id':name
        });
        $select.appendTo($div);
        $select.addClass('custom-select');
        numberSelect++;

        //створення видалення селекта
        var $delete = $('<span>');
        $delete.text('X');
        $delete.css({
            'cursor':'pointer'
        });
        $delete.on('click',function(){
            var bool=false;
            var value = animalSelect.getValue();
            if(value!==0){
                for(var i =0;i<groups.length;++i){
                    if(+groups[i].id===+value){
                        bool=true;
                        masCreateSelect.push(groups[i]);
                        for(var j=0;j<selectOption.length;++j){
                            if(+value===+selectOption[j].idValue){
                                selectOption.splice(j,1);
                                break;
                            }
                        }
                    }
                }
                if(!bool){
                }
                $div.remove();
                lenthSelect--;
                if(noSelect===0){
                    createSelect();
                }
            }


        });
        $delete.appendTo($div);

        //створення заголовку селекта
        var $title =$('<span>');
        $title.addClass('custom-select-title');
        $title.appendTo($select);



        //створення контейнера для опцій
        var $ul = $('<ul>');
        $ul.appendTo($select);
        $ul.addClass('custom-select-options');
        var animalSelect = new CustomSelect({
            elem: $('#'+name)
        });



        animalSelect.setValue('Добавить групу','-1');

        $title.on('click',function(){
            var newMas=[];
            for (var i = 0; i < masCreateSelect.length; ++i) {
                newMas.push(masCreateSelect[i]);
            }
            for (var i = 0; i < groups.length; ++i) {
                if (+groups[i].id === +animalSelect.getValue()) {
                    newMas.push(groups[i]);
                    break;
                }
            }
            createOption(newMas,$ul);
        });

        if(selectElement){
            for(var i=0;i<selectElement.length;++i){
                selectOption.push({
                    color:selectElement[i].color,
                    idValue:''+selectElement[i].id,
                    idSelect:name,
                    name:selectElement[i].name
                });
                for(var j=0;j<masCreateSelect.length;++j){
                    if(masCreateSelect[j].id===selectElement[i].id){
                        masCreateSelect.splice(j,1);
                        break;
                    }
                }
                $title.empty();
                noSelect--;
                createSpanColorAndText($title,selectElement[i]);

                animalSelect.setValues(selectElement[i].id);
                selectElement.splice(i,1);
                i--;
                createSelect();
            }
        }

    }

    createSelect();


    self.getMasGroups = function(){
        return selectOption;
    }


}