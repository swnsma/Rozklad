
$(document).ready(function(){
    $(".button").click(function(){
        var href=$(this).attr("href");
        window.location=href;
    })
});

