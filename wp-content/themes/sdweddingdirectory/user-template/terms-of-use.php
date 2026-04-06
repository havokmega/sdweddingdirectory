<?php
/**
 *   Template name: Terms and Condition
 *   ----------------------------------
 */
global $wp_query, $post, $page;

/**
 *  Current Page
 *  ------------
 */
$paged  =   get_query_var( 'paged' ) 

        ?   absint( get_query_var( 'paged' ) ) 

        :   absint( '1' );

/**
 *  Container Start
 *  ---------------
 */
do_action( 'sdweddingdirectory_main_container' );

    /**
     *   Content Here
     *   ------------
     */
    if ( have_posts() ){

        while ( have_posts() ) {

            the_post();

            the_content();
        }

        wp_reset_postdata();
    }

/**
 *  Container End
 *  -------------
 */
do_action( 'sdweddingdirectory_main_container_end' ); ?>
