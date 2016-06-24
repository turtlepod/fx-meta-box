<?php
namespace fx_wpshop\fx_meta_box;
if ( ! defined( 'WPINC' ) ) { die; }

/**
 * Text
 * Also can be use for: URL, Email, etc.
 */
function text( $args, $post, $box ){
	$args_default = array(
		'field_id'     => "fx-mb-field_{$args['name']}",
		'input_id'     => "fx-mb-input_{$args['name']}",
		'label'        => '',
		'name'         => '',
		'description'  => '',
		'value'        => '',
		'placeholder'  => '',
		'input_class'  => 'large-text',
		'input_type'   => 'text',
	);
	$args = wp_parse_args( $args, $args_default );
	?>
		<div id="<?php echo esc_attr( $args['field_id'] );?>" class="fx-mb-field fx-mb-field-text">

			<?php if( $args['label'] ){ ?>
			<p>
				<label class="fx-mb-label" for="<?php echo esc_attr( $args['input_id'] );?>">
					<?php echo $args['label']; ?>
				</label>
			</p>
			<?php } // label ?>

			<p>
				<input autocomplete="off" id="<?php echo esc_attr( $args['input_id'] );?>" placeholder="<?php echo esc_attr( $args['placeholder'] );?>" type="<?php echo esc_attr( $args['input_type'] ); ?>" class="<?php echo esc_attr( $args['input_class'] ); ?>" name="<?php echo esc_attr( $args['name'] );?>" value="<?php echo $args['value']; ?>">
			</p>

			<?php if( $args['description'] ){ ?>
			<p class="fx-mb-description">
				<?php echo $args['description'];?>
			</p>
			<?php } // description ?>

		</div><!-- .fx-mb-field -->
	<?php
}