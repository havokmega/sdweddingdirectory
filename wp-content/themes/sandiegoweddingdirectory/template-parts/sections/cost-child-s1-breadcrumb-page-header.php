<?php
/**
 * Cost Child: Breadcrumb + H1 + cost-image-blank.png with 5 price overlays.
 *
 * Five overlay slots map to the 5 zones on cost-image-blank.png:
 *   1) the dark teal pill in the bell curve (range — "most couples spend between")
 *   2) lower-range grey box (bottom row, far left)
 *   3) upper-range grey box (bottom row)
 *   4) most-couples-spend grey box (bottom row)
 *   5) average-cost grey box (bottom row, far right)
 *
 * Per-page values can be supplied via post-meta keys when ready:
 *   cost_pill, cost_lower, cost_upper, cost_typical, cost_average
 * Falls back to "$1000" placeholder for all five.
 */

$page_id = absint( get_queried_object_id() );
$title   = $page_id ? get_the_title( $page_id ) : '';

$default      = '$1000';
$default_pill = '$1000 - $1500';
$prices       = [
    'pill'    => $page_id ? ( get_post_meta( $page_id, 'cost_pill',    true ) ?: $default_pill ) : $default_pill,
    'lower'   => $page_id ? ( get_post_meta( $page_id, 'cost_lower',   true ) ?: $default ) : $default,
    'upper'   => $page_id ? ( get_post_meta( $page_id, 'cost_upper',   true ) ?: $default ) : $default,
    'typical' => $page_id ? ( get_post_meta( $page_id, 'cost_typical', true ) ?: $default ) : $default,
    'average' => $page_id ? ( get_post_meta( $page_id, 'cost_average', true ) ?: $default ) : $default,
];

$image_src = get_template_directory_uri() . '/assets/images/pages/cost-image-blank.png';

// Map the cost-page slug to its matching vendor-category slug.
$cost_to_vendor_cat = [
    'wedding-catering-san-diego'        => 'catering',
    'wedding-photographer-san-diego'    => 'photography',
    'wedding-cake-san-diego'            => 'cakes',
    'wedding-flowers-san-diego'         => 'flowers',
    'wedding-planner-san-diego'         => 'wedding-planners',
    'wedding-hair-and-makeup-san-diego' => 'hair-makeup',
    'wedding-dj-san-diego'              => 'djs',
    'wedding-venue-san-diego'           => 'venues',
    'wedding-videographer-san-diego'    => 'videography',
    'wedding-band-san-diego'            => 'bands',
    'wedding-dress-san-diego'           => 'dress-attire',
    'wedding-tuxedo-san-diego'          => 'dress-attire',
    'wedding-officiant-san-diego'       => 'officiants',
    'wedding-ceremony-music-san-diego'  => 'ceremony-music',
    'wedding-photo-booths-san-diego'    => 'photo-booths',
    'wedding-rentals-san-diego'         => 'event-rentals',
];

$current_slug      = $page_id ? get_post_field( 'post_name', $page_id ) : '';
$vendor_cat_slug   = $cost_to_vendor_cat[ $current_slug ] ?? '';
$vendor_cat_term   = $vendor_cat_slug ? get_term_by( 'slug', $vendor_cat_slug, 'vendor-category' ) : null;

$vendor_query = null;
if ( $vendor_cat_term && ! is_wp_error( $vendor_cat_term ) ) {
    $vendor_query = new WP_Query( [
        'post_type'      => 'vendor',
        'post_status'    => 'publish',
        'posts_per_page' => 3,
        'no_found_rows'  => true,
        'tax_query'      => [
            [
                'taxonomy' => 'vendor-category',
                'field'    => 'term_id',
                'terms'    => $vendor_cat_term->term_id,
            ],
        ],
    ] );
}

$vendor_archive_url = '';
if ( $vendor_cat_term && function_exists( 'sdwdv2_get_vendor_category_url' ) ) {
    $vendor_archive_url = sdwdv2_get_vendor_category_url( $vendor_cat_term );
} elseif ( $vendor_cat_term ) {
    $vendor_archive_url = get_term_link( $vendor_cat_term );
}
?>
<section class="cost-child-s1-breadcrumb-page-header" aria-label="<?php echo esc_attr( $title ); ?>">
    <div class="container cost-child-s1-breadcrumb-page-header__inner">
        <nav class="cost-child-s1-breadcrumb-page-header__crumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'sandiegoweddingdirectory' ); ?>">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'sandiegoweddingdirectory' ); ?></a>
            <span class="cost-child-s1-breadcrumb-page-header__sep" aria-hidden="true">/</span>
            <a href="<?php echo esc_url( home_url( '/cost/' ) ); ?>"><?php esc_html_e( 'Cost', 'sandiegoweddingdirectory' ); ?></a>
            <span class="cost-child-s1-breadcrumb-page-header__sep" aria-hidden="true">/</span>
            <span><?php echo esc_html( $title ); ?></span>
        </nav>

        <h1 class="cost-child-s1-breadcrumb-page-header__title"><?php echo esc_html( $title ); ?></h1>

        <div class="cost-child-s1-breadcrumb-page-header__layout">
            <div class="cost-child-s1-breadcrumb-page-header__main">
                <figure class="cost-child-s1-breadcrumb-page-header__chart" role="img" aria-label="<?php esc_attr_e( 'Wedding cost overview chart', 'sandiegoweddingdirectory' ); ?>">
                    <img class="cost-child-s1-breadcrumb-page-header__chart-image" src="<?php echo esc_url( $image_src ); ?>" alt="" loading="lazy">

                    <span class="cost-child-s1-breadcrumb-page-header__price cost-child-s1-breadcrumb-page-header__price--pill"><?php echo esc_html( $prices['pill'] ); ?></span>
                    <span class="cost-child-s1-breadcrumb-page-header__price cost-child-s1-breadcrumb-page-header__price--lower"><?php echo esc_html( $prices['lower'] ); ?></span>
                    <span class="cost-child-s1-breadcrumb-page-header__price cost-child-s1-breadcrumb-page-header__price--upper"><?php echo esc_html( $prices['upper'] ); ?></span>
                    <span class="cost-child-s1-breadcrumb-page-header__price cost-child-s1-breadcrumb-page-header__price--typical"><?php echo esc_html( $prices['typical'] ); ?></span>
                    <span class="cost-child-s1-breadcrumb-page-header__price cost-child-s1-breadcrumb-page-header__price--average"><?php echo esc_html( $prices['average'] ); ?></span>
                </figure>

                <?php if ( $vendor_query && $vendor_query->have_posts() ) : ?>
                    <div class="cost-child-s1-breadcrumb-page-header__vendors">
                        <h2 class="cost-child-s1-breadcrumb-page-header__vendors-heading">
                            <?php
                            printf(
                                /* translators: %s: vendor category name */
                                esc_html__( 'Top %s in San Diego', 'sandiegoweddingdirectory' ),
                                esc_html( $vendor_cat_term->name )
                            );
                            ?>
                        </h2>

                        <div class="cost-child-s1-breadcrumb-page-header__vendor-grid grid grid--3col">
                            <?php while ( $vendor_query->have_posts() ) :
                                $vendor_query->the_post();
                                get_template_part( 'template-parts/components/vendor-card', null, [
                                    'post_id'    => get_the_ID(),
                                    'image_size' => 'medium',
                                ] );
                            endwhile;
                            wp_reset_postdata(); ?>
                        </div>

                        <?php if ( $vendor_archive_url ) : ?>
                            <div class="cost-child-s1-breadcrumb-page-header__vendors-cta">
                                <a class="btn btn--outline" href="<?php echo esc_url( $vendor_archive_url ); ?>">
                                    <?php
                                    printf(
                                        /* translators: %s: vendor category name */
                                        esc_html__( 'See all %s', 'sandiegoweddingdirectory' ),
                                        esc_html( $vendor_cat_term->name )
                                    );
                                    ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <aside class="cost-child-s1-breadcrumb-page-header__sidebar">
                <div class="cost-child-s1-breadcrumb-page-header__sidebar-card">
                    <h2 class="cost-child-s1-breadcrumb-page-header__sidebar-title"><?php esc_html_e( 'Get matched with vendors', 'sandiegoweddingdirectory' ); ?></h2>
                    <p class="cost-child-s1-breadcrumb-page-header__sidebar-text"><?php esc_html_e( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt.', 'sandiegoweddingdirectory' ); ?></p>
                    <a class="btn btn--primary cost-child-s1-breadcrumb-page-header__sidebar-cta" href="<?php echo esc_url( home_url( '/dashboard/budget/' ) ); ?>"><?php esc_html_e( 'Open budget tool', 'sandiegoweddingdirectory' ); ?></a>
                </div>
            </aside>
        </div>
    </div>
</section>
