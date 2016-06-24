<?php
namespace fx_wpshop\fx_meta_box;
if ( ! defined( 'WPINC' ) ) { die; }

/**
 * Select
 */
function select( $args, $post, $box ){
	$args_default = array(
		'field_id'     => "fx-mb-field_{$args['name']}",
		'input_id'     => "fx-mb-input_{$args['name']}",
		'label'        => '',
		'name'         => '',
		'description'  => '',
		'value'        => '',
		'placeholder'  => '',
		'input_class'  => '',
		'input_type'   => 'text',
		'option_none'  => __( '— Select —' ),
		'default'      => '',
		'choices'      => array(),
	);
	$args = wp_parse_args( $args, $args_default );
	?>
		<div id="<?php echo esc_attr( $args['field_id'] );?>" class="fx-mb-field fx-mb-field-select">

			<?php if( $args['label'] ){ ?>
			<p>
				<label class="fx-mb-label" for="<?php echo esc_attr( $args['input_id'] );?>">
					<?php echo $args['label']; ?>
				</label>
			</p>
			<?php } // label ?>

			<p>
				<select autocomplete="off" id="<?php echo esc_attr( $args['input_id'] );?>" name="<?php echo esc_attr( $args['name'] );?>" class="<?php echo esc_attr( $args['input_class'] );?>">

					<?php if( false !== $args['option_none'] ){ ?>
					<option value="" <?php selected( $args['value'], '' ); ?>><?php echo $args['option_none']; ?></option>
					<?php } ?>

					<?php foreach( $args['choices'] as $c_value => $c_label ){ ?>
						<option value="<?php echo esc_attr( $c_value ); ?>" <?php selected( $args['value'], $c_value ); ?>><?php echo strip_tags( $c_label ); ?></option>
					<?php } ?>

				</select>
			</p>

			<?php if( $args['description'] ){ ?>
			<p class="fx-mb-description">
				<?php echo $args['description'];?>
			</p>
			<?php } // description ?>

		</div><!-- .fx-mb-field -->
	<?php
}