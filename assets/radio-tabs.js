/**
 * Radio Tabs
**/
;( function( $ ){

	/* === Prepare: open selected tab === */

	$( '.fx-mb-field-radio-tabs .wp-tab-bar' ).each( function() {

		/* Get input value */
		var input_value = $( this ).find( 'input[name="' + $( this ).data( 'name' ) + '"]' ).val();

		/* Activate selected tab */
		$( this ).find( 'a' ).each( function() {

			var value = $( this ).data( 'value' );
			if( input_value == value ){

				/* Active Tab */
				var tab = $( this ).parent( '.tabs' );
				tab.addClass( 'wp-tab-active' );
				tab.siblings( '.tabs' ).removeClass( 'wp-tab-active' );

				/* Show/Hide Active Panel */
				var target = $( this ).attr( 'href' );
				$( target ).siblings( '.wp-tab-panel' ).hide();
				$( target ).show();
			}
		});

	});


	/* === Click Tab === */

	$( '.fx-mb-field-radio-tabs .wp-tab-bar a' ).click( function(e) {
		e.preventDefault();

		/* Active Tab */
		var tab = $( this ).parent( '.tabs' );
		tab.addClass( 'wp-tab-active' );
		tab.siblings( '.tabs' ).removeClass( 'wp-tab-active' );

		/* Show/Hide Active Panel */
		var target = $( this ).attr( 'href' );
		$( target ).siblings( '.wp-tab-panel' ).hide();
		$( target ).show();

		/* Update Hidden Option. */
		var input_name = $( this ).parents( '.wp-tab-bar' ).data( 'name' );
		var input_value = $( this ).data( 'value' );
		$( 'input[name="' + input_name + '"]' ).val( input_value );
	});

})( jQuery );
