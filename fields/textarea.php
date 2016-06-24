<?php
namespace fx_wpshop\fx_meta_box;
if ( ! defined( 'WPINC' ) ) { die; }

/**
 * Textarea
 */
function textarea( $args, $post, $box ){
	$args_default = array(
		'field_id'     => "fx-mb-field_{$args['name']}",
		'input_id'     => "fx-mb-input_{$args['name']}",
		'label'        => '',
		'name'         => '',
		'description'  => '',
		'value'        => '',
		'sanitize'     => 'esc_textarea',
		'placeholder'  => '',
		'input_class'  => 'widefat',
	);
	$args = wp_parse_args( $args, $args_default );
	?>
		<div id="<?php echo esc_attr( $args['field_id'] );?>" class="fx-mb-field fx-mb-field-textarea">

			<?php if( $args['label'] ){ ?>
			<p>
				<label class="fx-mb-label" for="<?php echo esc_attr( $args['input_id'] );?>">
					<?php echo $args['label']; ?>
				</label>
			</p>
			<?php } // label ?>

			<div class="fx-mb-p">
				<textarea autocomplete="off" id="<?php echo esc_attr( $args['input_id'] );?>" class="<?php echo esc_attr( $args['input_class'] ); ?>" placeholder="<?php echo esc_attr( $args['placeholder'] );?>" name="<?php echo esc_attr( $args['name'] );?>" rows="2"><?php echo esc_textarea( $args['value'] ); ?></textarea>
			</div>

			<?php if( $args['description'] ){ ?>
			<p class="fx-mb-description">
				<?php echo $args['description'];?>
			</p>
			<?php } // description ?>

		</div><!-- .fx-mb-field -->
	<?php
}