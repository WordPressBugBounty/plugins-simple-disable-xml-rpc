<?php
/**
 * Core Functionality Class
 *
 * @package SIMDISXMLRPC
 */

// Exit if accessed directly.
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
        // Block direct access as early as possible on init
        add_action( 'init', array( __CLASS__, 'block_direct_xmlrpc_requests' ), 1 );

        // Standard WordPress XML-RPC filter
        add_filter( 'xmlrpc_enabled', array( __CLASS__, 'disable_xmlrpc' ), 999 );

        // Remove HTTP pingback headers
        add_filter( 'wp_headers', array( __CLASS__, 'remove_x_pingback_header' ), 999 );

        // Filter XML-RPC methods (strip pingback methods)
        add_filter( 'xmlrpc_methods', array( __CLASS__, 'filter_xmlrpc_methods' ), 999 );

        // Disable pingbacks on posts
        add_filter( 'pings_open', array( __CLASS__, 'disable_pings_open' ), 999, 2 );

        // Remove discovery links from HTML <head>
        add_action( 'wp_head', array( __CLASS__, 'remove_head_links' ), 1 );
    }

    /**
     * Check if XML-RPC is disabled in plugin settings
     *
     * @return bool
     */
    public static function is_xmlrpc_disabled() {
        return ( get_option( 'sdxrpc_disable_enabled', '0' ) === '1' );
    }

    /**
     * Block direct access to xmlrpc.php with HTTP 403 Forbidden
     * This avoids booting up WordPress XML-RPC server and prevents resource exhaustion
     */
    public static function block_direct_xmlrpc_requests() {
        if ( ! self::is_xmlrpc_disabled() ) {
            return;
        }

        // Check if direct request blocking is enabled
        if ( get_option( 'sdxrpc_block_direct_access', '1' ) !== '1' ) {
            return;
        }

        $request_uri = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
        $script_name = isset( $_SERVER['SCRIPT_NAME'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SCRIPT_NAME'] ) ) : '';

        $is_xmlrpc = (
            ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST ) ||
            false !== strpos( $request_uri, 'xmlrpc.php' ) ||
            ( strlen( $script_name ) >= 10 && substr( $script_name, -10 ) === 'xmlrpc.php' )
        );

        if ( $is_xmlrpc ) {
            status_header( 403 );
            nocache_headers();
            header( 'Content-Type: text/plain; charset=utf-8' );
            header( 'X-Robots-Tag: noindex, nofollow' );

            wp_die(
                esc_html__( 'XML-RPC services are disabled on this site.', 'simple-disable-xml-rpc' ),
                esc_html__( 'XML-RPC Disabled', 'simple-disable-xml-rpc' ),
                array(
                    'response'  => 403,
                    'back_link' => false,
                )
            );
            exit;
        }
    }

    /**
     * Disable XML-RPC based on user settings
     *
     * @param bool $enabled Current XML-RPC status
     * @return bool Modified XML-RPC status
     */
    public static function disable_xmlrpc( $enabled ) {
        if ( self::is_xmlrpc_disabled() ) {
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
        if ( self::is_xmlrpc_disabled() ) {
            unset( $headers['X-Pingback'] );
            unset( $headers['x-pingback'] );
        }

        return $headers;
    }

    /**
     * Filter XML-RPC methods to disable pingbacks
     *
     * @param array $methods List of XML-RPC methods
     * @return array Modified methods
     */
    public static function filter_xmlrpc_methods( $methods ) {
        if ( self::is_xmlrpc_disabled() ) {
            return array();
        }

        if ( get_option( 'sdxrpc_disable_pingbacks', '1' ) === '1' && is_array( $methods ) ) {
            unset( $methods['pingback.ping'] );
            unset( $methods['pingback.extensions.getPingbacks'] );
        }

        return $methods;
    }

    /**
     * Disable pingbacks on posts
     *
     * @param bool $open Whether pings are open
     * @param int  $post_id Post ID
     * @return bool
     */
    public static function disable_pings_open( $open, $post_id ) {
        if ( self::is_xmlrpc_disabled() && get_option( 'sdxrpc_disable_pingbacks', '1' ) === '1' ) {
            return false;
        }

        return $open;
    }

    /**
     * Remove RSD and Windows Live Writer links from <head>
     */
    public static function remove_head_links() {
        if ( ! self::is_xmlrpc_disabled() ) {
            return;
        }

        if ( get_option( 'sdxrpc_remove_rsd', '1' ) === '1' ) {
            remove_action( 'wp_head', 'rsd_link' );
        }

        if ( get_option( 'sdxrpc_remove_wlw', '1' ) === '1' ) {
            remove_action( 'wp_head', 'wlwmanifest_link' );
        }
    }
}
