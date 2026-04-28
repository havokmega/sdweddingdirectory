<?php
/**
 * SDWD Couple — Guest Management
 *
 * Couples manage their guest list across 4 events: wedding, rehearsal,
 * shower, dance. Stored as user meta `sdwd_guests`.
 *
 * Schema per row: [ name, email, phone, event, status, meal, notes ]
 *   event  ∈ wedding | rehearsal | shower | dance
 *   status ∈ attended | invited | declined
 */

defined( 'ABSPATH' ) || exit;

add_action( 'wp_ajax_sdwd_save_guests', 'sdwd_handle_save_guests' );

function sdwd_handle_save_guests() {
    check_ajax_referer( 'sdwd_guests_nonce', 'nonce' );

    $user = wp_get_current_user();
    if ( ! $user->ID ) {
        wp_send_json_error( [ 'message' => __( 'Please log in.', 'sdwd-couple' ) ] );
    }

    $guests = $_POST['guests'] ?? [];
    if ( ! is_array( $guests ) ) { $guests = []; }

    $allowed_events   = [ 'wedding', 'rehearsal', 'shower', 'dance' ];
    $allowed_statuses = [ 'attended', 'invited', 'declined' ];

    $clean = [];
    foreach ( array_slice( $guests, 0, 500 ) as $g ) {
        $name = sanitize_text_field( wp_unslash( $g['name'] ?? '' ) );
        if ( empty( $name ) ) { continue; }
        $event  = in_array( $g['event']  ?? '', $allowed_events,   true ) ? $g['event']  : 'wedding';
        $status = in_array( $g['status'] ?? '', $allowed_statuses, true ) ? $g['status'] : 'invited';
        $clean[] = [
            'name'   => $name,
            'email'  => sanitize_email( wp_unslash( $g['email'] ?? '' ) ),
            'phone'  => sanitize_text_field( wp_unslash( $g['phone'] ?? '' ) ),
            'event'  => $event,
            'status' => $status,
            'meal'   => sanitize_text_field( wp_unslash( $g['meal']  ?? '' ) ),
            'notes'  => sanitize_text_field( wp_unslash( $g['notes'] ?? '' ) ),
        ];
    }

    update_user_meta( $user->ID, 'sdwd_guests', $clean );
    wp_send_json_success( [
        'message' => __( 'Guest list saved.', 'sdwd-couple' ),
        'count'   => count( $clean ),
    ] );
}
