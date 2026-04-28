<?php
/**
 * Vendor Profile — s6e Filters
 *
 * CRITICAL per spec: filters are NOT hardcoded UI. They come from
 * taxonomy + meta config, dynamic per category. UI here is a thin
 * shell that renders fields based on a config registered in PHP.
 *
 * Sources of truth (registered later via filter `sdwd_filter_config`):
 *   - VENUE → global filter set: location, type, capacity, price_range,
 *     indoor_outdoor, amenities
 *   - VENDOR → per-category filter set, keyed by vendor_category term slug
 *
 * Until that registry is wired, this template shows the appropriate
 * filter set based on the user's role and primary category, with
 * placeholders for unregistered categories.
 *
 * $args:
 *   - post_id (int)
 *   - is_venue (bool)
 */

$post_id  = (int) ( $args['post_id'] ?? 0 );
$is_venue = ! empty( $args['is_venue'] );

// Pull primary category for vendors.
$primary_cat = '';
if ( ! $is_venue ) {
    $terms = get_the_terms( $post_id, 'vendor-category' );
    if ( $terms && ! is_wp_error( $terms ) ) {
        $primary_cat = $terms[0]->slug;
    }
}

// Default filter sets (will move to filter-config registry under sdwd-core).
$venue_filters = [
    'venue_type'      => [ 'label' => __( 'Venue Type', 'sandiegoweddingdirectory' ), 'type' => 'multi-select', 'options' => [ 'beach', 'estate', 'garden', 'historic', 'industrial', 'rooftop', 'winery', 'church' ] ],
    'guest_capacity'  => [ 'label' => __( 'Guest Capacity', 'sandiegoweddingdirectory' ), 'type' => 'number-range', 'min' => 0, 'max' => 500 ],
    'price_range'     => [ 'label' => __( 'Price Range', 'sandiegoweddingdirectory' ), 'type' => 'select', 'options' => [ '$', '$$', '$$$', '$$$$' ] ],
    'indoor_outdoor'  => [ 'label' => __( 'Indoor / Outdoor', 'sandiegoweddingdirectory' ), 'type' => 'multi-select', 'options' => [ 'indoor', 'outdoor', 'both' ] ],
    'amenities'       => [ 'label' => __( 'Amenities', 'sandiegoweddingdirectory' ), 'type' => 'multi-select', 'options' => [ 'parking', 'in_house_catering', 'bar_service', 'dance_floor', 'pet_friendly', 'wheelchair_accessible', 'overnight_lodging' ] ],
];

$vendor_filter_sets = [
    'photographer' => [
        'style'         => [ 'label' => __( 'Style', 'sandiegoweddingdirectory' ), 'type' => 'multi-select', 'options' => [ 'documentary', 'editorial', 'classic', 'fine_art', 'moody', 'bright_airy' ] ],
        'delivery_time' => [ 'label' => __( 'Delivery Time', 'sandiegoweddingdirectory' ), 'type' => 'select', 'options' => [ '1-2 weeks', '3-4 weeks', '5-8 weeks' ] ],
        'price_range'   => [ 'label' => __( 'Price Range', 'sandiegoweddingdirectory' ), 'type' => 'select', 'options' => [ '$', '$$', '$$$', '$$$$' ] ],
    ],
    'dj' => [
        'genres'      => [ 'label' => __( 'Genres', 'sandiegoweddingdirectory' ), 'type' => 'multi-select', 'options' => [ 'top_40', 'hip_hop', 'house', 'latin', 'country', 'rock', 'oldies', 'edm' ] ],
        'services'    => [ 'label' => __( 'Services', 'sandiegoweddingdirectory' ), 'type' => 'multi-select', 'options' => [ 'mc', 'lighting', 'uplighting', 'cold_sparks', 'photo_booth' ] ],
        'equipment'   => [ 'label' => __( 'Equipment Provided', 'sandiegoweddingdirectory' ), 'type' => 'multi-select', 'options' => [ 'speakers', 'microphones', 'subwoofer', 'fog_machine' ] ],
        'price_range' => [ 'label' => __( 'Price Range', 'sandiegoweddingdirectory' ), 'type' => 'select', 'options' => [ '$', '$$', '$$$', '$$$$' ] ],
    ],
    'florist' => [
        'floral_style' => [ 'label' => __( 'Floral Style', 'sandiegoweddingdirectory' ), 'type' => 'multi-select', 'options' => [ 'modern', 'romantic', 'bohemian', 'tropical', 'minimal' ] ],
        'services'     => [ 'label' => __( 'Services', 'sandiegoweddingdirectory' ), 'type' => 'multi-select', 'options' => [ 'bouquets', 'centerpieces', 'arches', 'install', 'rentals' ] ],
        'price_range'  => [ 'label' => __( 'Price Range', 'sandiegoweddingdirectory' ), 'type' => 'select', 'options' => [ '$', '$$', '$$$', '$$$$' ] ],
    ],
];

if ( $is_venue ) {
    $config = $venue_filters;
    $key    = 'venue_filters';
    $title  = __( 'Venue Filters', 'sandiegoweddingdirectory' );
} else {
    $config = $vendor_filter_sets[ $primary_cat ] ?? null;
    $key    = $primary_cat ? ( 'vendor_filters_' . $primary_cat ) : 'vendor_filters';
    $title  = $primary_cat
        ? sprintf( /* translators: %s: category name */ __( 'Filters for %s', 'sandiegoweddingdirectory' ), ucfirst( $primary_cat ) )
        : __( 'Filters', 'sandiegoweddingdirectory' );
}

$saved = get_post_meta( $post_id, $key, true );
if ( ! is_array( $saved ) ) { $saved = []; }
?>

<form class="cd-form" data-cd-form="filters">
    <input type="hidden" name="filter_key" value="<?php echo esc_attr( $key ); ?>">
    <fieldset class="cd-form__group">
        <legend class="cd-form__legend"><?php echo esc_html( $title ); ?></legend>

        <?php if ( ! $config ) : ?>
            <p class="cd-section__soon">
                <?php esc_html_e( 'Filter set for this category has not been registered yet. Once admin registers a filter config for your vendor category, fields show here.', 'sandiegoweddingdirectory' ); ?>
            </p>
        <?php else :
            foreach ( $config as $field_key => $field ) :
                $type    = $field['type'];
                $value   = $saved[ $field_key ] ?? ( $type === 'multi-select' ? [] : '' );
                if ( $type === 'multi-select' && ! is_array( $value ) ) { $value = []; }
        ?>
            <div class="cd-form__field">
                <label><?php echo esc_html( $field['label'] ); ?></label>
                <?php if ( $type === 'select' ) : ?>
                    <select name="filters[<?php echo esc_attr( $field_key ); ?>]">
                        <option value=""><?php esc_html_e( 'Choose…', 'sandiegoweddingdirectory' ); ?></option>
                        <?php foreach ( $field['options'] as $opt ) : ?>
                            <option value="<?php echo esc_attr( $opt ); ?>" <?php selected( $value, $opt ); ?>><?php echo esc_html( ucwords( str_replace( '_', ' ', $opt ) ) ); ?></option>
                        <?php endforeach; ?>
                    </select>
                <?php elseif ( $type === 'multi-select' ) : ?>
                    <div class="vd-filters__chips">
                        <?php foreach ( $field['options'] as $opt ) :
                            $checked = in_array( $opt, $value, true );
                        ?>
                            <label class="vd-filters__chip<?php echo $checked ? ' vd-filters__chip--on' : ''; ?>">
                                <input type="checkbox" name="filters[<?php echo esc_attr( $field_key ); ?>][]" value="<?php echo esc_attr( $opt ); ?>" <?php checked( $checked ); ?>>
                                <span><?php echo esc_html( ucwords( str_replace( '_', ' ', $opt ) ) ); ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                <?php elseif ( $type === 'number-range' ) : ?>
                    <div class="cd-form__row">
                        <input type="number" name="filters[<?php echo esc_attr( $field_key ); ?>][min]" min="<?php echo (int) $field['min']; ?>" max="<?php echo (int) $field['max']; ?>" value="<?php echo esc_attr( $value['min'] ?? '' ); ?>" placeholder="<?php esc_attr_e( 'Min', 'sandiegoweddingdirectory' ); ?>">
                        <input type="number" name="filters[<?php echo esc_attr( $field_key ); ?>][max]" min="<?php echo (int) $field['min']; ?>" max="<?php echo (int) $field['max']; ?>" value="<?php echo esc_attr( $value['max'] ?? '' ); ?>" placeholder="<?php esc_attr_e( 'Max', 'sandiegoweddingdirectory' ); ?>">
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; endif; ?>

        <p class="cd-form__hint">
            <?php esc_html_e( 'These values control how couples find you in search and category browse. Fields shown depend on your category — they are not hardcoded.', 'sandiegoweddingdirectory' ); ?>
        </p>
    </fieldset>

    <?php if ( $config ) : ?>
        <div class="cd-form__actions">
            <button type="submit" class="btn btn--primary"><?php esc_html_e( 'Save Filters', 'sandiegoweddingdirectory' ); ?></button>
        </div>
        <div class="cd-form__status" aria-live="polite"></div>
    <?php endif; ?>
</form>
