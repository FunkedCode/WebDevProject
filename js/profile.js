$(document).ready(function(){

	$radio = $("input[name='color']");

    $radio.change(function()
    {
    	if($("input[name='color']:checked").val() == "white")
    	{
            $(".jumbotron").css("color","#c4b6b3");
    		$("label,h3,h1,h2,h4").css("color","black");
    		$("body").css("background-color","white");
    	}
    	else
    	{
            $(".jumbotron").css("color","#0b1016");
    		$("label,h3,h1,h2,h4").css("color","ffffff");
    		$("body").css("background-color","#141d26");
    	}
    });
});