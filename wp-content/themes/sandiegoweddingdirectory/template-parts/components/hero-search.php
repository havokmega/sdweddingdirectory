<?php
/**
 * Component: Hero Search
 *
 * Radio toggle (Venues / Vendors) + search form with venue-type and vendor-category
 * dropdowns. The vendor side uses a 3-col mega-menu of category icons. Used on
 * the home page hero and on the /vendors and /venues landing pages.
 *
 * The dropdown panel uses position:fixed (set by JS) so it escapes any
 * `overflow: hidden` on ancestor heroes.
 *
 * @param array $args {
 *     @type string $default_mode 'venues' or 'vendors'. Default 'venues'.
 * }
 */

$default_mode = ( ( $args['default_mode'] ?? 'venues' ) === 'vendors' ) ? 'vendors' : 'venues';

$venue_types = get_terms( [
    'taxonomy'   => 'venue-type',
    'hide_empty' => true,
    'orderby'    => 'name',
    'order'      => 'ASC',
] );
$venue_types = is_wp_error( $venue_types ) ? [] : $venue_types;

$location_terms = get_terms( [
    'taxonomy'   => 'venue-location',
    'hide_empty' => false,
    'parent'     => 0,
    'orderby'    => 'name',
    'order'      => 'ASC',
] );
$location_terms = is_wp_error( $location_terms ) ? [] : $location_terms;

$vendor_cats = get_terms( [
    'taxonomy'   => 'vendor-category',
    'hide_empty' => false,
    'orderby'    => 'name',
    'order'      => 'ASC',
    'exclude'    => [ 140 ], // stray 'Venues' term in vendor-category taxonomy
] );
$vendor_cats = is_wp_error( $vendor_cats ) ? [] : $vendor_cats;

$vendor_category_icons = [
    'photography'      => 'icon-camera-alt',
    'videography'      => 'icon-videographer',
    'djs'              => 'icon-music',
    'bands'            => 'icon-music',
    'ceremony-music'   => 'icon-music',
    'flowers'          => 'icon-flowers',
    'catering'         => 'icon-wine',
    'cakes'            => 'icon-cake',
    'officiants'       => 'icon-church',
    'hair-makeup'      => 'icon-fashion',
    'dress-attire'     => 'icon-bridal-wear',
    'event-rentals'    => 'icon-four-side-table-1',
    'photo-booths'     => 'icon-camera',
    'invitations'      => 'icon-heart-envelope',
    'transportation'   => 'icon-bus',
    'travel-agents'    => 'icon-birde',
    'wedding-planners' => 'icon-checklist',
    'lighting-decor'   => 'icon-bell',
    'jewelry'          => 'icon-ballon-heart',
    'favors-gifts'     => 'icon-ballon-heart',
];

$is_vendor_default = $default_mode === 'vendors';
?>
<div class="hero__search">
    <div class="hero__toggle">
        <span class="hero__toggle-label"><?php esc_html_e( 'Choose what you want to search for:', 'sandiegoweddingdirectory' ); ?></span>

        <label class="hero__toggle-option<?php echo ! $is_vendor_default ? ' hero__toggle-option--active' : ''; ?>">
            <input type="radio" name="sd_search_mode" value="venues"<?php checked( ! $is_vendor_default ); ?>>
            <span class="hero__toggle-radio" aria-hidden="true"></span>
            <span class="hero__toggle-text"><?php esc_html_e( 'Venues', 'sandiegoweddingdirectory' ); ?></span>
        </label>

        <label class="hero__toggle-option<?php echo $is_vendor_default ? ' hero__toggle-option--active' : ''; ?>">
            <input type="radio" name="sd_search_mode" value="vendors"<?php checked( $is_vendor_default ); ?>>
            <span class="hero__toggle-radio" aria-hidden="true"></span>
            <span class="hero__toggle-text"><?php esc_html_e( 'Vendors', 'sandiegoweddingdirectory' ); ?></span>
        </label>
    </div>

    <form class="hero__form"
          action="<?php echo esc_url( home_url( $is_vendor_default ? '/vendors/' : '/venues/' ) ); ?>"
          method="get"
          data-venue-action="<?php echo esc_url( home_url( '/venues/' ) ); ?>"
          data-vendor-action="<?php echo esc_url( home_url( '/vendors/' ) ); ?>">

        <div class="hero__form-fields hero__venue-fields"<?php echo $is_vendor_default ? ' hidden' : ''; ?>>
            <div class="hero__field hero__field--type hero__field--dropdown">
                <span class="hero__field-icon icon-search" aria-hidden="true"></span>
                <button class="hero__dropdown-trigger" type="button" aria-expanded="false" aria-haspopup="true" data-dropdown="venue-type">
                    <span class="hero__dropdown-text"><?php esc_html_e( 'Search by type', 'sandiegoweddingdirectory' ); ?></span>
                    <span class="hero__dropdown-arrow icon-chevron-down" aria-hidden="true"></span>
                </button>
                <input type="hidden" name="cat_id" value="">
                <div class="hero__dropdown-panel" data-panel="venue-type" hidden>
                    <div class="hero__dropdown-grid">
                        <?php foreach ( $venue_types as $vtype ) : ?>
                            <button class="hero__dropdown-item" type="button" data-value="<?php echo esc_attr( $vtype->term_id ); ?>" data-slug="<?php echo esc_attr( $vtype->slug ); ?>">
                                <?php echo esc_html( $vtype->name ); ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="hero__field hero__field--location hero__field--dropdown">
                <span class="hero__field-prefix" aria-hidden="true"><?php esc_html_e( 'in', 'sandiegoweddingdirectory' ); ?></span>
                <button class="hero__dropdown-trigger" type="button" aria-expanded="false" aria-haspopup="true" data-dropdown="venue-location">
                    <span class="hero__dropdown-text"><?php esc_html_e( 'Location', 'sandiegoweddingdirectory' ); ?></span>
                    <span class="hero__dropdown-arrow icon-chevron-down" aria-hidden="true"></span>
                </button>
                <input type="hidden" name="location" value="">
                <div class="hero__dropdown-panel" data-panel="venue-location" hidden>
                    <div class="hero__dropdown-grid">
                        <?php foreach ( $location_terms as $loc ) : ?>
                            <button class="hero__dropdown-item" type="button" data-value="<?php echo esc_attr( $loc->slug ); ?>" data-slug="<?php echo esc_attr( $loc->slug ); ?>">
                                <?php echo esc_html( $loc->name ); ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="hero__form-fields hero__vendor-fields"<?php echo ! $is_vendor_default ? ' hidden' : ''; ?>>
            <div class="hero__field hero__field--type hero__field--dropdown">
                <span class="hero__field-icon icon-search" aria-hidden="true"></span>
                <button class="hero__dropdown-trigger" type="button" aria-expanded="false" aria-haspopup="true" data-dropdown="vendor-category">
                    <span class="hero__dropdown-text"><?php esc_html_e( 'Search by category', 'sandiegoweddingdirectory' ); ?></span>
                    <span class="hero__dropdown-arrow icon-chevron-down" aria-hidden="true"></span>
                </button>
                <input type="hidden" name="cat_id" value="">
                <div class="hero__dropdown-panel hero__dropdown-panel--mega" data-panel="vendor-category" hidden>
                    <div class="hero__dropdown-grid hero__dropdown-grid--icons">
                        <?php foreach ( $vendor_cats as $vcat ) :
                            $icon_class = $vendor_category_icons[ $vcat->slug ] ?? 'icon-vendor-manager'; ?>
                            <button class="hero__dropdown-item hero__dropdown-item--with-icon" type="button" data-value="<?php echo esc_attr( $vcat->term_id ); ?>" data-slug="<?php echo esc_attr( $vcat->slug ); ?>">
                                <span class="hero__dropdown-item-icon <?php echo esc_attr( $icon_class ); ?>" aria-hidden="true"></span>
                                <span class="hero__dropdown-item-label"><?php echo esc_html( $vcat->name ); ?></span>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <button class="btn btn--primary hero__submit" type="submit">
            <?php esc_html_e( 'Search Now', 'sandiegoweddingdirectory' ); ?>
        </button>
    </form>
</div>
