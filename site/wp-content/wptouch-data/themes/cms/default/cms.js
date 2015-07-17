/* WPtouch CMS Theme Js File */


function doCmsReady() {

	// Triggers focus on the search field when the search tab item is clicked
	jQuery( '#search-menu-button' ).on( 'click', function(){
		jQuery( '#search-text' ).focus();
	});

	// Helps with usability and the fastclick module— it's too fast and triggers taps too quickly!
	jQuery( '#section-slider a' ).each( function(){
		jQuery( this ).addClass( 'needsclick' );
	});

	jQuery( '#content' ).on( 'click', '.entry', function(){
		var postLink = jQuery( this ).find( 'a:first').attr( 'href' );
		window.location = postLink;
	});

	cmsWebApp();

}

function cmsWebApp(){
	jQuery( window ).resize( function() {
		var windowHeight = ( jQuery( window ).height() - 142 );
		if ( jQuery( 'body.web-app-mode.ios7.smartphone.portrait' ).length ) {
			jQuery( '.wptouch-menu' ).css( 'max-height', windowHeight );
		}
		if ( jQuery( 'body.web-app-mode.ios7.smartphone.landscape' ).length ) {
			jQuery( '.wtouch-menu' ).css( 'max-height', windowHeight );
		}
	}).resize();

	if ( jQuery( 'body.web-app-mode.ios7' ).length ) {
		jQuery( 'body' ).prepend( '<span class="fixed-header-fill"></span>' );

		/* No touchmove, please */
		jQuery( '#header-title-logo' ).each( function(){
			jQuery( this ).on( 'touchmove', function( e ){ e.preventDefault(); } );
		});
	}
}

jQuery( document ).ready( function() { doCmsReady(); } );