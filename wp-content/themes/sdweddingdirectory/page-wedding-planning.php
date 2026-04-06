<?php
/*
Template Name: Wedding Planning
*/

/**
 * SDWeddingDirectory - Wedding Planning Page
 * ------------------------------------------
 * FAQ data is now theme-owned PHP. The old Elementor _elementor_data
 * extraction has been removed (Phase 3 — Public Template Ownership).
 */
global $wp_query;

get_header();

get_template_part( 'template-parts/planning/planning-hero' );

do_action( 'sdweddingdirectory_container_start' );

$primary_class = class_exists( 'SDWeddingDirectory_Container' )
    ? join( ' ', SDWeddingDirectory_Container::sdweddingdirectory_get_primary_class( '' ) )
    : 'content-area primary';

printf( '<section id="primary" class="%1$s">', esc_attr( $primary_class ) );

/**
 * Planning FAQ items — theme-owned data.
 * Previously extracted from Elementor _elementor_data post meta.
 */
$accordion_settings = [
    'have_id'                                   => 'sdwd-planning-faq',
    'sdweddingdirectory_group_accordion'         => [
        [
            '_id'                => 'faq-1',
            'accordion_heading'  => esc_html__( 'Are the Wedding Planning Tools free?', 'sdweddingdirectory' ),
            'accordion_content'  => '<p>' . esc_html__( 'Yes. Every planning tool on SDWeddingDirectory is available at no cost. That includes your Wedding Website, Seating Chart, Checklist, Budget tracker, Vendor Manager, countdown tools, and more.', 'sdweddingdirectory' ) . '</p>',
        ],
        [
            '_id'                => 'faq-2',
            'accordion_heading'  => esc_html__( 'Is a Wedding Checklist included?', 'sdweddingdirectory' ),
            'accordion_content'  => '<p>' . esc_html__( 'Absolutely. Your free Wedding Checklist comes preloaded with essential tasks, and you can edit, remove, or add items at any time to match your specific plans.', 'sdweddingdirectory' ) . '</p>',
        ],
        [
            '_id'                => 'faq-3',
            'accordion_heading'  => esc_html__( 'How do I use the planning tools to organize my wedding?', 'sdweddingdirectory' ),
            'accordion_content'  => '<p>' . esc_html__( 'Our tools are designed to guide you step by step. Begin by setting up your Budget, Guest List, and Checklist. Then build your Wedding Website to share event details and stay connected with guests. You can use every tool together or pick the ones that fit your needs.', 'sdweddingdirectory' ) . '</p>',
        ],
    ],
];

get_template_part( 'template-parts/planning/planning-intro' );
get_template_part( 'template-parts/planning/planning-checklist' );
get_template_part( 'template-parts/planning/planning-vendor-organizer' );
get_template_part( 'template-parts/planning/planning-wedding-website' );
get_template_part( 'template-parts/planning/planning-secondary-intro' );
get_template_part( 'template-parts/planning/planning-tool-cards' );
get_template_part( 'template-parts/planning/planning-detailed-copy' );
get_template_part( 'template-parts/planning/planning-faq', null, [
    'accordion_settings' => $accordion_settings,
] );

?></section><?php

do_action( 'sdweddingdirectory_container_end' );

if ( isset( $wp_query ) ) {
    wp_reset_postdata();
}

get_footer();
