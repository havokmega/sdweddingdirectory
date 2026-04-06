<?php
/**
 *  Archive : Vendor Posts
 *  ----------------------
 */
global $wp_query, $post;

/**
 *  Extract
 *  -------
 */
extract( [

    'meta_query'        =>      [],

    'paged'             =>      get_query_var( 'paged' )  ?  absint( get_query_var( 'paged' ) ) :  absint( '1' ),

    'meta_required'     =>      [ 'profile_banner' ],

    'collection'        =>      '',

    'posts_per_page'    =>      apply_filters( 'sdweddingdirectory/vendor/post-per-page', absint( '12' ) )

] );

/**
 *  Have Meta ?
 *  -----------
 */
if(  SDWeddingDirectory:: _is_array( $meta_required )  ){

    foreach( $meta_required as $key => $value ){

        /**
         *  Venue Min Price Filter
         *  ------------------------
         */
        $meta_query[]       =   array(

                                    'key'           =>      esc_attr( $value ),

                                    'compare'       =>      '!=',

                                    'value'         =>      ''
                                );
    }
}

/**
 *  Create Query
 *  ------------
 */
$query      =       new WP_Query( array_merge(

                        /**
                         *  1. Default Args
                         *  ---------------
                         */
                        [   'post_type'         =>  esc_attr( 'vendor' ),

                            'post_status'       =>  esc_attr( 'publish' ),

                            'paged'             =>  $paged
                        ],

                        /**
                         *  2. Have Post Per Page ?
                         *  -----------------------
                         */
                        ! empty( $posts_per_page )

                        ?   [   'posts_per_page'          =>  absint( $posts_per_page )    ]

                        :   [],

                        /**
                         *  3. Have Meta Query ?
                         *  --------------------
                         */
                        SDWeddingDirectory:: _is_array( $meta_query )

                        ?   array(

                                'meta_query'    =>  array(

                                    'relation'  =>  'AND',

                                    $meta_query,
                                )
                            )

                        :   []

                    ) );

/**
 *  Container Start
 *  ---------------
 */
do_action( 'sdweddingdirectory_main_container' );

/**
 *  Have Post ?
 *  -----------
 */
if ( $query->have_posts() && class_exists( 'SDWeddingDirectory_Vendor' ) ){

    ?><div class="row row-cols-xxl-4 row-cols-xl-4 row-cols-lg-3 row-cols-md-2 row-cols-sm-2 row-cols-1"><?php

    /**
     *  Load Post One by One
     *  --------------------
     */
    while ( $query->have_posts() ) {   $query->the_post();

        /**
         *  Load Article
         *  ------------
         */
        printf( '<div class="col">%1$s</div>', 

            /**
             *  1. Load Real Wedding Post
             *  -------------------------
             */
            apply_filters( 'sdweddingdirectory/vendor/post', [

                'post_id'   =>  absint( get_the_ID() ),

                'layout'    =>  absint( '1' )
            ] )
        );
    }

    /**
     *  Have Pagination ?
     *  -----------------
     */
    if( absint( $query->max_num_pages ) >= absint( '2' ) ){

        /**
         *  Create Pagination
         *  -----------------
         */
        print       apply_filters( 'sdweddingdirectory/pagination', [

                        'numpages'      =>  absint( $query->max_num_pages ),

                        'paged'         =>  absint( $paged )

                    ] );
    }

    ?></div><?php

    /**
     *  Reset WP Query
     *  --------------
     */
    if( isset( $query ) ){

        wp_reset_postdata();    
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