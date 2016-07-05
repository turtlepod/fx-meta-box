;( function( $ ){

	/**
	 * f(x) Meta Box Tabs UI Script
	 **/

	/* === PREPARE === */

	/* Add CSS for styling */
	$( '.fx-mbtabs' ).parents( '.postbox' ).addClass( 'fx-mbtabs-ui' );

	/* Open Selected Tabs */
	$( '.fx-mbtabs-nav' ).each( function() {

		/* Get input value */
		var input_value = $( this ).find( 'input[data-id="fx_mbtabs_active_tab"]' ).val();

		/* Activate selected tab */
		$( this ).find( 'a' ).each( function() {

			var value = $( this ).data( 'value' );
			if( input_value == value ){

				/* Active Tab */
				var tab = $( this ).parent( '.fx-mbtabs-tab' );
				tab.addClass( 'selected' );
				tab.siblings( '.fx-mbtabs-tab' ).removeClass( 'selected' );

				/* Show/Hide Active Section */
				var target = $( this ).attr( 'href' );
				$( target ).siblings( '.fx-mbtabs-section' ).hide();
				$( target ).show();
			}
		});

	});

	/* === ACTION === */

	/* Click Tab */
	$( '.fx-mbtabs-nav li a' ).click( function(e){
		e.preventDefault();

		/* Nav Status */
		var tab = $( this ).parent( '.fx-mbtabs-tab' );
		tab.addClass( 'selected' );
		tab.siblings( '.fx-mbtabs-tab' ).removeClass( 'selected' );

		/* Show/Hide Active Section */
		var section = $( this ).attr( 'href' );
		$( section ).siblings( '.fx-mbtabs-section' ).hide();
		$( section ).show();

		/* Update Hidden Option. */
		var input_value = $( this ).data( 'value' );
		var input_hidden = $( this ).parents( '.fx-mbtabs-nav' ).find( 'input[data-id="fx_mbtabs_active_tab"]' );
		input_hidden.val( input_value );
	});


})( jQuery );