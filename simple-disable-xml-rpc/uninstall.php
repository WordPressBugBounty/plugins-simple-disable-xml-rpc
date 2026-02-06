<?php
/**
 * Uninstall Script
 * Fired when the plugin is uninstalled
 *
 * @package SIMDISXMLRPC
 */

// If uninstall not called from WordPress, exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

// Delete plugin options for single site
delete_option( 'sdxrpc_disable_enabled' );

// Delete transients
delete_transient( 'sdxrpc_activation_redirect' );

// For multisite installations
if ( is_multisite() ) {
    // Get all blog IDs using recommended WordPress function
    $sdxrpc_blog_ids = get_sites(
        array(
            'fields'     => 'ids',
            'network_id' => get_current_network_id(),
        )
    );
    
    // Loop through each site and delete options
    foreach ( $sdxrpc_blog_ids as $sdxrpc_blog_id ) {
        switch_to_blog( $sdxrpc_blog_id );
        delete_option( 'sdxrpc_disable_enabled' );
        delete_transient( 'sdxrpc_activation_redirect' );
        restore_current_blog();
    }
}

// Clear any cached data
wp_cache_flush();