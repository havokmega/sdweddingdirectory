<?php
/**
 * Archive: Wedding Websites
 *
 * Simple card grid for wedding website posts.
 */

get_header();

get_template_part( 'template-parts/components/page-header', null, [
    'title'       => __( 'Wedding Websites', 'sdweddingdirectory-v2' ),
    'breadcrumbs' => [
        [ 'label' => __( 'Home', 'sdweddingdirectory-v2' ), 'url' => home_url( '/' ) ],
        [ 'label' => __( 'Wedding Websites', 'sdweddingdirectory-v2' ), 'url' => '' ],
    ],
] );
?>

<section class="section">
    <div class="container">
        <?php if ( have_posts() ) : ?>
            <div class="grid grid--4col">
                <?php
                while ( have_posts() ) :
                    the_post();
                    ?>
                    <a class="card" href="<?php the_permalink(); ?>">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="card__media">
                                <?php the_post_thumbnail( 'medium', [ 'class' => 'card__image' ] ); ?>
                            </div>
                        <?php endif; ?>
                        <div class="card__body">
                            <h3 class="card__title"><?php the_title(); ?></h3>
                        </div>
                    </a>
                <?php endwhile; ?>
            </div>

            <?php
            get_template_part( 'template-parts/components/pagination', null, [] );
            ?>
        <?php else : ?>
            <p><?php esc_html_e( 'No wedding websites found.', 'sdweddingdirectory-v2' ); ?></p>
        <?php endif; ?>
    </div>
</section>

<?php
get_footer();
