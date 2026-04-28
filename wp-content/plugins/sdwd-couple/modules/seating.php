<?php
/**
 * SDWD Couple — Seating Chart
 *
 * Save the table layout + per-table guest assignments.
 *
 * Schema (sdwd_seating_tables):
 *   [ id, name, seats, shape, x, y, guests:[ name… ] ]
 */

defined( 'ABSPATH' ) || exit;

add_action( 'wp_ajax_sdwd_save_seating', 'sdwd_handle_save_seating' );

function sdwd_handle_save_seating() {
    check_ajax_referer( 'sdwd_seating_nonce', 'nonce' );

    $user = wp_get_current_user();
    if ( ! $user->ID ) {
        wp_send_json_error( [ 'message' => __( 'Please log in.', 'sdwd-couple' ) ] );
    }

    $tables = $_POST['tables'] ?? [];
    if ( ! is_array( $tables ) ) { $tables = []; }

    $allowed_shapes = [ 'round', 'rectangular' ];
    $clean = [];
    foreach ( array_slice( $tables, 0, 60 ) as $t ) {
        $name = sanitize_text_field( wp_unslash( $t['name'] ?? '' ) );
        if ( empty( $name ) ) { continue; }
        $shape = in_array( $t['shape'] ?? '', $allowed_shapes, true ) ? $t['shape'] : 'round';
        $row_guests = [];
        if ( ! empty( $t['guests'] ) && is_array( $t['guests'] ) ) {
            foreach ( array_slice( $t['guests'], 0, 20 ) as $name_g ) {
                $g = sanitize_text_field( wp_unslash( $name_g ) );
                if ( $g ) { $row_guests[] = $g; }
            }
        }
        $clean[] = [
            'id'     => sanitize_key( $t['id'] ?? '' ) ?: ( 'tbl' . wp_rand( 1000, 999999 ) ),
            'name'   => $name,
            'seats'  => max( 1, min( 30, (int) ( $t['seats'] ?? 8 ) ) ),
            'shape'  => $shape,
            'x'      => (float) ( $t['x'] ?? 0 ),
            'y'      => (float) ( $t['y'] ?? 0 ),
            'guests' => $row_guests,
        ];
    }

    update_user_meta( $user->ID, 'sdwd_seating_tables', $clean );
    wp_send_json_success( [ 'message' => __( 'Layout saved.', 'sdwd-couple' ), 'count' => count( $clean ) ] );
}
