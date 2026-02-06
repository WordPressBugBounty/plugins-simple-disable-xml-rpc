<?php
/**
 * Admin Settings Class
 *
 * @package SIMDISXMLRPC
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Admin class for settings page
 */
class SDXRPC_Admin {

    /**
     * Initialize admin functionality
     */
    public static function init() {
        add_action( 'admin_menu', array( __CLASS__, 'add_settings_page' ) );
        add_action( 'admin_init', array( __CLASS__, 'register_settings' ) );
        add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_admin_assets' ) );
        add_filter( 'plugin_action_links_' . SDXRPC_PLUGIN_BASENAME, array( __CLASS__, 'add_action_links' ) );
        add_action( 'admin_init', array( __CLASS__, 'activation_redirect' ) );
    }

    /**
     * Add settings page to admin menu
     */
    public static function add_settings_page() {
        add_options_page(
            esc_html__( 'Simple Disable XML-RPC Settings', 'simple-disable-xml-rpc' ),
            esc_html__( 'Disable XML-RPC', 'simple-disable-xml-rpc' ),
            'manage_options',
            'simple-disable-xml-rpc',
            array( __CLASS__, 'render_settings_page' )
        );
    }

    /**
     * Register plugin settings
     */
    public static function register_settings() {
        register_setting(
            'sdxrpc_settings_group',
            'sdxrpc_disable_enabled',
            array(
                'type'              => 'string',
                'sanitize_callback' => array( __CLASS__, 'sanitize_checkbox' ),
                'default'           => '0',
            )
        );
    }

    /**
     * Sanitize checkbox value
     *
     * @param string $input Input value
     * @return string Sanitized value (1 or 0)
     */
    public static function sanitize_checkbox( $input ) {
        return ( $input === '1' ) ? '1' : '0';
    }

    /**
     * Enqueue admin styles and scripts
     *
     * @param string $hook Current admin page hook
     */
    public static function enqueue_admin_assets( $hook ) {
        // Only load on our settings page
        if ( 'settings_page_simple-disable-xml-rpc' !== $hook ) {
            return;
        }

        wp_enqueue_style(
            'sdxrpc-admin-style',
            SDXRPC_PLUGIN_URL . 'assets/css/admin-style.css',
            array(),
            SDXRPC_VERSION
        );

        wp_enqueue_script(
            'sdxrpc-admin-script',
            SDXRPC_PLUGIN_URL . 'assets/js/admin-script.js',
            array( 'jquery' ),
            SDXRPC_VERSION,
            true
        );
    }

    /**
     * Render settings page
     */
    public static function render_settings_page() {
        // Check user capabilities
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'simple-disable-xml-rpc' ) );
        }

        $enabled = get_option( 'sdxrpc_disable_enabled', '0' );
        ?>
        <div class="wrap sdxrpc-settings-wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            
            <div class="sdxrpc-container">
                <div class="sdxrpc-card">
                    <div class="sdxrpc-card-header">
                        <h2><?php esc_html_e( 'XML-RPC Configuration', 'simple-disable-xml-rpc' ); ?></h2>
                    </div>
                    
                    <div class="sdxrpc-card-body">
                        <form method="post" action="options.php">
                            <?php settings_fields( 'sdxrpc_settings_group' ); ?>
                            <?php do_settings_sections( 'sdxrpc_settings_group' ); ?>
                            
                            <div class="sdxrpc-setting-row">
                                <div class="sdxrpc-setting-label">
                                    <h3><?php esc_html_e( 'Disable XML-RPC', 'simple-disable-xml-rpc' ); ?></h3>
                                    <p class="description">
                                        <?php esc_html_e( 'Enable this option to completely disable XML-RPC functionality on your WordPress site. This can help improve security by preventing XML-RPC attacks.', 'simple-disable-xml-rpc' ); ?>
                                    </p>
                                </div>
                                
                                <div class="sdxrpc-setting-control">
                                    <label class="sdxrpc-toggle">
                                        <input 
                                            type="checkbox" 
                                            id="sdxrpc_disable_enabled" 
                                            name="sdxrpc_disable_enabled" 
                                            value="1" 
                                            <?php checked( $enabled, '1' ); ?>
                                        />
                                        <span class="sdxrpc-toggle-slider"></span>
                                    </label>
                                    <span class="sdxrpc-toggle-status">
                                        <span class="status-enabled"><?php esc_html_e( 'Disabled', 'simple-disable-xml-rpc' ); ?></span>
                                        <span class="status-disabled"><?php esc_html_e( 'Enabled', 'simple-disable-xml-rpc' ); ?></span>
                                    </span>
                                </div>
                            </div>
                            
                            <?php submit_button( esc_html__( 'Save Changes', 'simple-disable-xml-rpc' ), 'primary', 'submit', false ); ?>
                        </form>
                    </div>
                </div>

                <div class="sdxrpc-info-card">
                    <h3><?php esc_html_e( 'What is XML-RPC?', 'simple-disable-xml-rpc' ); ?></h3>
                    <p><?php esc_html_e( 'XML-RPC is a feature that allows remote connections to your WordPress site. While it can be useful for some applications, it can also be exploited by attackers for:', 'simple-disable-xml-rpc' ); ?></p>
                    <ul>
                        <li><?php esc_html_e( 'Brute force attacks', 'simple-disable-xml-rpc' ); ?></li>
                        <li><?php esc_html_e( 'DDoS attacks', 'simple-disable-xml-rpc' ); ?></li>
                        <li><?php esc_html_e( 'Resource exhaustion', 'simple-disable-xml-rpc' ); ?></li>
                    </ul>
                    <p><strong><?php esc_html_e( 'Note:', 'simple-disable-xml-rpc' ); ?></strong> <?php esc_html_e( 'Disabling XML-RPC may affect some plugins or services that rely on it (like Jetpack, mobile apps, etc.).', 'simple-disable-xml-rpc' ); ?></p>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Add plugin action links
     *
     * @param array $links Existing links
     * @return array Modified links
     */
    public static function add_action_links( $links ) {
        $settings_link = sprintf(
            '<a href="%s">%s</a>',
            esc_url( admin_url( 'options-general.php?page=simple-disable-xml-rpc' ) ),
            esc_html__( 'Settings', 'simple-disable-xml-rpc' )
        );
        
        array_unshift( $links, $settings_link );
        
        return $links;
    }

    /**
     * Redirect to settings page after activation
     * Only for single plugin activation, not bulk
     */
    public static function activation_redirect() {
        // Check if we should redirect
        if ( ! get_transient( 'sdxrpc_activation_redirect' ) ) {
            return;
        }

        // Delete the transient
        delete_transient( 'sdxrpc_activation_redirect' );

        // Bail if activating from network
        if ( is_network_admin() ) {
            return;
        }

        // Check if this is wp-cli or doing cron
        if ( ( defined( 'WP_CLI' ) && WP_CLI ) || wp_doing_cron() ) {
            return;
        }

        // Redirect to settings page
        wp_safe_redirect( admin_url( 'options-general.php?page=simple-disable-xml-rpc' ) );
        exit;
    }
}