<?php
/**
 * SDWD Core — Vendor / Venue Frontend AJAX
 *
 * Specific endpoints used by the vendor + venue dashboards that don't fit
 * cleanly into the generic sdwd_save_dashboard handler.
 *
 *   sdwd_save_packages      — vendor-set price packages
 *   sdwd_save_hours         — open/close/closed/24h per day
 *   sdwd_save_filters       — taxonomy/meta-driven filter selections
 *   sdwd_save_quote_reply   — reply to a quote request inbox item
 */

defined( 'ABSPATH' ) || exit;

add_action( 'wp_ajax_sdwd_save_packages',    'sdwd_handle_save_packages' );
add_action( 'wp_ajax_sdwd_save_hours',       'sdwd_handle_save_hours' );
add_action( 'wp_ajax_sdwd_save_filters',     'sdwd_handle_save_filters' );
add_action( 'wp_ajax_sdwd_save_quote_reply', 'sdwd_handle_save_quote_reply' );

function sdwd_vendor_post_id_for_current_user() {
    $user = wp_get_current_user();
    if ( ! $user->ID ) { return 0; }
    $post_id = (int) get_user_meta( $user->ID, 'sdwd_post_id', true );
    if ( ! $post_id ) { return 0; }
    if ( get_post_field( 'post_author', $post_id ) != $user->ID ) { return 0; }
    return $post_id;
}

function sdwd_handle_save_packages() {
    check_ajax_referer( 'sdwd_packages_nonce', 'nonce' );
    $post_id = sdwd_vendor_post_id_for_current_user();
    if ( ! $post_id ) { wp_send_json_error( [ 'message' => __( 'Not allowed.', 'sdwd-core' ) ] ); }

    $packages = $_POST['packages'] ?? [];
    if ( ! is_array( $packages ) ) { $packages = []; }

    $allowed_billing = [ 'one-time', 'hourly', 'starting-at' ];
    $clean = [];
    foreach ( array_slice( $packages, 0, 12 ) as $p ) {
        $name = sanitize_text_field( wp_unslash( $p['name'] ?? '' ) );
        if ( empty( $name ) ) { continue; }
        $features = [];
        if ( ! empty( $p['features'] ) && is_array( $p['features'] ) ) {
            foreach ( array_slice( $p['features'], 0, 15 ) as $f ) {
                $val = sanitize_text_field( wp_unslash( $f ) );
                if ( $val ) { $features[] = $val; }
            }
        }
        $clean[] = [
            'name'     => $name,
            'price'    => (float) ( $p['price'] ?? 0 ),
            'billing'  => in_array( $p['billing'] ?? '', $allowed_billing, true ) ? $p['billing'] : 'one-time',
            'cta'      => sanitize_text_field( wp_unslash( $p['cta']     ?? '' ) ),
            'features' => $features,
        ];
    }
    update_post_meta( $post_id, 'sdwd_packages', $clean );
    wp_send_json_success( [ 'message' => __( 'Packages saved.', 'sdwd-core' ), 'count' => count( $clean ) ] );
}

function sdwd_handle_save_hours() {
    check_ajax_referer( 'sdwd_hours_nonce', 'nonce' );
    $post_id = sdwd_vendor_post_id_for_current_user();
    if ( ! $post_id ) { wp_send_json_error( [ 'message' => __( 'Not allowed.', 'sdwd-core' ) ] ); }

    $hours = $_POST['hours'] ?? [];
    if ( ! is_array( $hours ) ) { $hours = []; }

    $valid_days = [ 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday' ];
    $clean = [];
    foreach ( $valid_days as $d ) {
        $row = $hours[ $d ] ?? [];
        $clean[ $d ] = [
            'open'       => sanitize_text_field( $row['open']  ?? '' ),
            'close'      => sanitize_text_field( $row['close'] ?? '' ),
            'closed'     => ! empty( $row['closed'] ),
            'twentyfour' => ! empty( $row['twentyfour'] ),
        ];
    }
    update_post_meta( $post_id, 'sdwd_hours', $clean );
    wp_send_json_success( [ 'message' => __( 'Hours saved.', 'sdwd-core' ) ] );
}

function sdwd_handle_save_filters() {
    check_ajax_referer( 'sdwd_filters_nonce', 'nonce' );
    $post_id = sdwd_vendor_post_id_for_current_user();
    if ( ! $post_id ) { wp_send_json_error( [ 'message' => __( 'Not allowed.', 'sdwd-core' ) ] ); }

    $key     = sanitize_key( $_POST['filter_key'] ?? '' );
    $filters = $_POST['filters'] ?? [];
    if ( empty( $key ) ) { wp_send_json_error( [ 'message' => __( 'Bad filter key.', 'sdwd-core' ) ] ); }
    if ( ! is_array( $filters ) ) { $filters = []; }

    // Strict allow-list: only whitelisted keys saved.
    $allowed_keys = [ 'venue_filters', 'vendor_filters_photographer', 'vendor_filters_dj', 'vendor_filters_florist' ];
    if ( ! in_array( $key, $allowed_keys, true ) ) {
        wp_send_json_error( [ 'message' => __( 'Filter set not registered.', 'sdwd-core' ) ] );
    }

    $clean = sdwd_recursive_sanitize( $filters );
    update_post_meta( $post_id, $key, $clean );
    wp_send_json_success( [ 'message' => __( 'Filters saved.', 'sdwd-core' ) ] );
}

function sdwd_recursive_sanitize( $value ) {
    if ( is_array( $value ) ) {
        $out = [];
        foreach ( $value as $k => $v ) {
            $clean_k = is_string( $k ) ? sanitize_key( $k ) : $k;
            $out[ $clean_k ] = sdwd_recursive_sanitize( $v );
        }
        return $out;
    }
    return is_string( $value ) ? sanitize_text_field( wp_unslash( $value ) ) : $value;
}

function sdwd_handle_save_quote_reply() {
    check_ajax_referer( 'sdwd_quote_reply_nonce', 'nonce' );
    $post_id = sdwd_vendor_post_id_for_current_user();
    if ( ! $post_id ) { wp_send_json_error( [ 'message' => __( 'Not allowed.', 'sdwd-core' ) ] ); }

    $quote_id = sanitize_key( $_POST['quote_id'] ?? '' );
    $reply    = sanitize_textarea_field( wp_unslash( $_POST['reply'] ?? '' ) );
    if ( ! $quote_id ) { wp_send_json_error( [ 'message' => __( 'Bad quote id.', 'sdwd-core' ) ] ); }

    $quotes = get_post_meta( $post_id, 'sdwd_quote_requests', true );
    if ( ! is_array( $quotes ) ) { $quotes = []; }
    foreach ( $quotes as $i => $q ) {
        if ( ( $q['id'] ?? '' ) === $quote_id ) {
            $quotes[ $i ]['reply']    = $reply;
            $quotes[ $i ]['replied']  = current_time( 'mysql' );
            $quotes[ $i ]['status']   = 'replied';
            break;
        }
    }
    update_post_meta( $post_id, 'sdwd_quote_requests', $quotes );
    wp_send_json_success( [ 'message' => __( 'Reply saved.', 'sdwd-core' ) ] );
}
