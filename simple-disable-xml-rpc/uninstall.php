<?php
/**
 * Uninstall Script
 * Fired when the plugin is uninstalled
 *
 * @package SIMDISXMLRPC
 */

// If uninstall not called from WordPress, exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

// Plugin options to clean up.
$sdxrpc_options = array(
    'sdxrpc_disable_enabled',
    'sdxrpc_block_direct_access',
    'sdxrpc_disable_pingbacks',
    'sdxrpc_remove_rsd',
    'sdxrpc_remove_wlw',
);

// Delete plugin options for single site.
foreach ( $sdxrpc_options as $sdxrpc_option ) {
    delete_option( $sdxrpc_option );
}

// Delete transients.
delete_transient( 'sdxrpc_activation_redirect' );
delete_transient( 'sdxrpc_recommended_plugins_wpdelower' );

// For multisite installations.
if ( is_multisite() ) {
    $sdxrpc_blog_ids = get_sites(
        array(
            'fields'     => 'ids',
            'network_id' => get_current_network_id(),
        )
    );

    foreach ( $sdxrpc_blog_ids as $sdxrpc_blog_id ) {
        switch_to_blog( $sdxrpc_blog_id );
        foreach ( $sdxrpc_options as $sdxrpc_option ) {
            delete_option( $sdxrpc_option );
        }
        delete_transient( 'sdxrpc_activation_redirect' );
        delete_transient( 'sdxrpc_recommended_plugins_wpdelower' );
        restore_current_blog();
    }
}

// Clear any cached data.
wp_cache_flush();
