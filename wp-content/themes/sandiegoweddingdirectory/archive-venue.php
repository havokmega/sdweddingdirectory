<?php
/**
 * Archive: Venues
 */

get_header();

get_template_part( 'template-parts/venues/listing', null, [
    'title'       => __( 'Wedding Venues', 'sandiegoweddingdirectory' ),
    'desc'        => __( 'Browse venue profiles across San Diego County and narrow the directory with practical filters.', 'sandiegoweddingdirectory' ),
    'breadcrumbs' => [
        [ 'label' => __( 'Home', 'sandiegoweddingdirectory' ), 'url' => home_url( '/' ) ],
        [ 'label' => __( 'Venues', 'sandiegoweddingdirectory' ), 'url' => '' ],
    ],
    'current_url' => get_post_type_archive_link( 'venue' ),
] );

get_footer();
