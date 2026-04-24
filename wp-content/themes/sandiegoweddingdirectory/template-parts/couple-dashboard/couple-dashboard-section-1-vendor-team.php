<?php
/**
 * Couple Dashboard — Section 1: Your Vendor Team
 *
 * Pulls from the couple's wishlist (sdwd_wishlist user meta), which is an
 * array of vendor/venue post IDs. If empty, shows a prompt to browse vendors.
 *
 * $args:
 *   - wishlist (array of post IDs)
 */

$wishlist = $args['wishlist'] ?? [];
$wishlist = is_array( $wishlist ) ? array_filter( array_map( 'intval', $wishlist ) ) : [];
?>

<section class="cd-card cd-vendor-team">
    <div class="cd-card__head">
        <h2 class="cd-card__title"><?php esc_html_e( 'Your Vendor Team', 'sandiegoweddingdirectory' ); ?></h2>
        <a href="<?php echo esc_url( home_url( '/couple-dashboard/vendor-manager/' ) ); ?>" class="cd-card__link">
            <?php esc_html_e( 'Manage vendors', 'sandiegoweddingdirectory' ); ?> &rarr;
        </a>
    </div>

    <?php if ( empty( $wishlist ) ) : ?>
        <div class="cd-card__empty">
            <span class="cd-card__empty-title"><?php esc_html_e( 'No vendors saved yet', 'sandiegoweddingdirectory' ); ?></span>
            <?php
            printf(
                /* translators: %s: link to vendors page */
                esc_html__( 'Browse the %s directory and tap the heart on any vendor to start building your team.', 'sandiegoweddingdirectory' ),
                '<a href="' . esc_url( home_url( '/vendors/' ) ) . '">' . esc_html__( 'San Diego vendor', 'sandiegoweddingdirectory' ) . '</a>'
            );
            ?>
        </div>
    <?php else :
        $query = new WP_Query( [
            'post_type'           => [ 'vendor', 'venue' ],
            'post__in'            => $wishlist,
            'orderby'             => 'post__in',
            'posts_per_page'      => 12,
            'ignore_sticky_posts' => true,
            'no_found_rows'       => true,
        ] );
    ?>
        <div class="cd-vendor-team__grid">
            <?php while ( $query->have_posts() ) : $query->the_post();
                $post_id  = get_the_ID();
                $cat_name = '';
                $terms    = get_the_terms( $post_id, get_post_type() === 'venue' ? 'venue-type' : 'vendor-category' );
                if ( $terms && ! is_wp_error( $terms ) ) {
                    $cat_name = $terms[0]->name;
                }
                $thumb = get_the_post_thumbnail_url( $post_id, 'thumbnail' );
            ?>
                <a href="<?php the_permalink(); ?>" class="cd-vendor-card">
                    <?php if ( $thumb ) : ?>
                        <img src="<?php echo esc_url( $thumb ); ?>" alt="" class="cd-vendor-card__thumb" loading="lazy">
                    <?php else : ?>
                        <span class="cd-vendor-card__thumb" aria-hidden="true"></span>
                    <?php endif; ?>
                    <p class="cd-vendor-card__name"><?php the_title(); ?></p>
                    <?php if ( $cat_name ) : ?>
                        <span class="cd-vendor-card__cat"><?php echo esc_html( $cat_name ); ?></span>
                    <?php endif; ?>
                </a>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    <?php endif; ?>
</section>
