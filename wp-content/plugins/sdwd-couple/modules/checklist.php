<?php
/**
 * SDWD Couple — Wedding Checklist
 *
 * Couples can manage a to-do list for wedding planning.
 * Stored as user meta (array of items with text + completed status).
 */

defined( 'ABSPATH' ) || exit;

add_action( 'wp_ajax_sdwd_save_checklist', 'sdwd_handle_save_checklist' );

function sdwd_handle_save_checklist() {
    check_ajax_referer( 'sdwd_checklist_nonce', 'nonce' );

    $user = wp_get_current_user();
    if ( ! $user->ID ) {
        wp_send_json_error( [ 'message' => __( 'Please log in.', 'sdwd-couple' ) ] );
    }

    $items = $_POST['items'] ?? [];
    if ( ! is_array( $items ) ) {
        $items = [];
    }

    $clean = [];
    foreach ( array_slice( $items, 0, 200 ) as $item ) {
        $text = sanitize_text_field( wp_unslash( $item['text'] ?? '' ) );
        if ( ! empty( $text ) ) {
            $clean[] = [
                'id'        => sanitize_key( $item['id'] ?? '' ) ?: ( 't' . wp_rand( 1000, 999999 ) ),
                'text'      => $text,
                'completed' => ! empty( $item['completed'] ),
                'due_date'  => sanitize_text_field( $item['due_date'] ?? '' ),
                'group'     => sanitize_key( $item['group'] ?? '' ),
            ];
        }
    }

    update_user_meta( $user->ID, 'sdwd_checklist', $clean );

    wp_send_json_success( [ 'message' => __( 'Checklist saved.', 'sdwd-couple' ) ] );
}

/**
 * Get the current user's checklist.
 */
function sdwd_get_checklist() {
    if ( ! is_user_logged_in() ) {
        return [];
    }
    $list = get_user_meta( get_current_user_id(), 'sdwd_checklist', true );
    return is_array( $list ) ? $list : [];
}

/**
 * Get default checklist items for new couples.
 */
function sdwd_get_default_checklist() {
    return [
        [ 'text' => __( 'Set your budget', 'sdwd-couple' ), 'completed' => false, 'due_date' => '' ],
        [ 'text' => __( 'Choose a wedding date', 'sdwd-couple' ), 'completed' => false, 'due_date' => '' ],
        [ 'text' => __( 'Book your venue', 'sdwd-couple' ), 'completed' => false, 'due_date' => '' ],
        [ 'text' => __( 'Hire a photographer', 'sdwd-couple' ), 'completed' => false, 'due_date' => '' ],
        [ 'text' => __( 'Book a DJ or band', 'sdwd-couple' ), 'completed' => false, 'due_date' => '' ],
        [ 'text' => __( 'Choose your wedding party', 'sdwd-couple' ), 'completed' => false, 'due_date' => '' ],
        [ 'text' => __( 'Send save-the-dates', 'sdwd-couple' ), 'completed' => false, 'due_date' => '' ],
        [ 'text' => __( 'Order invitations', 'sdwd-couple' ), 'completed' => false, 'due_date' => '' ],
        [ 'text' => __( 'Plan the honeymoon', 'sdwd-couple' ), 'completed' => false, 'due_date' => '' ],
        [ 'text' => __( 'Get marriage license', 'sdwd-couple' ), 'completed' => false, 'due_date' => '' ],
    ];
}
