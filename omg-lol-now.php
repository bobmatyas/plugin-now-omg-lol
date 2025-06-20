<?php
/**
 * Plugin Name: Now Page via OMG.lol Connector
 * Description: Display OMG.lol /now pages in WordPress using blocks or shortcodes
 * Version: 1.0.0
 * Author: Bob Matyas
 * Author URI: https://www.bobmatyas.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: now-omg-lol
 *
 * @package OMG_LOL_Now
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Define plugin constants.
define( 'OMG_LOL_NOW_VERSION', '1.0.0' );
define( 'OMG_LOL_NOW_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'OMG_LOL_NOW_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Include required files.
require_once OMG_LOL_NOW_PLUGIN_DIR . 'includes/class-omg-lol-now.php';
require_once OMG_LOL_NOW_PLUGIN_DIR . 'includes/class-omg-lol-now-admin.php';
require_once OMG_LOL_NOW_PLUGIN_DIR . 'includes/class-omg-lol-now-api.php';

/**
 * Initialize the plugin.
 */
function omg_lol_now_init() {
	$plugin = new OMG_LOL_Now();
	$plugin->run();
}

add_action( 'plugins_loaded', 'omg_lol_now_init' );

/**
 * Enqueue block editor assets.
 *
 * @return void
 */
function omg_lol_now_enqueue_block_editor_assets() {
	wp_enqueue_script(
		'omg-lol-now-editor',
		OMG_LOL_NOW_PLUGIN_URL . 'build/index.js',
		array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-data', 'wp-i18n' ),
		OMG_LOL_NOW_VERSION,
		true
	);

	wp_enqueue_style(
		'omg-lol-now-editor',
		OMG_LOL_NOW_PLUGIN_URL . 'build/editor.css',
		array(),
		OMG_LOL_NOW_VERSION
	);

	// Always load Font Awesome in editor since we can't predict if icons will be used.
	wp_enqueue_style(
		'font-awesome',
		OMG_LOL_NOW_PLUGIN_URL . 'assets/fontawesome/all.min.css',
		array(),
		'6.4.0'
	);
}
add_action( 'enqueue_block_editor_assets', 'omg_lol_now_enqueue_block_editor_assets' );

/**
 * Enqueue frontend styles.
 *
 * @return void
 */
function omg_lol_now_enqueue_frontend_styles() {
	wp_enqueue_style(
		'omg-lol-now',
		OMG_LOL_NOW_PLUGIN_URL . 'build/style-index.css',
		array(),
		OMG_LOL_NOW_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'omg_lol_now_enqueue_frontend_styles' );

/**
 * Conditionally enqueue Font Awesome on frontend when needed.
 *
 * @return void
 */
function omg_lol_now_maybe_enqueue_font_awesome() {
	global $post;

	if ( ! $post ) {
		return;
	}

	// Check if the post content contains our shortcode or block.
	$content = $post->post_content;
	if ( has_shortcode( $content, 'omg_lol_now' ) ||
		strpos( $content, 'wp-block-omg-lol-now' ) !== false ||
		strpos( $content, 'omg-lol-now/now-page' ) !== false ) {

		wp_enqueue_style(
			'font-awesome',
			OMG_LOL_NOW_PLUGIN_URL . 'assets/fontawesome/all.min.css',
			array(),
			'6.4.0'
		);
	}
}
add_action( 'wp_enqueue_scripts', 'omg_lol_now_maybe_enqueue_font_awesome' );
