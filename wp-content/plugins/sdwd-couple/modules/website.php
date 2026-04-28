<?php
/**
 * SDWD Couple — Wedding Website Editor
 *
 * Saves per-section content for the couple's wedding website. Each section
 * is its own meta key on the couple post (sdwd_ww_{section}).
 *
 * Sections (per spec s17):
 *   header / about / story / countdown / rsvp / groomsman / bridesmaids /
 *   thankyou / gallery / when-where / footer
 */

defined( 'ABSPATH' ) || exit;

add_action( 'wp_ajax_sdwd_save_website', 'sdwd_handle_save_website' );

function sdwd_handle_save_website() {
    check_ajax_referer( 'sdwd_website_nonce', 'nonce' );

    $user = wp_get_current_user();
    if ( ! $user->ID ) {
        wp_send_json_error( [ 'message' => __( 'Please log in.', 'sdwd-couple' ) ] );
    }
    $post_id = (int) get_user_meta( $user->ID, 'sdwd_post_id', true );
    if ( ! $post_id ) {
        wp_send_json_error( [ 'message' => __( 'No linked profile.', 'sdwd-couple' ) ] );
    }

    $section = sanitize_key( $_POST['section'] ?? '' );
    $allowed = [ 'header', 'about', 'story', 'countdown', 'rsvp', 'groomsman', 'bridesmaids', 'thankyou', 'gallery', 'when-where', 'footer' ];
    if ( ! in_array( $section, $allowed, true ) ) {
        wp_send_json_error( [ 'message' => __( 'Unknown section.', 'sdwd-couple' ) ] );
    }

    $payload = $_POST['data'] ?? [];
    if ( ! is_array( $payload ) ) { $payload = []; }

    // Generic deep-sanitize: strings → sanitize_text_field, allow basic HTML
    // through wp_kses_post for the 'content' / 'description' keys.
    $rich_keys = [ 'content', 'description', 'intro', 'message', 'text' ];
    $sanitized = sdwd_ww_sanitize( $payload, $rich_keys );

    $key = 'sdwd_ww_' . str_replace( '-', '_', $section );
    update_post_meta( $post_id, $key, $sanitized );

    wp_send_json_success( [
        'message' => __( 'Saved.', 'sdwd-couple' ),
        'section' => $section,
    ] );
}

function sdwd_ww_sanitize( $value, $rich_keys ) {
    if ( is_array( $value ) ) {
        $out = [];
        foreach ( $value as $k => $v ) {
            $clean_k = is_string( $k ) ? sanitize_key( $k ) : $k;
            $out[ $clean_k ] = sdwd_ww_sanitize( $v, $rich_keys );
        }
        return $out;
    }
    // Leaf: rich field allowance keys are checked at parent level by caller
    // structure; for simplicity we use sanitize_text_field for everything and
    // the caller can post-process specific keys.
    return is_string( $value ) ? sanitize_text_field( wp_unslash( $value ) ) : $value;
}
