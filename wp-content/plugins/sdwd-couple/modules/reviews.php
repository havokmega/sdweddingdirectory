<?php
/**
 * SDWD Couple — Reviews
 *
 * Couples can leave reviews on vendor/venue profiles.
 * Reviews are stored as a custom post type linked to the reviewed post.
 */

defined( 'ABSPATH' ) || exit;

// Register review CPT.
add_action( 'init', function () {
    register_post_type( 'sdwd_review', [
        'labels' => [
            'name'          => __( 'Reviews', 'sdwd-couple' ),
            'singular_name' => __( 'Review', 'sdwd-couple' ),
            'all_items'     => __( 'All Reviews', 'sdwd-couple' ),
            'menu_name'     => __( 'Reviews', 'sdwd-couple' ),
        ],
        'public'            => false,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'menu_position'     => 28,
        'menu_icon'         => 'dashicons-star-filled',
        'supports'          => [ 'title', 'editor' ],
        'capability_type'   => 'post',
        'map_meta_cap'      => true,
    ] );
} );

// AJAX: Submit review.
add_action( 'wp_ajax_sdwd_submit_review', 'sdwd_handle_submit_review' );

function sdwd_handle_submit_review() {
    check_ajax_referer( 'sdwd_review_nonce', 'nonce' );

    $user = wp_get_current_user();
    if ( ! $user->ID || ! in_array( 'couple', $user->roles, true ) ) {
        wp_send_json_error( [ 'message' => __( 'Only couples can leave reviews.', 'sdwd-couple' ) ] );
    }

    $reviewed_id = absint( $_POST['reviewed_id'] ?? 0 );
    $rating      = absint( $_POST['rating'] ?? 0 );
    $content     = sanitize_textarea_field( wp_unslash( $_POST['content'] ?? '' ) );

    if ( ! $reviewed_id || ! in_array( get_post_type( $reviewed_id ), [ 'vendor', 'venue' ], true ) ) {
        wp_send_json_error( [ 'message' => __( 'Invalid vendor or venue.', 'sdwd-couple' ) ] );
    }

    if ( $rating < 1 || $rating > 5 ) {
        wp_send_json_error( [ 'message' => __( 'Rating must be between 1 and 5.', 'sdwd-couple' ) ] );
    }

    if ( empty( $content ) ) {
        wp_send_json_error( [ 'message' => __( 'Please write a review.', 'sdwd-couple' ) ] );
    }

    // Check for existing review.
    $existing = get_posts( [
        'post_type'   => 'sdwd_review',
        'author'      => $user->ID,
        'meta_key'    => 'sdwd_reviewed_id',
        'meta_value'  => $reviewed_id,
        'numberposts' => 1,
    ] );

    if ( ! empty( $existing ) ) {
        wp_send_json_error( [ 'message' => __( 'You have already reviewed this business.', 'sdwd-couple' ) ] );
    }

    $reviewed_title = get_the_title( $reviewed_id );

    $review_id = wp_insert_post( [
        'post_type'    => 'sdwd_review',
        'post_title'   => sprintf( '%s — %s', $user->display_name, $reviewed_title ),
        'post_content' => $content,
        'post_status'  => 'publish',
        'post_author'  => $user->ID,
    ] );

    if ( is_wp_error( $review_id ) ) {
        wp_send_json_error( [ 'message' => __( 'Failed to save review.', 'sdwd-couple' ) ] );
    }

    update_post_meta( $review_id, 'sdwd_reviewed_id', $reviewed_id );
    update_post_meta( $review_id, 'sdwd_rating', $rating );

    wp_send_json_success( [ 'message' => __( 'Review submitted!', 'sdwd-couple' ) ] );
}

/**
 * Get reviews for a vendor/venue.
 */
function sdwd_get_reviews( $post_id, $limit = 10 ) {
    return get_posts( [
        'post_type'   => 'sdwd_review',
        'post_status' => 'publish',
        'meta_key'    => 'sdwd_reviewed_id',
        'meta_value'  => $post_id,
        'numberposts' => $limit,
        'orderby'     => 'date',
        'order'       => 'DESC',
    ] );
}

/**
 * Get average rating for a vendor/venue.
 */
function sdwd_get_average_rating( $post_id ) {
    $reviews = sdwd_get_reviews( $post_id, -1 );
    if ( empty( $reviews ) ) {
        return 0;
    }
    $total = 0;
    foreach ( $reviews as $review ) {
        $total += (int) get_post_meta( $review->ID, 'sdwd_rating', true );
    }
    return round( $total / count( $reviews ), 1 );
}
