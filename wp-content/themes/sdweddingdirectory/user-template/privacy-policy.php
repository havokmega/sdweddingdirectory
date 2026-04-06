<?php
/**
 *   Template name: Privacy Policy
 *   -------------------------------
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
    the_content();

/**
 *  Container End
 *  -------------
 */
do_action( 'sdweddingdirectory_main_container_end' ); ?>