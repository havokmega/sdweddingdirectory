<?php
/**
 * SDWD Quote Request
 * Handles the vendor profile sidebar "Request Pricing" form.
 */

defined( 'ABSPATH' ) || exit;

add_action( 'wp_ajax_sdwd_send_quote',        'sdwd_handle_send_quote' );
add_action( 'wp_ajax_nopriv_sdwd_send_quote', 'sdwd_handle_send_quote' );

function sdwd_handle_send_quote() {
    check_ajax_referer( 'sdwd_quote_nonce', 'nonce' );

    $post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;
    $name    = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
    $email   = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
    $phone   = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
    $date    = isset( $_POST['wedding_date'] ) ? sanitize_text_field( wp_unslash( $_POST['wedding_date'] ) ) : '';
    $guests  = isset( $_POST['guests'] ) ? absint( $_POST['guests'] ) : 0;
    $message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';

    if ( ! $post_id || ! $name || ! is_email( $email ) || ! $message ) {
        wp_send_json_error( [ 'message' => __( 'Please fill in all required fields.', 'sdwd-core' ) ] );
    }

    $post = get_post( $post_id );
    if ( ! $post || ! in_array( $post->post_type, [ 'vendor', 'venue' ], true ) ) {
        wp_send_json_error( [ 'message' => __( 'Invalid recipient.', 'sdwd-core' ) ] );
    }

    $vendor_email = get_post_meta( $post_id, 'sdwd_email', true );
    if ( ! is_email( $vendor_email ) ) {
        $vendor_email = get_option( 'admin_email' );
    }

    $subject = sprintf( __( 'New quote request from %1$s — %2$s', 'sdwd-core' ), $name, get_the_title( $post_id ) );

    $body  = sprintf( __( "New quote request via %s\n\n", 'sdwd-core' ), home_url( '/' ) );
    $body .= sprintf( __( "Vendor: %s\n", 'sdwd-core' ), get_the_title( $post_id ) );
    $body .= sprintf( __( "Profile: %s\n\n", 'sdwd-core' ), get_permalink( $post_id ) );
    $body .= sprintf( __( "Name: %s\n", 'sdwd-core' ), $name );
    $body .= sprintf( __( "Email: %s\n", 'sdwd-core' ), $email );
    if ( $phone )   { $body .= sprintf( __( "Phone: %s\n", 'sdwd-core' ), $phone ); }
    if ( $date )    { $body .= sprintf( __( "Wedding date: %s\n", 'sdwd-core' ), $date ); }
    if ( $guests )  { $body .= sprintf( __( "Guests: %d\n", 'sdwd-core' ), $guests ); }
    $body .= "\n" . __( 'Message:', 'sdwd-core' ) . "\n" . $message . "\n";

    $headers = [
        'Content-Type: text/plain; charset=UTF-8',
        'Reply-To: ' . $name . ' <' . $email . '>',
    ];

    $sent = wp_mail( $vendor_email, $subject, $body, $headers );

    if ( $sent ) {
        wp_send_json_success( [ 'message' => __( 'Message sent. The vendor will be in touch soon.', 'sdwd-core' ) ] );
    }

    wp_send_json_error( [ 'message' => __( 'Could not send message. Please try again later.', 'sdwd-core' ) ] );
}
