<?php
namespace fx_meta_box;
if ( ! defined( 'WPINC' ) ) { die; }

/**
 * Radio Tab
 */
function checkbox( $args, $post, $box ){
	$args_default = array(
		'field_id'     => "fx-mb-field_{$args['name']}",
		'input_id'     => "fx-mb-input_{$args['name']}",
		'label'        => '',
		'name'         => '',
		'description'  => '',
		'value'        => array(),
		'default'      => array(),
		'choices'      => array(),
	);
	$args = wp_parse_args( $args, $args_default );
	if( empty( $args['value'] ) ){
		$args['value'] = is_array( $args['default'] ) ? array() : '';
	}
	else{
		$args['value'] = is_array( $args['default'] ) ? (array)$args['value'] : (string)$args['value'];
	}
	?>
		<div id="<?php echo esc_attr( $args['field_id'] );?>" class="fx-mb-field fx-mb-field-checkbox">

			<?php if( $args['label'] ){ ?>
			<p>
				<span class="fx-mb-label">
					<?php echo $args['label']; ?>
				</span>
			</p>
			<?php } // label ?>

			<ul class="fx-mb-field-checkbox-list">
			<?php foreach( $args['choices'] as $value => $label ){
				if( is_array( $args['default'] ) ){
					$checked = in_array( $value, $args['value'] ) ? 'checked="checked"' : '';
				}
				else{
					$checked = ( $args['value'] == $value ) ? 'checked="checked"' : '';
				}
				?>
				<li>
					<label>
						<input type="checkbox" name="<?php echo esc_attr( $args['name'] );?>" value="<?php echo esc_attr( $value );?>" <?php echo $checked; ?>>
						<?php echo $label; ?>
					</label>
				</li>
			<?php } ?>
			</ul>

			<?php if( $args['description'] ){ ?>
			<p class="fx-mb-description">
				<?php echo $args['description'];?>
			</p>
			<?php } // description ?>

		</div><!-- .fx-mb-field -->
	<?php
}
