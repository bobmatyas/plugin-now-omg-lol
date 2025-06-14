<?php
/**
 * Plugin Name: OMG.lol Now Page
 * Description: Display OMG.lol /now pages in WordPress using blocks or shortcodes
 * Version: 1.0.0
 * Author: Your Name
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: omg-lol-now
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

// Enqueue block assets
function omg_lol_now_enqueue_block_editor_assets() {
    wp_enqueue_script(
        'omg-lol-now-editor',
        OMG_LOL_NOW_PLUGIN_URL . 'build/index.js',
        array('wp-blocks', 'wp-element', 'wp-components', 'wp-data', 'wp-i18n'),
        OMG_LOL_NOW_VERSION,
        true
    );

    wp_enqueue_style(
        'omg-lol-now-editor',
        OMG_LOL_NOW_PLUGIN_URL . 'build/index.css',
        array(),
        OMG_LOL_NOW_VERSION
    );
}
add_action('enqueue_block_editor_assets', 'omg_lol_now_enqueue_block_editor_assets');
