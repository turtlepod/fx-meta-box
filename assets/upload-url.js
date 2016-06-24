/**
 * Upload URL
**/
;( function( $ ){

	/* === Upload Media Modal === */

	/* the vars */
	var fx_mbtabs_media_frame = {};

	/* Click the upload button */
	$( document.body ).on( 'click', '.fx-mb-field-upload-url .fx-mb-upload-button', function(e){
		e.preventDefault();

		var this_button = $( this );

		/* Unique media frame based on library type. */
		var lib_type = this_button.data( 'library-type' ).replace( "/", "_" );

		/* Open the frame if already loaded. */
		if ( fx_mbtabs_media_frame[lib_type] ) {
			fx_mbtabs_media_frame[lib_type].open();
			return;
		}

		/* If media frame doesn't exist, create it with some options. */
		fx_mbtabs_media_frame[lib_type] = wp.media.frames.file_frame = wp.media({
			className: 'media-frame fx-media-frame',
			frame: 'select',
			title: this_button.data( 'title' ),
			library: { type: this_button.data( 'library-type' ) },
			button: { text:  this_button.data( 'button' ) },
			multiple: false,
		});

		/* Insert. */
		fx_mbtabs_media_frame[lib_type].on( 'select', function(){
			var this_attachment = fx_mbtabs_media_frame[lib_type].state().get('selection').first().toJSON();
			this_button.siblings( 'input[type="url"]' ).val( this_attachment.url );
		});

		/* Open frame. */
		fx_mbtabs_media_frame[lib_type].open();
	});

	/* === Remove Button === */

	$( document.body ).on( 'click', '.fx-mb-field-upload-url .fx-mb-remove-button', function(e){
		e.preventDefault();
		$( this ).siblings( 'input[type="url"]' ).val( '' );
	});

})( jQuery );