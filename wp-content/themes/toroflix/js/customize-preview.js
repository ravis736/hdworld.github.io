/**
 * Live-update changed settings in real time in the Customizer preview.
 */

( function( $ ) {
	var api = wp.customize;

	// Logo.
	api( 'toroflix_theme_options[logo]', function( value ) {
		value.bind( function( to ) {
            $('.custom-logo').attr('src', to);
		} );
	} );

} )( jQuery );