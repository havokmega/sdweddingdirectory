<?php
/**
 *  SDWeddingDirectory - Team Member Single
 *  --------------------------------------
 */

global $wp_query, $post, $page;

/**
 *  Container Start
 *  ---------------
 */
do_action( 'sdweddingdirectory_main_container' );

if ( have_posts() ){

    while ( have_posts() ) { the_post();

        $post_id        = absint( get_the_ID() );

        $designation    = get_post_meta( absint( $post_id ), sanitize_key( 'designation' ), true );

        ?>
        <div class="row">

            <div class="col-lg-8 col-md-12">

                <article class="team-single">

                    <?php if ( has_post_thumbnail( $post_id ) ){

                        echo '<div class="mb-4">' . get_the_post_thumbnail( $post_id, 'large', [ 'class' => 'img-fluid rounded' ] ) . '</div>';
                    }
                    ?>

                    <h1 class="mb-2"><?php the_title(); ?></h1>

                    <?php if( ! empty( $designation ) ){ ?>
                        <p class="text-muted mb-4"><?php echo esc_attr( $designation ); ?></p>
                    <?php } ?>

                    <div class="team-content">
                        <?php the_content(); ?>
                    </div>

                </article>

            </div>

            <div class="col-lg-4 col-md-12">

                <aside class="singular-team-wrap">

                    <h3 class="widget-title mb-3"><?php esc_attr_e( 'More Team Members', 'sdweddingdirectory' ); ?></h3>

                    <?php
                    $team_query = new WP_Query( [

                        'post_type'         =>  esc_attr( 'team' ),

                        'post_status'       =>  esc_attr( 'publish' ),

                        'posts_per_page'    =>  -1,

                        'orderby'           =>  esc_attr( 'title' ),

                        'order'             =>  esc_attr( 'ASC' ),

                        'post__not_in'      =>  [],

                    ] );

                    if( $team_query->have_posts() ){

                        while ( $team_query->have_posts() ){ $team_query->the_post();

                            $team_id        = absint( get_the_ID() );

                            $team_title     = get_the_title( $team_id );

                            $team_role      = get_post_meta( absint( $team_id ), sanitize_key( 'designation' ), true );

                            $thumb_url      = get_the_post_thumbnail_url( $team_id, 'thumbnail' );

                            if( empty( $thumb_url ) && class_exists( 'SDWeddingDirectory' ) ){

                                $thumb_url = SDWeddingDirectory:: placeholder( 'team' );
                            }

                            ?>
                            <div class="team-item">

                                <?php if( ! empty( $thumb_url ) ){ ?>
                                    <div class="thumb">
                                        <img loading="lazy" src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( $team_title ); ?>">
                                    </div>
                                <?php } ?>

                                <div class="content">

                                    <?php if( $team_id === $post_id ){ ?>
                                        <h3><strong><?php echo esc_attr( $team_title ); ?></strong></h3>
                                    <?php }else{ ?>
                                        <h3><a href="<?php echo esc_url( get_permalink( $team_id ) ); ?>"><?php echo esc_attr( $team_title ); ?></a></h3>
                                    <?php } ?>

                                    <?php if( ! empty( $team_role ) ){ ?>
                                        <h5><?php echo esc_attr( $team_role ); ?></h5>
                                    <?php } ?>

                                </div>

                            </div>
                            <?php
                        }

                        wp_reset_postdata();
                    }
                    ?>

                </aside>

            </div>

        </div>
        <?php
    }

    if( isset( $wp_query ) ){

        wp_reset_postdata();
    }

}else{

    do_action( 'sdweddingdirectory_empty_article' );
}

/**
 *  Container End
 *  -------------
 */
do_action( 'sdweddingdirectory_main_container_end' );
