<?php
/**
 *  SDWeddingDirectory - Team Page
 *  -----------------------------
 */
global $wp_query;

$paged = get_query_var( 'paged' )
    ? absint( get_query_var( 'paged' ) )
    : absint( '1' );

do_action( 'sdweddingdirectory_main_container' );
?>

<div class="row">
    <div class="col-12 mb-4">
        <div class="section-title col text-start">
            <h1>Our Team</h1>
        </div>
    </div>
</div>

<?php
if ( class_exists( 'SDWeddingDirectory_Shortcode_Team' ) ) {

    $team_query = new WP_Query(
        [
            'post_type'      => esc_attr( 'team' ),
            'post_status'    => esc_attr( 'publish' ),
            'posts_per_page' => -1,
            'orderby'        => esc_attr( 'menu_order' ),
            'order'          => esc_attr( 'ASC' ),
        ]
    );

    if ( $team_query->have_posts() ) {

        echo '<div class="row row-cols-xxl-4 row-cols-xl-4 row-cols-lg-4 row-cols-md-3 row-cols-sm-2 row-cols-1 g-4">';

        while ( $team_query->have_posts() ) {
            $team_query->the_post();

            echo SDWeddingDirectory_Shortcode_Team::page_builder(
                [
                    'layout'  => absint( '1' ),
                    'post_id' => absint( get_the_ID() ),
                    'style'   => esc_attr( '1' ),
                ]
            );
        }

        echo '</div>';

        wp_reset_postdata();
    }
}

if ( isset( $wp_query ) ) {
    print apply_filters(
        'sdweddingdirectory/pagination',
        [
            'numpages' => absint( $wp_query->max_num_pages ),
            'paged'    => absint( $paged ),
        ]
    );
}

do_action( 'sdweddingdirectory_main_container_end' );
