<?php
/**
 * Taxonomy: Vendor Category
 */

get_header();

$term = get_queried_object();
$term_link = $term instanceof WP_Term ? get_term_link( $term ) : sdwdv2_get_vendors_url();

if ( is_wp_error( $term_link ) ) {
    $term_link = sdwdv2_get_vendors_url();
}

get_template_part(
    'template-parts/vendors/listing',
    null,
    [
        'title'             => single_term_title( '', false ),
        'desc'              => term_description(),
        'breadcrumbs'       => [
            [ 'label' => __( 'Home', 'sdweddingdirectory-v2' ), 'url' => home_url( '/' ) ],
            [ 'label' => __( 'Vendors', 'sdweddingdirectory-v2' ), 'url' => sdwdv2_get_vendors_url() ],
            [ 'label' => single_term_title( '', false ), 'url' => '' ],
        ],
        'current_url'       => $term_link,
        'fixed_category_id' => $term instanceof WP_Term ? $term->term_id : 0,
    ]
);

get_footer();
