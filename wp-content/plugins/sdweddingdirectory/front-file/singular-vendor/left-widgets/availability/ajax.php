<?php
/**
 * Availability AJAX endpoint.
 */
if( ! function_exists( 'sd_get_vendor_availability' ) ){

    function sd_get_vendor_availability() {

        $vendor_id = absint( isset( $_GET['vendor_id'] ) ? $_GET['vendor_id'] : 0 );

        if( ! $vendor_id ){
            wp_send_json_error();
        }

        $booked = get_post_meta( $vendor_id, sanitize_key( 'vendor_booked_dates' ), true );

        if( ! is_array( $booked ) ){
            $booked = get_post_meta( $vendor_id, sanitize_key( 'venue_booked_dates' ), true );
        }

        if( ! is_array( $booked ) ){
            $booked = [];
        }

        $booked = array_values( array_unique( array_filter( array_map( function( $date ){
            $date = sanitize_text_field( $date );
            return preg_match( '/^\d{4}-\d{2}-\d{2}$/', $date ) ? $date : '';
        }, $booked ) ) ) );

        wp_send_json_success([
            'booked_dates'    => $booked,
            'available_dates' => [],
        ]);
    }

    add_action( 'wp_ajax_sd_get_vendor_availability', 'sd_get_vendor_availability' );
    add_action( 'wp_ajax_nopriv_sd_get_vendor_availability', 'sd_get_vendor_availability' );
}
