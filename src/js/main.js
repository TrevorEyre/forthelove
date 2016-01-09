jQuery(function ($) {

	$("header .genesis-nav-menu, .nav-primary .genesis-nav-menu, .nav-secondary .genesis-nav-menu").addClass("responsive-menu").before('<div class="responsive-menu-icon"></div>');

	$(".responsive-menu-icon").click(function(){
		$(this).next("header .genesis-nav-menu, .nav-primary .genesis-nav-menu, .nav-secondary .genesis-nav-menu").slideToggle(200);
		$(this).toggleClass("open");
	});

	$(window).resize(function(){
		// Responsive nav menu response to resize event
        if(window.innerWidth > 767) {
			$("header .genesis-nav-menu, .nav-primary .genesis-nav-menu, .nav-secondary .genesis-nav-menu, nav .sub-menu").removeAttr("style");
			$(".responsive-menu > .menu-item").removeClass("menu-open");
		}
	});

	$(".responsive-menu > .menu-item").click(function(event){
		if (event.target !== this)
		return;
		
		$(this).find(".sub-menu:first").slideToggle(function() {
			$(this).parent().toggleClass("menu-open");
		});
	});
	
	// Window scroll effects for nav bar, right social icons
	$(window).scroll(function () {
		var scrollTop = $(this).scrollTop();
		
		// White background on nav after scrolling down a bit
		if (scrollTop > 68) {
			$('.nav-primary').addClass('gbo-fixed');
		} else {
			$('.nav-primary').removeClass('gbo-fixed');
		}
		
		// Fixed nav logo after scrolling past hero image
		if (scrollTop > $('.site-inner').offset().top) {
			$('.nav-primary').addClass('gbo-fixed-logo');
			$('.floating-social-icons-right').removeClass('gbo-fixed');
		} else {
			$('.nav-primary').removeClass('gbo-fixed-logo');
			$('.floating-social-icons-right').addClass('gbo-fixed');
		}
	});
	
	// Hero scroll button
	$('#hero-scroll').on('click', function () {
		var scrollDest = $('.site-inner').offset().top;
		$('html, body').animate({scrollTop: scrollDest}, 500);
	});
});
