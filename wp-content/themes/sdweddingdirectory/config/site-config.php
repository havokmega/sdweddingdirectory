<?php
/**
 * SDWeddingDirectory - Site configuration constants
 */
defined( 'ABSPATH' ) || exit;

if ( ! defined( 'SDW_LOGO_PATH' ) ) {
    define( 'SDW_LOGO_PATH', 'assets/images/logo/sdweddingdirectorylogo.png' );
}

if ( ! defined( 'SDW_MAP_DEFAULT_LATITUDE' ) ) {
    define( 'SDW_MAP_DEFAULT_LATITUDE', '32.7157' );
}

if ( ! defined( 'SDW_MAP_DEFAULT_LONGITUDE' ) ) {
    define( 'SDW_MAP_DEFAULT_LONGITUDE', '-117.1611' );
}

if ( ! defined( 'SDW_MAP_DEFAULT_ZOOM' ) ) {
    define( 'SDW_MAP_DEFAULT_ZOOM', 9 );
}

if ( ! defined( 'SDW_MAP_CLUSTER_IMAGE' ) ) {
    define( 'SDW_MAP_CLUSTER_IMAGE', SDWEDDINGDIRECTORY_THEME_MEDIA . 'map/sdweddingdirectory-map-cluster.png' );
}

if ( ! defined( 'SDW_MAP_MARKER_IMAGE' ) ) {
    define( 'SDW_MAP_MARKER_IMAGE', SDWEDDINGDIRECTORY_THEME_MEDIA . 'map/sdweddingdirectory-map-marker.svg' );
}

if ( ! defined( 'SDW_VENDOR_PLACEHOLDER' ) ) {
    define( 'SDW_VENDOR_PLACEHOLDER', SDWEDDINGDIRECTORY_THEME_MEDIA . 'placeholders/vendor-placeholder.png' );
}

if ( ! defined( 'SDW_VENUE_PLACEHOLDER' ) ) {
    define( 'SDW_VENUE_PLACEHOLDER', SDWEDDINGDIRECTORY_THEME_MEDIA . 'placeholders/venue-placeholder.png' );
}

if ( ! defined( 'SDW_HEADER_ONE_TOP_NAV_ENABLED' ) ) {
    define( 'SDW_HEADER_ONE_TOP_NAV_ENABLED', false );
}

if ( ! defined( 'SDW_HEADER_ONE_TOP_NAV_ITEMS' ) ) {
    define( 'SDW_HEADER_ONE_TOP_NAV_ITEMS', [] );
}

if ( ! defined( 'SDW_HEADER_ONE_SOCIAL_ITEMS' ) ) {
    define( 'SDW_HEADER_ONE_SOCIAL_ITEMS', [] );
}

if ( ! defined( 'SDW_DEFAULT_PAGE_HEADER_LAYOUT' ) ) {
    define( 'SDW_DEFAULT_PAGE_HEADER_LAYOUT', 'layout-one' );
}

if ( ! defined( 'SDW_DEFAULT_PAGE_HEADER_IMAGE' ) ) {
    define( 'SDW_DEFAULT_PAGE_HEADER_IMAGE', SDWEDDINGDIRECTORY_THEME_MEDIA . 'banner-bg.jpg' );
}

if ( ! defined( 'SDW_DEFAULT_PAGE_HEADER_BG_COLOR' ) ) {
    define( 'SDW_DEFAULT_PAGE_HEADER_BG_COLOR', 'rgba(0, 0, 0, 0.4)' );
}

if ( ! defined( 'SDW_DEFAULT_FOOTER_COLUMNS' ) ) {
    define( 'SDW_DEFAULT_FOOTER_COLUMNS', 'column_4' );
}

if ( ! defined( 'SDW_FOOTER_COPYRIGHT_TEXT' ) ) {
    define( 'SDW_FOOTER_COPYRIGHT_TEXT', 'SD Wedding Directory' );
}

if ( ! defined( 'SDW_404_TITLE' ) ) {
    define( 'SDW_404_TITLE', 'Opps! Looks like the page is gone.' );
}

if ( ! defined( 'SDW_404_DESCRIPTION' ) ) {
    define( 'SDW_404_DESCRIPTION', 'The link is broken or the page has been moved. Try these pages instead:' );
}

if ( ! defined( 'SDW_404_BUTTONS' ) ) {
    define( 'SDW_404_BUTTONS', [
        [
            'title' => 'Vendors',
            'name'  => 'Vendors',
            'link'  => '/',
        ],
        [
            'title' => 'About us',
            'name'  => 'About us',
            'link'  => '/',
        ],
        [
            'title' => 'Contact us',
            'name'  => 'Contact us',
            'link'  => '/',
        ],
        [
            'title' => 'Real Weddings',
            'name'  => 'Real Weddings',
            'link'  => '/',
        ],
    ] );
}

if ( ! defined( 'SDW_DEFAULT_404_IMAGE' ) ) {
    define( 'SDW_DEFAULT_404_IMAGE', 'assets/images/404-error-page/404_error.svg' );
}

add_filter( 'sdweddingdirectory/placeholder', function( $placeholders = [] ) {
    $defaults = [
        'vendor-post' => esc_url( get_theme_file_uri( SDW_VENDOR_PLACEHOLDER ) ),
        'venue-post'  => esc_url( get_theme_file_uri( SDW_VENUE_PLACEHOLDER ) ),
    ];

    return array_merge( $defaults, $placeholders );
} );

add_filter( 'sdweddingdirectory/placeholder/modal-popup', function( $placeholders = [] ) {
    $defaults = [
        'couple-login-register-popup' => esc_url( get_theme_file_uri( 'assets/images/placeholders/modal-popup/couple-login-register.jpg' ) ),
        'vendor-login-register-popup' => esc_url( get_theme_file_uri( 'assets/images/placeholders/modal-popup/vendor-login-register.jpg' ) ),
        'forgot-password-popup'       => esc_url( get_theme_file_uri( 'assets/images/placeholders/modal-popup/forgot-password.jpg' ) ),
    ];

    return array_merge( $defaults, $placeholders );
} );
