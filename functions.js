// Browser detection for when you get desparate. A measure of last resort.
// http://rog.ie/post/9089341529/html5boilerplatejs

// var b = document.documentElement;
// b.setAttribute('data-useragent',  navigator.userAgent);
// b.setAttribute('data-platform', navigator.platform);

// sample CSS: html[data-useragent*='Chrome/13.0'] { ... }


// remap jQuery to $
(function($){


/* trigger when page is ready */
$(document).ready(function (){


	// fit text if desktop

	if ($(window).width() >= 480) {

		// h1
		if ($("body").hasClass("maybe"))
			$("h1").fitText(0.5);
		else
			$("h1").fitText(0.3);

		// h2
		if ($("body").hasClass("rain") || $("body").hasClass("maybe")) {
			$("h2").fitText(1.2);
		}
		else
			$("h2").fitText(0.9);
	}


	// center text

	var wh 		= $(window).height();
	var box_h	= $("header").height();

	$("header").css("margin-top", box_h/2 * (-1) - 30);

});


/* optional triggers

$(window).load(function() {
	
});

*/

$(window).resize(function() {

	var wh 		= $(window).height();
	var box_h	= $("header").height();

	$("header").css("margin-top", box_h/2 * (-1) - 30);
	
});


})(window.jQuery);