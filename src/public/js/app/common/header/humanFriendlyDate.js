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

    function getDateNow(){
        return Date.now() / 1000 | 0;
    }
}