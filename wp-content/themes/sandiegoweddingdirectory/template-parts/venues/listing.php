<?php
/**
 * Shared venue listing renderer.
 *
 * @param array $args {
 *     @type string $title               Page title.
 *     @type string $desc                Optional description.
 *     @type array  $breadcrumbs         Breadcrumb items.
 *     @type string $current_url         Current page URL.
 *     @type int    $fixed_category_id   Optional fixed venue type term ID.
 *     @type string $fixed_location_slug Optional fixed location slug.
 *     @type bool   $show_discovery      Whether to show landing discovery sections.
 * }
 */

$title               = $args['title'] ?? '';
$desc                = $args['desc'] ?? '';
$breadcrumbs         = $args['breadcrumbs'] ?? [];
$current_url         = $args['current_url'] ?? sdwdv2_get_venues_url();
$fixed_category_id   = absint( $args['fixed_category_id'] ?? 0 );
$fixed_location_slug = sanitize_title( $args['fixed_location_slug'] ?? '' );
$show_discovery      = ! empty( $args['show_discovery'] );

$filter_overrides = [
    'fixed_category' => $fixed_category_id > 0,
    'fixed_location' => '' !== $fixed_location_slug,
    'posts_per_page' => 12,
];

if ( $fixed_category_id > 0 ) {
    $filter_overrides['category_id'] = $fixed_category_id;
}

if ( '' !== $fixed_location_slug ) {
    $filter_overrides['location_slug'] = $fixed_location_slug;
}

$filters = sdwdv2_get_venue_filter_state( $filter_overrides );

$options     = sdwdv2_get_venue_filter_options( $filters );
$venue_query = new WP_Query( sdwdv2_build_venue_query_args( $filters ) );

$selected_category = ! empty( $filters['category_id'] ) ? get_term( absint( $filters['category_id'] ), 'venue-type' ) : null;
$selected_location = ! empty( $filters['location_slug'] ) ? get_term_by( 'slug', $filters['location_slug'], 'venue-location' ) : null;

$price_filter    = sdwdv2_parse_range_filter( $filters['price_filter'] );
$capacity_filter = sdwdv2_parse_range_filter( $filters['capacity_filter'] );
$has_filters     = sdwdv2_has_active_venue_filters( $filters );

$city_terms = [];
if ( $show_discovery && ! $has_filters ) {
    $city_terms = get_terms( [
        'taxonomy'   => 'venue-location',
        'hide_empty' => true,
        'parent'     => 0,
        'orderby'    => 'count',
        'order'      => 'DESC',
        'number'     => 16,
    ] );

    if ( is_wp_error( $city_terms ) ) {
        $city_terms = [];
    }
}

$type_terms = [];
if ( $show_discovery && ! $has_filters ) {
    $type_terms = get_terms( [
        'taxonomy'   => 'venue-type',
        'hide_empty' => false,
        'orderby'    => 'name',
        'order'      => 'ASC',
    ] );

    if ( is_wp_error( $type_terms ) ) {
        $type_terms = [];
    }
}

get_template_part( 'template-parts/components/page-header', null, [
    'title'       => $title,
    'desc'        => $desc,
    'breadcrumbs' => $breadcrumbs,
] );
?>

<?php if ( $show_discovery && ! $has_filters && ( ! empty( $city_terms ) || ! empty( $type_terms ) ) ) : ?>
    <section class="section venues-discovery">
        <div class="container">
            <?php if ( ! empty( $city_terms ) ) : ?>
                <?php
                get_template_part( 'template-parts/components/section-title', null, [
                    'heading' => __( 'Browse Wedding Venues by City', 'sandiegoweddingdirectory' ),
                    'desc'    => __( 'Start with the areas couples search most often across San Diego County.', 'sandiegoweddingdirectory' ),
                ] );
                ?>

                <div class="venues-carousel">
                    <div class="venues-carousel__track">
                        <?php foreach ( $city_terms as $city_term ) : ?>
                            <a class="venues-carousel__slide" href="<?php echo esc_url( sdwdv2_get_venue_directory_url( [ 'location' => $city_term->slug ] ) ); ?>">
                                <img src="<?php echo esc_url( sdwdv2_get_venue_location_image_url( $city_term->slug ) ); ?>" alt="<?php echo esc_attr( $city_term->name ); ?>">
                                <span class="venues-carousel__label"><?php echo esc_html( $city_term->name ); ?></span>
                                <span class="venues-carousel__count"><?php echo esc_html( sprintf( _n( '%s venue', '%s venues', $city_term->count, 'sandiegoweddingdirectory' ), number_format_i18n( $city_term->count ) ) ); ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ( ! empty( $type_terms ) ) : ?>
                <?php
                get_template_part( 'template-parts/components/section-title', null, [
                    'heading' => __( 'Explore Venue Types', 'sandiegoweddingdirectory' ),
                    'desc'    => __( 'Use the venue directory as a starting point, then narrow by type, setting, and guest count.', 'sandiegoweddingdirectory' ),
                ] );
                ?>

                <div class="venues-button-row">
                    <?php foreach ( $type_terms as $type_term ) : ?>
                        <a class="btn btn--outline" href="<?php echo esc_url( sdwdv2_get_venue_directory_url( [ 'cat_id' => $type_term->term_id ] ) ); ?>">
                            <?php echo esc_html( $type_term->name ); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
<?php endif; ?>

<section class="section venues-browser">
    <div class="container">
        <form class="venues-search-form" method="get" action="<?php echo esc_url( $current_url ); ?>">
            <?php if ( $fixed_category_id && $selected_category && ! is_wp_error( $selected_category ) ) : ?>
                <div class="venues-search-form__chip">
                    <span class="venues-search-form__chip-label"><?php esc_html_e( 'Venue type', 'sandiegoweddingdirectory' ); ?></span>
                    <strong><?php echo esc_html( $selected_category->name ); ?></strong>
                </div>
            <?php else : ?>
                <label class="venues-search-form__field">
                    <span class="screen-reader-text"><?php esc_html_e( 'Venue type', 'sandiegoweddingdirectory' ); ?></span>
                    <select name="cat_id">
                        <option value=""><?php esc_html_e( 'All venue types', 'sandiegoweddingdirectory' ); ?></option>
                        <?php foreach ( $options['categories'] as $category ) : ?>
                            <option value="<?php echo esc_attr( $category->term_id ); ?>" <?php selected( $filters['category_id'], $category->term_id ); ?>>
                                <?php echo esc_html( $category->name ); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </label>
            <?php endif; ?>

            <?php if ( $fixed_location_slug && $selected_location && ! is_wp_error( $selected_location ) ) : ?>
                <div class="venues-search-form__chip">
                    <span class="venues-search-form__chip-label"><?php esc_html_e( 'Location', 'sandiegoweddingdirectory' ); ?></span>
                    <strong><?php echo esc_html( $selected_location->name ); ?></strong>
                </div>
            <?php else : ?>
                <label class="venues-search-form__field">
                    <span class="screen-reader-text"><?php esc_html_e( 'Location', 'sandiegoweddingdirectory' ); ?></span>
                    <select name="location">
                        <option value=""><?php esc_html_e( 'All locations', 'sandiegoweddingdirectory' ); ?></option>
                        <?php foreach ( $options['locations'] as $location ) : ?>
                            <option value="<?php echo esc_attr( $location->slug ); ?>" <?php selected( $filters['location_slug'], $location->slug ); ?>>
                                <?php echo esc_html( $location->name ); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </label>
            <?php endif; ?>

            <label class="venues-search-form__field">
                <span class="screen-reader-text"><?php esc_html_e( 'Sort venues', 'sandiegoweddingdirectory' ); ?></span>
                <select name="sort">
                    <?php foreach ( $options['sorts'] as $value => $label ) : ?>
                        <option value="<?php echo esc_attr( $value ); ?>" <?php selected( $filters['sort_by'], $value ); ?>>
                            <?php echo esc_html( $label ); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>

            <button class="btn btn--primary venues-search-form__submit" type="submit">
                <?php esc_html_e( 'Search Venues', 'sandiegoweddingdirectory' ); ?>
            </button>
        </form>

        <div class="archive-filtered venues-browser__layout">
            <aside class="archive-filtered__sidebar">
                <form class="venues-filter-form" method="get" action="<?php echo esc_url( $current_url ); ?>">
                    <?php if ( ! empty( $filters['category_id'] ) ) : ?>
                        <input type="hidden" name="cat_id" value="<?php echo esc_attr( $filters['category_id'] ); ?>">
                    <?php endif; ?>

                    <?php if ( ! empty( $filters['location_slug'] ) ) : ?>
                        <input type="hidden" name="location" value="<?php echo esc_attr( $filters['location_slug'] ); ?>">
                    <?php endif; ?>

                    <?php if ( ! empty( $filters['sort_by'] ) ) : ?>
                        <input type="hidden" name="sort" value="<?php echo esc_attr( $filters['sort_by'] ); ?>">
                    <?php endif; ?>

                    <?php if ( ! empty( $options['price_ranges'] ) ) : ?>
                        <div class="venues-filter-form__group">
                            <label class="venues-filter-form__label" for="venues-price-filter">
                                <?php esc_html_e( 'Price range', 'sandiegoweddingdirectory' ); ?>
                            </label>
                            <select id="venues-price-filter" name="price-filter">
                                <option value=""><?php esc_html_e( 'Any price', 'sandiegoweddingdirectory' ); ?></option>
                                <?php foreach ( $options['price_ranges'] as $range ) : ?>
                                    <?php $range_value = sprintf( '[%1$d-%2$d]', $range['min'], $range['max'] ); ?>
                                    <option value="<?php echo esc_attr( $range_value ); ?>" <?php selected( ! empty( $price_filter ) && $price_filter['min'] === $range['min'] && $price_filter['max'] === $range['max'] ); ?>>
                                        <?php echo esc_html( $range['label'] ); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>

                    <?php if ( ! empty( $options['capacity_ranges'] ) ) : ?>
                        <div class="venues-filter-form__group">
                            <label class="venues-filter-form__label" for="venues-capacity-filter">
                                <?php esc_html_e( 'Guest count', 'sandiegoweddingdirectory' ); ?>
                            </label>
                            <select id="venues-capacity-filter" name="capacity">
                                <option value=""><?php esc_html_e( 'Any guest count', 'sandiegoweddingdirectory' ); ?></option>
                                <?php foreach ( $options['capacity_ranges'] as $range ) : ?>
                                    <?php $range_value = sprintf( '[%1$d-%2$d]', $range['min'], $range['max'] ); ?>
                                    <option value="<?php echo esc_attr( $range_value ); ?>" <?php selected( ! empty( $capacity_filter ) && $capacity_filter['min'] === $range['min'] && $capacity_filter['max'] === $range['max'] ); ?>>
                                        <?php echo esc_html( $range['label'] ); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>

                    <?php foreach ( [
                        'venue_setting'   => $options['settings'],
                        'venue_amenities' => $options['amenities'],
                        'service'         => $options['services'],
                        'style'           => $options['styles'],
                    ] as $field_name => $field_options ) : ?>
                        <?php if ( empty( $field_options ) ) : ?>
                            <?php continue; ?>
                        <?php endif; ?>

                        <fieldset class="venues-filter-form__group">
                            <legend class="venues-filter-form__label">
                                <?php
                                echo esc_html(
                                    [
                                        'venue_setting'   => __( 'Setting', 'sandiegoweddingdirectory' ),
                                        'venue_amenities' => __( 'Amenities', 'sandiegoweddingdirectory' ),
                                        'service'         => __( 'Services', 'sandiegoweddingdirectory' ),
                                        'style'           => __( 'Style', 'sandiegoweddingdirectory' ),
                                    ][ $field_name ]
                                );
                                ?>
                            </legend>

                            <div class="venues-filter-form__options">
                                <?php foreach ( $field_options as $value => $label ) : ?>
                                    <label class="venues-filter-form__option">
                                        <input type="checkbox" name="<?php echo esc_attr( $field_name ); ?>[]" value="<?php echo esc_attr( $value ); ?>" <?php checked( in_array( $value, $filters[ $field_name ], true ) ); ?>>
                                        <span><?php echo esc_html( $label ); ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </fieldset>
                    <?php endforeach; ?>

                    <button class="btn btn--primary venues-filter-form__submit" type="submit">
                        <?php esc_html_e( 'Apply Filters', 'sandiegoweddingdirectory' ); ?>
                    </button>

                    <?php if ( $has_filters ) : ?>
                        <a class="btn btn--outline venues-filter-form__reset" href="<?php echo esc_url( $current_url ); ?>">
                            <?php esc_html_e( 'Clear Filters', 'sandiegoweddingdirectory' ); ?>
                        </a>
                    <?php endif; ?>
                </form>
            </aside>

            <div class="archive-filtered__content">
                <div class="venues-browser__results-head">
                    <div>
                        <h2 class="venues-browser__results-title"><?php esc_html_e( 'Wedding Venue Results', 'sandiegoweddingdirectory' ); ?></h2>
                        <p class="venues-browser__results-summary">
                            <?php
                            echo esc_html(
                                sprintf(
                                    _n( '%s venue matched your criteria.', '%s venues matched your criteria.', $venue_query->found_posts, 'sandiegoweddingdirectory' ),
                                    number_format_i18n( $venue_query->found_posts )
                                )
                            );
                            ?>
                        </p>
                    </div>
                </div>

                <?php if ( $venue_query->have_posts() ) : ?>
                    <div class="grid grid--3col archive-grid">
                        <?php
                        while ( $venue_query->have_posts() ) :
                            $venue_query->the_post();
                            get_template_part( 'template-parts/components/venue-card', null, [
                                'post_id'    => get_the_ID(),
                                'image_size' => 'medium',
                            ] );
                        endwhile;
                        wp_reset_postdata();
                        ?>
                    </div>

                    <?php
                    get_template_part( 'template-parts/components/pagination', null, [
                        'query' => $venue_query,
                    ] );
                    ?>
                <?php else : ?>
                    <div class="archive-empty">
                        <h3 class="archive-empty__title"><?php esc_html_e( 'No venues matched these filters.', 'sandiegoweddingdirectory' ); ?></h3>
                        <p><?php esc_html_e( 'Try broadening the location, price range, or amenities to see more results.', 'sandiegoweddingdirectory' ); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
