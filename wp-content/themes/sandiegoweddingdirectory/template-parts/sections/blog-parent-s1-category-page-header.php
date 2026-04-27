<?php
/**
 * Blog Parent: Category page header — title + description + filter chips.
 *
 * Filter chips are immediate child categories of the current term.
 */

$current = get_queried_object();
if ( ! $current || empty( $current->term_id ) ) {
    return;
}

$desc     = category_description( $current->term_id );
$children = get_categories( [
    'parent'     => absint( $current->term_id ),
    'hide_empty' => false,
    'orderby'    => 'name',
    'order'      => 'ASC',
] );

$banner_image_dir = get_template_directory() . '/assets/images/blog';
$banner_image_url = get_template_directory_uri() . '/assets/images/blog';
$banner_path      = $banner_image_dir . '/' . $current->slug . '.jpg';
$banner_src       = file_exists( $banner_path ) ? $banner_image_url . '/' . $current->slug . '.jpg' : '';
?>
<section class="blog-parent-s1-category-page-header" aria-label="<?php echo esc_attr( $current->name ); ?>">
    <?php if ( $banner_src ) : ?>
        <div class="blog-parent-s1-category-page-header__bg" style="--blog-parent-bg: url('<?php echo esc_url( $banner_src ); ?>');" aria-hidden="true"></div>
    <?php endif; ?>

    <div class="container blog-parent-s1-category-page-header__inner">
        <nav class="blog-parent-s1-category-page-header__crumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'sandiegoweddingdirectory' ); ?>">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'sandiegoweddingdirectory' ); ?></a>
            <span class="blog-parent-s1-category-page-header__sep" aria-hidden="true">/</span>
            <a href="<?php echo esc_url( home_url( '/wedding-inspiration/' ) ); ?>"><?php esc_html_e( 'Wedding Inspiration', 'sandiegoweddingdirectory' ); ?></a>
            <span class="blog-parent-s1-category-page-header__sep" aria-hidden="true">/</span>
            <span><?php echo esc_html( $current->name ); ?></span>
        </nav>

        <h1 class="blog-parent-s1-category-page-header__title"><?php echo esc_html( $current->name ); ?></h1>
        <?php if ( $desc ) : ?>
            <div class="blog-parent-s1-category-page-header__desc"><?php echo wp_kses_post( $desc ); ?></div>
        <?php endif; ?>

        <?php if ( ! empty( $children ) ) : ?>
            <ul class="blog-parent-s1-category-page-header__chips" aria-label="<?php esc_attr_e( 'Filter by sub-topic', 'sandiegoweddingdirectory' ); ?>">
                <?php foreach ( $children as $child ) : ?>
                    <li class="blog-parent-s1-category-page-header__chip">
                        <a class="blog-parent-s1-category-page-header__chip-link" href="<?php echo esc_url( get_category_link( $child->term_id ) ); ?>">
                            <?php echo esc_html( $child->name ); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</section>
