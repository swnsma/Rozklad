(function(){
    $("#change")
        .on("click", function(){
            var field = $("#mail");
            field
                .css("display", "none");
            $("#mailInput")
                .css("display", "block")
                .val(field.text())
                .focus();
            $("#submit")
                .css("display", "block");
            $("#change")
                .css("display", "none");
        });
    $("#submit")
        .on("click", function(){
            var inp = $("#mailInput");
            console.log(inp);
            var email = inp.val();
            universalAPI(url+"app/admin/setMail","POST", function(){
                $("#mail")
                    .text(email)
                    .css("display", "block");
                inp.css("display", "none");
                $("#submit").css("display", "none");
                $("#change").css("display", "block");
            }, function(){console.log("Oh no! Something going wrong...")}, {mail:email})
    })
})();