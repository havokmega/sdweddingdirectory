<?php
/**
 * Shared vendor listing renderer.
 *
 * @param array $args {
 *     @type string $title             Page title.
 *     @type string $desc              Optional description.
 *     @type array  $breadcrumbs       Breadcrumb items.
 *     @type string $current_url       Current page URL.
 *     @type int    $fixed_category_id Optional fixed category term ID.
 *     @type bool   $show_discovery    Whether to show landing discovery sections.
 * }
 */

$title             = $args['title'] ?? '';
$desc              = $args['desc'] ?? '';
$breadcrumbs       = $args['breadcrumbs'] ?? [];
$current_url       = $args['current_url'] ?? sdwdv2_get_vendors_url();
$fixed_category_id = absint( $args['fixed_category_id'] ?? 0 );
$show_discovery    = ! empty( $args['show_discovery'] );

$filter_overrides = [
    'fixed_category' => $fixed_category_id > 0,
    'posts_per_page' => 12,
];

if ( $fixed_category_id > 0 ) {
    $filter_overrides['category_id'] = $fixed_category_id;
}

$filters           = sdwdv2_get_vendor_filter_state( $filter_overrides );
$options           = sdwdv2_get_vendor_filter_options( $filters );
$vendor_query      = new WP_Query( sdwdv2_build_vendor_query_args( $filters ) );
$selected_category = ! empty( $filters['category_id'] ) ? get_term( absint( $filters['category_id'] ), 'vendor-category' ) : null;
$has_filters       = sdwdv2_has_active_vendor_filters( $filters );

$location_terms = get_terms( [
    'taxonomy'   => 'venue-location',
    'hide_empty' => true,
    'parent'     => 0,
    'orderby'    => 'name',
    'order'      => 'ASC',
] );
if ( is_wp_error( $location_terms ) ) {
    $location_terms = [];
}

$featured_categories = [];
$category_link_rows  = [];

if ( $show_discovery && ! $has_filters ) {
    $featured_categories = sdwdv2_get_vendor_category_terms(
        [
            'hide_empty' => true,
            'orderby'    => 'count',
            'order'      => 'DESC',
            'number'     => 12,
        ]
    );

    $category_links = array_map(
        static function ( $term ) {
            return [
                'label' => $term->name,
                'url'   => sdwdv2_get_vendor_category_url( $term ),
            ];
        },
        $options['categories']
    );

    $category_link_rows = array_chunk( $category_links, 5 );
}

$results_heading = $selected_category instanceof WP_Term
    ? $selected_category->name
    : __( 'All Wedding Vendors', 'sandiegoweddingdirectory' );

if ( ! empty( $filters['search'] ) ) {
    $results_heading = sprintf(
        /* translators: %s: search term. */
        __( 'Results for "%s"', 'sandiegoweddingdirectory' ),
        $filters['search']
    );
}

$results_summary = sprintf(
    /* translators: %s: number of vendors. */
    _n( '%s vendor found', '%s vendors found', $vendor_query->found_posts, 'sandiegoweddingdirectory' ),
    number_format_i18n( $vendor_query->found_posts )
);

?>
<div class="vendors-listing-hero">
    <div class="container">
        <div class="vendors-listing-hero__layout">
            <div class="vendors-listing-hero__text">
                <?php if ( ! empty( $breadcrumbs ) ) : ?>
                    <?php get_template_part( 'template-parts/components/breadcrumbs', null, [ 'items' => $breadcrumbs ] ); ?>
                <?php endif; ?>
                <h1 class="vendors-listing-hero__title"><?php echo esc_html( $title ); ?></h1>
            </div>

            <form class="vendors-hero-search" method="get" action="<?php echo esc_url( $current_url ); ?>">
                <div class="vendors-hero-search__field vendors-hero-search__field--category">
                    <?php if ( $fixed_category_id && $selected_category && ! is_wp_error( $selected_category ) ) : ?>
                        <span class="vendors-hero-search__text"><?php echo esc_html( sprintf( __( 'Wedding %s', 'sandiegoweddingdirectory' ), $selected_category->name ) ); ?></span>
                    <?php else : ?>
                        <select name="vendor_cat" aria-label="<?php esc_attr_e( 'Vendor category', 'sandiegoweddingdirectory' ); ?>">
                            <option value=""><?php esc_html_e( 'Wedding Vendors', 'sandiegoweddingdirectory' ); ?></option>
                            <?php foreach ( $options['categories'] as $category ) : ?>
                                <option value="<?php echo esc_attr( $category->term_id ); ?>" <?php selected( $filters['category_id'], $category->term_id ); ?>>
                                    <?php echo esc_html( $category->name ); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>
                </div>

                <div class="vendors-hero-search__field vendors-hero-search__field--location">
                    <select name="location" aria-label="<?php esc_attr_e( 'Location', 'sandiegoweddingdirectory' ); ?>">
                        <option value=""><?php esc_html_e( 'Location', 'sandiegoweddingdirectory' ); ?></option>
                        <?php foreach ( $location_terms as $loc ) : ?>
                            <option value="<?php echo esc_attr( $loc->slug ); ?>"><?php echo esc_html( $loc->name ); ?></option>
                        <?php endforeach; ?>
                    </select>

                    <button class="btn btn--primary vendors-hero-search__submit" type="submit" aria-label="<?php esc_attr_e( 'Search', 'sandiegoweddingdirectory' ); ?>">
                        <span class="icon-search" aria-hidden="true"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if ( $show_discovery && ! $has_filters && ( ! empty( $featured_categories ) || ! empty( $category_link_rows ) ) ) : ?>
    <section class="section vendors-discovery">
        <div class="container">
            <?php if ( ! empty( $featured_categories ) ) : ?>
                <?php
                get_template_part(
                    'template-parts/components/section-title',
                    null,
                    [
                        'heading' => __( 'Browse Wedding Vendors by Category', 'sandiegoweddingdirectory' ),
                        'desc'    => __( 'Start with the vendor categories couples shop first, then narrow the directory with filters that match your style and budget.', 'sandiegoweddingdirectory' ),
                    ]
                );
                ?>

                <div class="vendors-carousel">
                    <div class="vendors-carousel__track">
                        <?php foreach ( $featured_categories as $category ) : ?>
                            <a class="vendors-category-card" href="<?php echo esc_url( sdwdv2_get_vendor_category_url( $category ) ); ?>">
                                <span class="vendors-category-card__media">
                                    <img src="<?php echo esc_url( sdwdv2_get_vendor_category_image_url( $category ) ); ?>" alt="<?php echo esc_attr( $category->name ); ?>">
                                </span>
                                <span class="vendors-category-card__body">
                                    <strong class="vendors-category-card__title"><?php echo esc_html( $category->name ); ?></strong>
                                    <span class="vendors-category-card__count">
                                        <?php
                                        echo esc_html(
                                            sprintf(
                                                _n( '%s vendor', '%s vendors', $category->count, 'sandiegoweddingdirectory' ),
                                                number_format_i18n( $category->count )
                                            )
                                        );
                                        ?>
                                    </span>
                                </span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php
            if ( ! empty( $category_link_rows ) ) {
                get_template_part(
                    'template-parts/components/inline-link-grid',
                    null,
                    [
                        'heading' => __( 'All Vendor Categories', 'sandiegoweddingdirectory' ),
                        'rows'    => $category_link_rows,
                    ]
                );
            }
            ?>
        </div>
    </section>
<?php endif; ?>

<section class="section vendors-browser">
    <div class="container">
        <div class="archive-filtered vendors-browser__layout">
            <aside class="archive-filtered__sidebar">
                <form class="vendors-filter-form" method="get" action="<?php echo esc_url( $current_url ); ?>">
                    <?php if ( ! empty( $filters['search'] ) ) : ?>
                        <input type="hidden" name="vendor_search" value="<?php echo esc_attr( $filters['search'] ); ?>">
                    <?php endif; ?>

                    <?php if ( ! empty( $filters['sort_by'] ) ) : ?>
                        <input type="hidden" name="sort" value="<?php echo esc_attr( $filters['sort_by'] ); ?>">
                    <?php endif; ?>

                    <?php if ( ! $fixed_category_id && ! empty( $filters['category_id'] ) ) : ?>
                        <input type="hidden" name="vendor_cat" value="<?php echo esc_attr( $filters['category_id'] ); ?>">
                    <?php endif; ?>

                    <?php if ( ! empty( $options['pricing'] ) ) : ?>
                        <fieldset class="vendors-filter-form__group">
                            <legend class="vendors-filter-form__label"><?php esc_html_e( 'Pricing', 'sandiegoweddingdirectory' ); ?></legend>
                            <div class="vendors-filter-form__options">
                                <?php foreach ( $options['pricing'] as $price_option ) : ?>
                                    <label class="vendors-filter-form__option">
                                        <input type="checkbox" name="vendor_pricing[]" value="<?php echo esc_attr( $price_option['value'] ); ?>" <?php checked( in_array( $price_option['value'], $filters['vendor_pricing'], true ) ); ?>>
                                        <span><?php echo esc_html( $price_option['label'] ); ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </fieldset>
                    <?php endif; ?>

                    <?php foreach ( [
                        'vendor_services'    => [ 'label' => __( 'Services', 'sandiegoweddingdirectory' ), 'options' => $options['services'] ],
                        'vendor_style'       => [ 'label' => __( 'Style', 'sandiegoweddingdirectory' ), 'options' => $options['styles'] ],
                        'vendor_specialties' => [ 'label' => __( 'Specialties', 'sandiegoweddingdirectory' ), 'options' => $options['specialties'] ],
                    ] as $field_name => $field_group ) : ?>
                        <?php if ( empty( $field_group['options'] ) ) : ?>
                            <?php continue; ?>
                        <?php endif; ?>

                        <fieldset class="vendors-filter-form__group">
                            <legend class="vendors-filter-form__label"><?php echo esc_html( $field_group['label'] ); ?></legend>
                            <div class="vendors-filter-form__options">
                                <?php foreach ( $field_group['options'] as $value => $label ) : ?>
                                    <label class="vendors-filter-form__option">
                                        <input type="checkbox" name="<?php echo esc_attr( $field_name ); ?>[]" value="<?php echo esc_attr( $value ); ?>" <?php checked( in_array( $value, $filters[ $field_name ], true ) ); ?>>
                                        <span><?php echo esc_html( $label ); ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </fieldset>
                    <?php endforeach; ?>

                    <button class="btn btn--primary vendors-filter-form__submit" type="submit">
                        <?php esc_html_e( 'Apply Filters', 'sandiegoweddingdirectory' ); ?>
                    </button>

                    <?php if ( $has_filters ) : ?>
                        <a class="btn btn--outline vendors-filter-form__reset" href="<?php echo esc_url( $current_url ); ?>">
                            <?php esc_html_e( 'Clear Filters', 'sandiegoweddingdirectory' ); ?>
                        </a>
                    <?php endif; ?>
                </form>
            </aside>

            <div class="archive-filtered__content">
                <div class="vendors-browser__results-head">
                    <div>
                        <h2 class="vendors-browser__results-title"><?php echo esc_html( $results_heading ); ?></h2>
                        <p class="vendors-browser__results-summary"><?php echo esc_html( $results_summary ); ?></p>
                    </div>
                </div>

                <?php if ( $vendor_query->have_posts() ) : ?>
                    <div class="grid grid--3col">
                        <?php
                        while ( $vendor_query->have_posts() ) :
                            $vendor_query->the_post();
                            get_template_part(
                                'template-parts/components/vendor-card',
                                null,
                                [
                                    'post_id'    => get_the_ID(),
                                    'image_size' => 'medium',
                                ]
                            );
                        endwhile;
                        wp_reset_postdata();
                        ?>
                    </div>

                    <?php
                    get_template_part(
                        'template-parts/components/pagination',
                        null,
                        [
                            'query' => $vendor_query,
                        ]
                    );
                    ?>
                <?php else : ?>
                    <div class="archive-empty">
                        <h3 class="archive-empty__title"><?php esc_html_e( 'No vendors matched those filters.', 'sandiegoweddingdirectory' ); ?></h3>
                        <p><?php esc_html_e( 'Try broadening your category or clearing a few filter selections.', 'sandiegoweddingdirectory' ); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
