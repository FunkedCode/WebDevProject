$(document).ready(function(){

	$radio = $("input[name='color']");

    $radio.change(function()
    {
    	if($("input[name='color']:checked").val() == 1)
    	{
    		$("label,p,h3").css("color","black");
    		$("body").css("background-color","white");
    	}
    	else
    	{
    		$("label,p,h3").css("color","white");
    		$("body").css("background-color","#212121");
    	}
    });
});