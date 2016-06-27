<?php
/**
 * Plugin Name: f(x) Meta Box Library
 * Plugin URI: http://genbumedia.com/plugins/fx-meta-box/
 * Description: Single class meta box library for developer.
 * Version: 1.0.0
 * Author: David Chandra Purnama
 * Author URI: http://shellcreeper.com/
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: fx-meta-box
 * Domain Path: /languages/
 *
 * @author David Chandra Purnama <david@genbumedia.com>
 * @copyright Copyright (c) 2016, Genbu Media
**/

/* Load Class */
fx_Meta_Box::get_instance();

/**
 * Meta Box Library
 * @since 1.0.0
 */
final class fx_Meta_Box{

	static $version = '';
	static $uri     = '';
	static $path    = '';

	/* Construct
	------------------------------------------ */
	public function __construct(){

		/* Vars */
		self::$version = '1.0.0';
		self::$path    = trailingslashit( plugin_dir_path(__FILE__) );
		self::$uri     = trailingslashit( plugin_dir_url( __FILE__ ) );

		/* Register Admin Scripts */
		add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ), 1 );
	}


	/* Admin Scripts
	------------------------------------------ */

	/**
	 * Register Admin Scripts
	 * @since 1.0.0
	 */
	public static function scripts( $hook_suffix ){

		/* Enqueue or use this as dependency */
		wp_register_style( 'fx-meta-box', self::$uri . 'assets/style.css', array(), self::$version );
		wp_register_script( 'fx-meta-box', self::$uri . 'assets/script.js', array( 'jquery' ), self::$version, true );

		/* Leave this, will auto-load when the field is used. */
		wp_register_script( 'fx-meta-box_radio-tabs', self::$uri . 'assets/radio-tabs.js', array( 'jquery', 'fx-meta-box' ), self::$version, true );
		wp_register_script( 'fx-meta-box_upload-url', self::$uri . 'assets/upload-url.js', array( 'jquery', 'fx-meta-box' ), self::$version, true );

	}


	/* Fields Wrapper
	------------------------------------------ */

	/**
	 * Create Tabbed UI in Meta Box
	 * @since 1.0.0
	 */
	public static function tabs_ui( $tabs, $meta_box_id = '' ){
		if( $meta_box_id ){
			$tabs = apply_filters( sanitize_title( 'fx_meta_box_tabs-' . $meta_box_id ), $tabs );
		}
		if( ! is_array( $tabs ) || ! $tabs ) return false;
		?>
		<div class="fx-mbtabs">

			<ul class="fx-mbtabs-nav">
				<?php
				$i = 0;
				foreach( $tabs as $nav ){
					$i++; $class = ( 1 === $i ) ? 'selected' : '';
					$id = "fx-mbtab_{$nav['id']}";
					?>
					<li class="<?php echo esc_attr( $class ); ?>">
						<a href="#<?php echo esc_attr( $id ); ?>">
							<i class="dashicons <?php echo esc_attr( $nav['icon'] ); ?>"></i>
							<span class="label"><?php echo esc_attr( $nav['label'] ); ?></span>
						</a>
					</li><!-- .fx-mbtabs-nav-li -->
					<?php
				}
				?>
			</ul><!-- .fx-mbtabs-nav -->

			<div class="fx-mbtabs-content">
				<?php
				$i = 0;
				foreach( $tabs as $section ){
					$i++; $style = ( 1 === $i ) ? 'display:block;' : 'display:none;';
					$id = "fx-mbtab_{$section['id']}";
					?>
					<div id="<?php echo esc_attr( $id ); ?>" class="fx-mbtabs-section" style="<?php echo esc_attr( $style ); ?>">
						<?php if ( is_callable( $section['callback'] ) ){
							call_user_func( $section['callback'] );
						} ?>
					</div><!-- .fx-mbtabs-section -->
					<?php
				}
				?>
			</div><!-- .fx-mbtabs-content -->

		</div><!-- .fx-mbtabs -->
		<?php
	}

	/* Fields
	------------------------------------------ */

	/**
	 * Input Field
	 * Valid for: Text, URL, Email, etc.
	 * @since 1.0.0
	 */
	public static function input_field( $args ){
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
			<div id="<?php echo sanitize_html_class( $args['field_id'] );?>" class="fx-mb-field fx-mb-field-input">

				<div class="fx-mb-label">
					<?php if( $args['label'] ){ ?>
						<p>
							<label for="<?php echo sanitize_html_class( $args['input_id'] );?>">
								<?php echo $args['label']; ?>
							</label>
						</p>
					<?php } // label ?>
				</div><!-- .fx-mb-label -->

				<div class="fx-mb-content">
					<p>
						<input autocomplete="off" id="<?php echo sanitize_html_class( $args['input_id'] );?>" placeholder="<?php echo esc_attr( $args['placeholder'] );?>" type="<?php echo esc_attr( $args['input_type'] ); ?>" class="<?php echo esc_attr( $args['input_class'] ); ?>" name="<?php echo esc_attr( $args['name'] );?>" value="<?php echo $args['value']; ?>">
					</p>

					<?php if( $args['description'] ){ ?>
					<p class="fx-mb-description">
						<?php echo $args['description'];?>
					</p>
					<?php } // description ?>
				</div><!-- .fx-mb-content -->

			</div><!-- .fx-mb-field -->
		<?php
	}


	/**
	 * Textarea Field
	 * @since 1.0.0
	 */
	public static function textarea_field( $args ){
		$args_default = array(
			'field_id'     => "fx-mb-field_{$args['name']}",
			'input_id'     => "fx-mb-input_{$args['name']}",
			'label'        => '',
			'name'         => '',
			'description'  => '',
			'value'        => '',
			'placeholder'  => '',
			'input_class'  => 'widefat',
		);
		$args = wp_parse_args( $args, $args_default );
		?>
			<div id="<?php echo sanitize_html_class( $args['field_id'] );?>" class="fx-mb-field fx-mb-field-textarea">

				<div class="fx-mb-label">
					<?php if( $args['label'] ){ ?>
					<p>
						<label for="<?php echo sanitize_html_class( $args['input_id'] );?>">
							<?php echo $args['label']; ?>
						</label>
					</p>
					<?php } // label ?>
				</div><!-- .fx-mb-label -->

				<div class="fx-mb-content">
					<div class="fx-mb-p">
						<textarea autocomplete="off" id="<?php echo sanitize_html_class( $args['input_id'] );?>" class="<?php echo esc_attr( $args['input_class'] ); ?>" placeholder="<?php echo esc_attr( $args['placeholder'] );?>" name="<?php echo esc_attr( $args['name'] );?>" rows="2"><?php echo esc_textarea( $args['value'] ); ?></textarea>
					</div>

					<?php if( $args['description'] ){ ?>
					<p class="fx-mb-description">
						<?php echo $args['description'];?>
					</p>
					<?php } // description ?>
				</div><!-- .fx-mb-content -->

			</div><!-- .fx-mb-field -->
		<?php
	}

	/**
	 * Select Field
	 * @since 1.0.0
	 */
	public static function select_field( $args ){
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
			'option_none'  => '&mdash; Select &mdash;',
			'default'      => '',
			'choices'      => array(),
		);
		$args = wp_parse_args( $args, $args_default );
		?>
			<div id="<?php echo sanitize_html_class( $args['field_id'] );?>" class="fx-mb-field fx-mb-field-select">

				<div class="fx-mb-label">
					<?php if( $args['label'] ){ ?>
					<p>
						<label for="<?php echo sanitize_html_class( $args['input_id'] );?>">
							<?php echo $args['label']; ?>
						</label>
					</p>
					<?php } // label ?>
				</div><!-- .fx-mb-label -->

				<div class="fx-mb-content">
					<p>
						<select autocomplete="off" id="<?php echo sanitize_html_class( $args['input_id'] );?>" name="<?php echo esc_attr( $args['name'] );?>" class="<?php echo esc_attr( $args['input_class'] );?>">

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
				</div><!-- .fx-mb-content -->

			</div><!-- .fx-mb-field -->
		<?php
	}


	/**
	 * Checkbox Field
	 * Also for multiple checkbox
	 * @since 1.0.0
	 */
	public static function checkbox_field( $args ){
		$args_default = array(
			'field_id'     => "fx-mb-field_{$args['name']}",
			'input_id'     => "fx-mb-input_{$args['name']}",
			'label'        => '',
			'name'         => '',
			'description'  => '',
			'value'        => array(),
			'multiple'     => true,
			'choices'      => array(),
		);
		$args = wp_parse_args( $args, $args_default );
		$args['value'] = $args['multiple'] ? (array)$args['value'] : (string)$args['value'];
		?>
			<div id="<?php echo sanitize_html_class( $args['field_id'] );?>" class="fx-mb-field fx-mb-field-checkbox">

				<div class="fx-mb-label">
					<?php if( $args['label'] ){ ?>
					<p>
						<span>
							<?php echo $args['label']; ?>
						</span>
					</p>
					<?php } // label ?>
				</div><!-- .fx-mb-label -->

				<div class="fx-mb-content">
					<ul class="fx-mb-field-checkbox-list">
					<?php foreach( $args['choices'] as $value => $label ){
						if( $args['multiple'] ){
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
				</div><!-- .fx-mb-content -->

			</div><!-- .fx-mb-field -->
		<?php
	}


	/**
	 * Upload URL
	 * URL fields with upload button to easily insert file URL.
	 * @since 1.0.0
	 */
	public static function upload_url_field( $args ){
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
			<div id="<?php echo sanitize_html_class( $args['field_id'] );?>" class="fx-mb-field fx-mb-field-upload-url">

				<div class="fx-mb-label">
					<?php if( $args['label'] ){ ?>
					<p>
						<label for="<?php echo sanitize_html_class( $args['input_id'] );?>">
							<?php echo $args['label']; ?>
						</label>
					</p>
					<?php } // label ?>
				</div><!-- .fx-mb-label -->

				<div class="fx-mb-content">
					<p>
						<input autocomplete="off" id="<?php echo sanitize_html_class( $args['input_id'] );?>" placeholder="http://" type="url" class="<?php echo esc_attr( $args['input_class'] ); ?>" name="<?php echo esc_attr( $args['name'] );?>" value="<?php echo $args['value']; ?>">

						<a class="button button-primary fx-mb-upload-button" href="#" data-title="<?php echo esc_attr( $args['media']['title'] ); ?>" data-button="<?php echo esc_attr( $args['media']['button'] ); ?>" data-library-type="<?php echo esc_attr( $args['media']['library_type'] ); ?>" ><?php _e( 'Upload', 'fx-wpdev' ); ?></a> 
						<a class="button fx-mb-remove-button" href="#"><?php _e( 'Remove', 'fx-wpdev' ); ?></a>
					</p>

					<?php if( $args['description'] ){ ?>
					<p class="fx-mb-description">
						<?php echo $args['description'];?>
					</p>
					<?php } // description ?>
				</div><!-- .fx-mb-content -->

			</div><!-- .fx-mb-field -->
		<?php
	}


	/**
	 * Radio Tabs Field
	 * Also wrapper to create fields within tab.
	 * @since 1.0.0
	 */
	public static function radio_tabs_field( $args ){
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
			<div id="<?php echo sanitize_html_class( $args['field_id'] );?>" class="fx-mb-field fx-mb-field-radio-tabs">

				<div class="fx-mb-label">
					<?php if( $args['label'] ){ ?>
					<p>
						<span>
							<?php echo $args['label']; ?>
						</span>
					</p>
					<?php } // label ?>
				</div><!-- .fx-mb-label -->

				<div class="fx-mb-content">
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
						<div id="<?php echo sanitize_html_class( "{$args['input_id']}-{$key}" );?>" class="wp-tab-panel" style="<?php echo esc_attr( $style ); ?>">
							<?php if ( is_callable( $panel['callback'] ) ){
								call_user_func( $panel['callback'] );
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
				</div><!-- .fx-mb-content -->

			</div><!-- .fx-mb-field -->
		<?php
	}


	/* Other Utility
	------------------------------------------ */

	/**
	 * Create unique nonce ID for a meta box
	 * @since 1.0.0
	 */
	public static function nonce_id( $meta_box_id ){
		return $meta_box_id . '-fx_meta_box_nonce';
	}

	/**
	 * Check and validate Save Meta Data
	 */
	public static function verify_save_post( $meta_box_id, $nonce_value, $post_id, $post ){

		/* Request */
		$request = stripslashes_deep( $_POST );

		/* Get meta box nonce ID */
		$nonce_id = self::nonce_id( $meta_box_id );

		/* Check if it's valid */
		if ( ! isset( $request[$nonce_id] ) || ! wp_verify_nonce( $request[$nonce_id], $nonce_value ) ){
			return $post_id;
		}
		/* No Auto Save */
		if( defined('DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){
			return $post_id;
		}
		/* Check user caps */
		$post_type = get_post_type_object( $post->post_type );
		if ( !current_user_can( $post_type->cap->edit_post, $post_id ) ){
			return $post_id;
		}

		/* Return true after pass all check. */
		return true;
	}

	/**
	 * Utility to Save Meta Data
	 * This function should be added in save_post hook
	 * @since 1.0.0
	 */
	public static function save_post_meta( $post_id, $key, $new_data, $unique = true ){

		/* Get (old) saved data */
		$old_data = get_post_meta( $post_id, $key, $unique );

		/* New data submitted, No previous data, create it  */
		if ( $new_data && empty( $old_data ) ){
			add_post_meta( $post_id, $key, $new_data, $unique );
		}
		/* New data submitted, but it's different data than previously stored data, update it */
		elseif( $new_data && ( $new_data != $old_data ) ){
			update_post_meta( $post_id, $key, $new_data );
		}
		/* New data submitted is empty, but there's old data available, delete it. */
		elseif ( empty( $new_data ) && $old_data ){
			delete_post_meta( $post_id, $key );
		}
	}


	/* Sanitize Functions
	------------------------------------------ */

	/**
	 * Sanitize Version
	 */
	public static function sanitize_version( $input ){
		$output = sanitize_text_field( $input );
		$output = str_replace( ' ', '', $output );
		return trim( esc_attr( $output ) );
	}

	/* Use singleton pattern
	------------------------------------------ */

	/**
	 * Returns the instance.
	 * @since  1.0.0
	 */
	public static function get_instance(){
		static $instance = null;
		if ( is_null( $instance ) ){
			$instance = new self;
		}
		return $instance;
	}

} // end class
