<?php
/**
 * Admin Settings Class
 *
 * @package SIMDISXMLRPC
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Admin class for settings and management
 */
class SDXRPC_Admin {

    /**
     * Initialize admin functionality
     */
    public static function init() {
        add_action( 'admin_menu', array( __CLASS__, 'add_admin_menu' ) );
        add_action( 'admin_init', array( __CLASS__, 'register_settings' ) );
        add_action( 'admin_init', array( __CLASS__, 'handle_legacy_redirect' ) );
        add_action( 'admin_init', array( __CLASS__, 'activation_redirect' ) );
        add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_admin_assets' ) );
        add_filter( 'plugin_action_links_' . SDXRPC_PLUGIN_BASENAME, array( __CLASS__, 'add_action_links' ) );
        add_action( 'wp_ajax_sdxrpc_test_xmlrpc', array( __CLASS__, 'ajax_test_xmlrpc' ) );
    }

    /**
     * Add admin menu and submenus
     */
    public static function add_admin_menu() {
        // Main top-level menu
        add_menu_page(
            esc_html__( 'Simple Disable XML-RPC', 'simple-disable-xml-rpc' ),
            esc_html__( 'Disable XML-RPC', 'simple-disable-xml-rpc' ),
            'manage_options',
            'simple-disable-xml-rpc',
            array( __CLASS__, 'render_settings_page' ),
            'dashicons-shield',
            80
        );

        // Submenu: Settings
        add_submenu_page(
            'simple-disable-xml-rpc',
            esc_html__( 'Settings - Simple Disable XML-RPC', 'simple-disable-xml-rpc' ),
            esc_html__( 'Settings', 'simple-disable-xml-rpc' ),
            'manage_options',
            'simple-disable-xml-rpc',
            array( __CLASS__, 'render_settings_page' )
        );

        // Submenu: Recommended Plugins
        add_submenu_page(
            'simple-disable-xml-rpc',
            esc_html__( 'Recommended Plugins by wpdelower', 'simple-disable-xml-rpc' ),
            esc_html__( 'Recommended Plugins', 'simple-disable-xml-rpc' ),
            'manage_options',
            'simple-disable-xml-rpc-recommended',
            array( 'SDXRPC_Recommended', 'render_recommended_page' )
        );
    }

    /**
     * Handle legacy redirect from options-general.php?page=simple-disable-xml-rpc
     */
    public static function handle_legacy_redirect() {
        global $pagenow, $plugin_page;

        if ( 'options-general.php' === $pagenow && 'simple-disable-xml-rpc' === $plugin_page ) {
            wp_safe_redirect( admin_url( 'admin.php?page=simple-disable-xml-rpc' ) );
            exit;
        }
    }

    /**
     * Register plugin settings
     */
    public static function register_settings() {
        // Master toggle
        register_setting(
            'sdxrpc_settings_group',
            'sdxrpc_disable_enabled',
            array(
                'type'              => 'string',
                'sanitize_callback' => array( __CLASS__, 'sanitize_checkbox' ),
                'default'           => '0',
            )
        );

        // Block direct access with HTTP 403
        register_setting(
            'sdxrpc_settings_group',
            'sdxrpc_block_direct_access',
            array(
                'type'              => 'string',
                'sanitize_callback' => array( __CLASS__, 'sanitize_checkbox' ),
                'default'           => '1',
            )
        );

        // Disable pingbacks & trackbacks
        register_setting(
            'sdxrpc_settings_group',
            'sdxrpc_disable_pingbacks',
            array(
                'type'              => 'string',
                'sanitize_callback' => array( __CLASS__, 'sanitize_checkbox' ),
                'default'           => '1',
            )
        );

        // Remove RSD link from <head>
        register_setting(
            'sdxrpc_settings_group',
            'sdxrpc_remove_rsd',
            array(
                'type'              => 'string',
                'sanitize_callback' => array( __CLASS__, 'sanitize_checkbox' ),
                'default'           => '1',
            )
        );

        // Remove Windows Live Writer link from <head>
        register_setting(
            'sdxrpc_settings_group',
            'sdxrpc_remove_wlw',
            array(
                'type'              => 'string',
                'sanitize_callback' => array( __CLASS__, 'sanitize_checkbox' ),
                'default'           => '1',
            )
        );
    }

    /**
     * Sanitize checkbox value
     *
     * @param mixed $input Input value
     * @return string Sanitized value ('1' or '0')
     */
    public static function sanitize_checkbox( $input ) {
        return ( '1' === (string) $input ) ? '1' : '0';
    }

    /**
     * Enqueue admin styles and scripts
     *
     * @param string $hook Current admin page hook
     */
    public static function enqueue_admin_assets( $hook ) {
        // Only load on our plugin pages
        if (
            'toplevel_page_simple-disable-xml-rpc' !== $hook &&
            'disable-xml-rpc_page_simple-disable-xml-rpc-recommended' !== $hook &&
            false === strpos( $hook, 'simple-disable-xml-rpc' )
        ) {
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

        wp_localize_script(
            'sdxrpc-admin-script',
            'sdxrpc_vars',
            array(
                'ajax_url'            => admin_url( 'admin-ajax.php' ),
                'admin_nonce'         => wp_create_nonce( 'sdxrpc_admin_nonce' ),
                'plugin_action_nonce' => wp_create_nonce( 'sdxrpc_plugin_action_nonce' ),
                'strings'             => array(
                    'installing'         => esc_html__( 'Installing...', 'simple-disable-xml-rpc' ),
                    'activating'         => esc_html__( 'Activating...', 'simple-disable-xml-rpc' ),
                    'deactivating'       => esc_html__( 'Deactivating...', 'simple-disable-xml-rpc' ),
                    'active'             => esc_html__( 'Active', 'simple-disable-xml-rpc' ),
                    'activate'           => esc_html__( 'Activate', 'simple-disable-xml-rpc' ),
                    'deactivate'         => esc_html__( 'Deactivate', 'simple-disable-xml-rpc' ),
                    'install_now'        => esc_html__( 'Install Now', 'simple-disable-xml-rpc' ),
                    'testing'            => esc_html__( 'Testing connection to xmlrpc.php...', 'simple-disable-xml-rpc' ),
                    'confirm_deactivate' => esc_html__( 'Are you sure you want to deactivate this plugin?', 'simple-disable-xml-rpc' ),
                    'error'              => esc_html__( 'An error occurred. Please try again.', 'simple-disable-xml-rpc' ),
                ),
            )
        );
    }

    /**
     * Render shared header with branding, version, and navigation tabs
     *
     * @param string $active_tab Active tab slug ('settings' or 'recommended')
     */
    public static function render_header( $active_tab = 'settings' ) {
        $enabled = get_option( 'sdxrpc_disable_enabled', '0' );
        ?>
        <div class="sdxrpc-header-container">
            <div class="sdxrpc-header-main">
                <div class="sdxrpc-header-brand">
                    <div class="sdxrpc-header-icon">
                        <span class="dashicons dashicons-shield"></span>
                    </div>
                    <div class="sdxrpc-header-text">
                        <div class="sdxrpc-title-row">
                            <h1 class="sdxrpc-header-title"><?php esc_html_e( 'Simple Disable XML-RPC', 'simple-disable-xml-rpc' ); ?></h1>
                            <span class="sdxrpc-version-badge">v<?php echo esc_html( SDXRPC_VERSION ); ?></span>
                            <?php if ( '1' === $enabled ) : ?>
                                <span class="sdxrpc-badge-pill sdxrpc-pill-protected">
                                    <span class="sdxrpc-dot"></span> <?php esc_html_e( 'Protected', 'simple-disable-xml-rpc' ); ?>
                                </span>
                            <?php else : ?>
                                <span class="sdxrpc-badge-pill sdxrpc-pill-exposed">
                                    <span class="sdxrpc-dot"></span> <?php esc_html_e( 'XML-RPC Active', 'simple-disable-xml-rpc' ); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        <p class="sdxrpc-header-subtitle">
                            <?php esc_html_e( 'Developed with passion by Delower Hossain', 'simple-disable-xml-rpc' ); ?>
                            (<a href="https://profiles.wordpress.org/wpdelower/" target="_blank" rel="noopener noreferrer">@wpdelower</a>)
                            &bull;
                            <a href="https://www.monarchwp.com" target="_blank" rel="noopener noreferrer">MonarchWP</a>
                        </p>
                    </div>
                </div>

                <div class="sdxrpc-header-links">
                    <a href="https://wordpress.org/support/plugin/simple-disable-xml-rpc/reviews/#new-post" target="_blank" rel="noopener noreferrer" class="sdxrpc-nav-link">
                        <span class="dashicons dashicons-star-filled"></span> <?php esc_html_e( 'Rate 5 Stars', 'simple-disable-xml-rpc' ); ?>
                    </a>
                    <a href="https://wordpress.org/support/plugin/simple-disable-xml-rpc/" target="_blank" rel="noopener noreferrer" class="sdxrpc-nav-link">
                        <span class="dashicons dashicons-sos"></span> <?php esc_html_e( 'Support Forum', 'simple-disable-xml-rpc' ); ?>
                    </a>
                </div>
            </div>

            <nav class="sdxrpc-tab-nav" aria-label="<?php esc_attr_e( 'Plugin navigation tabs', 'simple-disable-xml-rpc' ); ?>">
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=simple-disable-xml-rpc' ) ); ?>" class="sdxrpc-tab-item <?php echo 'settings' === $active_tab ? 'is-active' : ''; ?>">
                    <span class="dashicons dashicons-admin-generic"></span>
                    <span><?php esc_html_e( 'Settings & Hardening', 'simple-disable-xml-rpc' ); ?></span>
                </a>
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=simple-disable-xml-rpc-recommended' ) ); ?>" class="sdxrpc-tab-item <?php echo 'recommended' === $active_tab ? 'is-active' : ''; ?>">
                    <span class="dashicons dashicons-star-filled"></span>
                    <span><?php esc_html_e( 'Recommended Plugins', 'simple-disable-xml-rpc' ); ?></span>
                    <span class="sdxrpc-tab-badge"><?php esc_html_e( 'by wpdelower', 'simple-disable-xml-rpc' ); ?></span>
                </a>
            </nav>
        </div>
        <?php
    }

    /**
     * Render the settings page
     */
    public static function render_settings_page() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'simple-disable-xml-rpc' ) );
        }

        $enabled          = get_option( 'sdxrpc_disable_enabled', '0' );
        $block_direct     = get_option( 'sdxrpc_block_direct_access', '1' );
        $disable_pingback = get_option( 'sdxrpc_disable_pingbacks', '1' );
        $remove_rsd       = get_option( 'sdxrpc_remove_rsd', '1' );
        $remove_wlw       = get_option( 'sdxrpc_remove_wlw', '1' );
        ?>
        <div class="wrap sdxrpc-settings-wrap">
            <?php self::render_header( 'settings' ); ?>

            <?php
            // phpcs:disable WordPress.Security.NonceVerification.Recommended
            if ( isset( $_GET['settings-updated'] ) && 'true' === sanitize_text_field( wp_unslash( $_GET['settings-updated'] ) ) ) :
            ?>
                <div class="notice notice-success is-dismissible sdxrpc-notice">
                    <p><strong><?php esc_html_e( 'Settings saved successfully! Your XML-RPC protection configuration is updated.', 'simple-disable-xml-rpc' ); ?></strong></p>
                </div>
            <?php
            endif;
            // phpcs:enable WordPress.Security.NonceVerification.Recommended
            ?>

            <!-- Status Hero Banner -->
            <?php if ( '1' === $enabled ) : ?>
                <div class="sdxrpc-hero-banner sdxrpc-hero-protected">
                    <div class="sdxrpc-hero-icon">
                        <span class="dashicons dashicons-shield-alt"></span>
                    </div>
                    <div class="sdxrpc-hero-body">
                        <h3><?php esc_html_e( 'Your Site is Protected: XML-RPC is Disabled', 'simple-disable-xml-rpc' ); ?></h3>
                        <p><?php esc_html_e( 'All external XML-RPC method requests and pingbacks are blocked. Your site is safeguarded against automated brute-force password guessing and DDoS amplification attacks.', 'simple-disable-xml-rpc' ); ?></p>
                    </div>
                </div>
            <?php else : ?>
                <div class="sdxrpc-hero-banner sdxrpc-hero-warning">
                    <div class="sdxrpc-hero-icon">
                        <span class="dashicons dashicons-warning"></span>
                    </div>
                    <div class="sdxrpc-hero-body">
                        <h3><?php esc_html_e( 'Notice: XML-RPC is Currently Enabled', 'simple-disable-xml-rpc' ); ?></h3>
                        <p><?php esc_html_e( 'Your website is accepting remote XML-RPC requests. If you do not use the WordPress mobile app or Jetpack, we strongly recommend toggling "Disable XML-RPC" on below to harden your security.', 'simple-disable-xml-rpc' ); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <div class="sdxrpc-layout-container">
                <!-- Main Column -->
                <div class="sdxrpc-main-col">
                    <form method="post" action="options.php" class="sdxrpc-form">
                        <?php settings_fields( 'sdxrpc_settings_group' ); ?>

                        <!-- Master Switch Card -->
                        <div class="sdxrpc-card sdxrpc-master-card">
                            <div class="sdxrpc-card-header">
                                <h2>
                                    <span class="dashicons dashicons-shield"></span>
                                    <?php esc_html_e( 'Main Protection Switch', 'simple-disable-xml-rpc' ); ?>
                                </h2>
                            </div>
                            <div class="sdxrpc-card-body">
                                <div class="sdxrpc-toggle-row">
                                    <div class="sdxrpc-toggle-info">
                                        <h3><?php esc_html_e( 'Disable XML-RPC Completely', 'simple-disable-xml-rpc' ); ?></h3>
                                        <p class="description">
                                            <?php esc_html_e( 'Completely disables the XML-RPC API across your WordPress installation. Stops remote method execution, brute-force attempts, and unauthorized API calls.', 'simple-disable-xml-rpc' ); ?>
                                        </p>
                                    </div>
                                    <div class="sdxrpc-toggle-switch-wrap">
                                        <label class="sdxrpc-toggle" for="sdxrpc_disable_enabled">
                                            <input
                                                type="checkbox"
                                                id="sdxrpc_disable_enabled"
                                                name="sdxrpc_disable_enabled"
                                                value="1"
                                                <?php checked( $enabled, '1' ); ?>
                                            />
                                            <span class="sdxrpc-toggle-slider"></span>
                                        </label>
                                        <span class="sdxrpc-toggle-label">
                                            <span class="label-off"><?php esc_html_e( 'Disabled', 'simple-disable-xml-rpc' ); ?></span>
                                            <span class="label-on"><?php esc_html_e( 'Enabled (Blocked)', 'simple-disable-xml-rpc' ); ?></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Security Hardening Card -->
                        <div class="sdxrpc-card sdxrpc-hardening-card <?php echo '1' !== $enabled ? 'is-disabled' : ''; ?>">
                            <div class="sdxrpc-card-header">
                                <h2>
                                    <span class="dashicons dashicons-admin-tools"></span>
                                    <?php esc_html_e( 'Advanced Hardening & Header Cleanup', 'simple-disable-xml-rpc' ); ?>
                                </h2>
                                <span class="sdxrpc-header-tag"><?php esc_html_e( 'Recommended', 'simple-disable-xml-rpc' ); ?></span>
                            </div>
                            <div class="sdxrpc-card-body">
                                <p class="sdxrpc-card-intro">
                                    <?php esc_html_e( 'These additional security hardening layers are applied when XML-RPC is disabled to optimize server performance and eliminate scanner footprints.', 'simple-disable-xml-rpc' ); ?>
                                </p>

                                <!-- Option: Block Direct Requests 403 -->
                                <div class="sdxrpc-sub-setting-row">
                                    <div class="sdxrpc-sub-info">
                                        <h4><?php esc_html_e( 'Block Direct xmlrpc.php Requests (HTTP 403)', 'simple-disable-xml-rpc' ); ?></h4>
                                        <p><?php esc_html_e( 'Immediately terminates requests to xmlrpc.php with an HTTP 403 Forbidden header before full WordPress initialization. Saves server CPU, database queries, and memory during attacks.', 'simple-disable-xml-rpc' ); ?></p>
                                    </div>
                                    <label class="sdxrpc-toggle sdxrpc-toggle-sm" for="sdxrpc_block_direct_access">
                                        <input
                                            type="checkbox"
                                            id="sdxrpc_block_direct_access"
                                            name="sdxrpc_block_direct_access"
                                            value="1"
                                            <?php checked( $block_direct, '1' ); ?>
                                        />
                                        <span class="sdxrpc-toggle-slider"></span>
                                    </label>
                                </div>

                                <!-- Option: Disable Pingbacks & Trackbacks -->
                                <div class="sdxrpc-sub-setting-row">
                                    <div class="sdxrpc-sub-info">
                                        <h4><?php esc_html_e( 'Disable Pingback & Trackback Methods', 'simple-disable-xml-rpc' ); ?></h4>
                                        <p><?php esc_html_e( 'Explicitly strips pingback.ping and pingback.extensions.getPingbacks methods to eliminate XML-RPC pingback DDoS amplification vectors.', 'simple-disable-xml-rpc' ); ?></p>
                                    </div>
                                    <label class="sdxrpc-toggle sdxrpc-toggle-sm" for="sdxrpc_disable_pingbacks">
                                        <input
                                            type="checkbox"
                                            id="sdxrpc_disable_pingbacks"
                                            name="sdxrpc_disable_pingbacks"
                                            value="1"
                                            <?php checked( $disable_pingback, '1' ); ?>
                                        />
                                        <span class="sdxrpc-toggle-slider"></span>
                                    </label>
                                </div>

                                <!-- Option: Remove RSD Link -->
                                <div class="sdxrpc-sub-setting-row">
                                    <div class="sdxrpc-sub-info">
                                        <h4><?php esc_html_e( 'Remove RSD (Really Simple Discovery) Link', 'simple-disable-xml-rpc' ); ?></h4>
                                        <p><?php esc_html_e( 'Removes the EditURI / RSD link from your website\'s HTML header so automated vulnerability scanners cannot locate XML-RPC.', 'simple-disable-xml-rpc' ); ?></p>
                                    </div>
                                    <label class="sdxrpc-toggle sdxrpc-toggle-sm" for="sdxrpc_remove_rsd">
                                        <input
                                            type="checkbox"
                                            id="sdxrpc_remove_rsd"
                                            name="sdxrpc_remove_rsd"
                                            value="1"
                                            <?php checked( $remove_rsd, '1' ); ?>
                                        />
                                        <span class="sdxrpc-toggle-slider"></span>
                                    </label>
                                </div>

                                <!-- Option: Remove WLW Manifest -->
                                <div class="sdxrpc-sub-setting-row">
                                    <div class="sdxrpc-sub-info">
                                        <h4><?php esc_html_e( 'Remove Windows Live Writer Manifest Link', 'simple-disable-xml-rpc' ); ?></h4>
                                        <p><?php esc_html_e( 'Strips the obsolete wlwmanifest.xml link from HTML headers, reducing unnecessary DOM clutter and header leakage.', 'simple-disable-xml-rpc' ); ?></p>
                                    </div>
                                    <label class="sdxrpc-toggle sdxrpc-toggle-sm" for="sdxrpc_remove_wlw">
                                        <input
                                            type="checkbox"
                                            id="sdxrpc_remove_wlw"
                                            name="sdxrpc_remove_wlw"
                                            value="1"
                                            <?php checked( $remove_wlw, '1' ); ?>
                                        />
                                        <span class="sdxrpc-toggle-slider"></span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="sdxrpc-form-actions">
                            <?php submit_button( esc_html__( 'Save Protection Settings', 'simple-disable-xml-rpc' ), 'primary large', 'submit', false ); ?>
                        </div>
                    </form>

                    <!-- Diagnostics & Endpoint Tester -->
                    <div class="sdxrpc-card sdxrpc-test-card">
                        <div class="sdxrpc-card-header">
                            <h2>
                                <span class="dashicons dashicons-search"></span>
                                <?php esc_html_e( 'Live XML-RPC Diagnostics & Self-Test', 'simple-disable-xml-rpc' ); ?>
                            </h2>
                        </div>
                        <div class="sdxrpc-card-body">
                            <p>
                                <?php esc_html_e( 'Perform a live test against your site\'s xmlrpc.php endpoint to verify whether XML-RPC is safely blocked or reachable by external attackers.', 'simple-disable-xml-rpc' ); ?>
                            </p>
                            <div class="sdxrpc-test-controls">
                                <button type="button" id="sdxrpc-run-test" class="button button-secondary">
                                    <span class="dashicons dashicons-update"></span>
                                    <span class="button-text"><?php esc_html_e( 'Test XML-RPC Endpoint', 'simple-disable-xml-rpc' ); ?></span>
                                </button>
                                <span class="sdxrpc-endpoint-url">
                                    <?php echo esc_html( site_url( 'xmlrpc.php' ) ); ?>
                                </span>
                            </div>

                            <!-- Test Result Box -->
                            <div id="sdxrpc-test-result" class="sdxrpc-test-result-box" style="display: none;">
                                <div class="sdxrpc-test-result-header">
                                    <span class="sdxrpc-test-status-pill"></span>
                                    <span class="sdxrpc-test-http-code"></span>
                                </div>
                                <p class="sdxrpc-test-message"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Column -->
                <div class="sdxrpc-sidebar-col">
                    <!-- Security Overview Card -->
                    <div class="sdxrpc-card sdxrpc-sidebar-card">
                        <div class="sdxrpc-card-header">
                            <h3>
                                <span class="dashicons dashicons-info"></span>
                                <?php esc_html_e( 'Why Disable XML-RPC?', 'simple-disable-xml-rpc' ); ?>
                            </h3>
                        </div>
                        <div class="sdxrpc-card-body">
                            <p><?php esc_html_e( 'XML-RPC was introduced in early WordPress versions for remote publishing. Today, WordPress uses the modern REST API. XML-RPC is now one of the most frequently abused endpoints for:', 'simple-disable-xml-rpc' ); ?></p>
                            <ul class="sdxrpc-feature-list">
                                <li>
                                    <span class="dashicons dashicons-lock"></span>
                                    <strong><?php esc_html_e( 'Brute-Force Attacks:', 'simple-disable-xml-rpc' ); ?></strong>
                                    <span><?php esc_html_e( 'Attackers test hundreds of credentials in a single request via system.multicall.', 'simple-disable-xml-rpc' ); ?></span>
                                </li>
                                <li>
                                    <span class="dashicons dashicons-performance"></span>
                                    <strong><?php esc_html_e( 'DDoS Amplification:', 'simple-disable-xml-rpc' ); ?></strong>
                                    <span><?php esc_html_e( 'Exploited pingback methods can turn your server into an unintended participant in DDoS attacks.', 'simple-disable-xml-rpc' ); ?></span>
                                </li>
                                <li>
                                    <span class="dashicons dashicons-dashboard"></span>
                                    <strong><?php esc_html_e( 'Resource Exhaustion:', 'simple-disable-xml-rpc' ); ?></strong>
                                    <span><?php esc_html_e( 'Persistent scans against xmlrpc.php waste server memory, CPU cycles, and bandwidth.', 'simple-disable-xml-rpc' ); ?></span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Author Card -->
                    <div class="sdxrpc-card sdxrpc-sidebar-card sdxrpc-author-card">
                        <div class="sdxrpc-card-header">
                            <h3>
                                <span class="dashicons dashicons-admin-users"></span>
                                <?php esc_html_e( 'About the Author', 'simple-disable-xml-rpc' ); ?>
                            </h3>
                        </div>
                        <div class="sdxrpc-card-body">
                            <div class="sdxrpc-author-profile-mini">
                                <div class="sdxrpc-mini-avatar">
                                    <span class="dashicons dashicons-admin-users"></span>
                                </div>
                                <div>
                                    <h4>Delower Hossain</h4>
                                    <p class="sdxrpc-author-role"><?php esc_html_e( 'WordPress Core & Plugin Developer', 'simple-disable-xml-rpc' ); ?></p>
                                </div>
                            </div>
                            <p class="sdxrpc-author-bio">
                                <?php esc_html_e( 'Delower Hossain is a passionate WordPress developer and open source enthusiast, creating reliable security and optimization plugins under @wpdelower.', 'simple-disable-xml-rpc' ); ?>
                            </p>
                            <a href="<?php echo esc_url( admin_url( 'admin.php?page=simple-disable-xml-rpc-recommended' ) ); ?>" class="button button-primary button-fullwidth">
                                <span class="dashicons dashicons-star-filled"></span> <?php esc_html_e( 'View Recommended Plugins', 'simple-disable-xml-rpc' ); ?>
                            </a>
                        </div>
                    </div>

                    <!-- Help & Review Card -->
                    <div class="sdxrpc-card sdxrpc-sidebar-card">
                        <div class="sdxrpc-card-header">
                            <h3>
                                <span class="dashicons dashicons-heart"></span>
                                <?php esc_html_e( 'Support & Feedback', 'simple-disable-xml-rpc' ); ?>
                            </h3>
                        </div>
                        <div class="sdxrpc-card-body">
                            <p><?php esc_html_e( 'If you find Simple Disable XML-RPC helpful, please consider leaving a 5-star review on WordPress.org. It helps support continuous development and updates!', 'simple-disable-xml-rpc' ); ?></p>
                            <a href="https://wordpress.org/support/plugin/simple-disable-xml-rpc/reviews/#new-post" target="_blank" rel="noopener noreferrer" class="button button-secondary button-fullwidth">
                                <span class="dashicons dashicons-star-filled" style="color: #f0b849;"></span>
                                <?php esc_html_e( 'Leave a 5-Star Review', 'simple-disable-xml-rpc' ); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * AJAX handler: Test XML-RPC endpoint status
     */
    public static function ajax_test_xmlrpc() {
        check_ajax_referer( 'sdxrpc_admin_nonce', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => esc_html__( 'Permission denied.', 'simple-disable-xml-rpc' ) ) );
        }

        $xmlrpc_url = site_url( 'xmlrpc.php' );

        $response = wp_remote_post(
            $xmlrpc_url,
            array(
                'timeout'   => 10,
                'sslverify' => false,
                'headers'   => array(
                    'Content-Type' => 'text/xml',
                ),
                'body'      => '<?xml version="1.0"?><methodCall><methodName>system.listMethods</methodName><params></params></methodCall>',
            )
        );

        if ( is_wp_error( $response ) ) {
            wp_send_json_error(
                array(
                    'status'  => 'error',
                    'message' => sprintf(
                        /* translators: %s: Error message */
                        esc_html__( 'Failed to connect to %1$s: %2$s', 'simple-disable-xml-rpc' ),
                        $xmlrpc_url,
                        $response->get_error_message()
                    ),
                )
            );
        }

        $code = wp_remote_retrieve_response_code( $response );
        $body = wp_remote_retrieve_body( $response );

        if ( 403 === $code ) {
            wp_send_json_success(
                array(
                    'status'  => 'protected_403',
                    'code'    => 403,
                    'title'   => esc_html__( 'Protected: HTTP 403 Forbidden', 'simple-disable-xml-rpc' ),
                    'message' => esc_html__( 'Great news! Direct access to xmlrpc.php is completely blocked. Attackers receive an HTTP 403 Forbidden response without burdening your server.', 'simple-disable-xml-rpc' ),
                )
            );
        } elseif ( 404 === $code ) {
            wp_send_json_success(
                array(
                    'status'  => 'protected_404',
                    'code'    => 404,
                    'title'   => esc_html__( 'Protected: HTTP 404 Not Found', 'simple-disable-xml-rpc' ),
                    'message' => esc_html__( 'xmlrpc.php is not accessible (returned HTTP 404). Your site is safe from XML-RPC attacks.', 'simple-disable-xml-rpc' ),
                )
            );
        } elseif ( false !== strpos( $body, 'XML-RPC services are disabled' ) || false !== strpos( $body, 'faultCode' ) ) {
            wp_send_json_success(
                array(
                    'status'  => 'disabled_filter',
                    'code'    => $code,
                    'title'   => esc_html__( 'Protected: XML-RPC Disabled by Filter', 'simple-disable-xml-rpc' ),
                    'message' => esc_html__( 'XML-RPC requests are disabled by the WordPress core filter. Remote methods cannot be executed.', 'simple-disable-xml-rpc' ),
                )
            );
        } elseif ( false !== strpos( $body, 'methodResponse' ) ) {
            wp_send_json_success(
                array(
                    'status'  => 'exposed',
                    'code'    => $code,
                    'title'   => esc_html__( 'Warning: XML-RPC is Active', 'simple-disable-xml-rpc' ),
                    'message' => esc_html__( 'xmlrpc.php accepted the test request and returned active methods. Turn on the "Disable XML-RPC Completely" switch to secure your site.', 'simple-disable-xml-rpc' ),
                )
            );
        } else {
            wp_send_json_success(
                array(
                    'status'  => 'custom',
                    'code'    => $code,
                    'title'   => sprintf(
                        /* translators: %d: HTTP response status code */
                        esc_html__( 'Server Response: HTTP %d', 'simple-disable-xml-rpc' ),
                        $code
                    ),
                    'message' => esc_html__( 'The endpoint responded. Review your server configuration and test results.', 'simple-disable-xml-rpc' ),
                )
            );
        }
    }

    /**
     * Add plugin action links on plugins.php
     *
     * @param array $links Existing links
     * @return array Modified links
     */
    public static function add_action_links( $links ) {
        $settings_link = sprintf(
            '<a href="%s">%s</a>',
            esc_url( admin_url( 'admin.php?page=simple-disable-xml-rpc' ) ),
            esc_html__( 'Settings', 'simple-disable-xml-rpc' )
        );

        $recommended_link = sprintf(
            '<a href="%s" style="color: #2271b1; font-weight: 600;">%s</a>',
            esc_url( admin_url( 'admin.php?page=simple-disable-xml-rpc-recommended' ) ),
            esc_html__( 'Recommended Plugins', 'simple-disable-xml-rpc' )
        );

        $support_link = sprintf(
            '<a href="%s" target="_blank" rel="noopener noreferrer">%s</a>',
            esc_url( 'https://wordpress.org/support/plugin/simple-disable-xml-rpc/' ),
            esc_html__( 'Support', 'simple-disable-xml-rpc' )
        );

        array_unshift( $links, $settings_link, $recommended_link );
        $links[] = $support_link;

        return $links;
    }

    /**
     * Redirect to settings page after plugin activation
     */
    public static function activation_redirect() {
        if ( ! get_transient( 'sdxrpc_activation_redirect' ) ) {
            return;
        }

        delete_transient( 'sdxrpc_activation_redirect' );

        if ( is_network_admin() ) {
            return;
        }

        if ( ( defined( 'WP_CLI' ) && WP_CLI ) || wp_doing_cron() ) {
            return;
        }

        wp_safe_redirect( admin_url( 'admin.php?page=simple-disable-xml-rpc' ) );
        exit;
    }
}
