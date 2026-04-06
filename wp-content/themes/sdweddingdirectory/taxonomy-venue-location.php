<?php
/**
 *  Global Var
 *  ----------
 */
global $post, $wp_query;

$queried_term = get_queried_object();
$term_args    = [];

if ( $queried_term instanceof WP_Term ) {
    $term_args = [
        'term_id'  => absint( $queried_term->term_id ),
        'taxonomy' => sanitize_key( $queried_term->taxonomy ),
    ];
}

/**
 *  Container Start
 *  ---------------
 */
do_action( 'sdweddingdirectory_main_container' );

/**
 *  Taxonomy Page Content
 *  ---------------------
 */
do_action( 'sdweddingdirectory/term-page/venue-location', $term_args );

/**
 *  Container End
 *  -------------
 */
do_action( 'sdweddingdirectory_main_container_end' );
