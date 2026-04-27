<?php
/**
 * Blog: 10-up circle category row.
 *
 * Pulls top-level WP categories that map to the blog parent groupings.
 * Uses /assets/images/blog/{slug}.jpg as the circle image when available.
 */

$blog_image_dir = get_template_directory() . '/assets/images/blog';
$blog_image_url = get_template_directory_uri() . '/assets/images/blog';

$category_slugs = [
    'wedding-planning-how-to' => __( 'Planning Basics', 'sandiegoweddingdirectory' ),
    'wedding-ceremony'        => __( 'Ceremony',        'sandiegoweddingdirectory' ),
    'wedding-reception'       => __( 'Reception',       'sandiegoweddingdirectory' ),
    'wedding-services'        => __( 'Services',        'sandiegoweddingdirectory' ),
    'songs-music'             => __( 'Songs & Music',   'sandiegoweddingdirectory' ),
    'wedding-decor'           => __( 'Decor',           'sandiegoweddingdirectory' ),
    'wedding-fashion'         => __( 'Fashion',         'sandiegoweddingdirectory' ),
    'destination-weddings'    => __( 'Destination',     'sandiegoweddingdirectory' ),
    'married-life'            => __( 'Married Life',    'sandiegoweddingdirectory' ),
    'events-parties'          => __( 'Events & Parties','sandiegoweddingdirectory' ),
];

$existing_image_map = [
    'wedding-planning-how-to' => 'wedding-planning-how-to.jpg',
    'wedding-ceremony'        => 'wedding-ceremony.jpg',
    'wedding-reception'       => 'wedding-reception.jpg',
    'wedding-services'        => 'wedding-services.jpg',
    'wedding-fashion'         => 'wedding-fashion.jpg',
];
?>
<section class="blog-s2-circle-category-row-10up section" aria-label="<?php esc_attr_e( 'Browse categories', 'sandiegoweddingdirectory' ); ?>">
    <div class="container">
        <h2 class="blog-s2-circle-category-row-10up__heading"><?php esc_html_e( 'Browse by category', 'sandiegoweddingdirectory' ); ?></h2>

        <ul class="blog-s2-circle-category-row-10up__list">
            <?php foreach ( $category_slugs as $slug => $label ) :
                $term = get_category_by_slug( $slug );
                $url  = $term ? get_category_link( $term->term_id ) : home_url( '/category/' . $slug . '/' );

                $image_filename = $existing_image_map[ $slug ] ?? ( $slug . '.jpg' );
                $image_path     = $blog_image_dir . '/' . $image_filename;
                $image_src      = file_exists( $image_path ) ? $blog_image_url . '/' . $image_filename : '';
                ?>
                <li class="blog-s2-circle-category-row-10up__item">
                    <a class="blog-s2-circle-category-row-10up__card" href="<?php echo esc_url( $url ); ?>">
                        <span class="blog-s2-circle-category-row-10up__circle"<?php if ( $image_src ) : ?> style="--blog-circle-bg: url('<?php echo esc_url( $image_src ); ?>');"<?php endif; ?> aria-hidden="true"></span>
                        <span class="blog-s2-circle-category-row-10up__label"><?php echo esc_html( $label ); ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>
