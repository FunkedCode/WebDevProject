$(document).ready(function(){

    $radio = $("input[name='color']");

    $radio.change(function()
    {
        if($("input[name='color']:checked").val() == "white")
        {
            $("#header").css("background-color","#c4b6b3");
            $(".section").css("background-color","white");
            $("label,h3,h2,h4").css("color","black");
            $("body").css("background-color","white");
        }
        else
        {
            $("#header").css("background-color","#0b1016");
            $(".section").css("background-color","#243447");
            $("label,h3,h2,h4,h1").css("color","white");
            $("body").css("background-color","#141d26");
        }
    });
});