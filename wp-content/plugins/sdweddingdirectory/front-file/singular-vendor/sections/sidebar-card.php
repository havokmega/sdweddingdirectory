<?php
/**
 * Vendor Singular - Section 3: Sticky Sidebar Card
 */

$post_id = isset( $post_id ) ? absint( $post_id ) : absint( get_the_ID() );

$sidebar_element_id = isset( $sidebar_element_id ) && ! empty( $sidebar_element_id )
    ? sanitize_html_class( $sidebar_element_id )
    : sanitize_html_class( 'sd-profile-sidebar' );

if( empty( $post_id ) ){
    return;
}

$company_name = get_post_meta( $post_id, sanitize_key( 'company_name' ), true );
$company_name = ! empty( $company_name ) ? $company_name : get_the_title( $post_id );

$average_rating = apply_filters( 'sdweddingdirectory/rating/average', '', [
    'vendor_id' => absint( $post_id ),
] );

$rating_count = apply_filters( 'sdweddingdirectory/rating/found', absint( '0' ), [
    'vendor_id' => absint( $post_id ),
] );

$average_rating = $average_rating !== '' ? $average_rating : esc_attr( '0.0' );
$rating_count   = absint( $rating_count );

$company_address = get_post_meta( $post_id, sanitize_key( 'company_address' ), true );
$company_address = ! empty( $company_address ) ? $company_address : esc_html__( 'San Diego, California', 'sdweddingdirectory' );

$pricing_data = get_post_meta( $post_id, sanitize_key( 'sdwd_vendor_pricing_tiers' ), true );
$starting_price = '';

if( is_array( $pricing_data ) && isset( $pricing_data['tiers'] ) && is_array( $pricing_data['tiers'] ) ){
    $numbers = [];

    foreach( $pricing_data['tiers'] as $tier ){
        if( ! is_array( $tier ) || ! isset( $tier['price'] ) ){
            continue;
        }

        $value = sanitize_text_field( $tier['price'] );
        $value = preg_replace( '/[^0-9.]/', '', $value );

        if( $value !== '' && is_numeric( $value ) ){
            $numbers[] = (float) $value;
        }
    }

    if( ! empty( $numbers ) ){
        sort( $numbers );
        $starting_price = number_format_i18n( $numbers[0], 0 );
    }
}

if( $starting_price === '' ){
    $starting_price = esc_html__( 'Custom', 'sdweddingdirectory' );
}

$booked_dates = get_post_meta( $post_id, sanitize_key( 'vendor_booked_dates' ), true );
$booked_dates = is_array( $booked_dates ) ? array_filter( $booked_dates ) : [];
$limited_count = count( $booked_dates );

$availability_text = $limited_count > 0
    ? sprintf( esc_html__( '%1$s limited dates', 'sdweddingdirectory' ), absint( $limited_count ) )
    : esc_html__( 'Check availability', 'sdweddingdirectory' );

$request_attrs = '';
$request_class = 'btn w-100 text-white mb-2';

if( ! is_user_logged_in() ){
    $request_attrs = apply_filters( 'sdweddingdirectory/couple-login/attr', '' );
}else{
    $request_class .= ' sdweddingdirectory-request-quote-popup';
    $request_attrs = sprintf( 'data-venue-id="%1$s" data-event-call="0" id="%2$s"', absint( $post_id ), esc_attr( wp_unique_id( 'sdwd_request_' ) ) );
}
?>
<div class="sd-profile-sidebar-sticky" id="<?php echo esc_attr( $sidebar_element_id ); ?>">
    <div class="card shadow">
        <div class="card-body">
            <h1 class="h4 fw-bold mb-1"><?php echo esc_html( $company_name ); ?></h1>
            <div class="mb-2 sd-profile-rating">
                <span class="text-warning"><i class="fa fa-star"></i></span>
                <span class="fw-bold"><?php echo esc_html( $average_rating ); ?></span>
                <span class="text-muted"><?php echo esc_html( sprintf( esc_html__( 'Fantastic · %1$s reviews', 'sdweddingdirectory' ), absint( $rating_count ) ) ); ?></span>
            </div>
            <?php
            $five_star_count = 0;
            $five_star_query = new WP_Query( [
                'post_type'      => 'venue-review',
                'post_status'    => 'publish',
                'posts_per_page' => -1,
                'fields'         => 'ids',
                'meta_query'     => [
                    'relation' => 'AND',
                    [
                        'key'     => 'vendor_id',
                        'value'   => absint( $post_id ),
                        'compare' => '=',
                        'type'    => 'NUMERIC',
                    ],
                    [
                        'key'     => 'average_rating',
                        'value'   => '5',
                        'compare' => '=',
                        'type'    => 'NUMERIC',
                    ],
                ],
            ] );
            $five_star_count = absint( $five_star_query->found_posts );
            wp_reset_postdata();

            if( $five_star_count >= 5 ){
                $award_label = esc_html__( 'Rising Star', 'sdweddingdirectory' );
                if( $five_star_count >= 50 ){ $award_label = esc_html__( 'Hall of Fame', 'sdweddingdirectory' ); }
                elseif( $five_star_count >= 25 ){ $award_label = esc_html__( 'Couples\' Favorite', 'sdweddingdirectory' ); }
                elseif( $five_star_count >= 10 ){ $award_label = esc_html__( 'Highly Rated', 'sdweddingdirectory' ); }
            ?>
            <div class="sd-profile-award-badge mb-2 d-flex align-items-center gap-2">
                <span class="badge bg-warning text-dark"><i class="fa fa-trophy"></i> <?php echo esc_html( $award_label ); ?></span>
                <small class="text-muted"><?php echo esc_html( sprintf( __( '%s five-star reviews', 'sdweddingdirectory' ), $five_star_count ) ); ?></small>
            </div>
            <?php } ?>
            <p class="mb-2 sd-profile-meta"><i class="fa fa-map-marker"></i> <?php echo esc_html( $company_address ); ?></p>
            <p class="mb-2 sd-profile-meta"><i class="fa fa-tag"></i> <strong><?php echo esc_html( '$' . $starting_price ); ?></strong> <?php esc_html_e( 'starting price', 'sdweddingdirectory' ); ?></p>
            <p class="mb-3 sd-profile-meta"><i class="fa fa-calendar"></i> <a href="#section_vendor_availability"><?php echo esc_html( $availability_text ); ?></a></p>

            <a href="javascript:" class="<?php echo esc_attr( $request_class ); ?>" style="background-color: var(--sdweddingdirectory-color-orange);" <?php echo $request_attrs; ?>>
                <?php esc_html_e( 'Request pricing', 'sdweddingdirectory' ); ?>
            </a>

            <?php
            $is_saved = false;
            $is_hired = false;

            if( is_user_logged_in() && function_exists( 'sdwd_profile_user_has_item' ) ){
                $is_saved = sdwd_profile_user_has_item( 'sdwd_saved_profiles', $post_id );
                $is_hired = sdwd_profile_user_has_item( 'sdwd_hired_profiles', $post_id );
            }
            ?>
            <div class="d-flex gap-2 mb-2">
                <a href="javascript:" class="btn flex-fill btn-sm sidebar-save-btn<?php echo $is_saved ? ' active' : ''; ?>" data-post-id="<?php echo absint( $post_id ); ?>">
                    <i class="fa <?php echo $is_saved ? 'fa-heart' : 'fa-heart-o'; ?>"></i>
                    <?php echo $is_saved ? esc_html__( 'Saved', 'sdweddingdirectory' ) : esc_html__( 'Save', 'sdweddingdirectory' ); ?>
                </a>
                <a href="javascript:" class="btn flex-fill btn-sm sidebar-hire-btn<?php echo $is_hired ? ' active' : ''; ?>" data-post-id="<?php echo absint( $post_id ); ?>">
                    <i class="fa <?php echo $is_hired ? 'fa-check-circle' : 'fa-handshake-o'; ?>"></i>
                    <?php echo $is_hired ? esc_html__( 'Hired', 'sdweddingdirectory' ) : esc_html__( 'Hired ?', 'sdweddingdirectory' ); ?>
                </a>
            </div>
        </div>
    </div>
</div>
