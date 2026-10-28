<?php
/**
 * Recommended Plugins Class
 *
 * @package SIMDISXMLRPC
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Recommended Plugins by wpdelower
 */
class SDXRPC_Recommended {

    /**
     * WordPress.org Author username
     */
    const AUTHOR_USERNAME = 'wpdelower';

    /**
     * Transient key for caching plugins list
     */
    const TRANSIENT_KEY = 'sdxrpc_recommended_plugins_wpdelower';

    /**
     * Initialize recommended plugins functionality
     */
    public static function init() {
        // Register AJAX actions
        add_action( 'wp_ajax_sdxrpc_plugin_action', array( __CLASS__, 'ajax_plugin_action' ) );
        add_action( 'wp_ajax_sdxrpc_refresh_recommended', array( __CLASS__, 'ajax_refresh_recommended' ) );
    }

    /**
     * Get fallback plugins list in case WordPress.org API is unreachable
     *
     * @return array
     */
    public static function get_fallback_plugins() {
        return array(
            array(
                'name'              => 'Simple Disable XML-RPC',
                'slug'              => 'simple-disable-xml-rpc',
                'version'           => SDXRPC_VERSION,
                'author'            => '<a href="https://profiles.wordpress.org/wpdelower/">Delower Hossain (wpdelower)</a>',
                'short_description' => 'Safely and easily disable XML-RPC functionality on your WordPress site. Reduce brute force and DDoS attacks with one click.',
                'rating'            => 100,
                'num_ratings'       => 20,
                'active_installs'   => 10000,
                'tested'            => '7.1',
                'icons'             => array(
                    'svg'     => SDXRPC_PLUGIN_URL . 'assets/images/icon.svg',
                    'default' => SDXRPC_PLUGIN_URL . 'assets/images/icon.svg',
                ),
                'homepage'          => 'https://wordpress.org/plugins/simple-disable-xml-rpc/',
            ),
            array(
                'name'              => 'Bot Traffic Shield – Block Bad Bots & AI Crawlers',
                'slug'              => 'bot-traffic-shield',
                'version'           => '1.1.0',
                'author'            => '<a href="https://profiles.wordpress.org/wpdelower/">Delower Hossain (wpdelower)</a>',
                'short_description' => 'Block bad bots, spam scrapers, AI web crawlers, and malicious traffic to protect your website resources, bandwidth, and content.',
                'rating'            => 100,
                'num_ratings'       => 10,
                'active_installs'   => 1000,
                'tested'            => '7.1',
                'icons'             => array(
                    'svg'     => SDXRPC_PLUGIN_URL . 'assets/images/icon.svg',
                    'default' => SDXRPC_PLUGIN_URL . 'assets/images/icon.svg',
                ),
                'homepage'          => 'https://wordpress.org/plugins/bot-traffic-shield/',
            ),
            array(
                'name'              => 'Comments Shield – Disable Comments & Stop Spam',
                'slug'              => 'comments-shield',
                'version'           => '1.0.5',
                'author'            => '<a href="https://profiles.wordpress.org/wpdelower/">Delower Hossain (wpdelower)</a>',
                'short_description' => 'Completely disable comments, stop spam comments, close comments on specific post types, and bulk delete spam with zero hassle.',
                'rating'            => 100,
                'num_ratings'       => 8,
                'active_installs'   => 1000,
                'tested'            => '7.1',
                'icons'             => array(
                    'svg'     => SDXRPC_PLUGIN_URL . 'assets/images/icon.svg',
                    'default' => SDXRPC_PLUGIN_URL . 'assets/images/icon.svg',
                ),
                'homepage'          => 'https://wordpress.org/plugins/comments-shield/',
            ),
            array(
                'name'              => 'Default Post Author',
                'slug'              => 'default-post-author',
                'version'           => '1.0.2',
                'author'            => '<a href="https://profiles.wordpress.org/wpdelower/">Delower Hossain (wpdelower)</a>',
                'short_description' => 'Automatically set and manage a default author for all newly created posts or custom post types without manual assignment.',
                'rating'            => 100,
                'num_ratings'       => 6,
                'active_installs'   => 1000,
                'tested'            => '7.1',
                'icons'             => array(
                    'svg'     => SDXRPC_PLUGIN_URL . 'assets/images/icon.svg',
                    'default' => SDXRPC_PLUGIN_URL . 'assets/images/icon.svg',
                ),
                'homepage'          => 'https://wordpress.org/plugins/default-post-author/',
            ),
            array(
                'name'              => 'Quick Search Replace',
                'slug'              => 'quick-search-replace',
                'version'           => '1.0.3',
                'author'            => '<a href="https://profiles.wordpress.org/wpdelower/">Delower Hossain (wpdelower)</a>',
                'short_description' => 'Fast, safe search and replace utility for your WordPress database with serialized data handling and dry-run preview mode.',
                'rating'            => 100,
                'num_ratings'       => 7,
                'active_installs'   => 1000,
                'tested'            => '7.1',
                'icons'             => array(
                    'svg'     => SDXRPC_PLUGIN_URL . 'assets/images/icon.svg',
                    'default' => SDXRPC_PLUGIN_URL . 'assets/images/icon.svg',
                ),
                'homepage'          => 'https://wordpress.org/plugins/quick-search-replace/',
            ),
            array(
                'name'              => 'Easy SVG Upload',
                'slug'              => 'easy-svg-upload',
                'version'           => '1.0.1',
                'author'            => '<a href="https://profiles.wordpress.org/wpdelower/">Delower Hossain (wpdelower)</a>',
                'short_description' => 'Safely enable SVG uploads in the WordPress media library with automated XML/SVG sanitization to prevent XSS attacks.',
                'rating'            => 100,
                'num_ratings'       => 5,
                'active_installs'   => 1000,
                'tested'            => '7.1',
                'icons'             => array(
                    'svg'     => SDXRPC_PLUGIN_URL . 'assets/images/icon.svg',
                    'default' => SDXRPC_PLUGIN_URL . 'assets/images/icon.svg',
                ),
                'homepage'          => 'https://wordpress.org/plugins/easy-svg-upload/',
            ),
        );
    }

    /**
     * Fetch all plugins available under wpdelower on WordPress.org
     *
     * @param bool $force_refresh Whether to bypass transient cache
     * @return array
     */
    public static function get_plugins_list( $force_refresh = false ) {
        if ( ! $force_refresh ) {
            $cached = get_transient( self::TRANSIENT_KEY );
            if ( false !== $cached && is_array( $cached ) && ! empty( $cached ) ) {
                return self::decorate_plugins( $cached );
            }
        }

        if ( ! function_exists( 'plugins_api' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
        }

        $args = array(
            'author'   => self::AUTHOR_USERNAME,
            'per_page' => 30,
            'fields'   => array(
                'short_description' => true,
                'description'       => false,
                'sections'          => false,
                'tested'            => true,
                'requires'          => true,
                'rating'            => true,
                'ratings'           => true,
                'downloaded'        => true,
                'download_link'     => true,
                'active_installs'   => true,
                'last_updated'      => true,
                'icons'             => true,
                'banners'           => true,
                'homepage'          => true,
                'author'            => true,
                'version'           => true,
                'slug'              => true,
            ),
        );

        $api = plugins_api( 'query_plugins', $args );
        $plugins = array();

        if ( ! is_wp_error( $api ) && ! empty( $api->plugins ) ) {
            foreach ( $api->plugins as $plugin_obj ) {
                $plugins[] = (array) $plugin_obj;
            }
        }

        // If API fails or is empty, fall back to our curated list
        if ( empty( $plugins ) ) {
            $plugins = self::get_fallback_plugins();
        } else {
            // Cache successful result for 12 hours
            set_transient( self::TRANSIENT_KEY, $plugins, 12 * HOUR_IN_SECONDS );
        }

        return self::decorate_plugins( $plugins );
    }

    /**
     * Decorate plugin list with local installation & active status
     *
     * @param array $plugins Raw plugins list
     * @return array
     */
    public static function decorate_plugins( $plugins ) {
        if ( ! function_exists( 'get_plugins' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        $all_plugins = get_plugins();

        foreach ( $plugins as $key => $plugin ) {
            $slug = isset( $plugin['slug'] ) ? $plugin['slug'] : '';
            $status_info = self::find_plugin_file_and_status( $slug, $all_plugins );

            $plugins[ $key ]['is_current_plugin'] = ( 'simple-disable-xml-rpc' === $slug );
            $plugins[ $key ]['status']            = $status_info['status']; // 'active', 'installed', 'not_installed'
            $plugins[ $key ]['plugin_file']       = $status_info['file'];
        }

        return $plugins;
    }

    /**
     * Find plugin file and status from installed plugins
     *
     * @param string $slug Plugin slug
     * @param array  $installed_plugins List of installed plugins from get_plugins()
     * @return array
     */
    public static function find_plugin_file_and_status( $slug, $installed_plugins = null ) {
        if ( null === $installed_plugins ) {
            if ( ! function_exists( 'get_plugins' ) ) {
                require_once ABSPATH . 'wp-admin/includes/plugin.php';
            }
            $installed_plugins = get_plugins();
        }

        foreach ( $installed_plugins as $file => $data ) {
            if ( dirname( $file ) === $slug || $file === $slug . '.php' ) {
                $is_active = is_plugin_active( $file );
                return array(
                    'status' => $is_active ? 'active' : 'installed',
                    'file'   => $file,
                );
            }
        }

        return array(
            'status' => 'not_installed',
            'file'   => '',
        );
    }

    /**
     * Get best plugin icon URL
     *
     * @param array $plugin Plugin data
     * @return string
     */
    public static function get_plugin_icon( $plugin ) {
        if ( ! empty( $plugin['icons'] ) && is_array( $plugin['icons'] ) ) {
            if ( ! empty( $plugin['icons']['2x'] ) ) {
                return $plugin['icons']['2x'];
            }
            if ( ! empty( $plugin['icons']['1x'] ) ) {
                return $plugin['icons']['1x'];
            }
            if ( ! empty( $plugin['icons']['svg'] ) ) {
                return $plugin['icons']['svg'];
            }
            if ( ! empty( $plugin['icons']['default'] ) ) {
                return $plugin['icons']['default'];
            }
        }

        // Fallback to local SVG icon.
        return SDXRPC_PLUGIN_URL . 'assets/images/icon.svg';
    }

    /**
     * Render the Recommended Plugins admin page
     */
    public static function render_recommended_page() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'simple-disable-xml-rpc' ) );
        }

        $plugins = self::get_plugins_list();
        ?>
        <div class="wrap sdxrpc-settings-wrap">
            <?php SDXRPC_Admin::render_header( 'recommended' ); ?>

            <!-- Notification banner / Toast placeholder -->
            <div id="sdxrpc-ajax-notice" class="sdxrpc-toast" style="display: none;"></div>

            <!-- Author Hero Banner -->
            <div class="sdxrpc-author-hero">
                <div class="sdxrpc-author-avatar">
                    <span class="dashicons dashicons-admin-users"></span>
                </div>
                <div class="sdxrpc-author-content">
                    <h2><?php esc_html_e( 'Plugins by Delower Hossain (@wpdelower)', 'simple-disable-xml-rpc' ); ?></h2>
                    <p>
                        <?php esc_html_e( 'Explore our suite of lightweight, focused, and security-hardened WordPress plugins. Designed and maintained by WordPress community contributor Delower Hossain and the MonarchWP team.', 'simple-disable-xml-rpc' ); ?>
                    </p>
                    <div class="sdxrpc-author-actions">
                        <a href="https://profiles.wordpress.org/wpdelower/" target="_blank" rel="noopener noreferrer" class="button button-secondary">
                            <span class="dashicons dashicons-external"></span> <?php esc_html_e( 'WordPress.org Profile', 'simple-disable-xml-rpc' ); ?>
                        </a>
                        <button type="button" id="sdxrpc-refresh-plugins" class="button button-secondary">
                            <span class="dashicons dashicons-update"></span> <?php esc_html_e( 'Refresh List', 'simple-disable-xml-rpc' ); ?>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Search and Filter Bar -->
            <div class="sdxrpc-filter-bar">
                <div class="sdxrpc-filter-search">
                    <span class="dashicons dashicons-search"></span>
                    <input type="text" id="sdxrpc-plugin-search" placeholder="<?php esc_attr_e( 'Search recommended plugins...', 'simple-disable-xml-rpc' ); ?>" />
                </div>
                <div class="sdxrpc-filter-stats">
                    <?php
                    printf(
                        /* translators: %d: Total number of plugins */
                        esc_html__( 'Showing %d plugins', 'simple-disable-xml-rpc' ),
                        count( $plugins )
                    );
                    ?>
                </div>
            </div>

            <!-- Plugin Cards Grid -->
            <div class="sdxrpc-plugins-grid" id="sdxrpc-plugins-grid">
                <?php
                if ( ! empty( $plugins ) ) :
                    foreach ( $plugins as $plugin ) :
                        $slug           = isset( $plugin['slug'] ) ? $plugin['slug'] : '';
                        $name           = isset( $plugin['name'] ) ? wp_strip_all_tags( $plugin['name'] ) : '';
                        $version        = isset( $plugin['version'] ) ? $plugin['version'] : '';
                        $description    = isset( $plugin['short_description'] ) ? wp_strip_all_tags( $plugin['short_description'] ) : '';
                        $rating         = isset( $plugin['rating'] ) ? floatval( $plugin['rating'] ) : 0;
                        $num_ratings    = isset( $plugin['num_ratings'] ) ? intval( $plugin['num_ratings'] ) : 0;
                        $active_installs= isset( $plugin['active_installs'] ) ? intval( $plugin['active_installs'] ) : 0;
                        $tested         = isset( $plugin['tested'] ) ? $plugin['tested'] : '';
                        $status         = isset( $plugin['status'] ) ? $plugin['status'] : 'not_installed';
                        $plugin_file    = isset( $plugin['plugin_file'] ) ? $plugin['plugin_file'] : '';
                        $is_current     = ! empty( $plugin['is_current_plugin'] );
                        $icon_url       = self::get_plugin_icon( $plugin );
                        $wp_org_url     = 'https://wordpress.org/plugins/' . sanitize_key( $slug ) . '/';
                        ?>
                        <div class="sdxrpc-plugin-card" data-slug="<?php echo esc_attr( $slug ); ?>" data-name="<?php echo esc_attr( strtolower( $name ) ); ?>">
                            <div class="sdxrpc-plugin-card-header">
                                <div class="sdxrpc-plugin-icon-wrap">
                                    <img src="<?php echo esc_url( $icon_url ); ?>" alt="<?php echo esc_attr( $name ); ?>" onerror="this.onerror=null;this.src='<?php echo esc_url( SDXRPC_PLUGIN_URL . 'assets/images/icon.svg' ); ?>';" />
                                </div>
                                <div class="sdxrpc-plugin-title-wrap">
                                    <h3 class="sdxrpc-plugin-title">
                                        <a href="<?php echo esc_url( $wp_org_url ); ?>" target="_blank" rel="noopener noreferrer">
                                            <?php echo esc_html( $name ); ?>
                                        </a>
                                    </h3>
                                    <div class="sdxrpc-plugin-meta-inline">
                                        <?php if ( ! empty( $version ) ) : ?>
                                            <span class="sdxrpc-card-version">v<?php echo esc_html( $version ); ?></span>
                                        <?php endif; ?>
                                        <span class="sdxrpc-card-author"><?php esc_html_e( 'by Delower Hossain', 'simple-disable-xml-rpc' ); ?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="sdxrpc-plugin-card-body">
                                <p class="sdxrpc-plugin-desc"><?php echo esc_html( $description ); ?></p>

                                <div class="sdxrpc-plugin-card-metrics">
                                    <div class="sdxrpc-metric-rating">
                                        <?php
                                        // 5 stars rating calculation
                                        $stars = round( $rating / 20 );
                                        for ( $i = 1; $i <= 5; $i++ ) {
                                            if ( $i <= $stars ) {
                                                echo '<span class="dashicons dashicons-star-filled"></span>';
                                            } else {
                                                echo '<span class="dashicons dashicons-star-empty"></span>';
                                            }
                                        }
                                        ?>
                                        <span class="sdxrpc-rating-count">(<?php echo esc_html( $num_ratings ); ?>)</span>
                                    </div>
                                    <div class="sdxrpc-metric-installs">
                                        <span class="dashicons dashicons-admin-users"></span>
                                        <span>
                                            <?php
                                            if ( $active_installs >= 1000 ) {
                                                printf( '%s+ %s', esc_html( number_format_i18n( $active_installs ) ), esc_html__( 'Active', 'simple-disable-xml-rpc' ) );
                                            } else {
                                                printf( '%s+ %s', esc_html( $active_installs ), esc_html__( 'Active', 'simple-disable-xml-rpc' ) );
                                            }
                                            ?>
                                        </span>
                                    </div>
                                    <?php if ( ! empty( $tested ) ) : ?>
                                        <div class="sdxrpc-metric-tested">
                                            <span class="dashicons dashicons-yes"></span>
                                            <span>
                                                <?php
                                                printf(
                                                    /* translators: %s: WordPress version number */
                                                    esc_html__( 'WP %s', 'simple-disable-xml-rpc' ),
                                                    esc_html( $tested )
                                                );
                                                ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="sdxrpc-plugin-card-footer">
                                <div class="sdxrpc-card-actions" data-slug="<?php echo esc_attr( $slug ); ?>" data-file="<?php echo esc_attr( $plugin_file ); ?>">
                                    <?php if ( $is_current ) : ?>
                                        <span class="sdxrpc-badge-current">
                                            <span class="dashicons dashicons-yes-alt"></span> <?php esc_html_e( 'Active (Current Plugin)', 'simple-disable-xml-rpc' ); ?>
                                        </span>
                                    <?php elseif ( 'active' === $status ) : ?>
                                        <span class="sdxrpc-badge-active">
                                            <span class="dashicons dashicons-yes"></span> <?php esc_html_e( 'Active', 'simple-disable-xml-rpc' ); ?>
                                        </span>
                                        <button type="button" class="button button-secondary sdxrpc-plugin-action" data-action="deactivate" data-slug="<?php echo esc_attr( $slug ); ?>" data-file="<?php echo esc_attr( $plugin_file ); ?>">
                                            <?php esc_html_e( 'Deactivate', 'simple-disable-xml-rpc' ); ?>
                                        </button>
                                    <?php elseif ( 'installed' === $status ) : ?>
                                        <button type="button" class="button button-primary sdxrpc-plugin-action" data-action="activate" data-slug="<?php echo esc_attr( $slug ); ?>" data-file="<?php echo esc_attr( $plugin_file ); ?>">
                                            <?php esc_html_e( 'Activate', 'simple-disable-xml-rpc' ); ?>
                                        </button>
                                    <?php else : ?>
                                        <button type="button" class="button button-primary sdxrpc-plugin-action" data-action="install" data-slug="<?php echo esc_attr( $slug ); ?>" data-file="">
                                            <span class="dashicons dashicons-download"></span> <?php esc_html_e( 'Install Now', 'simple-disable-xml-rpc' ); ?>
                                        </button>
                                    <?php endif; ?>
                                </div>

                                <div class="sdxrpc-card-links">
                                    <a href="<?php echo esc_url( $wp_org_url ); ?>" target="_blank" rel="noopener noreferrer" class="sdxrpc-more-details-link">
                                        <?php esc_html_e( 'More Details', 'simple-disable-xml-rpc' ); ?> &rarr;
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php
                    endforeach;
                else :
                    ?>
                    <div class="sdxrpc-card sdxrpc-card-empty">
                        <p><?php esc_html_e( 'No recommended plugins found. Click "Refresh List" above to load plugins.', 'simple-disable-xml-rpc' ); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    /**
     * Handle AJAX plugin action: install, activate, deactivate
     */
    public static function ajax_plugin_action() {
        check_ajax_referer( 'sdxrpc_plugin_action_nonce', 'nonce' );

        $action = isset( $_POST['plugin_action'] ) ? sanitize_text_field( wp_unslash( $_POST['plugin_action'] ) ) : '';
        $slug   = isset( $_POST['slug'] ) ? sanitize_key( wp_unslash( $_POST['slug'] ) ) : '';

        if ( empty( $slug ) || empty( $action ) ) {
            wp_send_json_error( array( 'message' => esc_html__( 'Invalid request parameters.', 'simple-disable-xml-rpc' ) ) );
        }

        if ( 'install' === $action ) {
            if ( ! current_user_can( 'install_plugins' ) ) {
                wp_send_json_error( array( 'message' => esc_html__( 'You do not have permission to install plugins.', 'simple-disable-xml-rpc' ) ) );
            }

            require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
            require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
            require_once ABSPATH . 'wp-admin/includes/file.php';

            $api = plugins_api(
                'plugin_information',
                array(
                    'slug'   => $slug,
                    'fields' => array( 'sections' => false ),
                )
            );

            if ( is_wp_error( $api ) ) {
                wp_send_json_error( array( 'message' => $api->get_error_message() ) );
            }

            // Install plugin using WordPress native Upgrader Skin
            $skin     = new WP_Ajax_Upgrader_Skin();
            $upgrader = new Plugin_Upgrader( $skin );
            $result   = $upgrader->install( $api->download_link );

            if ( is_wp_error( $result ) ) {
                wp_send_json_error( array( 'message' => $result->get_error_message() ) );
            } elseif ( is_wp_error( $skin->result ) ) {
                wp_send_json_error( array( 'message' => $skin->result->get_error_message() ) );
            } elseif ( $skin->get_errors()->has_errors() ) {
                wp_send_json_error( array( 'message' => $skin->get_error_messages() ) );
            } elseif ( false === $result ) {
                wp_send_json_error( array( 'message' => esc_html__( 'Plugin installation failed.', 'simple-disable-xml-rpc' ) ) );
            }

            // Determine installed plugin file
            $status_info = self::find_plugin_file_and_status( $slug );
            $plugin_file = ! empty( $status_info['file'] ) ? $status_info['file'] : ( $slug . '/' . $slug . '.php' );

            wp_send_json_success(
                array(
                    'message'     => esc_html__( 'Installed successfully! You can now activate it.', 'simple-disable-xml-rpc' ),
                    'slug'        => $slug,
                    'plugin_file' => $plugin_file,
                    'status'      => 'installed',
                )
            );
        } elseif ( 'activate' === $action ) {
            if ( ! current_user_can( 'activate_plugins' ) ) {
                wp_send_json_error( array( 'message' => esc_html__( 'You do not have permission to activate plugins.', 'simple-disable-xml-rpc' ) ) );
            }

            $plugin_file = isset( $_POST['plugin_file'] ) ? sanitize_text_field( wp_unslash( $_POST['plugin_file'] ) ) : '';
            if ( empty( $plugin_file ) ) {
                $status_info = self::find_plugin_file_and_status( $slug );
                $plugin_file = $status_info['file'];
            }

            if ( empty( $plugin_file ) ) {
                wp_send_json_error( array( 'message' => esc_html__( 'Plugin file could not be found.', 'simple-disable-xml-rpc' ) ) );
            }

            $result = activate_plugin( $plugin_file );

            if ( is_wp_error( $result ) ) {
                wp_send_json_error( array( 'message' => $result->get_error_message() ) );
            }

            wp_send_json_success(
                array(
                    'message'     => esc_html__( 'Plugin activated successfully!', 'simple-disable-xml-rpc' ),
                    'slug'        => $slug,
                    'plugin_file' => $plugin_file,
                    'status'      => 'active',
                )
            );
        } elseif ( 'deactivate' === $action ) {
            if ( ! current_user_can( 'activate_plugins' ) ) {
                wp_send_json_error( array( 'message' => esc_html__( 'You do not have permission to deactivate plugins.', 'simple-disable-xml-rpc' ) ) );
            }

            $plugin_file = isset( $_POST['plugin_file'] ) ? sanitize_text_field( wp_unslash( $_POST['plugin_file'] ) ) : '';
            if ( empty( $plugin_file ) ) {
                $status_info = self::find_plugin_file_and_status( $slug );
                $plugin_file = $status_info['file'];
            }

            if ( empty( $plugin_file ) ) {
                wp_send_json_error( array( 'message' => esc_html__( 'Plugin file could not be found.', 'simple-disable-xml-rpc' ) ) );
            }

            deactivate_plugins( $plugin_file );

            wp_send_json_success(
                array(
                    'message'     => esc_html__( 'Plugin deactivated successfully!', 'simple-disable-xml-rpc' ),
                    'slug'        => $slug,
                    'plugin_file' => $plugin_file,
                    'status'      => 'installed',
                )
            );
        } else {
            wp_send_json_error( array( 'message' => esc_html__( 'Invalid action requested.', 'simple-disable-xml-rpc' ) ) );
        }
    }

    /**
     * Handle AJAX refresh of recommended plugins list
     */
    public static function ajax_refresh_recommended() {
        check_ajax_referer( 'sdxrpc_plugin_action_nonce', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => esc_html__( 'Permission denied.', 'simple-disable-xml-rpc' ) ) );
        }

        delete_transient( self::TRANSIENT_KEY );
        $plugins = self::get_plugins_list( true );

        wp_send_json_success(
            array(
                'message' => esc_html__( 'Recommended plugins refreshed successfully!', 'simple-disable-xml-rpc' ),
                'count'   => count( $plugins ),
            )
        );
    }
}
