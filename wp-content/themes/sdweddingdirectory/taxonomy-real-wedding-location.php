<?php
/**
 *  Global Var
 *  ----------
 */
global $post, $wp_query;

/**
 *  Container Start
 *  ---------------
 */
do_action( 'sdweddingdirectory_main_container' );

/**
 *  Load Taxonomy Post
 *  ------------------
 */
if( class_exists( 'SDWeddingDirectory_Loader' ) ){

    /**
     *  Taxonomy Slug
     *  -------------
     */
    $slug     =     esc_attr( 'real-wedding-location' );

    $obj      =     get_term_by(

                        /**
                         *  1. Venue Name
                         *  ---------------
                         */
                        esc_attr( 'name' ),

                        /**
                         *  2. Get Title
                         *  ------------
                         */
                        single_cat_title( '', false ), 

                        /**
                         *  3. Categroy Slug
                         *  ----------------
                         */
                        esc_attr( $slug ) 
                    );
    /**
     *  Category ID
     *  -----------
     */
    $term_id    =   absint ( $obj->term_id );

    /**
     *  Have Child Taxonomy ?
     *  ---------------------
     */
    $_have_child    =   get_term_children( $term_id, $slug );

    $terms          =   get_terms( $slug, array(

                            'hide_empty'    => false,

                            'orderby'       => 'name',

                            'order'         => 'ASC',

                            'parent'        => ''

                        ) );

    /**
     *   Have Term ?
     *   -----------
     */
    $args   =   [];

    if( SDWeddingDirectory_Loader:: _is_array( $terms ) ){

        foreach( $terms as $key ){

            if( $key->parent == $obj->term_id && $key->count >= absint( '1' ) ){

                $args[] =  absint( $key->term_id );
            }
        }
    }

    /**
     *  Is Array ?
     *  ----------
     */
    if( \SDWeddingDirectory_Loader:: _is_array( $args ) ){

        /**
         *  Load Post
         *  ---------
         */
        printf( '<div class="row row-cols-xxl-4 row-cols-xl-4 row-cols-lg-3 row-cols-md-2 row-cols-sm-2 row-cols-1">%1$s</div>', 

            /**
             *  Load Post
             *  ---------
             */
            class_exists( 'SDWeddingDirectory_Shortcode_Real_Wedding_Location' )

            ? SDWeddingDirectory_Shortcode_Real_Wedding_Location::page_builder( [

                    'style'     =>  absint( '1' ),

                    'post_ids'  =>  implode( ',', $args ),

                    'layout'    =>  absint( '1' ),
                ] )

            : ''
        );
    }

    /**
     *  It's last depth child
     *  ---------------------
     */
    else{

        /**
         *  Get Post IDs
         *  ------------
         */
        $_post_ids      =   apply_filters( 'sdweddingdirectory/post/data', [

                                'post_type'     =>  esc_attr( 'real-wedding' ),

                                'tax_query'     =>  [

                                    [
                                        'taxonomy'  =>  esc_attr( $slug ) ,

                                        'field'     =>  esc_attr( 'id' ),

                                        'terms'     =>  absint ( $term_id )
                                    ]
                                ]

                            ] );


        /**
         *  Have Post Ids ?
         *  ---------------
         */
        if( SDWeddingDirectory_Loader:: _is_array( $_post_ids ) ){

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

                        'post_ids'          =>  implode( ',', array_keys( $_post_ids ) ),

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
}

/**
 *  Container End
 *  -------------
 */
do_action( 'sdweddingdirectory_main_container_end' );
