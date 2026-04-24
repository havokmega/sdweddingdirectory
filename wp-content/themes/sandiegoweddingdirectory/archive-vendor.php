<?php
/**
 * Archive: Vendors
 */

get_header();

get_template_part(
    'template-parts/vendors/listing',
    null,
    [
        'title'       => __( 'Wedding Vendors', 'sandiegoweddingdirectory' ),
        'desc'        => __( 'Browse wedding professionals across San Diego County and filter the directory by category, services, style, and specialties.', 'sandiegoweddingdirectory' ),
        'breadcrumbs' => [
            [ 'label' => __( 'Home', 'sandiegoweddingdirectory' ), 'url' => home_url( '/' ) ],
            [ 'label' => __( 'Vendors', 'sandiegoweddingdirectory' ), 'url' => '' ],
        ],
        'current_url' => get_post_type_archive_link( 'vendor' ),
    ]
);

get_footer();
