<?php
/**
 * SDWD Couple — Budget Calculator
 *
 * Couples can set a total budget and track spending by category.
 * Stored as user meta.
 */

defined( 'ABSPATH' ) || exit;

add_action( 'wp_ajax_sdwd_save_budget', 'sdwd_handle_save_budget' );

function sdwd_handle_save_budget() {
    check_ajax_referer( 'sdwd_budget_nonce', 'nonce' );

    $user = wp_get_current_user();
    if ( ! $user->ID ) {
        wp_send_json_error( [ 'message' => __( 'Please log in.', 'sdwd-couple' ) ] );
    }

    $total = floatval( $_POST['total_budget'] ?? 0 );
    $items = $_POST['items'] ?? [];
    if ( ! is_array( $items ) ) {
        $items = [];
    }

    $clean = [];
    foreach ( array_slice( $items, 0, 50 ) as $item ) {
        $category = sanitize_text_field( wp_unslash( $item['category'] ?? '' ) );
        if ( ! empty( $category ) ) {
            $clean[] = [
                'category'  => $category,
                'estimated' => floatval( $item['estimated'] ?? 0 ),
                'actual'    => floatval( $item['actual'] ?? 0 ),
                'paid'      => floatval( $item['paid'] ?? 0 ),
                'notes'     => sanitize_text_field( wp_unslash( $item['notes'] ?? '' ) ),
            ];
        }
    }

    update_user_meta( $user->ID, 'sdwd_budget_total', $total );
    update_user_meta( $user->ID, 'sdwd_budget_items', $clean );

    wp_send_json_success( [ 'message' => __( 'Budget saved.', 'sdwd-couple' ) ] );
}

/**
 * Get the current user's budget data.
 */
function sdwd_get_budget() {
    if ( ! is_user_logged_in() ) {
        return [ 'total' => 0, 'items' => [] ];
    }
    $uid = get_current_user_id();
    return [
        'total' => floatval( get_user_meta( $uid, 'sdwd_budget_total', true ) ),
        'items' => (array) ( get_user_meta( $uid, 'sdwd_budget_items', true ) ?: [] ),
    ];
}

/**
 * Get default budget categories.
 */
function sdwd_get_default_budget_categories() {
    return [
        __( 'Venue', 'sdwd-couple' ),
        __( 'Catering', 'sdwd-couple' ),
        __( 'Photography', 'sdwd-couple' ),
        __( 'Videography', 'sdwd-couple' ),
        __( 'DJ / Band', 'sdwd-couple' ),
        __( 'Flowers', 'sdwd-couple' ),
        __( 'Wedding Planner', 'sdwd-couple' ),
        __( 'Attire', 'sdwd-couple' ),
        __( 'Hair & Makeup', 'sdwd-couple' ),
        __( 'Invitations', 'sdwd-couple' ),
        __( 'Cake / Dessert', 'sdwd-couple' ),
        __( 'Transportation', 'sdwd-couple' ),
        __( 'Officiant', 'sdwd-couple' ),
        __( 'Rentals', 'sdwd-couple' ),
        __( 'Photo Booth', 'sdwd-couple' ),
        __( 'Other', 'sdwd-couple' ),
    ];
}
