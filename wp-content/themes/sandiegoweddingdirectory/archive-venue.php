<?php
/**
 * Archive: Venues
 */

get_header();

get_template_part( 'template-parts/venues/listing', null, [
    'title'       => __( 'Wedding Venues', 'sdweddingdirectory-v2' ),
    'desc'        => __( 'Browse venue profiles across San Diego County and narrow the directory with practical filters.', 'sdweddingdirectory-v2' ),
    'breadcrumbs' => [
        [ 'label' => __( 'Home', 'sdweddingdirectory-v2' ), 'url' => home_url( '/' ) ],
        [ 'label' => __( 'Venues', 'sdweddingdirectory-v2' ), 'url' => '' ],
    ],
    'current_url' => get_post_type_archive_link( 'venue' ),
] );

get_footer();
