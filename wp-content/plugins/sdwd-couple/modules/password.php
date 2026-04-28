<?php
/**
 * SDWD Couple — Password Change
 *
 * Frontend password change. Requires current-password verification +
 * 8-char minimum. Re-issues auth cookie on success.
 *
 * Maps to spec couple-profile-s4-password and Phase 2 P2-SEC-04 in roadmap.
 */

defined( 'ABSPATH' ) || exit;

add_action( 'wp_ajax_sdwd_change_password', 'sdwd_handle_change_password' );

function sdwd_handle_change_password() {
    check_ajax_referer( 'sdwd_password_nonce', 'nonce' );

    $user = wp_get_current_user();
    if ( ! $user->ID ) {
        wp_send_json_error( [ 'message' => __( 'Please log in.', 'sdwd-couple' ) ] );
    }

    $old     = (string) ( $_POST['old_password']     ?? '' );
    $new     = (string) ( $_POST['new_password']     ?? '' );
    $confirm = (string) ( $_POST['confirm_password'] ?? '' );

    if ( ! $old || ! $new || ! $confirm ) {
        wp_send_json_error( [ 'message' => __( 'All fields are required.', 'sdwd-couple' ) ] );
    }
    if ( strlen( $new ) < 8 ) {
        wp_send_json_error( [ 'message' => __( 'New password must be at least 8 characters.', 'sdwd-couple' ) ] );
    }
    if ( $new !== $confirm ) {
        wp_send_json_error( [ 'message' => __( "Passwords don't match.", 'sdwd-couple' ) ] );
    }

    $verified = wp_check_password( wp_unslash( $old ), $user->user_pass, $user->ID );
    if ( ! $verified ) {
        wp_send_json_error( [ 'message' => __( 'Current password is incorrect.', 'sdwd-couple' ) ] );
    }

    wp_set_password( wp_unslash( $new ), $user->ID );
    wp_set_auth_cookie( $user->ID, true );

    wp_send_json_success( [ 'message' => __( 'Password updated.', 'sdwd-couple' ) ] );
}
