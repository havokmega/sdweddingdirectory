<?php
/**
 * Blog: 5-Card Grid Layout
 *
 * 1 large featured card + 4 smaller cards.
 * Expects to be called inside a loop or with a custom query.
 *
 * @param array $args {
 *     @type WP_Query $query Optional. Custom query. Defaults to global query.
 * }
 */

$query = $args['query'] ?? null;

if ( ! $query ) {
    global $wp_query;
    $query = $wp_query;
}

if ( ! $query->have_posts() ) {
    return;
}

$count = 0;
?>
<div class="blog-grid-5">
    <?php
    while ( $query->have_posts() ) :
        $query->the_post();
        $count++;

        if ( 1 === $count ) :
            // Large featured card
            $categories = get_the_category();
            $category   = ! empty( $categories ) ? $categories[0] : null;
    ?>
            <a class="blog-grid-5__featured" href="<?php the_permalink(); ?>">
                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="blog-grid-5__featured-media">
                        <?php the_post_thumbnail( 'large', [ 'class' => 'blog-grid-5__featured-image' ] ); ?>
                    </div>
                <?php endif; ?>
                <div class="blog-grid-5__featured-body">
                    <?php if ( $category ) : ?>
                        <span class="blog-grid-5__category"><?php echo esc_html( $category->name ); ?></span>
                    <?php endif; ?>
                    <h2 class="blog-grid-5__featured-title"><?php the_title(); ?></h2>
                    <?php if ( has_excerpt() ) : ?>
                        <p class="blog-grid-5__featured-excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
                    <?php endif; ?>
                </div>
            </a>

            <div class="blog-grid-5__small-grid grid grid--2col">
    <?php
        elseif ( $count <= 5 ) :
            get_template_part( 'template-parts/components/post-card', null, [
                'post_id' => get_the_ID(),
            ] );
        endif;

        if ( $count === 5 || ( ! $query->have_posts() && $count > 1 ) ) :
    ?>
            </div>
    <?php
        endif;
    endwhile;

    // Close the small grid if we had fewer than 5 posts but more than 1
    if ( $count > 1 && $count < 5 ) :
    ?>
        </div>
    <?php endif; ?>
</div>
<?php
wp_reset_postdata();
