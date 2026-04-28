<?php
/**
 * SDWD Couple — Real Wedding Submission
 *
 * Saves the couple's real-wedding draft on their couple post.
 * Spec s14: About / Wedding Info / Hire Vendors tabs.
 */

defined( 'ABSPATH' ) || exit;

add_action( 'wp_ajax_sdwd_save_real_wedding', 'sdwd_handle_save_real_wedding' );

function sdwd_handle_save_real_wedding() {
    check_ajax_referer( 'sdwd_real_wedding_nonce', 'nonce' );

    $user = wp_get_current_user();
    if ( ! $user->ID ) {
        wp_send_json_error( [ 'message' => __( 'Please log in.', 'sdwd-couple' ) ] );
    }
    $post_id = (int) get_user_meta( $user->ID, 'sdwd_post_id', true );
    if ( ! $post_id ) {
        wp_send_json_error( [ 'message' => __( 'No linked profile.', 'sdwd-couple' ) ] );
    }

    if ( isset( $_POST['rw_title'] ) ) {
        update_post_meta( $post_id, 'sdwd_rw_title', sanitize_text_field( wp_unslash( $_POST['rw_title'] ) ) );
    }
    if ( isset( $_POST['rw_description'] ) ) {
        update_post_meta( $post_id, 'sdwd_rw_description', wp_kses_post( wp_unslash( $_POST['rw_description'] ) ) );
    }
    if ( isset( $_POST['rw_cover_id'] ) ) {
        update_post_meta( $post_id, 'sdwd_rw_cover_id', absint( $_POST['rw_cover_id'] ) );
    }
    if ( isset( $_POST['rw_tags'] ) && is_array( $_POST['rw_tags'] ) ) {
        $tags = array_filter( array_map( fn( $t ) => sanitize_text_field( wp_unslash( $t ) ), array_slice( $_POST['rw_tags'], 0, 30 ) ) );
        update_post_meta( $post_id, 'sdwd_rw_tags', array_values( $tags ) );
    }
    if ( isset( $_POST['rw_vendors'] ) && is_array( $_POST['rw_vendors'] ) ) {
        $vendors = array_filter( array_map( 'absint', $_POST['rw_vendors'] ) );
        update_post_meta( $post_id, 'sdwd_rw_vendors', array_values( $vendors ) );
    }

    wp_send_json_success( [ 'message' => __( 'Real wedding draft saved.', 'sdwd-couple' ) ] );
}
