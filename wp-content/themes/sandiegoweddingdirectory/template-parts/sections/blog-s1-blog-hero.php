<?php
/**
 * Blog: Hero — breadcrumb + title + description + search.
 */

$page_id = absint( get_queried_object_id() );
$title   = $page_id ? get_the_title( $page_id ) : __( 'Wedding Inspiration', 'sandiegoweddingdirectory' );
$desc    = $page_id ? get_post_field( 'post_excerpt', $page_id ) : '';
if ( ! $desc ) {
    $desc = __( 'Real weddings, planning advice, and ideas for every part of your celebration.', 'sandiegoweddingdirectory' );
}
?>
<section class="blog-s1-blog-hero" aria-label="<?php echo esc_attr( $title ); ?>">
    <div class="container blog-s1-blog-hero__inner">
        <nav class="blog-s1-blog-hero__crumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'sandiegoweddingdirectory' ); ?>">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'sandiegoweddingdirectory' ); ?></a>
            <span class="blog-s1-blog-hero__sep" aria-hidden="true">/</span>
            <span><?php echo esc_html( $title ); ?></span>
        </nav>

        <h1 class="blog-s1-blog-hero__title"><?php echo esc_html( $title ); ?></h1>
        <p class="blog-s1-blog-hero__desc"><?php echo esc_html( $desc ); ?></p>

        <form class="blog-s1-blog-hero__search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
            <label class="screen-reader-text" for="blog-hero-search"><?php esc_html_e( 'Search articles', 'sandiegoweddingdirectory' ); ?></label>
            <span class="blog-s1-blog-hero__search-icon icon-search" aria-hidden="true"></span>
            <input id="blog-hero-search" class="blog-s1-blog-hero__search-input" type="search" name="s" placeholder="<?php esc_attr_e( 'Search wedding inspiration...', 'sandiegoweddingdirectory' ); ?>" autocomplete="off">
            <button class="btn btn--primary blog-s1-blog-hero__search-submit" type="submit"><?php esc_html_e( 'Search', 'sandiegoweddingdirectory' ); ?></button>
        </form>
    </div>
</section>
