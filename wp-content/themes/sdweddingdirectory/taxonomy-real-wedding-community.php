<?php

global $post, $wp_query;

/**
 *  Container Start
 *  ---------------
 */
do_action( 'sdweddingdirectory_main_container' );

/**
 *  Make sure core plugin activated
 *  -------------------------------
 */
if( class_exists( 'SDWeddingDirectory_Loader' ) ){

    /**
     *  Extract
     *  -------
     */
    extract( (array) get_queried_object() );

    /**
     *  Get Post IDs
     *  ------------
     */
    $post_ids   =   apply_filters( 'sdweddingdirectory/post/data', [

                        'post_type'     =>  esc_attr( 'real-wedding' ),

                        'tax_query'     =>  [
                                                [   'taxonomy'  =>  $taxonomy,

                                                    'terms'     =>  $term_id,
                                                ]
                                            ]

                    ] );

    /**
     *  Have Post Ids ?
     *  ---------------
     */
    if( SDWeddingDirectory_Loader:: _is_array( $post_ids ) ){

        /**
         *  Load Post
         *  ---------
         */
        printf( '<div class="row row-cols-xxl-4 row-cols-xl-4 row-cols-lg-3 row-cols-md-2 row-cols-sm-2 row-cols-1">%1$s</div>', 

            /**
             *  Load Post
             *  ---------
             */
            class_exists( 'SDWeddingDirectory_Shortcode_RealWedding_Post' )

            ? SDWeddingDirectory_Shortcode_RealWedding_Post::page_builder( [

                    'layout'            =>  absint( '1' ),

                    'style'             =>  absint( '1' ),

                    'post_ids'          =>  implode( ',', array_keys( $post_ids ) ),

                    'posts_per_page'    =>  absint( '12' ),

                    'pagination'        =>  esc_attr( 'false' ),
                ] )

            : ''
        );
    }

    /**
     *  Empty Article
     *  -------------
     */
    else{

        /**
         *  Article Not Found!
         *  ------------------
         */
        do_action( 'sdweddingdirectory_empty_article' );
    }
}

/**
 *  Empty Article
 *  -------------
 */
else{

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
do_action( 'sdweddingdirectory_main_container_end' );
