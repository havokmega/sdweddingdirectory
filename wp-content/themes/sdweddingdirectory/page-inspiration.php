<?php
/**
 *  SDWeddingDirectory - Inspiration Page
 *  ------------------------------------
 */
global $wp_query;

$paged = get_query_var( 'paged' )
    ? absint( get_query_var( 'paged' ) )
    : absint( '1' );

do_action( 'sdweddingdirectory_main_container' );

if ( have_posts() ) {

    while ( have_posts() ) {
        the_post();

        do_action(
            'sdweddingdirectory_article',
            [
                'layout'  => absint( '1' ),
                'post_id' => absint( get_the_ID() ),
            ]
        );
    }

    if ( isset( $wp_query ) ) {
        wp_reset_postdata();

        print apply_filters(
            'sdweddingdirectory/pagination',
            [
                'numpages' => absint( $wp_query->max_num_pages ),
                'paged'    => absint( $paged ),
            ]
        );
    }
} else {

    do_action( 'sdweddingdirectory_empty_article' );
}

do_action( 'sdweddingdirectory_main_container_end' );

