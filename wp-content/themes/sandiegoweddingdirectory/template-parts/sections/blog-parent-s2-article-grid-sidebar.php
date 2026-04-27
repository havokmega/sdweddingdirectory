<?php
/**
 * Blog Parent: Article grid + sticky sidebar + pagination.
 *
 * Reads from the main category query (don't run a custom WP_Query — let WP
 * render the natural archive results so pagination works correctly).
 */

$current = get_queried_object();
if ( ! $current || empty( $current->term_id ) ) {
    return;
}

$siblings = [];
if ( $current->parent ) {
    $siblings = get_categories( [
        'parent'     => absint( $current->parent ),
        'hide_empty' => false,
        'orderby'    => 'name',
        'order'      => 'ASC',
        'exclude'    => [ $current->term_id ],
    ] );
}
?>
<section class="blog-parent-s2-article-grid-sidebar section">
    <div class="container blog-parent-s2-article-grid-sidebar__inner">
        <div class="blog-parent-s2-article-grid-sidebar__main">
            <?php if ( have_posts() ) : ?>
                <div class="blog-parent-s2-article-grid-sidebar__grid">
                    <?php while ( have_posts() ) : the_post(); ?>
                        <?php get_template_part( 'template-parts/components/post-card', null, [
                            'post_id' => get_the_ID(),
                        ] ); ?>
                    <?php endwhile; ?>
                </div>

                <?php get_template_part( 'template-parts/components/pagination' ); ?>
            <?php else : ?>
                <p><?php esc_html_e( 'No articles in this category yet — check back soon.', 'sandiegoweddingdirectory' ); ?></p>
            <?php endif; ?>
        </div>

        <aside class="blog-parent-s2-article-grid-sidebar__sidebar">
            <?php if ( ! empty( $siblings ) ) : ?>
                <div class="blog-parent-s2-article-grid-sidebar__widget">
                    <h3 class="blog-parent-s2-article-grid-sidebar__widget-title"><?php esc_html_e( 'More in this section', 'sandiegoweddingdirectory' ); ?></h3>
                    <ul class="blog-parent-s2-article-grid-sidebar__sib-list">
                        <?php foreach ( $siblings as $sib ) : ?>
                            <li>
                                <a href="<?php echo esc_url( get_category_link( $sib->term_id ) ); ?>">
                                    <?php echo esc_html( $sib->name ); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="blog-parent-s2-article-grid-sidebar__widget blog-parent-s2-article-grid-sidebar__widget--cta">
                <h3 class="blog-parent-s2-article-grid-sidebar__widget-title"><?php esc_html_e( 'Plan with confidence', 'sandiegoweddingdirectory' ); ?></h3>
                <p><?php esc_html_e( 'Free San Diego planning tools — checklists, budget, registry, and more.', 'sandiegoweddingdirectory' ); ?></p>
                <a class="btn btn--primary" href="<?php echo esc_url( home_url( '/wedding-planning/' ) ); ?>"><?php esc_html_e( 'See planning tools', 'sandiegoweddingdirectory' ); ?></a>
            </div>
        </aside>
    </div>
</section>
