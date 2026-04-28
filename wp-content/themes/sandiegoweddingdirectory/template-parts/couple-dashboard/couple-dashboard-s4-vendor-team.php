<?php
/**
 * Couple Dashboard — s4 Your Vendor Team
 *
 * Category browse grid. Each card represents a vendor category the couple is
 * shopping for. Shows favorite count + "Hired" state. Top of section has a
 * "X of Y categories hired" line and a search bar.
 *
 * $args:
 *   - vendor_team   (array of [ category, hearts, hired ])
 *   - vendors_hired (int)
 *   - vendors_total (int)
 */

$vendor_team   = $args['vendor_team']   ?? [];
$vendors_hired = (int) ( $args['vendors_hired'] ?? 0 );
$vendors_total = (int) ( $args['vendors_total'] ?? 0 );

if ( ! is_array( $vendor_team ) ) { $vendor_team = []; }

// Map category slug → display name + icon class. Source of truth for categories
// will move to taxonomy later; hardcoded for the dashboard preview.
$category_meta = [
    'cake'           => [ 'name' => __( 'Cake',           'sandiegoweddingdirectory' ), 'icon' => 'icon-cake' ],
    'dress'          => [ 'name' => __( 'Dress',          'sandiegoweddingdirectory' ), 'icon' => 'icon-bridal-wear' ],
    'florist'        => [ 'name' => __( 'Florist',        'sandiegoweddingdirectory' ), 'icon' => 'icon-flowers' ],
    'jeweller'       => [ 'name' => __( 'Jeweller',       'sandiegoweddingdirectory' ), 'icon' => 'icon-ring-double' ],
    'music'          => [ 'name' => __( 'Music',          'sandiegoweddingdirectory' ), 'icon' => 'icon-music' ],
    'photographer'   => [ 'name' => __( 'Photographer',   'sandiegoweddingdirectory' ), 'icon' => 'icon-camera1' ],
    'transportation' => [ 'name' => __( 'Transportation', 'sandiegoweddingdirectory' ), 'icon' => 'icon-bus' ],
    'venue'          => [ 'name' => __( 'Venue',          'sandiegoweddingdirectory' ), 'icon' => 'icon-building' ],
    'videographer'   => [ 'name' => __( 'Videographer',   'sandiegoweddingdirectory' ), 'icon' => 'icon-videographer' ],
];

$cat_image_base = get_template_directory_uri() . '/assets/images/placeholders/couple-dashboard/categories';
$cat_fallback   = get_template_directory_uri() . '/assets/images/placeholders/vendor-placeholder.png';
?>

<section class="cd-card cd-vendor-team">
    <div class="cd-vendor-team__head">
        <div class="cd-vendor-team__heading">
            <p class="cd-vendor-team__progress"><?php
                /* translators: 1: hired count, 2: total categories */
                printf( esc_html__( '%1$d of %2$d categories hired', 'sandiegoweddingdirectory' ), $vendors_hired, $vendors_total );
            ?></p>
            <h2 class="cd-vendor-team__title"><?php esc_html_e( 'Your vendor team', 'sandiegoweddingdirectory' ); ?></h2>
        </div>
        <a href="<?php echo esc_url( home_url( '/couple-dashboard/vendor-manager/' ) ); ?>" class="cd-vendor-team__view-all">
            <?php esc_html_e( 'View all my vendors', 'sandiegoweddingdirectory' ); ?> &rsaquo;
        </a>
    </div>

    <form class="cd-vendor-search" action="<?php echo esc_url( home_url( '/vendors/' ) ); ?>" method="get" role="search">
        <label class="cd-vendor-search__field cd-vendor-search__field--query">
            <span class="cd-vendor-search__icon icon-search" aria-hidden="true"></span>
            <input type="search" name="q" placeholder="<?php esc_attr_e( 'Search By Vendors', 'sandiegoweddingdirectory' ); ?>" autocomplete="off">
        </label>
        <label class="cd-vendor-search__field cd-vendor-search__field--location">
            <span class="cd-vendor-search__sep"><?php esc_html_e( 'in', 'sandiegoweddingdirectory' ); ?></span>
            <input type="text" name="location" placeholder="<?php esc_attr_e( 'Location', 'sandiegoweddingdirectory' ); ?>" autocomplete="off">
        </label>
        <button type="submit" class="cd-vendor-search__submit"><?php esc_html_e( 'Search', 'sandiegoweddingdirectory' ); ?></button>
    </form>

    <?php if ( empty( $vendor_team ) ) : ?>
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
    <?php else : ?>
        <div class="cd-vendor-team__grid">
            <?php foreach ( $vendor_team as $row ) :
                $slug   = $row['category'] ?? '';
                $hearts = (int) ( $row['hearts'] ?? 0 );
                $hired  = ! empty( $row['hired'] );
                $meta   = $category_meta[ $slug ] ?? [ 'name' => ucfirst( $slug ), 'icon' => '' ];
                $card_url = home_url( '/vendors/' . rawurlencode( $slug ) . '/' );
                $img_url  = $cat_image_base . '/' . rawurlencode( $slug ) . '.jpg';
            ?>
                <a href="<?php echo esc_url( $card_url ); ?>" class="cd-vendor-cat<?php echo $hired ? ' cd-vendor-cat--hired' : ''; ?>" style="background-image: url('<?php echo esc_url( $img_url ); ?>'), url('<?php echo esc_url( $cat_fallback ); ?>');">
                    <span class="cd-vendor-cat__edit" aria-hidden="true">&#9998;</span>
                    <div class="cd-vendor-cat__center">
                        <?php if ( $meta['icon'] ) : ?>
                            <span class="cd-vendor-cat__icon <?php echo esc_attr( $meta['icon'] ); ?>" aria-hidden="true"></span>
                        <?php endif; ?>
                        <span class="cd-vendor-cat__name"><?php echo esc_html( $meta['name'] ); ?></span>
                    </div>
                    <div class="cd-vendor-cat__badges">
                        <span class="cd-vendor-cat__hearts">
                            <span class="icon-heart-ring" aria-hidden="true"></span>
                            <?php echo (int) $hearts; ?>
                        </span>
                        <?php if ( $hired ) : ?>
                            <span class="cd-vendor-cat__hired"><?php esc_html_e( 'Hired', 'sandiegoweddingdirectory' ); ?></span>
                        <?php endif; ?>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
