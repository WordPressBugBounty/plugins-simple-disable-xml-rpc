<?php
/**
 * Simple Disable XML-RPC
 *
 * @package       SIMDISXMLRPC
 * @author        WordPress Satkhira Community
 * @license       gplv2
 * @version       1.4.0
 *
 * @wordpress-plugin
 * Plugin Name:   Simple Disable XML-RPC
 * Plugin URI:    https://wordpress.org/plugins/simple-disable-xml-rpc/
 * Description:   Simple Disable XML-RPC is a user-friendly WordPress plugin that empowers website administrators to easily control and secure their site by enabling or disabling the XML-RPC functionality. With a simple toggle switch, this plugin helps protect your WordPress site from potential XML-RPC-related security threats, enhancing your website's overall safety and performance.
 * Version:       1.4.0
 * Requires at least: 6.1
 * Requires PHP:  7.4
 * Author:        MonarchWP
 * Author URI:    https://www.monarchwp.com
 * Text Domain:   simple-disable-xml-rpc
 * License:       GPLv2
 * License URI:   https://www.gnu.org/licenses/gpl-2.0.html
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define plugin constants
if ( ! defined( 'SDXRPC_VERSION' ) ) {
    define( 'SDXRPC_VERSION', '1.4.0' );
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
 * Include required files
 */
require_once SDXRPC_PLUGIN_DIR . 'includes/class-sdxrpc-core.php';

if ( is_admin() ) {
    require_once SDXRPC_PLUGIN_DIR . 'includes/class-sdxrpc-admin.php';
}

/**
 * Initialize the plugin
 */
function sdxrpc_init() {
    // Initialize core functionality
    SDXRPC_Core::init();
    
    // Initialize admin if in admin area
    if ( is_admin() ) {
        SDXRPC_Admin::init();
    }
}
add_action( 'plugins_loaded', 'sdxrpc_init' );

/**
 * Activation hook - set default options
 */
function sdxrpc_activate() {
    // Set default option if not exists
    if ( get_option( 'sdxrpc_disable_enabled' ) === false ) {
        add_option( 'sdxrpc_disable_enabled', '0' );
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