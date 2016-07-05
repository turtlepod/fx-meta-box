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
 * Important: Change this class name with unique prefix if used as library.
 * @since 1.0.0
 */
final class fx_Meta_Box{

	static $version = '';
	static $uri     = '';
	static $path    = '';

	/* Construct
	------------------------------------------ */
	function __construct(){

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
	public static function tabs_ui( $tabs, $meta_box_id = '', $post_id = false ){
		if( $meta_box_id ){
			$tabs = apply_filters( sanitize_title( 'fx_meta_box_tabs-' . $meta_box_id ), $tabs );
		}
		if( ! is_array( $tabs ) || ! $tabs ) return false;
		?>
		<div class="fx-mbtabs">

			<ul class="fx-mbtabs-nav">
				<?php
				$first_tab = '';
				$i = 0;
				foreach( $tabs as $nav ){
					$i++;
					if( 1 === $i ){ $first_tab = $nav['id']; }
					$class = ( 1 === $i ) ? 'fx-mbtabs-tab selected' : 'fx-mbtabs-tab';
					$id = "fx-mbtab_{$nav['id']}";
					?>
					<li class="<?php echo esc_attr( $class ); ?>">
						<a data-value="<?php echo esc_attr( $nav['id'] ); ?>" href="#<?php echo esc_attr( $id ); ?>">
							<i class="dashicons <?php echo esc_attr( $nav['icon'] ); ?>"></i>
							<span class="label"><?php echo esc_attr( $nav['label'] ); ?></span>
						</a>
					</li><!-- .fx-mbtabs-nav-li -->
					<?php
				}
				?>
				<?php
				/* Hidden Input to save active tabs. */
				$name = '';
				$value = $first_tab;
				if( false !== $post_id && $meta_box_id ){
					$name = sanitize_title( $meta_box_id . '_active_tab' );
					$value = get_post_meta( $post_id, $name, true );
					$value = $value ? $value : $first_tab;
				} ?>
				<li class="fx-mb-hideme" style="display:none;">
					<input data-id="fx_mbtabs_active_tab" type="hidden" autocomplete="off" value="<?php echo $value;?>" name="<?php echo $name; ?>">
				</li>
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
			'label'          => '',
			'description'    => '',
			'field_attr'     => array(),
			'control_attr'   => array( 'name' => '' ),
		);
		$args = wp_parse_args( $args, $args_default );

		/* Extract var */
		extract( $args );

		/* Field Attr */
		$field_attr_default = array(
			'id'           => $control_attr['name'] ? "fx-mb-field_{$control_attr['name']}" : false,
			'class'        => '',
		);
		$field_attr = wp_parse_args( $field_attr, $field_attr_default );
		$field_attr['class'] = trim( $field_attr['class'] . " fx-mb-field fx-mb-field-input" );

		/* Control Attr */
		$control_attr_default = array(
			'id'           => $control_attr['name'] ? "fx-mb-input_{$control_attr['name']}" : false,
			'class'        => 'large-text',
			'name'         => '',
			'value'        => '',
			'type'         => 'text',
			'autocomplete' => 'off',
		);
		$control_attr = wp_parse_args( $control_attr, $control_attr_default );
		?>
			<div <?php echo self::attr( $field_attr ); ?>>
				<?php self::label( $label, $control_attr['id'] ); ?>

				<div class="fx-mb-content">

					<p>
						<input <?php self::attr( $control_attr ); ?>>
					</p>

					<?php self::description( $description ); ?>
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
			'label'        => '',
			'description'  => '',
			'field_attr'   => array(),
			'control_attr' => array( 'name' => '' ),
		);
		$args = wp_parse_args( $args, $args_default );

		/* Extract var */
		extract( $args );

		/* Field Attr */
		$field_attr_default = array(
			'id'           => $control_attr['name'] ? "fx-mb-field_{$control_attr['name']}" : false,
			'class'        => '',
		);
		$field_attr = wp_parse_args( $field_attr, $field_attr_default );
		$field_attr['class'] = trim( $field_attr['class'] . " fx-mb-field fx-mb-field-textarea" );

		/* Control Attr */
		$control_attr_default = array(
			'id'           => $control_attr['name'] ? "fx-mb-input_{$control_attr['name']}" : false,
			'class'        => 'widefat',
			'name'         => '',
			'rows'         => 2,
			'autocomplete' => 'off',
			'value'        => '',
		);
		$control_attr = wp_parse_args( $control_attr, $control_attr_default );
		$textarea_value = $control_attr['value'];
		unset( $control_attr['value'] );
		?>
			<div <?php echo self::attr( $field_attr ); ?>>
				<?php self::label( $label, $control_attr['id'] ); ?>

				<div class="fx-mb-content">

					<div class="fx-mb-p">
						<textarea <?php self::attr( $control_attr ); ?>><?php echo esc_textarea( $textarea_value ); ?></textarea>
					</div>

					<?php self::description( $description ); ?>
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
			'label'        => '',
			'description'  => '',
			'field_attr'   => array(),
			'control_attr' => array( 'name' => '' ),
			'choices'      => array(),
			'option_none'  => '&mdash; Select &mdash;',
		);
		$args = wp_parse_args( $args, $args_default );

		/* Extract var */
		extract( $args );

		/* Field Attr */
		$field_attr_default = array(
			'id'           => $control_attr['name'] ? "fx-mb-field_{$control_attr['name']}" : false,
			'class'        => '',
		);
		$field_attr = wp_parse_args( $field_attr, $field_attr_default );
		$field_attr['class'] = trim( $field_attr['class'] . " fx-mb-field fx-mb-field-select" );

		/* Control Attr */
		$control_attr_default = array(
			'id'           => $control_attr['name'] ? "fx-mb-input_{$control_attr['name']}" : false,
			'class'        => '',
			'name'         => '',
			'autocomplete' => 'off',
			'value'        => '',
		);
		$control_attr = wp_parse_args( $control_attr, $control_attr_default );
		$selected_value = $control_attr['value'];
		unset( $control_attr['value'] );
		?>
			<div <?php echo self::attr( $field_attr ); ?>>
				<?php self::label( $label, $control_attr['id'] ); ?>

				<div class="fx-mb-content">

					<p>
						<select <?php self::attr( $control_attr ); ?>>

							<?php if( false !== $option_none ){ ?>
								<option value="" <?php selected( $selected_value, '' ); ?>><?php echo $option_none; ?></option>
							<?php } ?>

							<?php foreach( $choices as $value => $label ){ ?>
								<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $selected_value, $value ); ?>><?php echo strip_tags( $label ); ?></option>
							<?php } ?>

						</select>
					</p>

					<?php self::description( $description ); ?>
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
			'label'        => '',
			'description'  => '',
			'field_attr'   => array(),
			'control_attr' => array( 'name' => '' ),
			'multiple'     => true, // set to false for single checkbox
			'choices'      => array(),
		);
		$args = wp_parse_args( $args, $args_default );

		/* Extract var */
		extract( $args );

		/* Field Attr */
		$field_attr_default = array(
			'id'           => $control_attr['name'] ? "fx-mb-field_{$control_attr['name']}" : false,
			'class'        => '',
		);
		$field_attr = wp_parse_args( $field_attr, $field_attr_default );
		$field_attr['class'] = trim( $field_attr['class'] . " fx-mb-field fx-mb-field-checkbox" );

		/* Control Attr */
		$control_attr_default = array(
			'id'           => $control_attr['name'] ? "fx-mb-input_{$control_attr['name']}" : false,
			'name'         => '',
			'autocomplete' => 'off',
			'value'        => array(),
		);
		$control_attr = wp_parse_args( $control_attr, $control_attr_default );
		$control_attr['type'] = 'checkbox';
		$checked_value = $control_attr['value'];
		unset( $control_attr['value'] );
		?>
			<div <?php echo self::attr( $field_attr ); ?>>
				<?php self::label( $label, '' ); ?>

				<div class="fx-mb-content">

					<ul>
						<?php foreach( $choices as $value => $label ){
							$control_attr['value'] = $value;
							unset( $control_attr['checked'] );
							if( $multiple ){
								if( in_array( $value, $checked_value ) ){
									$control_attr['checked'] = 'checked';
								}
							}
							else{
								if( $checked_value == $value ){
									$control_attr['checked'] = 'checked';
								}
							}
							?>
							<li>
								<label>
									<input <?php self::attr( $control_attr ); ?>>
									<?php echo $label; ?>
								</label>
							</li>
						<?php } // end foreach ?>
					</ul>

					<?php self::description( $description ); ?>
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
			'label'          => '',
			'description'    => '',
			'field_attr'     => array(),
			'control_attr'   => array( 'name' => '' ),
			'media_args'     => array(),
		);
		$args = wp_parse_args( $args, $args_default );

		/* Extract var */
		extract( $args );

		/* Field Attr */
		$field_attr_default = array(
			'id'           => $control_attr['name'] ? "fx-mb-field_{$control_attr['name']}" : false,
			'class'        => '',
		);
		$field_attr = wp_parse_args( $field_attr, $field_attr_default );
		$field_attr['class'] = trim( $field_attr['class'] . " fx-mb-field fx-mb-field-upload-url" );

		/* Control Attr */
		$control_attr_default = array(
			'id'           => $control_attr['name'] ? "fx-mb-input_{$control_attr['name']}" : false,
			'class'        => 'large-text',
			'name'         => '',
			'value'        => '',
			'type'         => 'url',
			'placeholder'  => 'http://',
			'autocomplete' => 'off',
		);
		$control_attr = wp_parse_args( $control_attr, $control_attr_default );

		/* Media Label */
		$media_args_default = array(
			'upload_button'              => 'Upload',
			'remove_button'              => 'Remove',
			'media_modal_title'          => 'Upload',
			'media_modal_insert_button'  => 'Insert URL',
			'library_type'               => '',
		);
		$media_args =  wp_parse_args( $media_args, $media_args_default );
		?>
			<div <?php echo self::attr( $field_attr ); ?>>
				<?php self::label( $label, $control_attr['id'] ); ?>

				<div class="fx-mb-content">

					<p>
						<input <?php self::attr( $control_attr ); ?>>

						<?php
						$upload_attr = array(
							'class'             => 'button button-primary fx-mb-upload-button',
							'href'              => '#',
							'data-title'        => $media_args['media_modal_title'],
							'data-button'       => $media_args['media_modal_insert_button'],
							'data-library-type' => $media_args['library_type'],
						);
						?>

						<a <?php self::attr( $upload_attr ); ?>><?php echo $media_args['upload_button']; ?></a> <a class="button fx-mb-remove-button" href="#"><?php echo $media_args['remove_button']; ?></a>
					</p>

					<?php self::description( $description ); ?>
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
			'label'          => '',
			'description'    => '',
			'field_attr'     => array(),
			'control_attr'   => array( 'name' => '' ),
			'choices'        => array(),
		);
		$args = wp_parse_args( $args, $args_default );

		/* Extract var */
		extract( $args );

		/* Field Attr */
		$field_attr_default = array(
			'id'           => $control_attr['name'] ? "fx-mb-field_{$control_attr['name']}" : false,
			'class'        => '',
		);
		$field_attr = wp_parse_args( $field_attr, $field_attr_default );
		$field_attr['class'] = trim( $field_attr['class'] . " fx-mb-field fx-mb-field-radio-tabs" );

		/* Control Attr */
		$control_attr_default = array(
			'id'           => $control_attr['name'] ? "fx-mb-input_{$control_attr['name']}" : false,
			'name'         => '',
			'value'        => '',
			'type'         => 'hidden',
			'autocomplete' => 'off',
		);
		$control_attr = wp_parse_args( $control_attr, $control_attr_default );
		?>
			<div <?php echo self::attr( $field_attr ); ?>>
				<?php self::label( $label, '' ); ?>

				<div class="fx-mb-content">

					<ul class="wp-tab-bar" data-name="<?php echo esc_attr( $control_attr['name'] );?>" >

						<?php
						$i = 0;
						foreach( $choices as $key => $nav ){
							$i++;
							$class = ( 1 === $i ) ? 'tabs wp-tab-active' : 'tabs';
							?>
							<li class="<?php echo esc_attr( $class ); ?>">
								<a href="#<?php echo esc_attr( "{$control_attr['id']}-{$key}" );?>" data-value="<?php echo esc_attr( $key );?>"><?php echo $nav['label']; ?></a>
							</li>
							<?php
						}
						?>
						<li class="fx-mb-hideme" style="display:none;">
							<input <?php self::attr( $control_attr ); ?>>
						</li>

					</ul><!-- .wp-tab-bar -->

					<?php
					$i = 0;
					foreach( $choices as $key => $panel ){
						$i++;
						$style = ( 1 === $i ) ? 'display:block;' : 'display:none;';
						?>
						<div id="<?php echo sanitize_html_class( "{$control_attr['id']}-{$key}" );?>" class="wp-tab-panel" style="<?php echo esc_attr( $style ); ?>">
							<?php if ( is_callable( $panel['callback'] ) ){
								call_user_func( $panel['callback'] );
							} ?>
						</div><!-- .wp-tab-panel-->
						<?php
					}
					?>


					<?php self::description( $description ); ?>
				</div><!-- .fx-mb-content -->
			</div><!-- .fx-mb-field-radio-tabs -->
		<?php
	}


	/**
	 * WP Edior Field
	 * @since 1.0.0
	 */
	public static function wp_editor_field( $args ){
		$args_default = array(
			'label'          => '',
			'description'    => '',
			'field_attr'     => array(),
			'control_attr'   => array( 'name' => '' ),
			'settings'       => array(), // wp_editor() settings args
		);
		$args = wp_parse_args( $args, $args_default );

		/* Extract var */
		extract( $args );

		/* Field Attr */
		$field_attr_default = array(
			'id'           => $control_attr['name'] ? "fx-mb-field_{$control_attr['name']}" : false,
			'class'        => '',
		);
		$field_attr = wp_parse_args( $field_attr, $field_attr_default );
		$field_attr['class'] = trim( $field_attr['class'] . " fx-mb-field fx-mb-field-wp-editor" );

		/* Control Attr */
		$control_attr_default = array(
			'id'           => $control_attr['name'] ? "fx-mb-input_{$control_attr['name']}" : false,
			'name'         => '',
			'value'        => '',
		);
		$control_attr = wp_parse_args( $control_attr, $control_attr_default );

		/* Settings */
		$settings_default = array(
			'textarea_name'  => $control_attr['name'],
			'editor_height'  => 200,
		);
		$settings = wp_parse_args( $settings, $settings_default );

		/* Editor ID (remove hypens) */
		$editor_id = sanitize_title( $control_attr['id'] );
		$editor_id = str_replace( '-', '_', $editor_id );
		?>
			<div <?php echo self::attr( $field_attr ); ?>>
				<?php self::label( $label, '' ); ?>

				<div class="fx-mb-content">

					<div class="fx-mb-p">
						<?php
						wp_editor(
							$content    = $control_attr['value'],
							$editor_id  = $editor_id,
							$settings   = $settings
						);
						?>
					</div>

					<?php self::description( $description ); ?>
				</div><!-- .fx-mb-content -->

			</div><!-- .fx-mb-field -->
		<?php
	}


	/* Field Utility Functions
	------------------------------------------ */

	/**
	 * Render Label
	 * @since 1.0.0
	 */
	public static function label( $label = '', $input_id = '' ){
		?>
		<div class="fx-mb-label">
			<?php if( $label ){ ?>
				<p>
					<?php if( $input_id ){ ?>
						<label for="<?php echo sanitize_html_class( $input_id );?>">
							<?php echo $label; ?>
						</label>
					<?php } else { ?>
						<span>
							<?php echo $label; ?>
						</span>
					<?php } ?>
				</p>
			<?php } // label ?>
		</div><!-- .fx-mb-label -->
		<?php
	}

	/**
	 * Render Description
	 * @since 1.0.0
	 */
	public static function description( $description ){
	if( $description ){ ?>
		<p class="fx-mb-description">
			<?php echo $description;?>
		</p>
	<?php }
	}

	/**
	 * Build Attr
	 * Create element attr from array.
	 * @since 1.0.0
	 */
	public static function attr( $attr ){
		if( !is_array( $attr ) || empty( $attr ) ) return false;
		$out = '';
		foreach ( $attr as $name => $value ){
			$out .= false !== $value ? sprintf( ' %s="%s"', esc_html( $name ), esc_attr( $value ) ) : esc_html( " {$name}" );
		}
		echo $out;
	}


	/* Sanitize Utility
	------------------------------------------ */

	/**
	 * Sanitize Choices
	 * @since 1.0.0
	 */
	public static function sanitize_choices( $value = '', $choices = array(), $default = '' ){
		if( in_array( $value, $choices ) ){
			return $value;
		}
		return $default;
	}

	/**
	 * Sanitize Multi Choices
	 * @since 1.0.0
	 */
	public static function sanitize_multiple( $input = array(), $choices = array() ){
		if( !is_array( $input ) || !$input ) return array();
		$out = array();
		foreach( $input as $item ){
			if( in_array( $item, $choices ) ){
				$out[] = $item;
			}
		}
		return $out;
	}


	/* Save Post Utility
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

		/* Check if it's valid nonce */
		if ( ! isset( $request[$nonce_id] ) || ! wp_verify_nonce( $request[$nonce_id], $nonce_value ) ){
			return false;
		}
		/* No Auto Save */
		if( defined('DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){
			return false;
		}
		/* Check user caps */
		$post_type = get_post_type_object( $post->post_type );
		if ( !current_user_can( $post_type->cap->edit_post, $post_id ) ){
			return false;
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
