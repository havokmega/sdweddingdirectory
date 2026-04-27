<?php
/**
 * Blog: Article grid (2-col) + sticky sidebar + pagination.
 *
 * Pulls a paginated list of recent posts. Skips the 5 newest already shown in
 * the featured grid above so this section continues the feed.
 */

$paged = max( 1, absint( get_query_var( 'paged' ) ?: get_query_var( 'page' ) ) );

$query = new WP_Query( [
    'post_type'      => 'post',
    'posts_per_page' => 8,
    'paged'          => $paged,
    'offset'         => $paged === 1 ? 5 : ( 5 + ( $paged - 1 ) * 8 ),
] );

$popular_cats = get_categories( [
    'hide_empty' => true,
    'orderby'    => 'count',
    'order'      => 'DESC',
    'number'     => 8,
] );
?>
<section class="blog-s4-article-grid-sidebar section">
    <div class="container blog-s4-article-grid-sidebar__inner">
        <div class="blog-s4-article-grid-sidebar__main">
            <div class="blog-s4-article-grid-sidebar__header">
                <h2 class="blog-s4-article-grid-sidebar__heading"><?php esc_html_e( 'Latest articles', 'sandiegoweddingdirectory' ); ?></h2>
            </div>

            <?php if ( $query->have_posts() ) : ?>
                <div class="blog-s4-article-grid-sidebar__grid">
                    <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                        <?php get_template_part( 'template-parts/components/post-card', null, [
                            'post_id' => get_the_ID(),
                        ] ); ?>
                    <?php endwhile; ?>
                </div>

                <?php if ( $query->max_num_pages > 1 ) : ?>
                    <nav class="blog-s4-article-grid-sidebar__pagination" aria-label="<?php esc_attr_e( 'Articles pagination', 'sandiegoweddingdirectory' ); ?>">
                        <?php
                        echo paginate_links( [
                            'total'     => $query->max_num_pages,
                            'current'   => $paged,
                            'mid_size'  => 1,
                            'prev_text' => esc_html__( 'Previous', 'sandiegoweddingdirectory' ),
                            'next_text' => esc_html__( 'Next', 'sandiegoweddingdirectory' ),
                        ] );
                        ?>
                    </nav>
                <?php endif; ?>
            <?php else : ?>
                <p><?php esc_html_e( 'No articles yet — check back soon.', 'sandiegoweddingdirectory' ); ?></p>
            <?php endif; ?>
        </div>

        <aside class="blog-s4-article-grid-sidebar__sidebar">
            <div class="blog-s4-article-grid-sidebar__widget">
                <h3 class="blog-s4-article-grid-sidebar__widget-title"><?php esc_html_e( 'Popular topics', 'sandiegoweddingdirectory' ); ?></h3>
                <ul class="blog-s4-article-grid-sidebar__cat-list">
                    <?php foreach ( $popular_cats as $cat ) : ?>
                        <li>
                            <a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>">
                                <span><?php echo esc_html( $cat->name ); ?></span>
                                <span class="blog-s4-article-grid-sidebar__cat-count"><?php echo esc_html( $cat->count ); ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="blog-s4-article-grid-sidebar__widget blog-s4-article-grid-sidebar__widget--cta">
                <h3 class="blog-s4-article-grid-sidebar__widget-title"><?php esc_html_e( 'Plan smarter', 'sandiegoweddingdirectory' ); ?></h3>
                <p><?php esc_html_e( 'Open the free San Diego Wedding Directory dashboard for checklists, budget, and your registry — all in one place.', 'sandiegoweddingdirectory' ); ?></p>
                <a class="btn btn--primary" href="<?php echo esc_url( home_url( '/dashboard/' ) ); ?>"><?php esc_html_e( 'Open dashboard', 'sandiegoweddingdirectory' ); ?></a>
            </div>
        </aside>
    </div>
</section>
<?php wp_reset_postdata(); ?>
