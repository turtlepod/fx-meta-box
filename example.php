<?php
/**
 * Plugin Name: f(x) Meta Box Example
 * Plugin URI: http://genbumedia.com/plugins/fx-meta-box/
 * Description: Example f(x) Meta Box Implementation
 * Version: 1.0.0
 * Author: David Chandra Purnama
 * Author URI: http://shellcreeper.com/
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
**/
namespace fx_Meta_Box_Example;
use fx_Meta_Box;


/* Register meta boxes */
add_action( 'add_meta_boxes', __NAMESPACE__ . '\add_meta_boxes' );


/**
 * Add Meta Boxes
 * @since 1.0.0
**/
function add_meta_boxes(){

	/* Tabs UI */
	add_meta_box(
		$id         = 'fx_meta_box_tabs_ui',
		$title      = __( 'f(x) Meta Box Tabs', 'domain' ),
		$callback   = __NAMESPACE__ . '\meta_box_with_tabs',
		$screen     = array( 'page' ),
		$context    = 'normal'
	);

	/* Simple UI */
	add_meta_box(
		$id         = 'fx_meta_box_simple',
		$title      = __( 'f(x) Meta Box Simple', 'domain' ),
		$callback   = __NAMESPACE__ . '\meta_box_simple',
		$screen     = array( 'page' ),
		$context    = 'normal'
	);
}

/**
 * Meta Box Callback
 * @since 1.0.0
 */
function meta_box_with_tabs( $post, $box ){
	$post_id = $post->ID;

	/* Tabs */
	$tabs = array(
		'general' => array(
			'id'          => 'general',
			'icon'        => 'dashicons-admin-settings',
			'label'       => __( 'General', 'domain' ),
			'callback'    => function() use( $post_id ){

				/* Text */
				$args = array(
					'label'         => __( 'Text', 'domain' ),
					'description'   => __( 'Text input description...', 'domain' ),
					'input_attr'    => array(
						'name'          => 'test_text1',
						'value'         => sanitize_text_field( get_post_meta( $post_id, 'test_text1', true ) ),
						'placeholder'   => sanitize_text_field( 'Add text here...' ),
						'type'          => 'text',
						'class'         => 'medium-text',
					),
				);
				fx_Meta_Box::input_field( $args );

				/* Text Area */
				$args = array(
					'label'         => __( 'Text Area', 'domain' ),
					'description'   => __( 'Textarea description...', 'domain' ),
					'name'          => 'test_textarea1',
					'value'         => esc_textarea( get_post_meta( $post_id, 'test_textarea1', true ) ),
					'placeholder'   => esc_textarea( 'Lorem ipsum...' ),
				);
				fx_Meta_Box::textarea_field( $args );

			},
		),
		'uploads' => array(
			'id'          => 'uploads',
			'icon'        => 'dashicons-admin-media',
			'label'       => __( 'Uploads', 'domain' ),
			'callback'    => function() use( $post_id ){

				/* ZIP Upload */
				$args = array(
					'label'       => __( 'ZIP Upload', 'domain' ),
					'description' => __( 'URL to ZIP file.', 'domain' ),
					'name'        => 'test_upload1',
					'value'       => esc_url_raw( get_post_meta( $post_id, 'test_upload1', true ) ),
					'media'        => array(
						'title'          => __( 'Upload ZIP', 'domain' ),
						'button'         => __( 'Insert ZIP URL', 'domain' ),
						'library_type'   => 'application/zip',
					),
				);
				fx_Meta_Box::upload_url_field( $args );

				/* Image Upload */
				$args = array(
					'label'       => __( 'Image Upload', 'domain' ),
					'description' => __( 'URL to Image file.', 'domain' ),
					'name'        => 'test_upload2',
					'value'       => esc_url_raw( get_post_meta( $post_id, 'test_upload2', true ) ),
					'media'        => array(
						'title'          => __( 'Upload Image', 'domain' ),
						'button'         => __( 'Insert Image URL', 'domain' ),
						'library_type'   => 'image',
					),
				);
				fx_Meta_Box::upload_url_field( $args );
			},
		),
		'radio_tabs' => array(
			'id'          => 'radio_tabs',
			'icon'        => 'dashicons-editor-table',
			'label'       => __( 'Tabs-Inception', 'domain' ),
			'callback'    => function() use( $post_id ){

				/* Radio Tabs */
				$args = array(
					'label'         => __( 'Take One', 'domain' ),
					'description'   => __( 'and I will show you how deep the rabbit hole goes...', 'domain' ),
					'name'          => 'test_radio1',
					'value'         => get_post_meta( $post_id, 'test_radio1', true ),
					'default'       => 'blue',
					'choices'       => array(
						'blue' => array(
							'label'      => __( 'Blue Pill', 'domain' ),
							'callback'   => function() use( $post_id ){

								/* Another Text */
								$args = array(
									'label'         => __( 'Text 2', 'domain' ),
									'description'   => __( 'Text input description...', 'domain' ),
									'input_attr'    => array(
										'name'          => 'test_text2',
										'value'         => sanitize_text_field( get_post_meta( $post_id, 'test_text2', true ) ),
										'placeholder'   => sanitize_text_field( 'Add text here...' ),
										'type'          => 'text',
										'class'         => 'medium-text',
									),
								);
								fx_Meta_Box::input_field( $args );

							},
						),
						'red' => array(
							'label'      => __( 'Red Pill', 'domain' ),
							'callback'   => function() use( $post_id ){

								/* Another Text Area */
								$args = array(
									'label'         => __( 'Text Area 2', 'domain' ),
									'description'   => __( 'Textarea description...', 'domain' ),
									'name'          => 'test_textarea2',
									'value'         => esc_textarea( get_post_meta( $post_id, 'test_textarea2', true ) ),
									'placeholder'   => esc_textarea( 'Lorem ipsum...' ),
								);
								fx_Meta_Box::textarea_field( $args );
							},
						),
					),
				);
				fx_Meta_Box::radio_tabs_field( $args );

			},
		),
	);

	/* Create tabs */
	fx_Meta_Box::tabs_ui( $tabs, $box['id'], $post_id );

	/* Add nonce */
	wp_nonce_field( __FILE__ , fx_Meta_Box::nonce_id( $box['id'] ) );
}

/**
 * Simple Meta Box Example
 * @since 1.0.0
 */
function meta_box_simple( $post, $box ){
	$post_id = $post->ID;

	/* Text */
	$args = array(
		'label'         => __( 'Text 3', 'domain' ),
		'description'   => __( 'Text input description...', 'domain' ),
		'input_attr'    => array(
			'name'          => 'test_text3',
			'value'         => sanitize_text_field( get_post_meta( $post_id, 'test_text3', true ) ),
			'placeholder'   => sanitize_text_field( 'Add text here...' ),
			'type'          => 'text',
			'class'         => 'medium-text',
		),
	);
	fx_Meta_Box::input_field( $args );

	/* URL */
	$args = array(
		'label'         => __( 'Text 4 (URL)', 'domain' ),
		'description'   => __( 'Text input description...', 'domain' ),
		'input_attr'    => array(
			'name'          => 'test_text4',
			'value'         => esc_url_raw( get_post_meta( $post_id, 'test_text4', true ) ),
			'placeholder'   => esc_html( 'http://' ),
			'type'          => 'url',
			'class'         => 'large-text',
		),
	);
	fx_Meta_Box::input_field( $args );

	/* Num */
	$args = array(
		'label'         => __( 'Text 5 (URL)', 'domain' ),
		'description'   => __( 'Text input description...', 'domain' ),
		'input_attr'    => array(
			'name'          => 'test_text5',
			'value'         => intval( get_post_meta( $post_id, 'test_text5', true ) ),
			'placeholder'   => 0,
			'type'          => 'number',
			'class'         => 'small-text',
			'min'           => "0",
			'max'           => "3",
		),
	);
	fx_Meta_Box::input_field( $args );

	/* Add nonce */
	wp_nonce_field( __FILE__ , fx_Meta_Box::nonce_id( $box['id'] ) );
}


/* Save Post
------------------------------------------ */

/* Save post meta on the 'save_post' hook. */
add_action( 'save_post', __NAMESPACE__ . '\save_meta_box_with_tabs', 10, 2 );

/**
 * Save Post Data
 * @since 1.0.0
 */
function save_meta_box_with_tabs( $post_id, $post ){

	/* Verify save post */
	if( ! fx_Meta_Box::verify_save_post( 'fx_meta_box_tabs_ui', __FILE__, $post_id, $post ) ){
		return $post_id;
	}

	/* Stripslashes Submitted Data */
	$request = stripslashes_deep( $_POST );

	/* FIELDS */
	$fields = array(

		/* Save Active Tab */
		array(
			'key'  => 'fx_meta_box_tabs_ui_active_tab',
			'data' => isset( $request['fx_meta_box_tabs_ui_active_tab'] ) ? esc_attr( $request['fx_meta_box_tabs_ui_active_tab'] ) : '',
		),

		/* Save General Section */
		array(
			'key'  => 'test_text1',
			'data' => isset( $request['test_text1'] ) ? esc_attr( $request['test_text1'] ) : '',
		),
		array(
			'key'  => 'test_textarea1',
			'data' => isset( $request['test_textarea1'] ) ? wp_kses_post( $request['test_textarea1'] ) : '',
		),

		/* Save Uploads Section */
		array(
			'key'  => 'test_upload1',
			'data' => isset( $request['test_upload1'] ) ? esc_url_raw( $request['test_upload1'] ) : '',
		),
		array(
			'key'  => 'test_upload2',
			'data' => isset( $request['test_upload2'] ) ? esc_url_raw( $request['test_upload2'] ) : '',
		),

		/* Save Radio/Tab Section */
		array(
			'key'  => 'test_radio1',
			'data' => isset( $request['test_radio1'] ) ? esc_attr( $request['test_radio1'] ) : '',
		),
		array(
			'key'  => 'test_text2',
			'data' => isset( $request['test_text2'] ) ? esc_attr( $request['test_text2'] ) : '',
		),
		array(
			'key'  => 'test_textarea2',
			'data' => isset( $request['test_textarea2'] ) ? esc_attr( $request['test_textarea2'] ) : '',
		),
	);
	foreach( $fields as $args ){
		fx_Meta_Box::save_post_meta( $post_id, $args['key'], $args['data'] );
	}
}

/* Save post meta on the 'save_post' hook. */
add_action( 'save_post', __NAMESPACE__ . '\save_meta_box_simple', 10, 2 );


/**
 * Save Post Data
 * @since 1.0.0
 */
function save_meta_box_simple( $post_id, $post ){

	/* Verify save post */
	if( ! fx_Meta_Box::verify_save_post( 'fx_meta_box_simple', __FILE__, $post_id, $post ) ){
		return $post_id;
	}

	/* Stripslashes Submitted Data */
	$request = stripslashes_deep( $_POST );

	/* FIELDS */
	$fields = array(
		array(
			'key'  => 'test_text3',
			'data' => isset( $request['test_text3'] ) ? esc_attr( $request['test_text3'] ) : '',
		),
		array(
			'key'  => 'test_text4',
			'data' => isset( $request['test_text4'] ) ? esc_url_raw( $request['test_text4'] ) : '',
		),
		array(
			'key'  => 'test_text5',
			'data' => isset( $request['test_text5'] ) ? intval( $request['test_text5'] ) : '',
		),
	);
	foreach( $fields as $args ){
		fx_Meta_Box::save_post_meta( $post_id, $args['key'], $args['data'] );
	}
}


/* Scripts
------------------------------------------ */

/* Enqueue Scripts */
add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\admin_scripts' );

/**
 * Enqueue Needed Scripts
 * @since 1.0.0
 */
function admin_scripts( $hook_suffix ){
	global $post_type;
	if( in_array( $hook_suffix, array( 'post-new.php', 'post.php' ) ) && 'page' == $post_type ){
		wp_enqueue_style( 'fx-meta-box' );
		wp_enqueue_script( 'fx-meta-box' );
	}
}


