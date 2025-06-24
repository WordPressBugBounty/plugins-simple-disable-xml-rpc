<?php
/**
 * Simple Disable XML-RPC
 *
 * @package       SIMDISXMLRPC
 * @author        WordPress Satkhira Community
 * @license       gplv2
 * @version       1.3.5
 *
 * @wordpress-plugin
 * Plugin Name:   Simple Disable XML-RPC
 * Plugin URI:    https://wordpress.org/plugins/simple-disable-xml-rpc/
 * Description:   Simple Disable XML-RPC is a user-friendly WordPress plugin that empowers website administrators to easily control and secure their site by enabling or disabling the XML-RPC functionality. With a simple toggle switch, this plugin helps protect your WordPress site from potential XML-RPC-related security threats, enhancing your website's overall safety and performance.
 * Version:       1.3.5
 * Requires at least: 6.1
 * Requires PHP:  7.4
 * Author:        WordPress Satkhira Community
 * Author URI:    https://www.wpsatkhira.com
 * Text Domain:   simple-disable-xml-rpc
 * Domain Path:   /languages
 * License:       GPLv2
 * License URI:   https://www.gnu.org/licenses/gpl-2.0.html
 *
 */

// Avoiding Direct File Access

if ( ! defined( 'ABSPATH' ) ) exit;

// Load Plugin Text Domain

function sdxrpc_load_textdomain() {
    load_plugin_textdomain( 'simple-disable-xml-rpc', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
  }
  add_action( 'plugins_loaded', 'sdxrpc_load_textdomain' );

// Add settings page to the admin menu
function sdxrpc_disable_menu() {
    add_options_page('Simple Disable XML-RPC', 'Simple Disable XML-RPC', 'manage_options', 'simple-disable-xml-rpc', 'sdxrpc_disable_settings_page');
}
add_action('admin_menu', 'sdxrpc_disable_menu');

// Register plugin settings
function sdxrpc_disable_register_settings() {
    register_setting('simple-disable-xml-rpc-group', 'xmlrpc_disable_enabled');
}
add_action('admin_init', 'sdxrpc_disable_register_settings');

// Settings page content
function sdxrpc_disable_settings_page() {
    ?>
    <div class="wrap">
        <h2>Simple Disable XML-RPC Settings</h2>
        <form method="post" action="options.php">
            <?php settings_fields('simple-disable-xml-rpc-group'); ?>
            <?php $enabled = get_option('xmlrpc_disable_enabled'); ?>
            <label for="xmlrpc_disable_enabled">
                <input type="checkbox" id="xmlrpc_disable_enabled" name="xmlrpc_disable_enabled" <?php checked($enabled, 'on'); ?> />
                Disable XML-RPC
            </label>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Filter xmlrpc_enabled based on user settings
function sdxrpc_disable_xmlrpc_enabled($enabled) {
    $disable = get_option('xmlrpc_disable_enabled');
    if ($disable === 'on') {
        return false;
    }
    return $enabled;
}
add_filter('xmlrpc_enabled', 'sdxrpc_disable_xmlrpc_enabled');

// Simple Disable XML-RPC Option Links

add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'sdxr_add_action_links' );

function sdxr_add_action_links ( $actions ) {
   $mylinks = array(
      '<a href="' . admin_url( 'options-general.php?page=simple-disable-xml-rpc' ) . '">Settings</a>',
   );
   $actions = array_merge( $actions, $mylinks );
   return $actions;
}

// Redirect to settings page once the plugin is activated

function sdxrpc_activation_redirect( $plugin ) {
    if( $plugin == plugin_basename( __FILE__ ) ) {
        wp_safe_redirect( admin_url( 'options-general.php?page=simple-disable-xml-rpc' ) );
exit;
    }
}
add_action( 'activated_plugin', 'sdxrpc_activation_redirect' );
