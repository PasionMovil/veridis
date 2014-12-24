 /*	Menu START */
jQuery(function(){
	"use strict";
	// main navigation init
	jQuery('.main-menu ul.sf-menu').superfish({
		delay:	300,	// one second delay on mouseout 
		animation:   {opacity:'show',height:'show'}, // fade-in and slide-down animation
		speed:       'fast',  // faster animation speed 
		autoArrows:  true,   // generation of arrow mark-up (for submenu) 
		dropShadows: false
	});
});


 function menuPaddingSet() {
    var containerWidth = jQuery('.main-menu').outerWidth();
    var liWidthTotal = 0;
    var counter = 0;
	jQuery('.main-menu ul.sf-menu >  li > a').each(function() {
	jQuery(this).css('padding', '0');
   	 liWidthTotal += jQuery(this).innerWidth();
    	counter += 2;
	});  
	
	if(navigator.userAgent.toLowerCase().indexOf('firefox') > -1) {
		liWidthTotal = liWidthTotal + 4;
	}
	if(navigator.appName.indexOf("Internet Explorer")!=-1){ 
		liWidthTotal = liWidthTotal + 4;
	}
	var padding =  Math.floor((containerWidth - liWidthTotal) / counter);

	var x = containerWidth - liWidthTotal;
	var y = x - (padding*counter);
	var z = Math.floor(y/2);
	var c = y - (z*2);
	

	var counter_compare = 0;
	var extra_counter = 0;
	jQuery('.main-menu ul.sf-menu > li > a').each(function() {
	
		counter_compare += 2;
	 	if (extra_counter < z) {
			paddingnew = padding + 1;
			jQuery(this).css('padding', '0px ' + paddingnew + 'px');
	  	} else {
			if (c > 0) {
				paddingnew = padding + 1;
				jQuery(this).css('padding', '0px ' + padding + 'px 0px ' + paddingnew + 'px');
				c=0;
			}
			else {
				jQuery(this).css('padding', '0px ' + padding + 'px');
			}
	  	}
	   extra_counter += 1;
	});
	jQuery('.menu-wrapper').css('visibility', 'visible'); 
	jQuery('.menu-wrapper-hidden').css('display', 'none'); 
 }

jQuery(document).ready(function () {
	menuPaddingSet();
});

(function($,sr){

  // debouncing function from John Hann
  // http://unscriptable.com/index.php/2009/03/20/debouncing-javascript-methods/
  var debounce = function (func, threshold, execAsap) {
      var timeout;

      return function debounced () {
          var obj = this, args = arguments;
          function delayed () {
              if (!execAsap)
                  func.apply(obj, args);
              timeout = null;
          };

          if (timeout)
              clearTimeout(timeout);
          else if (execAsap)
              func.apply(obj, args);

          timeout = setTimeout(delayed, threshold || 100);
      };
  }
  // smartresize 
  jQuery.fn[sr] = function(fn){  return fn ? this.bind('resize', debounce(fn)) : this.trigger(sr); };

})(jQuery,'smartresize');


jQuery(window).smartresize(function(){
  menuPaddingSet();
});


jQuery(document).ready(function(){
		jQuery(".main-menu .sf-menu > li.menu-item > a, .main-menu .sf-menu > li.menu-item > .submenu_1").hover(function(){
	
		jQuery(".main-menu .sf-menu").addClass("default_color");
	},function(){
		jQuery(".main-menu .sf-menu").removeClass("default_color");
	});
});	




jQuery(function($) {

	if(sticky_menu == 'yes') {

		// grab the initial top offset of the navigation 
		var sticky_navigation_offset_top = $('.menu-wrapper').offset().top;
	
		// our function that decides weather the navigation bar should have "fixed" css position or not.
		var sticky_navigation = function(){
			var scroll_top = $(window).scrollTop(); // our current vertical position from the top
		
			// if we've scrolled more than the navigation, change its position to fixed to stick to top, otherwise change it back to relative
			if (scroll_top > sticky_navigation_offset_top) { 
				$('.menu-wrapper').css({ 'position': 'fixed', 'top':0, 'left':0 });
				$('.menu-wrapper-hidden').css({ 'display': 'block' });
			} else {
				$('.menu-wrapper').css({ 'position': 'relative' }); 
				$('.menu-wrapper-hidden').css({ 'display': 'none' });
			}   
		};
	
		// run our function on load
		sticky_navigation();
	
		// and run it again every time you scroll
		$(window).scroll(function() {
			 sticky_navigation();
		
		});
	
	}
	
});

/* menu END */

/* news ticker START */
jQuery(function($){
	$("ul.news-ticker").liScroll();
});
/* news ticker END */

/* single post gallery START */			
jQuery(window).load(function(){
      jQuery('.carousel-single-post').flexslider({
        animation: "slide",
        controlNav: false,
        animationLoop: false,
        slideshow: false,
        itemWidth: 210,
        itemMargin: 5,
        asNavFor: '.slider-single-post'
      });

      jQuery('.slider-single-post').flexslider({
        animation: "fade",
        controlNav: false,
        animationLoop: false,
        slideshow: false,
        sync: ".carousel-single-post",
        start: function(slider){
         // $('body').removeClass('loading');
        }
      });
});
 /* single post gallery END */	
 
 

/*	flexslider - carousel START */ 
jQuery(window).load(function() {
	"use strict";
	jQuery('.flexsliderCarousel').flexslider({animation: "slide", useCSS: false });
	jQuery('.flextwitter').flexslider({animation: "slide", useCSS: false});
})
/*	flexslider - carousel END */ 


/*	Testimonails START  */
jQuery(document).ready(function(){
	"use strict";
	jQuery( function() {	
		jQuery( '.cbp-qtrotator' ).cbpQTRotator();

	} );
});

/*	Testimonails END  */

/* audio player START */

jQuery(document).ready(function(){
	if (document.getElementById("container").offsetWidth < 500) {
		jQuery('audio').mediaelementplayer({audioWidth: 300,  iPhoneUseNativeControls: true });
	}
	else {
		jQuery('audio').mediaelementplayer();
	}
});

/* audio player END */


/* Circle progress bar
************************************************************/
 function easyCharts() {
      
	
	   jQuery('.easyPieChart').each(function () {
			var $this, $parent_width, $chart_size;
			$this =jQuery(this);
			$parent_width = jQuery(this).parent().width();
			$chart_size = $this.attr('data-barSize');
			if (!$this.hasClass('chart-animated')) {
				$this.easyPieChart({
					animate: 3000,
					lineCap: 'round',
					lineWidth: $this.attr('data-lineWidth'),
					size: $chart_size,
					barColor: $this.attr('data-barColor'),
					trackColor: $this.attr('data-trackColor'),
					scaleColor: 'transparent',
					onStep: function (value) {
						this.$el.find('.chart-percent span').text(Math.ceil(value));
					}
				});
			}
		});
	
        
 }

jQuery(document).ready(function () {
	easyCharts();
});



/* Search window open/close
************************************************************/
jQuery(document).ready(function() { 
	jQuery('.search-from-call').click(function() {
		jQuery('#upper-panel-wrapper #s').slideToggle("fast");
		jQuery('#upper-panel-wrapper #s').toggleClass("show-search");
		return false;
	});	
});	

/* ---------------------------------------------------------------------- */
/*	Dropdown Menu
/* ---------------------------------------------------------------------- */
jQuery(document).ready(function($){
	jQuery('.main-menu ul.sf-menu').mobileMenu({
    	defaultText: 'Navigate to...',
    	className: 'select-menu',
    	subMenuDash: '&nbsp;&nbsp;&nbsp;&ndash;'
	});	 
 	
});





jQuery(document).ready(function(){
	jQuery('.main-no-sidebar').each(function() { 
		var allhtml = jQuery(this).html();
		var htmlnospaces = allhtml.replace(/\s/g, ""); 
		if ((htmlnospaces == '') || (htmlnospaces == '<p></p>')|| (htmlnospaces == '<P></P>')|| (htmlnospaces == '</P>')) {
			jQuery(this).css({ 'display': 'none' });
		}
	});
	
});


jQuery(window).load(function() {
	"use strict";
	jQuery('.flexsliderPostSection').flexslider({animation: "slide", useCSS: false, slideshow: true, pauseOnHover: true, slideshowSpeed: 3500});
})


