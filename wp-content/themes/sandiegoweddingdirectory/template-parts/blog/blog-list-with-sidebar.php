<?php
/**
 * Blog: List with Sidebar Layout
 *
 * Main content area with post list + sidebar.
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
?>
<div class="blog-list-sidebar grid grid--2col">
    <div class="blog-list-sidebar__main">
        <?php if ( $query->have_posts() ) : ?>
            <div class="blog-list-sidebar__posts">
                <?php
                while ( $query->have_posts() ) :
                    $query->the_post();

                    $categories = get_the_category();
                    $category   = ! empty( $categories ) ? $categories[0] : null;
                ?>
                    <article class="blog-list-item">
                        <a class="blog-list-item__link" href="<?php the_permalink(); ?>">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <div class="blog-list-item__media">
                                    <?php the_post_thumbnail( 'medium', [ 'class' => 'blog-list-item__image' ] ); ?>
                                </div>
                            <?php endif; ?>
                            <div class="blog-list-item__body">
                                <?php if ( $category ) : ?>
                                    <span class="blog-list-item__category"><?php echo esc_html( $category->name ); ?></span>
                                <?php endif; ?>
                                <h2 class="blog-list-item__title"><?php the_title(); ?></h2>
                                <time class="blog-list-item__date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                                    <?php echo esc_html( get_the_date() ); ?>
                                </time>
                                <?php if ( has_excerpt() ) : ?>
                                    <p class="blog-list-item__excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
                                <?php endif; ?>
                            </div>
                        </a>
                    </article>
                <?php endwhile; ?>
            </div>

            <?php
            get_template_part( 'template-parts/components/pagination', null, [
                'query' => $query,
            ] );
            ?>
        <?php else : ?>
            <p><?php esc_html_e( 'No posts found.', 'sandiegoweddingdirectory' ); ?></p>
        <?php endif; ?>
        <?php wp_reset_postdata(); ?>
    </div>

    <?php get_sidebar(); ?>
</div>
