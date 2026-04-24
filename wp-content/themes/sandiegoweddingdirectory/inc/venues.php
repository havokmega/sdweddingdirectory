<?php
/**
 * Venue helpers for listing and profile templates.
 */

defined( 'ABSPATH' ) || exit;

/**
 * Get the canonical venues landing URL.
 */
function sdwdv2_get_venues_url() {
    return home_url( '/venues/' );
}

/**
 * Build a venues directory URL from query arguments.
 *
 * @param array $args Query arguments.
 * @return string
 */
function sdwdv2_get_venue_directory_url( $args = [] ) {
    $query_args = [];

    foreach ( (array) $args as $key => $value ) {
        if ( is_array( $value ) ) {
            $value = array_values( array_filter( array_map( 'sanitize_text_field', $value ) ) );
        } elseif ( is_string( $value ) ) {
            $value = sanitize_text_field( $value );
        } else {
            $value = absint( $value );
        }

        if ( '' === $value || 0 === $value || [] === $value ) {
            continue;
        }

        $query_args[ sanitize_key( $key ) ] = $value;
    }

    return add_query_arg( $query_args, sdwdv2_get_venues_url() );
}

/**
 * Get top-level venue terms, or all terms if the taxonomy is flat.
 *
 * @param string $taxonomy Taxonomy slug.
 * @return int[]
 */
function sdwdv2_get_venue_parent_term_ids( $taxonomy = 'venue-type' ) {
    $term_ids = get_terms( [
        'taxonomy'   => sanitize_key( $taxonomy ),
        'hide_empty' => false,
        'parent'     => 0,
        'fields'     => 'ids',
    ] );

    if ( is_wp_error( $term_ids ) || empty( $term_ids ) ) {
        $term_ids = get_terms( [
            'taxonomy'   => sanitize_key( $taxonomy ),
            'hide_empty' => false,
            'fields'     => 'ids',
        ] );
    }

    if ( is_wp_error( $term_ids ) ) {
        return [];
    }

    return array_values( array_unique( array_map( 'absint', $term_ids ) ) );
}

/**
 * Normalize a request parameter into a flat list of strings.
 *
 * @param mixed $raw Raw request value.
 * @return string[]
 */
function sdwdv2_normalize_request_list( $raw ) {
    if ( is_array( $raw ) ) {
        $items = array_map( 'sanitize_text_field', wp_unslash( $raw ) );
    } else {
        $raw   = sanitize_text_field( wp_unslash( (string) $raw ) );
        $items = preg_split( '/\s*,\s*/', $raw );
    }

    return array_values( array_unique( array_filter( array_map( 'trim', (array) $items ) ) ) );
}

/**
 * Parse a range query value such as "[1-2500]" or "[1-2500],[2501-5000]".
 *
 * @param mixed $raw Raw query value.
 * @return array{min:int,max:int}|array{}
 */
function sdwdv2_parse_range_filter( $raw ) {
    if ( is_array( $raw ) ) {
        $raw = implode( ',', array_map( 'sanitize_text_field', wp_unslash( $raw ) ) );
    } else {
        $raw = sanitize_text_field( wp_unslash( (string) $raw ) );
    }

    if ( '' === $raw ) {
        return [];
    }

    preg_match_all( '/\[(\d+)-(\d+)\]/', $raw, $matches, PREG_SET_ORDER );

    if ( empty( $matches ) && preg_match( '/(\d+)\s*-\s*(\d+)/', $raw, $fallback ) ) {
        $matches = [
            [ 0 => $fallback[0], 1 => $fallback[1], 2 => $fallback[2] ],
        ];
    }

    if ( empty( $matches ) ) {
        return [];
    }

    $mins = [];
    $maxs = [];

    foreach ( $matches as $match ) {
        $mins[] = absint( $match[1] );
        $maxs[] = absint( $match[2] );
    }

    return [
        'min' => min( $mins ),
        'max' => max( $maxs ),
    ];
}

/**
 * Get the current venue filter state from the request.
 *
 * @param array $overrides Context-specific overrides.
 * @return array
 */
function sdwdv2_get_venue_filter_state( $overrides = [] ) {
    $sort = isset( $overrides['sort_by'] ) ? $overrides['sort_by'] : ( $_GET['sort'] ?? $_GET['sort-by'] ?? '' ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
    $sort = sanitize_key( wp_unslash( (string) $sort ) );

    if ( ! in_array( $sort, [ 'title-asc', 'title-desc', 'newest', 'price-low', 'price-high' ], true ) ) {
        $sort = 'title-asc';
    }

    $paged = max( 1, get_query_var( 'paged', 1 ) );

    if ( isset( $_GET['paged'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $paged = max( $paged, absint( wp_unslash( $_GET['paged'] ) ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
    }

    return [
        'category_id'      => isset( $overrides['category_id'] )
            ? absint( $overrides['category_id'] )
            : absint( $_GET['cat_id'] ?? 0 ), // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        'location_slug'    => isset( $overrides['location_slug'] )
            ? sanitize_title( $overrides['location_slug'] )
            : sanitize_title( wp_unslash( (string) ( $_GET['location'] ?? '' ) ) ), // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        'price_filter'     => $overrides['price_filter'] ?? ( $_GET['price-filter'] ?? '' ), // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        'capacity_filter'  => $overrides['capacity_filter'] ?? ( $_GET['capacity'] ?? '' ), // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        'venue_setting'    => sdwdv2_normalize_request_list( $overrides['venue_setting'] ?? ( $_GET['venue_setting'] ?? [] ) ), // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        'venue_amenities'  => sdwdv2_normalize_request_list( $overrides['venue_amenities'] ?? ( $_GET['venue_amenities'] ?? [] ) ), // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        'service'          => sdwdv2_normalize_request_list( $overrides['service'] ?? ( $_GET['service'] ?? [] ) ), // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        'style'            => sdwdv2_normalize_request_list( $overrides['style'] ?? ( $_GET['style'] ?? [] ) ), // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        'sort_by'          => $sort,
        'paged'            => isset( $overrides['paged'] ) ? max( 1, absint( $overrides['paged'] ) ) : $paged,
        'posts_per_page'   => isset( $overrides['posts_per_page'] ) ? absint( $overrides['posts_per_page'] ) : 12,
        'fixed_category'   => ! empty( $overrides['fixed_category'] ),
        'fixed_location'   => ! empty( $overrides['fixed_location'] ),
    ];
}

/**
 * Determine whether any filters are currently active.
 *
 * @param array $filters Filter state.
 * @return bool
 */
function sdwdv2_has_active_venue_filters( $filters ) {
    return ! empty( $filters['category_id'] )
        || ! empty( $filters['location_slug'] )
        || ! empty( sdwdv2_parse_range_filter( $filters['price_filter'] ?? '' ) )
        || ! empty( sdwdv2_parse_range_filter( $filters['capacity_filter'] ?? '' ) )
        || ! empty( $filters['venue_setting'] )
        || ! empty( $filters['venue_amenities'] )
        || ! empty( $filters['service'] )
        || ! empty( $filters['style'] );
}

/**
 * Get merged range options from venue type term data.
 *
 * @param string $repeater_key  Term repeater key.
 * @param string $enable_key    Term enable key.
 * @param int    $category_id   Selected category.
 * @param array  $fallback      Fallback options.
 * @param bool   $respect_enable Whether to respect the enable toggle when aggregating.
 * @return array
 */
function sdwdv2_get_venue_range_options( $repeater_key, $enable_key, $category_id = 0, $fallback = [], $respect_enable = true ) {
    if ( ! function_exists( 'sdwd_get_term_repeater' ) ) {
        return $fallback;
    }

    $options  = [];
    $term_ids = $category_id ? [ absint( $category_id ) ] : sdwdv2_get_venue_parent_term_ids( 'venue-type' );

    foreach ( $term_ids as $term_id ) {
        if ( ! $category_id && $respect_enable && function_exists( 'sdwd_get_term_field' ) && ! sdwd_get_term_field( $enable_key, $term_id ) ) {
            continue;
        }

        $rows = sdwd_get_term_repeater( $repeater_key, $term_id, [ 'label', 'min', 'max' ] );

        if ( ! is_array( $rows ) ) {
            continue;
        }

        foreach ( $rows as $row ) {
            if ( ! isset( $row['min'], $row['max'] ) ) {
                continue;
            }

            $min = absint( $row['min'] );
            $max = absint( $row['max'] );

            if ( ! $min && ! $max ) {
                continue;
            }

            $key             = sprintf( '%1$d-%2$d', $min, $max );
            $options[ $key ] = [
                'min'   => $min,
                'max'   => $max,
                'label' => isset( $row['label'] ) && '' !== $row['label']
                    ? sanitize_text_field( $row['label'] )
                    : sprintf( '%1$s - %2$s', number_format_i18n( $min ), number_format_i18n( $max ) ),
            ];
        }
    }

    if ( ! empty( $options ) ) {
        return array_values( $options );
    }

    return $fallback;
}

/**
 * Get merged value options from venue type term data.
 *
 * @param string $repeater_key Term repeater key.
 * @param string $enable_key   Term enable key.
 * @param int    $category_id  Selected category.
 * @return array
 */
function sdwdv2_get_venue_value_options( $repeater_key, $enable_key, $category_id = 0 ) {
    if ( ! function_exists( 'sdwd_get_term_repeater' ) ) {
        return [];
    }

    $options  = [];
    $term_ids = $category_id ? [ absint( $category_id ) ] : sdwdv2_get_venue_parent_term_ids( 'venue-type' );

    foreach ( $term_ids as $term_id ) {
        if ( ! $category_id && function_exists( 'sdwd_get_term_field' ) && ! sdwd_get_term_field( $enable_key, $term_id ) ) {
            continue;
        }

        $rows = sdwd_get_term_repeater( $repeater_key, $term_id );

        if ( ! is_array( $rows ) ) {
            continue;
        }

        foreach ( $rows as $row ) {
            if ( empty( $row['value'] ) ) {
                continue;
            }

            $value             = sanitize_text_field( $row['value'] );
            $options[ $value ] = ! empty( $row['label'] )
                ? sanitize_text_field( $row['label'] )
                : $value;
        }
    }

    return $options;
}

/**
 * Get category-specific term-box options.
 *
 * @param int    $term_id Category term ID.
 * @param string $slug    Term box slug.
 * @return array
 */
function sdwdv2_get_venue_term_box_options( $term_id, $slug ) {
    if ( ! $term_id ) {
        return [];
    }

    $options = apply_filters( 'sdweddingdirectory/term-box-group', [
        'term_id' => absint( $term_id ),
        'slug'    => sanitize_key( $slug ),
    ] );

    return is_array( $options ) ? $options : [];
}

/**
 * Get all venue filter options for the current filter state.
 *
 * @param array $filters Filter state.
 * @return array
 */
function sdwdv2_get_venue_filter_options( $filters ) {
    $category_id = absint( $filters['category_id'] ?? 0 );

    $categories = get_terms( [
        'taxonomy'   => 'venue-type',
        'hide_empty' => false,
        'orderby'    => 'name',
        'order'      => 'ASC',
    ] );

    if ( is_wp_error( $categories ) ) {
        $categories = [];
    }

    $locations = get_terms( [
        'taxonomy'   => 'venue-location',
        'hide_empty' => false,
        'parent'     => 0,
        'orderby'    => 'name',
        'order'      => 'ASC',
    ] );

    if ( is_wp_error( $locations ) || empty( $locations ) ) {
        $locations = get_terms( [
            'taxonomy'   => 'venue-location',
            'hide_empty' => false,
            'orderby'    => 'name',
            'order'      => 'ASC',
        ] );
    }

    if ( is_wp_error( $locations ) ) {
        $locations = [];
    }

    return [
        'categories'      => $categories,
        'locations'       => $locations,
        'price_ranges'    => sdwdv2_get_venue_range_options(
            'pricing_options',
            'pricing_available',
            $category_id,
            [
                [ 'min' => 1, 'max' => 2500, 'label' => __( '$1 - $2,500', 'sandiegoweddingdirectory' ) ],
                [ 'min' => 2501, 'max' => 5000, 'label' => __( '$2,501 - $5,000', 'sandiegoweddingdirectory' ) ],
                [ 'min' => 5001, 'max' => 10000, 'label' => __( '$5,001 - $10,000', 'sandiegoweddingdirectory' ) ],
                [ 'min' => 10001, 'max' => 1000000, 'label' => __( '$10,001+', 'sandiegoweddingdirectory' ) ],
            ],
            false
        ),
        'capacity_ranges' => sdwdv2_get_venue_range_options( 'capacity_options', 'capacity_available', $category_id ),
        'settings'        => sdwdv2_get_venue_value_options( 'setting_options', 'setting_available', $category_id ),
        'amenities'       => sdwdv2_get_venue_value_options( 'amenities_options', 'amenities_available', $category_id ),
        'services'        => sdwdv2_get_venue_term_box_options( $category_id, 'service' ),
        'styles'          => sdwdv2_get_venue_term_box_options( $category_id, 'style' ),
        'sorts'           => [
            'title-asc'  => __( 'Name: A to Z', 'sandiegoweddingdirectory' ),
            'title-desc' => __( 'Name: Z to A', 'sandiegoweddingdirectory' ),
            'newest'     => __( 'Newest', 'sandiegoweddingdirectory' ),
            'price-low'  => __( 'Price: Low to High', 'sandiegoweddingdirectory' ),
            'price-high' => __( 'Price: High to Low', 'sandiegoweddingdirectory' ),
        ],
    ];
}

/**
 * Build a custom WP_Query args array for venues.
 *
 * @param array $filters   Filter state.
 * @param array $overrides Query arg overrides.
 * @return array
 */
function sdwdv2_build_venue_query_args( $filters = [], $overrides = [] ) {
    $filters    = wp_parse_args( $filters, sdwdv2_get_venue_filter_state() );
    $tax_query  = [];
    $meta_query = [];

    if ( ! empty( $filters['category_id'] ) ) {
        $tax_query[] = [
            'taxonomy' => 'venue-type',
            'field'    => 'term_id',
            'terms'    => [ absint( $filters['category_id'] ) ],
        ];
    }

    if ( ! empty( $filters['location_slug'] ) ) {
        $tax_query[] = [
            'taxonomy' => 'venue-location',
            'field'    => 'slug',
            'terms'    => [ sanitize_title( $filters['location_slug'] ) ],
        ];
    }

    $price_bounds = sdwdv2_parse_range_filter( $filters['price_filter'] ?? '' );
    if ( ! empty( $price_bounds ) ) {
        $meta_query[] = [
            'key'     => 'venue_min_price',
            'type'    => 'NUMERIC',
            'compare' => 'BETWEEN',
            'value'   => [ $price_bounds['min'], $price_bounds['max'] ],
        ];
    }

    $capacity_bounds = sdwdv2_parse_range_filter( $filters['capacity_filter'] ?? '' );
    if ( ! empty( $capacity_bounds ) ) {
        $meta_query[] = [
            'key'     => 'venue_seat_capacity',
            'type'    => 'NUMERIC',
            'compare' => 'BETWEEN',
            'value'   => [ $capacity_bounds['min'], $capacity_bounds['max'] ],
        ];
    }

    foreach ( [
        'venue_setting'   => 'venue_setting',
        'venue_amenities' => 'venue_amenities',
        'service'         => 'service',
        'style'           => 'style',
    ] as $filter_key => $meta_key ) {
        if ( empty( $filters[ $filter_key ] ) ) {
            continue;
        }

        foreach ( (array) $filters[ $filter_key ] as $value ) {
            $meta_query[] = [
                'key'     => sanitize_key( $meta_key ),
                'compare' => 'LIKE',
                'value'   => '"' . sanitize_text_field( $value ) . '"',
            ];
        }
    }

    $query_args = [
        'post_type'      => 'venue',
        'post_status'    => 'publish',
        'posts_per_page' => absint( $filters['posts_per_page'] ?? 12 ),
        'paged'          => max( 1, absint( $filters['paged'] ?? 1 ) ),
        'orderby'        => 'title',
        'order'          => 'ASC',
    ];

    switch ( $filters['sort_by'] ?? 'title-asc' ) {
        case 'title-desc':
            $query_args['order'] = 'DESC';
            break;

        case 'newest':
            $query_args['orderby'] = 'date';
            $query_args['order']   = 'DESC';
            break;

        case 'price-low':
            $query_args['meta_key'] = 'venue_min_price';
            $query_args['orderby']  = 'meta_value_num';
            $query_args['order']    = 'ASC';
            break;

        case 'price-high':
            $query_args['meta_key'] = 'venue_min_price';
            $query_args['orderby']  = 'meta_value_num';
            $query_args['order']    = 'DESC';
            break;
    }

    if ( ! empty( $tax_query ) ) {
        $query_args['tax_query'] = array_merge( [ 'relation' => 'AND' ], $tax_query );
    }

    if ( ! empty( $meta_query ) ) {
        $query_args['meta_query'] = array_merge( [ 'relation' => 'AND' ], $meta_query );
    }

    return array_merge( $query_args, (array) $overrides );
}

/**
 * Resolve a location image URL from the theme or term media.
 *
 * @param string $location_slug Location slug.
 * @return string
 */
function sdwdv2_get_venue_location_image_url( $location_slug ) {
    $location_slug = sanitize_title( $location_slug );

    if ( '' === $location_slug ) {
        return get_theme_file_uri( 'assets/images/banners/venues-search.png' );
    }

    $relative_path = 'assets/images/locations/' . $location_slug . '.jpg';
    $file_path     = get_theme_file_path( $relative_path );

    if ( file_exists( $file_path ) ) {
        return get_theme_file_uri( $relative_path );
    }

    $term = get_term_by( 'slug', $location_slug, 'venue-location' );

    if ( $term && ! is_wp_error( $term ) ) {
        $image = apply_filters( 'sdweddingdirectory/term/image', [
            'term_id'     => absint( $term->term_id ),
            'taxonomy'    => 'venue-location',
            'default_img' => get_theme_file_uri( 'assets/images/banners/venues-search.png' ),
        ] );

        if ( is_string( $image ) && '' !== $image ) {
            return $image;
        }
    }

    return get_theme_file_uri( 'assets/images/banners/venues-search.png' );
}

/**
 * Get normalized gallery image data for a venue.
 *
 * @param int $post_id Venue post ID.
 * @return array
 */
function sdwdv2_get_venue_gallery_images( $post_id ) {
    $post_id      = absint( $post_id );
    $gallery_meta = get_post_meta( $post_id, 'venue_gallery', true );
    $gallery_ids  = is_string( $gallery_meta ) ? array_filter( array_map( 'absint', explode( ',', $gallery_meta ) ) ) : [];
    $featured_id  = absint( get_post_thumbnail_id( $post_id ) );
    $media_ids    = array_values( array_unique( array_filter( array_merge( [ $featured_id ], $gallery_ids ) ) ) );
    $images       = [];

    foreach ( $media_ids as $media_id ) {
        $full_url  = wp_get_attachment_image_url( $media_id, 'large' );
        $thumb_url = wp_get_attachment_image_url( $media_id, 'medium_large' );

        if ( ! $full_url ) {
            continue;
        }

        $images[] = [
            'id'    => absint( $media_id ),
            'full'  => $full_url,
            'thumb' => $thumb_url ?: $full_url,
            'alt'   => get_post_meta( $media_id, '_wp_attachment_image_alt', true ) ?: get_the_title( $post_id ),
        ];
    }

    if ( empty( $images ) ) {
        $placeholder = get_theme_file_uri( 'assets/images/placeholders/venue-post/placeholder.jpg' );

        $images[] = [
            'id'    => 0,
            'full'  => $placeholder,
            'thumb' => $placeholder,
            'alt'   => get_the_title( $post_id ),
        ];
    }

    return $images;
}

/**
 * Get a formatted starting price label for a venue.
 *
 * @param int $post_id Venue post ID.
 * @return string
 */
function sdwdv2_get_venue_starting_price( $post_id ) {
    $post_id      = absint( $post_id );
    $pricing_data = get_post_meta( $post_id, 'sdwd_vendor_pricing_tiers', true );
    $prices       = [];

    if ( is_array( $pricing_data ) && ! empty( $pricing_data['tiers'] ) && is_array( $pricing_data['tiers'] ) ) {
        foreach ( $pricing_data['tiers'] as $tier ) {
            if ( ! is_array( $tier ) || empty( $tier['price'] ) ) {
                continue;
            }

            $value = preg_replace( '/[^0-9.]/', '', sanitize_text_field( $tier['price'] ) );

            if ( '' !== $value && is_numeric( $value ) ) {
                $prices[] = (float) $value;
            }
        }
    }

    if ( empty( $prices ) ) {
        $fallbacks = [
            get_post_meta( $post_id, 'venue_min_price', true ),
            get_post_meta( $post_id, 'venue_price', true ),
        ];

        foreach ( $fallbacks as $fallback ) {
            $value = preg_replace( '/[^0-9.]/', '', sanitize_text_field( (string) $fallback ) );

            if ( '' !== $value && is_numeric( $value ) ) {
                $prices[] = (float) $value;
                break;
            }
        }
    }

    if ( empty( $prices ) ) {
        return __( 'Custom pricing', 'sandiegoweddingdirectory' );
    }

    sort( $prices );

    return sprintf(
        /* translators: %s: formatted price */
        __( 'Starting at $%s', 'sandiegoweddingdirectory' ),
        number_format_i18n( $prices[0], 0 )
    );
}

/**
 * Normalize repeatable venue meta into a simple list.
 *
 * @param int    $post_id  Venue post ID.
 * @param string $meta_key Meta key.
 * @return string[]
 */
function sdwdv2_get_venue_meta_list( $post_id, $meta_key ) {
    $raw = get_post_meta( absint( $post_id ), sanitize_key( $meta_key ), true );

    if ( empty( $raw ) ) {
        return [];
    }

    $items = [];

    if ( is_array( $raw ) ) {
        foreach ( $raw as $value ) {
            if ( is_array( $value ) ) {
                if ( ! empty( $value['label'] ) ) {
                    $items[] = sanitize_text_field( $value['label'] );
                } elseif ( ! empty( $value['value'] ) ) {
                    $items[] = sanitize_text_field( $value['value'] );
                }
            } elseif ( is_scalar( $value ) ) {
                $items[] = sanitize_text_field( (string) $value );
            }
        }
    } elseif ( is_string( $raw ) ) {
        $items = preg_split( '/\s*,\s*/', sanitize_text_field( $raw ) );
    }

    return array_values( array_unique( array_filter( array_map( 'trim', (array) $items ) ) ) );
}

/**
 * Normalize a vendor website URL.
 *
 * @param string $url Raw URL.
 * @return string
 */
function sdwdv2_normalize_external_url( $url ) {
    $url = trim( (string) $url );

    if ( '' === $url ) {
        return '';
    }

    if ( ! preg_match( '#^https?://#i', $url ) ) {
        $url = 'https://' . ltrim( $url, '/' );
    }

    return esc_url_raw( $url );
}

/**
 * Get venue contact data from the linked vendor profile when available.
 *
 * @param int $post_id Venue post ID.
 * @return array
 */
function sdwdv2_get_venue_contact_data( $post_id ) {
    $post_id       = absint( $post_id );
    $author_id     = absint( get_post_field( 'post_author', $post_id ) );
    $vendor_post   = class_exists( 'SDWeddingDirectory_Config' ) ? absint( SDWeddingDirectory_Config::venue_author_post_id( $post_id ) ) : 0;
    $contact_post  = $vendor_post ?: 0;
    $contact_name  = trim( (string) get_the_author_meta( 'display_name', $author_id ) );
    $company_name  = '';
    $company_phone = '';
    $company_email = '';
    $company_site  = '';

    if ( $contact_post ) {
        $company_name  = sanitize_text_field( get_post_meta( $contact_post, 'company_name', true ) );
        $company_phone = sanitize_text_field( get_post_meta( $contact_post, 'company_contact', true ) );
        $company_email = sanitize_email( get_post_meta( $contact_post, 'company_email', true ) );
        $company_site  = sdwdv2_normalize_external_url( get_post_meta( $contact_post, 'company_website', true ) );

        $first_name = sanitize_text_field( get_post_meta( $contact_post, 'first_name', true ) );
        $last_name  = sanitize_text_field( get_post_meta( $contact_post, 'last_name', true ) );

        if ( '' !== trim( $first_name . ' ' . $last_name ) ) {
            $contact_name = trim( $first_name . ' ' . $last_name );
        }
    }

    if ( '' === $company_email ) {
        $company_email = sanitize_email( get_the_author_meta( 'user_email', $author_id ) );
    }

    if ( '' === $company_name ) {
        $company_name = get_the_title( $post_id );
    }

    return [
        'company_name' => $company_name,
        'contact_name' => $contact_name,
        'phone'        => $company_phone,
        'email'        => $company_email,
        'website'      => $company_site,
    ];
}

/**
 * Get venue booked dates.
 *
 * @param int $post_id Venue post ID.
 * @return string[]
 */
function sdwdv2_get_venue_booked_dates( $post_id ) {
    $post_id = absint( $post_id );
    $dates   = get_post_meta( $post_id, 'vendor_booked_dates', true );

    if ( ! is_array( $dates ) ) {
        $dates = get_post_meta( $post_id, 'venue_booked_dates', true );
    }

    if ( ! is_array( $dates ) ) {
        return [];
    }

    return array_values( array_filter( array_map(
        static function ( $date ) {
            $date = sanitize_text_field( (string) $date );
            return preg_match( '/^\d{4}-\d{2}-\d{2}$/', $date ) ? $date : '';
        },
        $dates
    ) ) );
}

/**
 * Get a review query for a venue.
 *
 * @param int $post_id Venue post ID.
 * @param int $limit   Number of reviews.
 * @return WP_Query
 */
function sdwdv2_get_venue_reviews_query( $post_id, $limit = 3 ) {
    return new WP_Query( [
        'post_type'      => 'venue-review',
        'post_status'    => 'publish',
        'posts_per_page' => absint( $limit ),
        'orderby'        => 'date',
        'order'          => 'DESC',
        'meta_query'     => [
            [
                'key'     => 'venue_id',
                'type'    => 'NUMERIC',
                'compare' => '=',
                'value'   => absint( $post_id ),
            ],
        ],
    ] );
}

/**
 * Get a venue's review summary text.
 *
 * @param int $post_id Venue post ID.
 * @return string
 */
function sdwdv2_get_venue_review_summary( $post_id ) {
    return sanitize_textarea_field( get_post_meta( absint( $post_id ), 'vendor_review_ai_summary', true ) );
}

/**
 * Get a venue map embed URL.
 *
 * @param int $post_id Venue post ID.
 * @return string
 */
function sdwdv2_get_venue_map_embed_url( $post_id ) {
    $post_id  = absint( $post_id );
    $lat      = trim( (string) get_post_meta( $post_id, 'venue_latitude', true ) );
    $lng      = trim( (string) get_post_meta( $post_id, 'venue_longitude', true ) );
    $address  = trim( (string) get_post_meta( $post_id, 'venue_address', true ) );
    $location = '';

    if ( '' !== $lat && '' !== $lng ) {
        $location = $lat . ',' . $lng;
    } elseif ( '' !== $address ) {
        $location = $address;
    }

    if ( '' === $location ) {
        return '';
    }

    return 'https://www.google.com/maps?q=' . rawurlencode( $location ) . '&z=14&output=embed';
}
