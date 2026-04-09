<?php
/**
 * SDWD Core — Custom Post Types
 *
 * Couple, Vendor, and Venue are registered with parallel structure.
 * All allow admin creation. Vendors and Venues are publicly queryable.
 * Couple posts are admin-only (not public profiles).
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'sdwd_register_post_types', 8 );

function sdwd_register_post_types() {

    // ── Couple ──────────────────────────────────────────────

    register_post_type( 'couple', [
        'labels' => [
            'name'               => __( 'Couples', 'sdwd-core' ),
            'singular_name'      => __( 'Couple', 'sdwd-core' ),
            'add_new'            => __( 'Add New Couple', 'sdwd-core' ),
            'add_new_item'       => __( 'Add New Couple', 'sdwd-core' ),
            'edit_item'          => __( 'Edit Couple', 'sdwd-core' ),
            'new_item'           => __( 'New Couple', 'sdwd-core' ),
            'view_item'          => __( 'View Couple', 'sdwd-core' ),
            'search_items'       => __( 'Search Couples', 'sdwd-core' ),
            'not_found'          => __( 'No couples found', 'sdwd-core' ),
            'not_found_in_trash' => __( 'No couples found in Trash', 'sdwd-core' ),
            'all_items'          => __( 'All Couples', 'sdwd-core' ),
            'menu_name'          => __( 'Couples', 'sdwd-core' ),
        ],
        'public'              => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'menu_position'       => 24,
        'menu_icon'           => 'dashicons-heart',
        'has_archive'         => false,
        'publicly_queryable'  => false,
        'exclude_from_search' => true,
        'supports'            => [ 'title', 'thumbnail' ],
        'capability_type'     => 'post',
        'map_meta_cap'        => true,
        'show_in_rest'        => false,
    ] );

    // ── Vendor ──────────────────────────────────────────────

    register_post_type( 'vendor', [
        'labels' => [
            'name'               => __( 'Vendors', 'sdwd-core' ),
            'singular_name'      => __( 'Vendor', 'sdwd-core' ),
            'add_new'            => __( 'Add New Vendor', 'sdwd-core' ),
            'add_new_item'       => __( 'Add New Vendor', 'sdwd-core' ),
            'edit_item'          => __( 'Edit Vendor', 'sdwd-core' ),
            'new_item'           => __( 'New Vendor', 'sdwd-core' ),
            'view_item'          => __( 'View Vendor', 'sdwd-core' ),
            'search_items'       => __( 'Search Vendors', 'sdwd-core' ),
            'not_found'          => __( 'No vendors found', 'sdwd-core' ),
            'not_found_in_trash' => __( 'No vendors found in Trash', 'sdwd-core' ),
            'all_items'          => __( 'All Vendors', 'sdwd-core' ),
            'menu_name'          => __( 'Vendors', 'sdwd-core' ),
        ],
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'menu_position'       => 25,
        'menu_icon'           => 'dashicons-store',
        'has_archive'         => false,
        'publicly_queryable'  => true,
        'exclude_from_search' => false,
        'supports'            => [ 'title', 'editor', 'thumbnail' ],
        'capability_type'     => 'post',
        'map_meta_cap'        => true,
        'rewrite'             => [ 'slug' => 'vendor', 'with_front' => false ],
        'show_in_rest'        => false,
    ] );

    // ── Venue ───────────────────────────────────────────────

    register_post_type( 'venue', [
        'labels' => [
            'name'               => __( 'Venues', 'sdwd-core' ),
            'singular_name'      => __( 'Venue', 'sdwd-core' ),
            'add_new'            => __( 'Add New Venue', 'sdwd-core' ),
            'add_new_item'       => __( 'Add New Venue', 'sdwd-core' ),
            'edit_item'          => __( 'Edit Venue', 'sdwd-core' ),
            'new_item'           => __( 'New Venue', 'sdwd-core' ),
            'view_item'          => __( 'View Venue', 'sdwd-core' ),
            'search_items'       => __( 'Search Venues', 'sdwd-core' ),
            'not_found'          => __( 'No venues found', 'sdwd-core' ),
            'not_found_in_trash' => __( 'No venues found in Trash', 'sdwd-core' ),
            'all_items'          => __( 'All Venues', 'sdwd-core' ),
            'menu_name'          => __( 'Venues', 'sdwd-core' ),
        ],
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'menu_position'       => 26,
        'menu_icon'           => 'dashicons-location',
        'has_archive'         => false,
        'publicly_queryable'  => true,
        'exclude_from_search' => false,
        'supports'            => [ 'title', 'editor', 'thumbnail' ],
        'capability_type'     => 'post',
        'map_meta_cap'        => true,
        'rewrite'             => [ 'slug' => 'venue', 'with_front' => false ],
        'show_in_rest'        => false,
    ] );
}

/**
 * Strip post passwords from vendor/venue posts on save.
 * Prevents accidental password-protection of public profiles.
 */
add_filter( 'wp_insert_post_data', function ( $data ) {
    if ( in_array( $data['post_type'] ?? '', [ 'vendor', 'venue' ], true ) ) {
        $data['post_password'] = '';
    }
    return $data;
} );
