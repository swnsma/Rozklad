var mod=(function(mod){

    var humanFriendlyDate = new HumanFriendlyDate();

    function HumanFriendlyDate(){

        var seconds = ["секунд","секунды","секунду"];
        var minutes = ["минут","минуты","минуту"];
        var hours = ["часов","часа","час"];
        var days = ["дней","дня","день"];
        var weeks = ["недель","недели","неделю"];
        var month = ["месяцев","месяца","месяц"];
        var years = ["год","года","год"];
        var massOfDate = [seconds,minutes,hours,days,weeks,month,years];

        this.getDateRus = function(date){
            var delta = getDateNow()-date;
            if(delta<60&&delta>0){
                return getRightFormat(delta,0);
            }
            else if(delta>=60&&delta<3600){
                return getRightFormat(Math.floor(delta/60),1);
            }
            else if(delta>=3600&&delta<86400){
                return getRightFormat(Math.floor(delta/3600),2);
            }
            else if(delta>=86400&&delta<604800){
                return getRightFormat(Math.floor(delta/86400),3);
            }
            else if(delta>=604800&&delta<2419200){
                return getRightFormat(Math.floor(delta/604800),4);
            }
            else if(delta>=2419200&&delta<29030400){
                return getRightFormat(Math.floor(delta/2419200),5);
            }
            else if(delta>=29030400){
                return getRightFormat(Math.floor(delta/29030400),6);
            }
        };

        function getRightFormat(dat,coef){
            var date = dat.toString();
            var ln= date.length;
            var firstDigit = parseInt(date[ln-1]);
            var secondDigit = parseInt(date[ln-2]);

            switch (firstDigit){
                case 1:
                    if(secondDigit===1){
                        return date+" "+massOfDate[coef][0]+" "+"назад";
                    }
                    return date+" "+massOfDate[coef][2]+" "+"назад";

                    break;
                case 2:
                case 3:
                case 4:
                    if(secondDigit===1){
                        return date+" "+massOfDate[coef][0]+" "+"назад";
                    }
                    return date+" "+massOfDate[coef][1]+" "+"назад";
                    break;
                default:
                    return date+" "+massOfDate[coef][0]+" "+"назад";
                    break;
            }

        }
    }
    function getDateNow(){
        return Date.now() / 1000 | 0;
    }
    var viewModel = {
        currentUser:ko.observable(),
        treeRoot:getTreeObject(),
        textForComment:ko.observable(),
        reply:ko.observable(true),
        sendNewComment:function(){
            sendNewComment();
        }
    };
    mod.viewModel = viewModel;

    function TreeObject(){
        var self=this;
        self.children = ko.observableArray();
        self.remove=function(data){
            removeComment(data,
                function(response){
                    if(response==='ok'){
                        self.children.remove(function(item) {
                            return item.id() == data.id();
                        });
                    }
                }
            );
        };

        self.addNewItem=function(data){
            addComment(data,
                function(response){
                    self.children.push(new TreeElement(response,response.CHILDREN));
                }
            );
        };

        self.push=function(el){
            self.children.push(el);
        };

        self.init=function(arr){
            self.children(arr);
        }
    };

    var TreeElement = function(comment, children){
        var self = this;
        self.name = ko.observable(comment.name+" "+comment.surname);
        self.children = ko.observableArray(children);
        self.user_id = ko.observable(comment.user_id);
        self.id=ko.observable(comment.com_id);
        self.date=ko.observable(humanFriendlyDate.getDateRus(comment.date));
        self.text=ko.observable(comment.text);
        self.pid=ko.observable(comment.pid);
        self.textForComment=ko.observable();
        self.reply=ko.observable(false);
        self.photo=ko.observable("");

        if(comment.fb_id) {
            self.photo('http://graph.facebook.com/' + comment.fb_id + '/picture?type(square)');
        }
        else{
            self.photo(comment.gm_photo);
        }

        self.remove=function(data){
            removeComment(data,
                function(response){
                    if(response==='ok'){
                        self.children.remove(function(item) {
                            return item.id() == data.id();
                        });
                    }
                }
            );
        };

        self.addNewItem=function(){
            self.reply(!self.reply());
        };

        self.sendNewComment=function(){
            sendNewCommentLevels(self);
        }
    };

    function getTreeObject(){
        return new TreeObject();
    }


    mod.getCurrentUser=function(func){
        mod.ajax(
            url+"app/comment/getCurrentUser",
            function(response){
                viewModel.currentUser(response);
                func();
            },
            {
                contentType: 'application/json',
                dataType: 'json',
                error:function(e){
                    console.log(e);
                }
            }
        );
    };

    var sendNewComment=function(){
        addCommentFirstLevel(viewModel,function(comment){
            viewModel.treeRoot.push(new TreeElement(comment,mod.addNewTree(comment.CHILDREN)));
            viewModel.textForComment('');
        });
    };

    var sendNewCommentLevels=function(self){
        var date = new Date();
        addComment(self,
            function(response){
                self.children.push(new TreeElement(response,response.CHILDREN));
                self.reply(false);
                self.textForComment('');
                setTimeout(function(){
                    showData(date);
                },2000);
            }
        );
    };
    mod.init=function(){
      mod.getCurrentUser(mod.getTree);
    };
    mod.getTree=function(){
        mod.ajax(
            url+"app/comment/tree",
            function(response){
                debugger;
                viewModel.treeRoot.init(mod.addNewTree(response));

                $("textarea").autogrow();
            },
            {
                contentType: 'application/json',
                dataType: 'json',
                error:function(e){
                    console.log(e);
                }
            }
        );
    };

    mod.addNewTree=function(comment){
        var array=[];
        if(comment&&comment.length){
            for(var i=0;i<comment.length;i++){
                array.push(new TreeElement(comment[i],mod.addNewTree(comment[i].CHILDREN)))
            }
        }
        return array;
    };

    mod.ajax=function(url,success,opt){
        var def={};
        def.url = url;
        def.success = success;
        var options =opt||{};
        var end=$.extend(def,options);
        //console.dir(end);
        $.ajax(end);
    };

    var addComment=function(self,success){
        mod.ajax(
            url+"app/comment/addComment/",
            success,
            {
                data:{
                    data:{
                        pid:self.id(),
                        lesson_id:1,
                        date:parseInt(getDateNow()),
                        text:self.textForComment()
                    }
                },
                type:"POST"
            }
        )
    };

    var addCommentFirstLevel=function(self,success){
        mod.ajax(
            url+"app/comment/addComment/",
            success,
            {
                data:{
                    data:{
                        pid:0,
                        lesson_id:1,
                        date:parseInt(getDateNow()),
                        text:self.textForComment()
                    }
                },
                type:"POST"
            }
        )
    };

    var removeComment=function(data,success){
        mod.ajax(
            url+"app/comment/removeComment/",
            success,
            {
                data:{
                    id:data.id()
                },
                type:"POST"
            }
        )
    };

    var getNormalizeDate=function(date){
        function format(num){
            if((num+'').length==1){
                num='0'+num;
            }
            return num;
        }
        var normalDate=date.getFullYear()+"-"+format(date.getMonth())+"-"+format(date.getDay())+" "+format(date.getHours())+":"+format(date.getMinutes())+":"+format(date.getSeconds());
        return normalDate;

    };
    var showData=function(data){
        var current = new Date();
        var year=data.getFullYear(),
            month=data.getMonth(),
            day=data.getDay(),
            hours=data.getHours(),
            minutes=data.getMinutes(),
            seconds=data.getSeconds();

        if(current.getSeconds()-seconds);
    };
    return mod;

}(mod||{}));

ko.bindingHandlers.handleTextareaAutogrow={
    update: function(element, valueAccessor){
        var accessor=valueAccessor();
        $(element).autogrow();
    }
};


(function ($) {
    $.fn.autogrow = function (options) {
        var $this, minHeight, lineHeight, shadow, update;
        this.filter('textarea').each(function () {
            $this = $(this);
            minHeight = $this.height();
            lineHeight = $this.css('lineHeight');
            $this.css('overflow','hidden');
            shadow = $('<div></div>').css({
                position: 'absolute',
                'word-wrap': 'break-word',
                top: -10000,
                left: -10000,
                width: $this.width(),
                fontSize: $this.css('fontSize'),
                fontFamily: $this.css('fontFamily'),
                lineHeight: $this.css('lineHeight'),
                resize: 'vertical'
            }).appendTo(document.body);
            update = function () {
                shadow.css('width', $(this).width());
                var val = this.value.replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/\n/g, '<br/>')
                    .replace(/\s/g,'&nbsp;');
                if (val.indexOf('<br/>', val.length - 5) !== -1) { val += '#'; }
                shadow.html(val);
                $(this).parent().css('height', Math.max(shadow.height()+15, minHeight));
            };
            $this.change(update).keyup(update).keydown(update);
            update.apply(this);
        });
        return this;
    };
    // On page-load, auto-expand textareas to be tall enough to contain initial content
}(jQuery));

