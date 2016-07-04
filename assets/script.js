;( function( $ ){

	/**
	 * f(x) Meta Box Tabs UI Script
	 **/

	/* Add CSS for styling */
	$( '.fx-mbtabs' ).parents( '.postbox' ).addClass( 'fx-mbtabs-ui' );

	/* Click Tab */
	$( '.fx-mbtabs-nav li a' ).click( function(e){
		e.preventDefault();

		/* Nav Status */
		$( this ).parents( '.fx-mbtabs-nav' ).children( 'li' ).removeClass( 'selected' );
		$( this ).parent( 'li' ).addClass( 'selected' );

		/* Show Content */
		var section = $( this ).attr( 'href' );
		$( section ).siblings( '.fx-mbtabs-section' ).hide();
		$( section ).show();
	});

})( jQuery );