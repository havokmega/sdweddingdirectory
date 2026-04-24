<?php
/**
 * Taxonomy: Venue Type
 */

get_header();

$term = get_queried_object();
$term_link = $term instanceof WP_Term ? get_term_link( $term ) : sdwdv2_get_venues_url();

if ( is_wp_error( $term_link ) ) {
    $term_link = sdwdv2_get_venues_url();
}

get_template_part( 'template-parts/venues/listing', null, [
    'title'             => single_term_title( '', false ),
    'desc'              => term_description(),
    'breadcrumbs'       => [
        [ 'label' => __( 'Home', 'sandiegoweddingdirectory' ), 'url' => home_url( '/' ) ],
        [ 'label' => __( 'Venues', 'sandiegoweddingdirectory' ), 'url' => sdwdv2_get_venues_url() ],
        [ 'label' => single_term_title( '', false ), 'url' => '' ],
    ],
    'current_url'       => $term_link,
    'fixed_category_id' => $term instanceof WP_Term ? $term->term_id : 0,
] );

get_footer();
