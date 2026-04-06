<?php
/**
 *    Template Name: Venue Category
 *    -------------------------------
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
     *  Have SDWeddingDirectory - Plugin Activated ?
     *  ------------------------------------
     */
    if( class_exists( 'SDWeddingDirectory_Loader' ) ){

        /**
         *  Create Array
         *  ------------
         */
        $collection             =       [];

        /**
         *  Have Category ?
         *  ---------------
         */
        $parent_collection      =       apply_filters( 'sdweddingdirectory/tax/parent', 'venue-type' );

        /**
         *  Have Data ?
         *  -----------
         */
        if( SDWeddingDirectory:: _is_array( $parent_collection ) ){

            ?><div class="row row-cols-xxl-4 row-cols-xl-4 row-cols-lg-3 row-cols-md-2 row-cols-sm-2 row-cols-1"><?php

            /**
             *  In Loop
             *  -------
             */
            foreach( $parent_collection as $key => $value ){

                /**
                 *  Page Builder : Arguments Pass to Print
                 *  --------------------------------------
                 */
                print class_exists( 'SDWeddingDirectory_Shortcode_Venue_Category' )
                    ? SDWeddingDirectory_Shortcode_Venue_Category::page_builder( [
                        'style'     => absint( '1' ),
                        'post_ids'  => esc_attr( $key ),
                    ] )
                    : '';
            }

            ?></div><?php

        }else{

            /**
             *  Article Not Found!
             *  ------------------
             */
            do_action( 'sdweddingdirectory_empty_article' );
        }

    }else{

        /**
         *  Article Not Found!
         *  ------------------
         */
        do_action( 'sdweddingdirectory_empty_article' );
    }

/**
 *  Container End
 *  -------------
 */
do_action( 'sdweddingdirectory_main_container_end' ); ?>
