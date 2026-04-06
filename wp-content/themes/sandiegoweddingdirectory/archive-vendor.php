<?php
/**
 * Archive: Vendors
 */

get_header();

get_template_part(
    'template-parts/vendors/listing',
    null,
    [
        'title'       => __( 'Wedding Vendors', 'sdweddingdirectory-v2' ),
        'desc'        => __( 'Browse wedding professionals across San Diego County and filter the directory by category, services, style, and specialties.', 'sdweddingdirectory-v2' ),
        'breadcrumbs' => [
            [ 'label' => __( 'Home', 'sdweddingdirectory-v2' ), 'url' => home_url( '/' ) ],
            [ 'label' => __( 'Vendors', 'sdweddingdirectory-v2' ), 'url' => '' ],
        ],
        'current_url' => get_post_type_archive_link( 'vendor' ),
    ]
);

get_footer();
