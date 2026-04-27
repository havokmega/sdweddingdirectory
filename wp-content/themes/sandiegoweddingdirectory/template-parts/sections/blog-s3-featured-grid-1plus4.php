<?php
/**
 * Blog: Featured grid — 1 large card + 2x2 small cards (5 most recent posts).
 */

$query = new WP_Query( [
    'post_type'           => 'post',
    'posts_per_page'      => 5,
    'ignore_sticky_posts' => false,
    'no_found_rows'       => true,
] );

if ( ! $query->have_posts() ) {
    wp_reset_postdata();
    return;
}

$posts = $query->posts;
$hero  = array_shift( $posts );
?>
<section class="blog-s3-featured-grid-1plus4 section" aria-label="<?php esc_attr_e( 'Featured articles', 'sandiegoweddingdirectory' ); ?>">
    <div class="container">
        <div class="blog-s3-featured-grid-1plus4__header">
            <h2 class="blog-s3-featured-grid-1plus4__heading"><?php esc_html_e( "What's new", 'sandiegoweddingdirectory' ); ?></h2>
        </div>

        <div class="blog-s3-featured-grid-1plus4__grid">
            <?php
            $cats     = get_the_category( $hero->ID );
            $cat      = ! empty( $cats ) ? $cats[0] : null;
            $thumb    = get_the_post_thumbnail_url( $hero->ID, 'large' );
            ?>
            <a class="blog-s3-featured-grid-1plus4__hero" href="<?php echo esc_url( get_permalink( $hero->ID ) ); ?>">
                <?php if ( $thumb ) : ?>
                    <span class="blog-s3-featured-grid-1plus4__hero-media" style="--blog-hero-bg: url('<?php echo esc_url( $thumb ); ?>');" aria-hidden="true"></span>
                <?php endif; ?>
                <span class="blog-s3-featured-grid-1plus4__hero-body">
                    <?php if ( $cat ) : ?>
                        <span class="blog-s3-featured-grid-1plus4__hero-category"><?php echo esc_html( $cat->name ); ?></span>
                    <?php endif; ?>
                    <span class="blog-s3-featured-grid-1plus4__hero-title"><?php echo esc_html( get_the_title( $hero->ID ) ); ?></span>
                </span>
            </a>

            <ul class="blog-s3-featured-grid-1plus4__small-list">
                <?php foreach ( $posts as $post_obj ) :
                    $small_cats  = get_the_category( $post_obj->ID );
                    $small_cat   = ! empty( $small_cats ) ? $small_cats[0] : null;
                    $small_thumb = get_the_post_thumbnail_url( $post_obj->ID, 'medium' );
                    ?>
                    <li class="blog-s3-featured-grid-1plus4__small-item">
                        <a class="blog-s3-featured-grid-1plus4__small-card" href="<?php echo esc_url( get_permalink( $post_obj->ID ) ); ?>">
                            <?php if ( $small_thumb ) : ?>
                                <span class="blog-s3-featured-grid-1plus4__small-media" style="--blog-small-bg: url('<?php echo esc_url( $small_thumb ); ?>');" aria-hidden="true"></span>
                            <?php endif; ?>
                            <span class="blog-s3-featured-grid-1plus4__small-body">
                                <?php if ( $small_cat ) : ?>
                                    <span class="blog-s3-featured-grid-1plus4__small-category"><?php echo esc_html( $small_cat->name ); ?></span>
                                <?php endif; ?>
                                <span class="blog-s3-featured-grid-1plus4__small-title"><?php echo esc_html( get_the_title( $post_obj->ID ) ); ?></span>
                            </span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</section>
<?php wp_reset_postdata(); ?>
