<?php
namespace fx_wpshop\fx_meta_box;
if ( ! defined( 'WPINC' ) ) { die; }

/**
 * Radio Tab
 */
function radio_tabs( $args, $post, $box ){
	wp_enqueue_script( 'fx-meta-box_radio-tabs' );
	$args_default = array(
		'field_id'     => "fx-mb-field_{$args['name']}",
		'input_id'     => "fx-mb-input_{$args['name']}",
		'label'        => '',
		'name'         => '',
		'description'  => '',
		'value'        => '',
		'default'      => '',
		'choices'      => array(),
	);
	$args = wp_parse_args( $args, $args_default );
	$value = array_key_exists( $args['value'], $args['choices'] ) ? $args['value'] : $args['default'];
	?>
		<div id="<?php echo esc_attr( $args['field_id'] );?>" class="fx-mb-field fx-mb-field-radio-tabs">

			<?php if( $args['label'] ){ ?>
			<p>
				<span class="fx-mb-label">
					<?php echo $args['label']; ?>
				</span>
			</p>
			<?php } // label ?>

			<ul class="wp-tab-bar" data-name="<?php echo esc_attr( $args['name'] );?>" >
				<?php
				$i = 0;
				foreach( $args['choices'] as $key => $nav ){
					$i++; $class = ( 1 === $i ) ? 'tabs wp-tab-active' : 'tabs';
					?>
					<li class="<?php echo esc_attr( $class ); ?>">
						<a href="#<?php echo esc_attr( "{$args['input_id']}-{$key}" );?>" data-value="<?php echo esc_attr( $key );?>"><?php echo $nav['label']; ?></a>
					</li>
					<?php
				}
				?>
				<input type="hidden" name="<?php echo esc_attr( $args['name'] );?>" value="<?php echo esc_attr( $value ); ?>" autocomplete="off">
			</ul><!-- .wp-tab-bar -->

			<?php
			$i = 0;
			foreach( $args['choices'] as $key => $panel ){
				$i++; $style = ( 1 === $i ) ? 'display:block;' : 'display:none;';
				?>
				<div id="<?php echo esc_attr( "{$args['input_id']}-{$key}" );?>" class="wp-tab-panel" style="<?php echo esc_attr( $style ); ?>">
					<?php if ( is_callable( $panel['callback'] ) ){
						call_user_func( $panel['callback'], $post, $box );
					} ?>
				</div><!-- .wp-tab-panel-->
				<?php
			}
			?>

			<?php if( $args['description'] ){ ?>
			<p class="fx-mb-description">
				<?php echo $args['description'];?>
			</p>
			<?php } // description ?>

		</div><!-- .fx-mb-field -->
	<?php
}
