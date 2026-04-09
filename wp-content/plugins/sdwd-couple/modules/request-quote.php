<?php
/**
 * SDWD Couple — Request Quote
 *
 * Couples can send quote requests to vendors/venues.
 * Stored as post meta on the couple's post. Notification sent to vendor/venue email.
 */

defined( 'ABSPATH' ) || exit;

add_action( 'wp_ajax_sdwd_request_quote', 'sdwd_handle_request_quote' );

function sdwd_handle_request_quote() {
    check_ajax_referer( 'sdwd_quote_nonce', 'nonce' );

    $user = wp_get_current_user();
    if ( ! $user->ID ) {
        wp_send_json_error( [ 'message' => __( 'Please log in first.', 'sdwd-couple' ) ] );
    }

    $vendor_id    = absint( $_POST['vendor_id'] ?? 0 );
    $message      = sanitize_textarea_field( wp_unslash( $_POST['message'] ?? '' ) );
    $event_date   = sanitize_text_field( wp_unslash( $_POST['event_date'] ?? '' ) );
    $guest_count  = absint( $_POST['guest_count'] ?? 0 );

    if ( ! $vendor_id || ! in_array( get_post_type( $vendor_id ), [ 'vendor', 'venue' ], true ) ) {
        wp_send_json_error( [ 'message' => __( 'Invalid vendor or venue.', 'sdwd-couple' ) ] );
    }

    if ( empty( $message ) ) {
        wp_send_json_error( [ 'message' => __( 'Please include a message.', 'sdwd-couple' ) ] );
    }

    // Store the quote request.
    $quote = [
        'vendor_id'   => $vendor_id,
        'vendor_name' => get_the_title( $vendor_id ),
        'message'     => $message,
        'event_date'  => $event_date,
        'guest_count' => $guest_count,
        'date'        => current_time( 'mysql' ),
        'status'      => 'sent',
    ];

    // Save to couple's post meta.
    $couple_post_id = get_user_meta( $user->ID, 'sdwd_post_id', true );
    if ( $couple_post_id ) {
        $quotes = get_post_meta( $couple_post_id, 'sdwd_quote_requests', true );
        if ( ! is_array( $quotes ) ) {
            $quotes = [];
        }
        $quotes[] = $quote;
        update_post_meta( $couple_post_id, 'sdwd_quote_requests', $quotes );
    }

    // Send email to vendor/venue.
    $vendor_email = get_post_meta( $vendor_id, 'sdwd_email', true );
    if ( $vendor_email && is_email( $vendor_email ) ) {
        $subject = sprintf(
            __( 'New quote request from %s', 'sdwd-couple' ),
            $user->display_name
        );
        $body = sprintf(
            "Name: %s\nEmail: %s\nEvent Date: %s\nGuest Count: %s\n\nMessage:\n%s",
            $user->display_name,
            $user->user_email,
            $event_date ?: 'Not specified',
            $guest_count ?: 'Not specified',
            $message
        );
        wp_mail( $vendor_email, $subject, $body );
    }

    wp_send_json_success( [ 'message' => __( 'Quote request sent!', 'sdwd-couple' ) ] );
}
