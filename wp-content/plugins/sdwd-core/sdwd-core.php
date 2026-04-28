<?php
/**
 * Plugin Name: SDWD Core
 * Description: Core plugin for San Diego Wedding Directory — vendors, venues, couples.
 * Version:     1.0.0
 * Author:      SD Wedding Directory
 * Text Domain: sdwd-core
 */

defined( 'ABSPATH' ) || exit;

define( 'SDWD_CORE_VERSION', '1.0.0' );
define( 'SDWD_CORE_PATH', plugin_dir_path( __FILE__ ) );
define( 'SDWD_CORE_URL', plugin_dir_url( __FILE__ ) );

/**
 * Activation — register roles.
 */
register_activation_hook( __FILE__, function () {
    require_once SDWD_CORE_PATH . 'includes/roles.php';
    sdwd_register_roles();
} );

/**
 * Deactivation — optionally remove roles.
 * Roles are left in place so user data isn't orphaned.
 */
register_deactivation_hook( __FILE__, function () {
    // Intentionally empty — roles persist so users keep access.
} );

/**
 * Bootstrap.
 */
add_action( 'plugins_loaded', function () {
    require_once SDWD_CORE_PATH . 'includes/roles.php';
    require_once SDWD_CORE_PATH . 'includes/post-types.php';
    require_once SDWD_CORE_PATH . 'includes/taxonomies.php';
    require_once SDWD_CORE_PATH . 'includes/user-post-link.php';
    require_once SDWD_CORE_PATH . 'includes/auth.php';
    require_once SDWD_CORE_PATH . 'includes/dashboard.php';
    require_once SDWD_CORE_PATH . 'includes/vendor-frontend.php';
    require_once SDWD_CORE_PATH . 'includes/claim.php';
    require_once SDWD_CORE_PATH . 'includes/quote.php';

    // Admin meta boxes + migration + custom dashboard.
    if ( is_admin() ) {
        require_once SDWD_CORE_PATH . 'includes/admin/couple-meta.php';
        require_once SDWD_CORE_PATH . 'includes/admin/vendor-meta.php';
        require_once SDWD_CORE_PATH . 'includes/admin/venue-meta.php';
        require_once SDWD_CORE_PATH . 'includes/admin/dashboard.php';
        require_once SDWD_CORE_PATH . 'includes/migrate.php';
    }
}, 5 );

/**
 * Enqueue admin assets for meta box screens.
 */
add_action( 'admin_enqueue_scripts', function ( $hook ) {
    if ( ! in_array( $hook, [ 'post.php', 'post-new.php' ], true ) ) {
        return;
    }
    $screen = get_current_screen();
    if ( ! $screen || ! in_array( $screen->post_type, [ 'couple', 'vendor', 'venue' ], true ) ) {
        return;
    }

    wp_enqueue_style( 'sdwd-admin', SDWD_CORE_URL . 'assets/admin.css', [], SDWD_CORE_VERSION );
    wp_enqueue_script( 'sdwd-admin', SDWD_CORE_URL . 'assets/admin.js', [], SDWD_CORE_VERSION, true );
} );
