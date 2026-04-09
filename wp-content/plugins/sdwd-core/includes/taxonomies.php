<?php
/**
 * SDWD Core — Taxonomies
 *
 * vendor-category  → vendors (Photographer, DJ, Caterer, etc.)
 * venue-type       → venues  (Garden, Hotel, Rooftop, etc.)
 * venue-location   → venues  (San Diego, La Jolla, Carlsbad, etc.)
 *
 * All are admin-managed and predetermined. Not user-editable.
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'sdwd_register_taxonomies', 8 );

function sdwd_register_taxonomies() {

    // ── Vendor Category ─────────────────────────────────────

    register_taxonomy( 'vendor-category', 'vendor', [
        'labels' => [
            'name'          => __( 'Vendor Categories', 'sdwd-core' ),
            'singular_name' => __( 'Category', 'sdwd-core' ),
            'search_items'  => __( 'Search Categories', 'sdwd-core' ),
            'all_items'     => __( 'All Categories', 'sdwd-core' ),
            'edit_item'     => __( 'Edit Category', 'sdwd-core' ),
            'update_item'   => __( 'Update Category', 'sdwd-core' ),
            'add_new_item'  => __( 'Add New Category', 'sdwd-core' ),
            'new_item_name' => __( 'New Category Name', 'sdwd-core' ),
            'menu_name'     => __( 'Categories', 'sdwd-core' ),
        ],
        'hierarchical'      => true,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_in_rest'      => false,
        'rewrite'           => [ 'slug' => 'vendors', 'with_front' => false ],
    ] );

    // ── Venue Type ──────────────────────────────────────────

    register_taxonomy( 'venue-type', 'venue', [
        'labels' => [
            'name'          => __( 'Venue Types', 'sdwd-core' ),
            'singular_name' => __( 'Venue Type', 'sdwd-core' ),
            'search_items'  => __( 'Search Types', 'sdwd-core' ),
            'all_items'     => __( 'All Types', 'sdwd-core' ),
            'edit_item'     => __( 'Edit Type', 'sdwd-core' ),
            'update_item'   => __( 'Update Type', 'sdwd-core' ),
            'add_new_item'  => __( 'Add New Type', 'sdwd-core' ),
            'new_item_name' => __( 'New Type Name', 'sdwd-core' ),
            'menu_name'     => __( 'Types', 'sdwd-core' ),
        ],
        'hierarchical'      => true,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_in_rest'      => false,
        'rewrite'           => [ 'slug' => 'venues/type', 'with_front' => false ],
    ] );

    // ── Venue Location ──────────────────────────────────────

    register_taxonomy( 'venue-location', 'venue', [
        'labels' => [
            'name'          => __( 'Venue Locations', 'sdwd-core' ),
            'singular_name' => __( 'Location', 'sdwd-core' ),
            'search_items'  => __( 'Search Locations', 'sdwd-core' ),
            'all_items'     => __( 'All Locations', 'sdwd-core' ),
            'edit_item'     => __( 'Edit Location', 'sdwd-core' ),
            'update_item'   => __( 'Update Location', 'sdwd-core' ),
            'add_new_item'  => __( 'Add New Location', 'sdwd-core' ),
            'new_item_name' => __( 'New Location Name', 'sdwd-core' ),
            'menu_name'     => __( 'Locations', 'sdwd-core' ),
        ],
        'hierarchical'      => true,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_in_rest'      => false,
        'rewrite'           => [ 'slug' => 'venues', 'with_front' => false ],
    ] );
}
