$(document).ready(function(){

	$eventButton = $("#makePlan");
	$closeButton = $("#close");

	var isMobile = {
    Android: function() {
        return navigator.userAgent.match(/Android/i);
    },
    BlackBerry: function() {
        return navigator.userAgent.match(/BlackBerry/i);
    },
    iOS: function() {
        return navigator.userAgent.match(/iPhone|iPad|iPod/i);
    },
    Opera: function() {
        return navigator.userAgent.match(/Opera Mini/i);
    },
    Windows: function() {
        return navigator.userAgent.match(/IEMobile/i);
    },
    any: function() {
        return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
    }
	};

	var lastScroll = $(this).scrollTop();
	var lengthBeforeScroll = 550;
	var count = 2;

	if (!isMobile.any())
	{
		$(window).scroll(function()
		{
   		$currentCard = $("#hiddenCard" + count);
   		console.log(lastScroll + " " + lengthBeforeScroll );

   		if (lengthBeforeScroll < lastScroll)
   		{
       		$currentCard.show(1500);

       		count++;

       		lengthBeforeScroll += 520;

   		}
 
   		lastScroll = $(this).scrollTop();

		});
	}
	else
	{
		$currentCard = $(".card").show();
	}

    $eventButton.click(function()
    {
        $("#eventForm").css("display","block");
        
        $eventButton.css("display","none");
        $closeButton.css("display","block");

    });

    $closeButton.click(function()
    {
        $("#eventForm").css("display","none");
        
        $eventButton.css("display","block");
        $closeButton.css("display","none");

    });

    
});