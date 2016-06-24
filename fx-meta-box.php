<?php
/**
 * Plugin Name: f(x) Meta Box Library (Alpha)
 * Plugin URI: http://genbumedia.com/plugins/fx-meta-box/
 * Description: Developer tool to create and manage meta boxes. This plugin requires PHP 5.3+
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

/* Change the namespace if including in plugin. */
namespace fx_meta_box;
if ( ! defined( 'WPINC' ) ) { die; }


/* Constants
------------------------------------------ */

define( __NAMESPACE__ . '\VERSION', '1.0.0' );
define( __NAMESPACE__ . '\PATH', trailingslashit( plugin_dir_path(__FILE__) ) );
define( __NAMESPACE__ . '\URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );


/* Tabs UI
------------------------------------------ */

/**
 * Tabs UI
 * This function need to be added in add_meta_box() callback function.
 * @since 1.0.0
 */
function tabs_ui( $post, $box, $tabs ){

	/* Bail early */
	if( ! is_array( $tabs ) || ! $tabs ) return false;
	?>
	<div class="fx-mbtabs">

		<ul class="fx-mbtabs-nav">
			<?php
			$i = 0;
			foreach( $tabs as $nav ){
				$i++; $class = ( 1 === $i ) ? 'selected' : '';
				?>
				<li class="<?php echo esc_attr( $class ); ?>">
					<a href="#fx-mbtab_<?php echo esc_attr( $nav['id'] ); ?>">
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
				$section['index'] = intval( $i );
				?>
				<div id="fx-mbtab_<?php echo esc_attr( $section['id'] ); ?>" class="fx-mbtabs-section" style="<?php echo esc_attr( $style ); ?>">
					<?php if ( is_callable( $section['callback'] ) ){
						call_user_func( $section['callback'], $post, $box );
					} ?>
				</div><!-- .fx-mbtabs-section -->
				<?php
			}
			?>
		</div><!-- .fx-mbtabs-content -->

	</div><!-- .fx-mbtabs -->
	<?php
}

/* Fields Functions
------------------------------------------ */

/* Basic */
require_once( PATH . 'fields/textarea.php' );
require_once( PATH . 'fields/text.php' );
require_once( PATH . 'fields/select.php' );
require_once( PATH . 'fields/checkbox.php' );

/* Advanced */
require_once( PATH . 'fields/upload-url.php' );
require_once( PATH . 'fields/radio-tabs.php' );



/* Save Meta Data
------------------------------------------ */

/**
 * Utility to Save Meta Data
 * This function should be added in save_post hook
 * @since 1.0.0
 */
function save_post_meta( $post_id, $key, $data ){

	/* Get (old) saved data */
	$old_data = get_post_meta( $post_id, $key, true );

	/* Get new submitted data and sanitize it. */
	$new_data = $data;

	/* New data submitted, No previous data, create it  */
	if ( $new_data && '' == $old_data ){
		add_post_meta( $post_id, $key, $new_data, true );
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

/* Admin Scripts
------------------------------------------ */

/* Register Admin Scripts */
add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\scripts', 1 );

/**
 * Register Admin Scripts
 * @since 1.0.0
 */
function scripts( $hook_suffix ){

	/* Use this as dependency in your scripts */
	wp_register_style( 'fx-meta-box', URI . 'assets/style.css', array(), VERSION );
	wp_register_script( 'fx-meta-box', URI . 'assets/script.js', array( 'jquery' ), VERSION, true );

	/* This will auto-load when the fields is used. */
	wp_register_script( 'fx-meta-box_radio-tabs', URI . 'assets/radio-tabs.js', array( 'jquery', 'fx-meta-box' ), VERSION, true );
	wp_register_script( 'fx-meta-box_upload-url', URI . 'assets/upload-url.js', array( 'jquery', 'fx-meta-box' ), VERSION, true );

}

