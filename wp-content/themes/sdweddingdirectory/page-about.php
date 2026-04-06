<?php
/**
 *  SDWeddingDirectory - About Page
 *  ------------------------------
 */

/**
 *  Container Start
 *  ---------------
 */
do_action( 'sdweddingdirectory_main_container' );

if( class_exists( 'SDWeddingDirectory_Loader' ) ){

    $team_query = new WP_Query( [

        'post_type'         =>  esc_attr( 'team' ),

        'post_status'       =>  esc_attr( 'publish' ),

        'posts_per_page'    =>  -1,

        'orderby'           =>  esc_attr( 'title' ),

        'order'             =>  esc_attr( 'ASC' ),

    ] );

    if( $team_query->have_posts() ){

        echo '<div class="row"><div class="col-12 text-center mb-4">';
        echo '<h2 class="mb-2">' . esc_attr__( 'Our Team', 'sdweddingdirectory' ) . '</h2>';
        echo '</div></div>';

        echo '<div class="row row-cols-xxl-4 row-cols-xl-4 row-cols-lg-3 row-cols-md-2 row-cols-sm-2 row-cols-1">';

        while ( $team_query->have_posts() ){ $team_query->the_post();

            if( class_exists( 'SDWeddingDirectory_Shortcode_Team' ) ){

                echo SDWeddingDirectory_Shortcode_Team:: page_builder( [

                    'layout'    =>  absint( '1' ),

                    'post_id'   =>  absint( get_the_ID() ),

                    'style'     =>  esc_attr( '1' ),
                ] );
            }
        }

        echo '</div>';

        wp_reset_postdata();
    }
}

/**
 *  Container End
 *  -------------
 */
do_action( 'sdweddingdirectory_main_container_end' );
