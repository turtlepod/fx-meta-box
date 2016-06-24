<?php
namespace fx_meta_box;
if ( ! defined( 'WPINC' ) ) { die; }

/**
 * Upload File
 */
function upload_url( $args, $post, $box ){
	wp_enqueue_media();
	wp_enqueue_script( 'fx-meta-box_upload-url' );
	$args_default = array(
		'field_id'     => "fx-mb-field_{$args['name']}",
		'input_id'     => "fx-mb-input_{$args['name']}",
		'label'        => '',
		'name'         => '',
		'description'  => '',
		'value'        => '',
		'input_class'  => 'large-text',
		'media'        => array(
			'title'          => 'Upload',
			'button'         => 'Insert File URL',
			'library_type'   => 'application/zip',
		),
	);
	$args = wp_parse_args( $args, $args_default );
	?>
		<div id="<?php echo esc_attr( $args['field_id'] );?>" class="fx-mb-field fx-mb-field-upload-url">

			<?php if( $args['label'] ){ ?>
			<p>
				<label class="fx-mb-label" for="<?php echo esc_attr( $args['input_id'] );?>">
					<?php echo $args['label']; ?>
				</label>
			</p>
			<?php } // label ?>

			<p>
				<input autocomplete="off" id="<?php echo esc_attr( $args['input_id'] );?>" placeholder="http://" type="url" class="<?php echo esc_attr( $args['input_class'] ); ?>" name="<?php echo esc_attr( $args['name'] );?>" value="<?php echo $args['value']; ?>">

				<a class="button button-primary fx-mb-upload-button" href="#" data-title="<?php echo esc_attr( $args['media']['title'] ); ?>" data-button="<?php echo esc_attr( $args['media']['button'] ); ?>" data-library-type="<?php echo esc_attr( $args['media']['library_type'] ); ?>" ><?php _e( 'Upload', 'fx-wpdev' ); ?></a> 
				<a class="button fx-mb-remove-button" href="#"><?php _e( 'Remove', 'fx-wpdev' ); ?></a>
			</p>

			<?php if( $args['description'] ){ ?>
			<p class="fx-mb-description">
				<?php echo $args['description'];?>
			</p>
			<?php } // description ?>

		</div><!-- .fx-mb-field -->
	<?php
}