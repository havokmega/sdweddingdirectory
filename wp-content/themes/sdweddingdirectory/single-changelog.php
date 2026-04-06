<?php
/**
 *  [ Change Log ] - Singular Page
 *  ------------------------------
 */
global $wp_query, $post, $page;

/**
 *  SDWeddingDirectory - Container Start
 *  ----------------------------
 */
do_action( 'sdweddingdirectory_main_container' );

/**
 *  Have Post ?
 *  -----------
 */
if ( have_posts() ){

    /**
     *  Load Post One By One
     *  --------------------
     */
    while ( have_posts() ) {    the_post();

        /**
         *  2. Load Vendor Singular Page
         *  ----------------------------
         */
        do_action( 'sdweddingdirectory/changelog/detail-page', [

            'post_id'       =>      absint( get_the_ID() )

        ] );
    }

    /**
     *  Reset Query
     *  -----------
     */
    if( isset( $wp_query ) ){

        wp_reset_postdata();
    }

}else{

    /**
     *  Not Found Post
     *  --------------
     */
    do_action( 'sdweddingdirectory_empty_article' );
}

/**
 *  SDWeddingDirectory - Container END
 *  --------------------------
 */
do_action( 'sdweddingdirectory_main_container_end' );