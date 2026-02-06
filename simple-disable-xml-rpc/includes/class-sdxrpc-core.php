<?php
/**
 * Core Functionality Class
 *
 * @package SIMDISXMLRPC
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Core class for XML-RPC functionality
 */
class SDXRPC_Core {

    /**
     * Initialize the core functionality
     */
    public static function init() {
        add_filter( 'xmlrpc_enabled', array( __CLASS__, 'disable_xmlrpc' ) );
        add_filter( 'wp_headers', array( __CLASS__, 'remove_x_pingback_header' ) );
    }

    /**
     * Disable XML-RPC based on user settings
     *
     * @param bool $enabled Current XML-RPC status
     * @return bool Modified XML-RPC status
     */
    public static function disable_xmlrpc( $enabled ) {
        $disable = get_option( 'sdxrpc_disable_enabled', '0' );
        
        if ( $disable === '1' ) {
            return false;
        }
        
        return $enabled;
    }

    /**
     * Remove X-Pingback header when XML-RPC is disabled
     *
     * @param array $headers HTTP headers
     * @return array Modified headers
     */
    public static function remove_x_pingback_header( $headers ) {
        $disable = get_option( 'sdxrpc_disable_enabled', '0' );
        
        if ( $disable === '1' && isset( $headers['X-Pingback'] ) ) {
            unset( $headers['X-Pingback'] );
        }
        
        return $headers;
    }
}