<?php
/**
 * SDWD Couple — Wishlist
 *
 * Couples can save vendors/venues to a favorites list.
 * Stored as user meta (array of post IDs).
 */

defined( 'ABSPATH' ) || exit;

add_action( 'wp_ajax_sdwd_toggle_wishlist', 'sdwd_handle_toggle_wishlist' );

function sdwd_handle_toggle_wishlist() {
    check_ajax_referer( 'sdwd_wishlist_nonce', 'nonce' );

    $user = wp_get_current_user();
    if ( ! $user->ID ) {
        wp_send_json_error( [ 'message' => __( 'Please log in.', 'sdwd-couple' ) ] );
    }

    $post_id = absint( $_POST['post_id'] ?? 0 );
    if ( ! $post_id || ! in_array( get_post_type( $post_id ), [ 'vendor', 'venue' ], true ) ) {
        wp_send_json_error( [ 'message' => __( 'Invalid vendor or venue.', 'sdwd-couple' ) ] );
    }

    $wishlist = get_user_meta( $user->ID, 'sdwd_wishlist', true );
    if ( ! is_array( $wishlist ) ) {
        $wishlist = [];
    }

    $key = array_search( $post_id, $wishlist, true );
    if ( $key !== false ) {
        unset( $wishlist[ $key ] );
        $wishlist = array_values( $wishlist );
        $added = false;
    } else {
        $wishlist[] = $post_id;
        $added = true;
    }

    update_user_meta( $user->ID, 'sdwd_wishlist', $wishlist );

    wp_send_json_success( [
        'message' => $added ? __( 'Added to favorites.', 'sdwd-couple' ) : __( 'Removed from favorites.', 'sdwd-couple' ),
        'added'   => $added,
        'count'   => count( $wishlist ),
    ] );
}

/**
 * Check if a post is in the current user's wishlist.
 */
function sdwd_is_in_wishlist( $post_id ) {
    if ( ! is_user_logged_in() ) {
        return false;
    }
    $wishlist = get_user_meta( get_current_user_id(), 'sdwd_wishlist', true );
    return is_array( $wishlist ) && in_array( $post_id, $wishlist, true );
}

/**
 * Get the current user's wishlist post IDs.
 */
function sdwd_get_wishlist() {
    if ( ! is_user_logged_in() ) {
        return [];
    }
    $wishlist = get_user_meta( get_current_user_id(), 'sdwd_wishlist', true );
    return is_array( $wishlist ) ? $wishlist : [];
}
