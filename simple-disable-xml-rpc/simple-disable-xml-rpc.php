<?php
/**
 * Simple Disable XML-RPC
 *
 * @package       SIMDISXMLRPC
 * @author        Delower Hossain (wpdelower), MonarchWP
 * @license       gplv2
 * @version       1.4.1
 *
 * @wordpress-plugin
 * Plugin Name:   Simple Disable XML-RPC
 * Plugin URI:    https://wordpress.org/plugins/simple-disable-xml-rpc/
 * Description:   Simple Disable XML-RPC is a lightweight and powerful security plugin that allows you to easily disable XML-RPC functionality, block direct XML-RPC requests (HTTP 403), prevent pingback DDoS amplification, and strip discovery headers to protect your WordPress site.
 * Version:       1.4.1
 * Requires at least: 6.1
 * Requires PHP:  7.4
 * Author:        wpdelower, MonarchWP
 * Author URI:    https://profiles.wordpress.org/wpdelower/
 * Text Domain:   simple-disable-xml-rpc
 * License:       GPLv2 or later
 * License URI:   https://www.gnu.org/licenses/gpl-2.0.html
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define plugin constants.
if ( ! defined( 'SDXRPC_VERSION' ) ) {
    define( 'SDXRPC_VERSION', '1.4.1' );
}

if ( ! defined( 'SDXRPC_PLUGIN_DIR' ) ) {
    define( 'SDXRPC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'SDXRPC_PLUGIN_URL' ) ) {
    define( 'SDXRPC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'SDXRPC_PLUGIN_BASENAME' ) ) {
    define( 'SDXRPC_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
}

/**
 * Include required classes
 */
require_once SDXRPC_PLUGIN_DIR . 'includes/class-sdxrpc-core.php';

if ( is_admin() ) {
    require_once SDXRPC_PLUGIN_DIR . 'includes/class-sdxrpc-admin.php';
    require_once SDXRPC_PLUGIN_DIR . 'includes/class-sdxrpc-recommended.php';
}

/**
 * Initialize the plugin
 */
function sdxrpc_init() {
    // Initialize core security functionality
    SDXRPC_Core::init();

    // Initialize admin screens if inside admin area
    if ( is_admin() ) {
        SDXRPC_Admin::init();
        SDXRPC_Recommended::init();
    }
}
add_action( 'plugins_loaded', 'sdxrpc_init' );

/**
 * Activation hook - set default options
 */
function sdxrpc_activate() {
    // Set default options if they don't already exist
    if ( get_option( 'sdxrpc_disable_enabled' ) === false ) {
        add_option( 'sdxrpc_disable_enabled', '0' );
    }

    if ( get_option( 'sdxrpc_block_direct_access' ) === false ) {
        add_option( 'sdxrpc_block_direct_access', '1' );
    }

    if ( get_option( 'sdxrpc_disable_pingbacks' ) === false ) {
        add_option( 'sdxrpc_disable_pingbacks', '1' );
    }

    if ( get_option( 'sdxrpc_remove_rsd' ) === false ) {
        add_option( 'sdxrpc_remove_rsd', '1' );
    }

    if ( get_option( 'sdxrpc_remove_wlw' ) === false ) {
        add_option( 'sdxrpc_remove_wlw', '1' );
    }

    // Set activation redirect flag
    set_transient( 'sdxrpc_activation_redirect', true, 30 );
}
register_activation_hook( __FILE__, 'sdxrpc_activate' );

/**
 * Deactivation hook
 */
function sdxrpc_deactivate() {
    // Clean up transients
    delete_transient( 'sdxrpc_activation_redirect' );
}
register_deactivation_hook( __FILE__, 'sdxrpc_deactivate' );
