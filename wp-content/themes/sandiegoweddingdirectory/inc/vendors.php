<?php
/**
 * Vendor helpers for listing and profile templates.
 */

defined( 'ABSPATH' ) || exit;

/**
 * Force vendor taxonomy links onto the /vendors/ base.
 *
 * @param array  $args     Taxonomy args.
 * @param string $taxonomy Taxonomy slug.
 * @return array
 */
function sdwdv2_filter_vendor_category_taxonomy_args( $args, $taxonomy ) {
    if ( 'vendor-category' !== $taxonomy ) {
        return $args;
    }

    $args['rewrite'] = [
        'slug'         => 'vendors',
        'with_front'   => false,
        'hierarchical' => true,
    ];

    return $args;
}
add_filter( 'register_taxonomy_args', 'sdwdv2_filter_vendor_category_taxonomy_args', 20, 2 );

/**
 * Flush vendor rewrite rules once after the taxonomy base changes.
 */
function sdwdv2_maybe_flush_vendor_rewrite_rules() {
    if ( ! taxonomy_exists( 'vendor-category' ) ) {
        return;
    }

    $version = 'vendor-category-vendors-base-v1';

    if ( get_option( 'sdwdv2_vendor_rewrite_version' ) === $version ) {
        return;
    }

    flush_rewrite_rules( false );
    update_option( 'sdwdv2_vendor_rewrite_version', $version );
}
add_action( 'init', 'sdwdv2_maybe_flush_vendor_rewrite_rules', 99 );

/**
 * Get the canonical vendors landing URL.
 *
 * @return string
 */
function sdwdv2_get_vendors_url() {
    return home_url( '/vendors/' );
}

/**
 * Get vendor category terms while excluding the venue crossover term.
 *
 * @param array $args Extra get_terms arguments.
 * @return WP_Term[]
 */
function sdwdv2_get_vendor_category_terms( $args = [] ) {
    $terms = get_terms(
        array_merge(
            [
                'taxonomy'   => 'vendor-category',
                'hide_empty' => false,
                'orderby'    => 'name',
                'order'      => 'ASC',
                'parent'     => 0,
            ],
            $args
        )
    );

    if ( is_wp_error( $terms ) || empty( $terms ) ) {
        return [];
    }

    if ( isset( $args['fields'] ) && 'ids' === $args['fields'] ) {
        return array_values( array_map( 'absint', (array) $terms ) );
    }

    return array_values(
        array_filter(
            (array) $terms,
            static function ( $term ) {
                return $term instanceof WP_Term
                    && ! in_array( $term->slug, [ 'venues', 'venue', 'wedding-venues' ], true );
            }
        )
    );
}

/**
 * Get all usable vendor category IDs.
 *
 * @return int[]
 */
function sdwdv2_get_vendor_category_ids() {
    return array_values(
        array_map(
            'absint',
            wp_list_pluck( sdwdv2_get_vendor_category_terms(), 'term_id' )
        )
    );
}

/**
 * Build the canonical vendor category URL.
 *
 * @param int|string|WP_Term $term Term instance, ID, or slug.
 * @return string
 */
function sdwdv2_get_vendor_category_url( $term ) {
    if ( $term instanceof WP_Term ) {
        $term_object = $term;
    } elseif ( is_numeric( $term ) ) {
        $term_object = get_term( absint( $term ), 'vendor-category' );
    } else {
        $term_object = get_term_by( 'slug', sanitize_title( $term ), 'vendor-category' );
    }

    if ( $term_object instanceof WP_Term ) {
        $term_link = get_term_link( $term_object );

        if ( ! is_wp_error( $term_link ) ) {
            return $term_link;
        }

        return home_url( '/vendors/' . $term_object->slug . '/' );
    }

    return sdwdv2_get_vendors_url();
}

/**
 * Get the theme-owned vendor category image URL.
 *
 * @param WP_Term|int|string $term Vendor term.
 * @return string
 */
function sdwdv2_get_vendor_category_image_url( $term ) {
    if ( $term instanceof WP_Term ) {
        $term_object = $term;
    } elseif ( is_numeric( $term ) ) {
        $term_object = get_term( absint( $term ), 'vendor-category' );
    } else {
        $term_object = get_term_by( 'slug', sanitize_title( $term ), 'vendor-category' );
    }

    $placeholder = get_theme_file_uri( 'assets/images/categories/venues.png' );

    if ( ! $term_object instanceof WP_Term ) {
        return $placeholder;
    }

    $image_map = [
        'wedding-planning'   => 'planners.png',
        'wedding-planners'   => 'planners.png',
        'planner'            => 'planners.png',
        'planners'           => 'planners.png',
        'bands'              => 'bands.png',
        'cakes'              => 'cakes.png',
        'catering'           => 'caterers.png',
        'caterers'           => 'caterers.png',
        'ceremony-music'     => 'ceremony-music.png',
        'djs'                => 'djs.png',
        'dress-attire'       => 'attire.png',
        'dress-and-attire'   => 'attire.png',
        'event-rentals'      => 'event-rentals.png',
        'favors-gifts'       => 'favors-and-gifts.png',
        'favors-and-gifts'   => 'favors-and-gifts.png',
        'flowers'            => 'flowers.png',
        'hair-makeup'        => 'hair-and-makeup.png',
        'hair-and-makeup'    => 'hair-and-makeup.png',
        'invitations'        => 'invitations.png',
        'jewelry'            => 'jewelry.png',
        'lighting-decor'     => 'lighting-and-decor.png',
        'lighting-and-decor' => 'lighting-and-decor.png',
        'officiants'         => 'officiants.png',
        'photo-booths'       => 'photo-booths.png',
        'photography'        => 'photography.png',
        'photographers'      => 'photographers.png',
        'transportation'     => 'transportation.png',
        'travel-agents'      => 'travel-agents.png',
        'videography'        => 'videography.png',
    ];

    $slug = sanitize_title( $term_object->slug );

    if ( isset( $image_map[ $slug ] ) ) {
        return get_theme_file_uri( 'assets/images/categories/' . $image_map[ $slug ] );
    }

    return $placeholder;
}

/**
 * Read a vendor-category term field.
 *
 * @param string $field   Field key.
 * @param int    $term_id Term ID.
 * @return mixed
 */
function sdwdv2_get_vendor_term_field( $field, $term_id ) {
    if ( function_exists( 'sdwd_get_term_field' ) ) {
        return sdwd_get_term_field( $field, $term_id );
    }

    return get_term_meta( $term_id, $field, true );
}

/**
 * Read a vendor-category repeater field.
 *
 * @param string   $field   Repeater key.
 * @param int      $term_id Term ID.
 * @param string[] $columns Columns to read.
 * @return array
 */
function sdwdv2_get_vendor_term_repeater( $field, $term_id, $columns = [] ) {
    if ( function_exists( 'sdwd_get_term_repeater' ) ) {
        $rows = sdwd_get_term_repeater( $field, $term_id, $columns );

        if ( is_array( $rows ) ) {
            return $rows;
        }
    }

    $count = absint( get_term_meta( $term_id, $field, true ) );

    if ( $count <= 0 ) {
        return [];
    }

    $rows = [];

    for ( $index = 0; $index < $count; $index++ ) {
        $row = [];

        foreach ( $columns as $column ) {
            $row[ $column ] = get_term_meta( $term_id, "{$field}_{$index}_{$column}", true );
        }

        if ( array_filter( $row, static fn( $value ) => '' !== (string) $value ) ) {
            $rows[] = $row;
        }
    }

    return $rows;
}

/**
 * Aggregate vendor pricing options across terms.
 *
 * @param int $category_id Optional fixed category ID.
 * @return array[]
 */
function sdwdv2_get_vendor_price_options( $category_id = 0 ) {
    $term_ids = $category_id ? [ absint( $category_id ) ] : sdwdv2_get_vendor_category_ids();
    $options  = [];

    foreach ( $term_ids as $term_id ) {
        if ( ! sdwdv2_get_vendor_term_field( 'vendor_pricing_available', $term_id ) ) {
            continue;
        }

        $rows = sdwdv2_get_vendor_term_repeater( 'vendor_pricing_options', $term_id, [ 'label', 'min', 'max' ] );

        foreach ( $rows as $row ) {
            $min = absint( $row['min'] ?? 0 );
            $max = absint( $row['max'] ?? 0 );

            if ( ! $min && ! $max ) {
                continue;
            }

            $key             = "{$min}-{$max}";
            $options[ $key ] = [
                'value' => $key,
                'min'   => $min,
                'max'   => $max,
                'label' => sanitize_text_field( $row['label'] ?? $key ),
            ];
        }
    }

    return array_values( $options );
}

/**
 * Aggregate vendor checkbox/select options across terms.
 *
 * @param string $field       Repeater field.
 * @param string $enable_key  Availability key.
 * @param int    $category_id Optional fixed category ID.
 * @return array
 */
function sdwdv2_get_vendor_value_options( $field, $enable_key, $category_id = 0 ) {
    $term_ids = $category_id ? [ absint( $category_id ) ] : sdwdv2_get_vendor_category_ids();
    $options  = [];

    foreach ( $term_ids as $term_id ) {
        if ( ! sdwdv2_get_vendor_term_field( $enable_key, $term_id ) ) {
            continue;
        }

        $rows = sdwdv2_get_vendor_term_repeater( $field, $term_id, [ 'label', 'value' ] );

        foreach ( $rows as $row ) {
            if ( empty( $row['value'] ) ) {
                continue;
            }

            $value             = sanitize_text_field( $row['value'] );
            $options[ $value ] = sanitize_text_field( $row['label'] ?? $value );
        }
    }

    return $options;
}

/**
 * Get the current vendor filter state.
 *
 * @param array $overrides Context overrides.
 * @return array
 */
function sdwdv2_get_vendor_filter_state( $overrides = [] ) {
    $sort = isset( $overrides['sort_by'] ) ? $overrides['sort_by'] : ( $_GET['sort'] ?? '' ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
    $sort = sanitize_key( wp_unslash( (string) $sort ) );

    if ( ! in_array( $sort, [ 'title-asc', 'title-desc', 'newest' ], true ) ) {
        $sort = 'title-asc';
    }

    $paged = max( 1, get_query_var( 'paged', 1 ) );

    if ( isset( $_GET['paged'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $paged = max( $paged, absint( wp_unslash( $_GET['paged'] ) ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
    }

    return [
        'search'          => isset( $overrides['search'] )
            ? sanitize_text_field( $overrides['search'] )
            : sanitize_text_field( wp_unslash( (string) ( $_GET['vendor_search'] ?? '' ) ) ), // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        'category_id'     => isset( $overrides['category_id'] )
            ? absint( $overrides['category_id'] )
            : absint( $_GET['vendor_cat'] ?? 0 ), // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        'vendor_pricing'  => sdwdv2_normalize_request_list( $overrides['vendor_pricing'] ?? ( $_GET['vendor_pricing'] ?? [] ) ), // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        'vendor_services' => sdwdv2_normalize_request_list( $overrides['vendor_services'] ?? ( $_GET['vendor_services'] ?? [] ) ), // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        'vendor_style'    => sdwdv2_normalize_request_list( $overrides['vendor_style'] ?? ( $_GET['vendor_style'] ?? [] ) ), // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        'vendor_specialties' => sdwdv2_normalize_request_list( $overrides['vendor_specialties'] ?? ( $_GET['vendor_specialties'] ?? [] ) ), // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        'sort_by'         => $sort,
        'paged'           => isset( $overrides['paged'] ) ? max( 1, absint( $overrides['paged'] ) ) : $paged,
        'posts_per_page'  => isset( $overrides['posts_per_page'] ) ? absint( $overrides['posts_per_page'] ) : 12,
        'fixed_category'  => ! empty( $overrides['fixed_category'] ),
    ];
}

/**
 * Determine whether vendor filters are active.
 *
 * @param array $filters Filter state.
 * @return bool
 */
function sdwdv2_has_active_vendor_filters( $filters ) {
    return ! empty( $filters['search'] )
        || ! empty( $filters['category_id'] )
        || ! empty( $filters['vendor_pricing'] )
        || ! empty( $filters['vendor_services'] )
        || ! empty( $filters['vendor_style'] )
        || ! empty( $filters['vendor_specialties'] );
}

/**
 * Get all vendor filter UI options.
 *
 * @param array $filters Filter state.
 * @return array
 */
function sdwdv2_get_vendor_filter_options( $filters ) {
    $category_id = absint( $filters['category_id'] ?? 0 );

    return [
        'categories'   => sdwdv2_get_vendor_category_terms(),
        'pricing'      => sdwdv2_get_vendor_price_options( $category_id ),
        'services'     => sdwdv2_get_vendor_value_options( 'vendor_services_options', 'vendor_services_available', $category_id ),
        'styles'       => sdwdv2_get_vendor_value_options( 'vendor_style_options', 'vendor_style_available', $category_id ),
        'specialties'  => sdwdv2_get_vendor_value_options( 'vendor_specialties_options', 'vendor_specialties_available', $category_id ),
        'sorts'        => [
            'title-asc'  => __( 'A to Z', 'sandiegoweddingdirectory' ),
            'title-desc' => __( 'Z to A', 'sandiegoweddingdirectory' ),
            'newest'     => __( 'Newest', 'sandiegoweddingdirectory' ),
        ],
    ];
}

/**
 * Build vendor listing query args.
 *
 * @param array $filters Filter state.
 * @return array
 */
function sdwdv2_build_vendor_query_args( $filters ) {
    $meta_query = [];

    if ( ! empty( $filters['vendor_pricing'] ) ) {
        $pricing_clause = [ 'relation' => 'OR' ];

        foreach ( $filters['vendor_pricing'] as $value ) {
            $pricing_clause[] = [
                'key'     => 'vendor_pricing',
                'compare' => 'LIKE',
                'value'   => '"' . sanitize_text_field( $value ) . '"',
            ];
        }

        $meta_query[] = $pricing_clause;
    }

    foreach ( [
        'vendor_services',
        'vendor_style',
        'vendor_specialties',
    ] as $meta_key ) {
        if ( empty( $filters[ $meta_key ] ) ) {
            continue;
        }

        foreach ( $filters[ $meta_key ] as $value ) {
            $meta_query[] = [
                'key'     => $meta_key,
                'compare' => 'LIKE',
                'value'   => '"' . sanitize_text_field( $value ) . '"',
            ];
        }
    }

    $query_args = [
        'post_type'           => 'vendor',
        'post_status'         => 'publish',
        'posts_per_page'      => absint( $filters['posts_per_page'] ?? 12 ),
        'paged'               => max( 1, absint( $filters['paged'] ?? 1 ) ),
        'ignore_sticky_posts' => true,
    ];

    if ( ! empty( $meta_query ) ) {
        $query_args['meta_query'] = array_merge( [ 'relation' => 'AND' ], $meta_query );
    }

    if ( ! empty( $filters['search'] ) ) {
        $query_args['s'] = sanitize_text_field( $filters['search'] );
    }

    if ( ! empty( $filters['category_id'] ) ) {
        $query_args['tax_query'] = [
            [
                'taxonomy' => 'vendor-category',
                'field'    => 'term_id',
                'terms'    => [ absint( $filters['category_id'] ) ],
            ],
        ];
    }

    switch ( $filters['sort_by'] ?? 'title-asc' ) {
        case 'title-desc':
            $query_args['orderby'] = 'title';
            $query_args['order']   = 'DESC';
            break;

        case 'newest':
            $query_args['orderby'] = 'date';
            $query_args['order']   = 'DESC';
            break;

        case 'title-asc':
        default:
            $query_args['orderby'] = 'title';
            $query_args['order']   = 'ASC';
            break;
    }

    return $query_args;
}

/**
 * Get the public-facing vendor company name.
 *
 * @param int $post_id Vendor post ID.
 * @return string
 */
function sdwdv2_get_vendor_company_name( $post_id ) {
    $company_name = sanitize_text_field( get_post_meta( $post_id, 'company_name', true ) );

    if ( '' !== $company_name ) {
        return $company_name;
    }

    return get_the_title( $post_id );
}

/**
 * Get the primary vendor category term.
 *
 * @param int $post_id Vendor post ID.
 * @return WP_Term|null
 */
function sdwdv2_get_vendor_primary_category( $post_id ) {
    $terms = wp_get_post_terms( $post_id, 'vendor-category' );

    if ( is_wp_error( $terms ) || empty( $terms ) ) {
        return null;
    }

    return $terms[0] instanceof WP_Term ? $terms[0] : null;
}

/**
 * Get the best vendor image attachment ID.
 *
 * @param int $post_id Vendor post ID.
 * @return int
 */
function sdwdv2_get_vendor_image_id( $post_id ) {
    $banner_id = absint( get_post_meta( $post_id, 'profile_banner', true ) );

    if ( $banner_id ) {
        return $banner_id;
    }

    return absint( get_post_thumbnail_id( $post_id ) );
}

/**
 * Get vendor gallery images.
 *
 * @param int $post_id Vendor post ID.
 * @return array[]
 */
function sdwdv2_get_vendor_gallery_images( $post_id ) {
    $image_ids = [];

    foreach ( [
        sdwdv2_get_vendor_image_id( $post_id ),
        ...array_filter( array_map( 'absint', explode( ',', (string) get_post_meta( $post_id, 'venue_gallery', true ) ) ) ),
    ] as $image_id ) {
        if ( $image_id > 0 && ! in_array( $image_id, $image_ids, true ) ) {
            $image_ids[] = $image_id;
        }
    }

    $images = [];

    foreach ( $image_ids as $image_id ) {
        $full_url = wp_get_attachment_image_url( $image_id, 'full' );
        $thumb    = wp_get_attachment_image_url( $image_id, 'large' );

        if ( ! $full_url || ! $thumb ) {
            continue;
        }

        $images[] = [
            'thumb' => $thumb,
            'full'  => $full_url,
            'alt'   => get_post_meta( $image_id, '_wp_attachment_image_alt', true ) ?: sdwdv2_get_vendor_company_name( $post_id ),
        ];
    }

    if ( empty( $images ) ) {
        $placeholder = get_theme_file_uri( 'assets/images/placeholders/vendor-post/vendor-post.jpg' );

        $images[] = [
            'thumb' => $placeholder,
            'full'  => $placeholder,
            'alt'   => sdwdv2_get_vendor_company_name( $post_id ),
        ];
    }

    return $images;
}

/**
 * Parse vendor pricing tiers.
 *
 * @param int $post_id Vendor post ID.
 * @return array[]
 */
function sdwdv2_get_vendor_pricing_tiers( $post_id ) {
    $saved_data = get_post_meta( $post_id, 'sdwd_vendor_pricing_tiers', true );

    if ( ! is_array( $saved_data ) || empty( $saved_data['tiers'] ) || ! is_array( $saved_data['tiers'] ) ) {
        return [];
    }

    $tiers = [];

    foreach ( $saved_data['tiers'] as $tier ) {
        $items = [];

        foreach ( (array) ( $tier['items'] ?? [] ) as $item ) {
            if ( empty( $item['text'] ) ) {
                continue;
            }

            $items[] = [
                'text'     => sanitize_text_field( $item['text'] ),
                'included' => ! empty( $item['included'] ),
            ];
        }

        $tiers[] = [
            'title' => sanitize_text_field( $tier['title'] ?? '' ),
            'price' => is_numeric( $tier['price'] ?? null ) ? (float) $tier['price'] : null,
            'hours' => absint( $tier['hours'] ?? 0 ),
            'items' => $items,
        ];
    }

    return array_values( array_filter( $tiers, static fn( $tier ) => ! empty( $tier['title'] ) || null !== $tier['price'] || ! empty( $tier['items'] ) ) );
}

/**
 * Get the formatted starting price for a vendor.
 *
 * @param int $post_id Vendor post ID.
 * @return string
 */
function sdwdv2_get_vendor_starting_price( $post_id ) {
    $tiers  = sdwdv2_get_vendor_pricing_tiers( $post_id );
    $prices = [];

    foreach ( $tiers as $tier ) {
        if ( null !== $tier['price'] ) {
            $prices[] = (float) $tier['price'];
        }
    }

    if ( ! empty( $prices ) ) {
        return sprintf(
            /* translators: %s: starting price. */
            __( 'Starting at $%s', 'sandiegoweddingdirectory' ),
            number_format_i18n( min( $prices ) )
        );
    }

    return __( 'Pricing available on request', 'sandiegoweddingdirectory' );
}

/**
 * Get vendor contact data.
 *
 * @param int $post_id Vendor post ID.
 * @return array
 */
function sdwdv2_get_vendor_contact_data( $post_id ) {
    $website = esc_url_raw( get_post_meta( $post_id, 'company_website', true ) );

    if ( $website && ! preg_match( '#^https?://#i', $website ) ) {
        $website = 'https://' . ltrim( $website, '/' );
    }

    return [
        'address'      => sanitize_text_field( get_post_meta( $post_id, 'company_address', true ) ),
        'email'        => sanitize_email( get_post_meta( $post_id, 'company_email', true ) ),
        'phone'        => sanitize_text_field( get_post_meta( $post_id, 'company_contact', true ) ),
        'website'      => $website,
        'contact_name' => sanitize_text_field( get_post_meta( $post_id, 'company_contact_name', true ) ),
    ];
}

/**
 * Get vendor overview HTML.
 *
 * @param int $post_id Vendor post ID.
 * @return string
 */
function sdwdv2_get_vendor_overview_html( $post_id ) {
    $content_raw = get_post_meta( $post_id, 'company_about', true );

    if ( '' === trim( (string) $content_raw ) ) {
        $content_raw = get_post_meta( $post_id, 'content', true );
    }

    if ( '' === trim( (string) $content_raw ) ) {
        $content_raw = get_post_field( 'post_content', $post_id );
    }

    $content = apply_filters( 'the_content', $content_raw );

    if ( '' === trim( wp_strip_all_tags( $content ) ) ) {
        $content = sprintf(
            '<p>%s</p>',
            esc_html(
                sprintf(
                    __( '%s is part of the SD Wedding Directory vendor collection. Reach out for pricing, services, and availability details.', 'sandiegoweddingdirectory' ),
                    sdwdv2_get_vendor_company_name( $post_id )
                )
            )
        );
    }

    return $content;
}

/**
 * Convert vendor profile meta values into readable labels.
 *
 * @param int         $post_id   Vendor post ID.
 * @param string      $meta_key  Meta key.
 * @param int|WP_Term $category  Optional category context.
 * @return string[]
 */
function sdwdv2_get_vendor_meta_labels( $post_id, $meta_key, $category = 0 ) {
    $raw = get_post_meta( $post_id, $meta_key, true );

    if ( empty( $raw ) ) {
        return [];
    }

    if ( $category instanceof WP_Term ) {
        $category_id = $category->term_id;
    } else {
        $category_id = absint( $category );
    }

    if ( is_array( $raw ) ) {
        $values = array_map( 'sanitize_text_field', $raw );
    } else {
        $values = array_map( 'sanitize_text_field', explode( ',', (string) $raw ) );
    }

    $values = array_values( array_filter( $values ) );

    if ( empty( $values ) ) {
        return [];
    }

    $options = [];

    switch ( $meta_key ) {
        case 'vendor_services':
            $options = sdwdv2_get_vendor_value_options( 'vendor_services_options', 'vendor_services_available', $category_id );
            break;

        case 'vendor_style':
            $options = sdwdv2_get_vendor_value_options( 'vendor_style_options', 'vendor_style_available', $category_id );
            break;

        case 'vendor_specialties':
            $options = sdwdv2_get_vendor_value_options( 'vendor_specialties_options', 'vendor_specialties_available', $category_id );
            break;
    }

    $labels = [];

    foreach ( $values as $value ) {
        $labels[] = $options[ $value ] ?? ucwords( str_replace( [ '-', '_' ], ' ', $value ) );
    }

    return array_values( array_unique( array_filter( $labels ) ) );
}

/**
 * Get vendor reviews query.
 *
 * @param int $post_id Vendor post ID.
 * @param int $limit   Number of reviews to fetch.
 * @return WP_Query
 */
function sdwdv2_get_vendor_reviews_query( $post_id, $limit = 4 ) {
    $query = new WP_Query(
        [
            'post_type'      => 'venue-review',
            'post_status'    => 'publish',
            'posts_per_page' => absint( $limit ),
            'orderby'        => 'date',
            'order'          => 'DESC',
            'meta_query'     => [
                [
                    'key'     => 'vendor_id',
                    'type'    => 'numeric',
                    'compare' => '=',
                    'value'   => absint( $post_id ),
                ],
            ],
        ]
    );

    if ( $query->have_posts() ) {
        return $query;
    }

    wp_reset_postdata();

    return new WP_Query(
        [
            'post_type'      => 'venue-review',
            'post_status'    => 'publish',
            'posts_per_page' => absint( $limit ),
            'orderby'        => 'date',
            'order'          => 'DESC',
            'meta_query'     => [
                [
                    'key'     => 'venue_id',
                    'type'    => 'numeric',
                    'compare' => '=',
                    'value'   => absint( $post_id ),
                ],
            ],
        ]
    );
}

/**
 * Get the vendor review summary text.
 *
 * @param int $post_id Vendor post ID.
 * @return string
 */
function sdwdv2_get_vendor_review_summary( $post_id ) {
    return sanitize_textarea_field( get_post_meta( $post_id, 'vendor_review_ai_summary', true ) );
}

/**
 * Get preferred venues connected to a vendor.
 *
 * @param int $post_id Vendor post ID.
 * @param int $limit   Optional limit.
 * @return int[]
 */
function sdwdv2_get_vendor_endorsed_venues( $post_id, $limit = 3 ) {
    $raw = get_post_meta( $post_id, 'sdwd_endorsed_venues', true );

    if ( is_array( $raw ) ) {
        $venue_ids = array_map( 'absint', $raw );
    } else {
        $venue_ids = array_map( 'absint', explode( ',', (string) $raw ) );
    }

    $venue_ids = array_values( array_filter( array_unique( $venue_ids ) ) );

    if ( $limit > 0 ) {
        $venue_ids = array_slice( $venue_ids, 0, $limit );
    }

    return $venue_ids;
}
