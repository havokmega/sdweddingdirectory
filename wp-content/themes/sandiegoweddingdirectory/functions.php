<?php
/**
 * SDWeddingDirectory v2 Theme Functions
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'SDWEDDINGDIRECTORY_THEME_VERSION' ) ) {
    define( 'SDWEDDINGDIRECTORY_THEME_VERSION', esc_attr( wp_get_theme()->get( 'Version' ) ) );
}

if ( ! defined( 'SDWEDDINGDIRECTORY_THEME_DIR' ) ) {
    define( 'SDWEDDINGDIRECTORY_THEME_DIR', trailingslashit( get_template_directory_uri() ) );
}

if ( ! defined( 'SDWEDDINGDIRECTORY_THEME_PATH' ) ) {
    define( 'SDWEDDINGDIRECTORY_THEME_PATH', trailingslashit( get_template_directory() ) );
}

if ( ! defined( 'SDWEDDINGDIRECTORY_THEME_LIBRARY' ) ) {
    define( 'SDWEDDINGDIRECTORY_THEME_LIBRARY', SDWEDDINGDIRECTORY_THEME_DIR . 'assets/library/' );
}

if ( ! defined( 'SDWEDDINGDIRECTORY_THEME_MEDIA' ) ) {
    define( 'SDWEDDINGDIRECTORY_THEME_MEDIA', SDWEDDINGDIRECTORY_THEME_DIR . 'assets/images/' );
}

/**
 * Theme setup
 */
add_action( 'after_setup_theme', function () {
    load_theme_textdomain( 'sandiegoweddingdirectory', get_template_directory() . '/languages' );

    register_nav_menus(
        [
            'primary-menu'     => __( 'Primary Menu', 'sandiegoweddingdirectory' ),
            'tiny-footer-menu' => __( 'Tiny Footer Menu', 'sandiegoweddingdirectory' ),
        ]
    );

    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support(
        'html5',
        [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ]
    );
    add_theme_support(
        'custom-logo',
        [
            'height'      => 80,
            'width'       => 220,
            'flex-height' => true,
            'flex-width'  => true,
        ]
    );

} );

/**
 * Widgets
 */
add_action( 'widgets_init', function () {
    for ( $i = 1; $i <= 4; $i++ ) {
        register_sidebar(
            [
                'name'          => sprintf( __( 'Footer Column %d', 'sandiegoweddingdirectory' ), $i ),
                'id'            => "sdwdv2-footer-{$i}",
                'description'   => __( 'Footer widget area', 'sandiegoweddingdirectory' ),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h3 class="widget-title">',
                'after_title'   => '</h3>',
            ]
        );
    }
} );

/**
 * Enqueue styles/scripts
 */
add_action( 'wp_enqueue_scripts', function () {
    $theme_version = wp_get_theme()->get( 'Version' );
    $theme_uri     = get_template_directory_uri();
    $theme_dir     = get_template_directory();
    $asset_version = static function ( $relative_path ) use ( $theme_dir, $theme_version ) {
        $asset_path = $theme_dir . $relative_path;
        return file_exists( $asset_path ) ? filemtime( $asset_path ) : $theme_version;
    };

    // Icon font (sdwd-icons only)
    wp_enqueue_style( 'sdwd-icons', $theme_uri . '/assets/library/sdwd-icons/style.css', [], $asset_version( '/assets/library/sdwd-icons/style.css' ) );

    // Core styles
    wp_enqueue_style( 'sdwdv2-foundation', $theme_uri . '/assets/css/foundation.css', [ 'sdwd-icons' ], $asset_version( '/assets/css/foundation.css' ) );
    wp_enqueue_style( 'sdwdv2-components', $theme_uri . '/assets/css/components.css', [ 'sdwdv2-foundation' ], $asset_version( '/assets/css/components.css' ) );
    wp_enqueue_style( 'sdwdv2-layout', $theme_uri . '/assets/css/layout.css', [ 'sdwdv2-components' ], $asset_version( '/assets/css/layout.css' ) );

    // Page-level styles — conditionally loaded
    $is_venues_landing = is_page( 'venues' ) || is_page_template( 'page-venues.php' );

    if ( is_front_page() || $is_venues_landing ) {
        wp_enqueue_style( 'sdwdv2-home', $theme_uri . '/assets/css/pages/home.css', [ 'sdwdv2-layout' ], $asset_version( '/assets/css/pages/home.css' ) );
    }

    if ( is_archive() || is_tax() || is_search() ) {
        wp_enqueue_style( 'sdwdv2-archive', $theme_uri . '/assets/css/pages/archive.css', [ 'sdwdv2-layout' ], $asset_version( '/assets/css/pages/archive.css' ) );
    }

    if ( $is_venues_landing || is_post_type_archive( 'venue' ) || is_tax( 'venue-type' ) || is_tax( 'venue-location' ) ) {
        wp_enqueue_style( 'sdwdv2-venues', $theme_uri . '/assets/css/pages/venues.css', [ 'sdwdv2-layout' ], $asset_version( '/assets/css/pages/venues.css' ) );
    }

    $is_vendors_landing = is_page( 'vendors' ) || is_page_template( 'page-vendors.php' );

    if ( $is_vendors_landing || is_post_type_archive( 'vendor' ) || is_tax( 'vendor-category' ) ) {
        wp_enqueue_style( 'sdwdv2-vendors', $theme_uri . '/assets/css/pages/vendors.css', [ 'sdwdv2-layout' ], $asset_version( '/assets/css/pages/vendors.css' ) );
    }

    if ( is_singular( [ 'vendor', 'venue', 'real-wedding', 'couple' ] ) ) {
        wp_enqueue_style( 'sdwdv2-profile', $theme_uri . '/assets/css/pages/profile.css', [ 'sdwdv2-layout' ], $asset_version( '/assets/css/pages/profile.css' ) );
    }

    // Claim-business button on single vendor/venue pages (P2-CLAIM-01).
    if ( is_singular( [ 'vendor', 'venue' ] ) ) {
        wp_enqueue_script( 'sdwd-claim', $theme_uri . '/assets/js/claim.js', [], $asset_version( '/assets/js/claim.js' ), true );
        wp_localize_script( 'sdwd-claim', 'sdwd_claim', [
            'url'   => admin_url( 'admin-ajax.php' ),
            'nonce' => wp_create_nonce( 'sdwd_claim_nonce' ),
        ] );
    }

    $is_planning = is_page_template( 'page-wedding-planning.php' )
        || ( is_category() && sdwdv2_is_planning_category() )
        || ( is_page() && absint( wp_get_post_parent_id( get_the_ID() ) ) === 4180 );

    if ( $is_planning ) {
        wp_enqueue_style( 'sdwdv2-planning', $theme_uri . '/assets/css/pages/planning.css', [ 'sdwdv2-layout' ], $asset_version( '/assets/css/pages/planning.css' ) );
    }

    if ( is_singular( 'post' ) || is_category() || is_page_template( 'page-inspiration.php' ) ) {
        wp_enqueue_style( 'sdwdv2-blog', $theme_uri . '/assets/css/pages/blog.css', [ 'sdwdv2-layout' ], $asset_version( '/assets/css/pages/blog.css' ) );
    }

    if ( is_page_template( [ 'page-about.php', 'page-contact.php', 'page-faqs.php', 'page-policy.php', 'page-our-team.php' ] )
         || is_404() || is_search() || is_singular( 'team' ) || is_singular( 'changelog' ) ) {
        wp_enqueue_style( 'sdwdv2-static', $theme_uri . '/assets/css/pages/static.css', [ 'sdwdv2-layout' ], $asset_version( '/assets/css/pages/static.css' ) );
    }

    if ( sdwdv2_is_dashboard_page() ) {
        wp_enqueue_style( 'sdwdv2-dashboard', $theme_uri . '/assets/css/pages/dashboard.css', [ 'sdwdv2-layout' ], $asset_version( '/assets/css/pages/dashboard.css' ) );
        wp_enqueue_script( 'sdwd-dashboard', $theme_uri . '/assets/js/dashboard.js', [], $asset_version( '/assets/js/dashboard.js' ), true );
        wp_localize_script( 'sdwd-dashboard', 'sdwd_dash', [
            'url'             => admin_url( 'admin-ajax.php' ),
            'nonce'           => wp_create_nonce( 'sdwd_dashboard_nonce' ),
            // Module-specific nonces — sdwd-couple/modules/{module}.php each
            // verifies its own nonce via check_ajax_referer. Consumer JS reads
            // e.g. sdwd_dash.budget_nonce and posts as 'nonce'. (LG-01)
            'budget_nonce'    => wp_create_nonce( 'sdwd_budget_nonce' ),
            'checklist_nonce' => wp_create_nonce( 'sdwd_checklist_nonce' ),
            'quote_nonce'     => wp_create_nonce( 'sdwd_quote_nonce' ),
            'review_nonce'    => wp_create_nonce( 'sdwd_review_nonce' ),
            'wishlist_nonce'  => wp_create_nonce( 'sdwd_wishlist_nonce' ),
        ] );

        if ( get_page_template_slug( get_queried_object_id() ) ==='user-template/couple-dashboard.php' ) {
            wp_enqueue_style( 'sdwdv2-couple-dashboard', $theme_uri . '/assets/css/pages/couple-dashboard.css', [ 'sdwdv2-dashboard' ], $asset_version( '/assets/css/pages/couple-dashboard.css' ) );
        }

        if ( get_page_template_slug( get_queried_object_id() ) ==='user-template/vendor-dashboard.php' ) {
            wp_enqueue_style( 'sdwdv2-vendor-dashboard', $theme_uri . '/assets/css/pages/vendor-dashboard.css', [ 'sdwdv2-dashboard' ], $asset_version( '/assets/css/pages/vendor-dashboard.css' ) );
        }
    }

    // Modals (logged-out users only).
    if ( ! is_user_logged_in() ) {
        wp_enqueue_style( 'sdwdv2-modals', $theme_uri . '/assets/css/pages/modals.css', [ 'sdwdv2-layout' ], $asset_version( '/assets/css/pages/modals.css' ) );
        wp_enqueue_script( 'sdwd-modals', $theme_uri . '/assets/js/modals.js', [], $asset_version( '/assets/js/modals.js' ), true );
        wp_localize_script( 'sdwd-modals', 'sdwd_ajax', [
            'url'   => admin_url( 'admin-ajax.php' ),
            'nonce' => wp_create_nonce( 'sdwd_auth_nonce' ),
        ] );
    }

    // JS
    wp_enqueue_script( 'sdwdv2-app', $theme_uri . '/assets/js/app.js', [], $asset_version( '/assets/js/app.js' ), true );

} );

// Nav walker for mega menu
require_once get_template_directory() . '/inc/sd-mega-navwalker.php';
require_once get_template_directory() . '/inc/venues.php';
require_once get_template_directory() . '/inc/vendors.php';

/**
 * Check if current category is a planning subcategory.
 */
function sdwdv2_is_planning_category() {
    if ( ! is_category() ) {
        return false;
    }

    $planning_parent = get_category_by_slug( 'wedding-planning-how-to' );
    if ( ! $planning_parent ) {
        return false;
    }

    $current = get_queried_object();
    if ( ! $current || ! isset( $current->parent ) ) {
        return false;
    }

    return (int) $current->parent === (int) $planning_parent->term_id
        || (int) $current->term_id === (int) $planning_parent->term_id;
}

/**
 * Check if current page is a dashboard page.
 */
function sdwdv2_is_dashboard_page() {
    $id = get_queried_object_id();
    $template = $id ? get_page_template_slug( $id ) : get_page_template_slug();
    return in_array( $template, [
        'user-template/vendor-dashboard.php',
        'user-template/vendor-profile.php',
        'user-template/venue-dashboard.php',
        'user-template/couple-dashboard.php',
    ], true );
}

/**
 * Register sidebar widget area.
 */
add_action( 'widgets_init', function () {
    register_sidebar( [
        'name'          => __( 'Blog Sidebar', 'sandiegoweddingdirectory' ),
        'id'            => 'sdwdv2-blog-sidebar',
        'description'   => __( 'Sidebar for blog and category pages', 'sandiegoweddingdirectory' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ] );
} );

