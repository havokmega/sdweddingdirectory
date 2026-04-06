<?php
/**
 * Single Blog Post Template
 */

get_header();

$categories = get_the_category();
$category   = ! empty( $categories ) ? $categories[0] : null;
$date       = get_the_date();
?>

<article class="blog-single">
    <div class="blog-single__topbar">
        <div class="container blog-single__topbar-row">
            <nav class="blog-single__breadcrumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'sdweddingdirectory' ); ?>">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Weddings', 'sdweddingdirectory' ); ?></a>
                <span aria-hidden="true">&rsaquo;</span>
                <a href="<?php echo esc_url( home_url( '/wedding-inspiration/' ) ); ?>"><?php esc_html_e( 'Inspiration', 'sdweddingdirectory' ); ?></a>
                <?php if ( $category ) : ?>
                    <span aria-hidden="true">&rsaquo;</span>
                    <a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>"><?php echo esc_html( $category->name ); ?></a>
                <?php endif; ?>
                <span aria-hidden="true">&rsaquo;</span>
                <span><?php the_title(); ?></span>
            </nav>
            <?php get_search_form(); ?>
        </div>
    </div>

    <div class="container section">
        <div class="blog-single__intro grid grid--2col">
            <?php if ( has_post_thumbnail() ) : ?>
                <div class="blog-single__media">
                    <?php the_post_thumbnail( 'large', [ 'class' => 'blog-single__image' ] ); ?>
                </div>
            <?php endif; ?>

            <div class="blog-single__meta">
                <?php if ( $category ) : ?>
                    <a class="blog-single__category" href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>"><?php echo esc_html( $category->name ); ?></a>
                <?php endif; ?>
                <h1 class="blog-single__title"><?php the_title(); ?></h1>
                <time class="blog-single__date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( $date ); ?></time>
                <?php if ( has_excerpt() ) : ?>
                    <p class="blog-single__excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
                <?php endif; ?>
            </div>
        </div>

        <div class="blog-single__content">
            <?php
            the_content();

            wp_link_pages( [
                'before' => '<nav class="blog-single__pagination">',
                'after'  => '</nav>',
            ] );
            ?>
        </div>
    </div>
</article>

<?php
get_footer();
