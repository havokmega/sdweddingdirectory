<?php
/**
 * SDWD Core — Dashboard AJAX Save Handler
 *
 * Handles profile saves from the frontend vendor/venue dashboards.
 */

defined( 'ABSPATH' ) || exit;

add_action( 'wp_ajax_sdwd_save_dashboard', 'sdwd_save_dashboard' );

/**
 * Save dashboard profile data via AJAX.
 */
function sdwd_save_dashboard() {
    check_ajax_referer( 'sdwd_dashboard_nonce', 'nonce' );

    $user = wp_get_current_user();
    if ( ! $user->ID ) {
        wp_send_json_error( [ 'message' => __( 'Not logged in.', 'sdwd-core' ) ] );
    }

    $role = '';
    if ( in_array( 'vendor', $user->roles, true ) ) {
        $role = 'vendor';
    } elseif ( in_array( 'venue', $user->roles, true ) ) {
        $role = 'venue';
    } elseif ( in_array( 'couple', $user->roles, true ) ) {
        $role = 'couple';
    }

    if ( ! $role ) {
        wp_send_json_error( [ 'message' => __( 'Invalid role.', 'sdwd-core' ) ] );
    }

    // Find the linked post.
    $post_id = get_user_meta( $user->ID, 'sdwd_post_id', true );
    if ( ! $post_id || get_post_field( 'post_author', $post_id ) != $user->ID ) {
        wp_send_json_error( [ 'message' => __( 'No linked profile found.', 'sdwd-core' ) ] );
    }

    // Update user name.
    if ( isset( $_POST['first_name'] ) ) {
        wp_update_user( [
            'ID'         => $user->ID,
            'first_name' => sanitize_text_field( wp_unslash( $_POST['first_name'] ) ),
            'last_name'  => sanitize_text_field( wp_unslash( $_POST['last_name'] ?? '' ) ),
        ] );
    }

    // Text meta fields (vendor/venue).
    $text_fields = [ 'sdwd_company_name', 'sdwd_email', 'sdwd_phone', 'sdwd_company_website' ];

    // Venue-only fields.
    if ( $role === 'venue' ) {
        $text_fields = array_merge( $text_fields, [ 'sdwd_street_address', 'sdwd_city', 'sdwd_zip_code' ] );
    }

    foreach ( $text_fields as $key ) {
        if ( isset( $_POST[ $key ] ) ) {
            update_post_meta( $post_id, $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) );
        }
    }

    // Capacity (venue only).
    if ( $role === 'venue' && isset( $_POST['sdwd_capacity'] ) ) {
        update_post_meta( $post_id, 'sdwd_capacity', absint( $_POST['sdwd_capacity'] ) );
    }

    // Description (post content).
    if ( isset( $_POST['sdwd_description'] ) ) {
        wp_update_post( [
            'ID'           => $post_id,
            'post_content' => wp_kses_post( wp_unslash( $_POST['sdwd_description'] ) ),
        ] );
    }

    // Social media.
    if ( isset( $_POST['sdwd_social'] ) && is_array( $_POST['sdwd_social'] ) ) {
        $social = [];
        foreach ( $_POST['sdwd_social'] as $row ) {
            $label = sanitize_text_field( wp_unslash( $row['label'] ?? '' ) );
            $url   = esc_url_raw( wp_unslash( $row['url'] ?? '' ) );
            if ( ! empty( $label ) || ! empty( $url ) ) {
                $social[] = [ 'label' => $label, 'url' => $url ];
            }
        }
        update_post_meta( $post_id, 'sdwd_social', $social );
    }

    // Business hours.
    if ( isset( $_POST['sdwd_hours'] ) && is_array( $_POST['sdwd_hours'] ) ) {
        $hours = [];
        foreach ( $_POST['sdwd_hours'] as $day => $vals ) {
            $hours[ sanitize_key( $day ) ] = [
                'open'   => sanitize_text_field( $vals['open'] ?? '' ),
                'close'  => sanitize_text_field( $vals['close'] ?? '' ),
                'closed' => ! empty( $vals['closed'] ),
            ];
        }
        update_post_meta( $post_id, 'sdwd_hours', $hours );
    }

    // Pricing tiers.
    if ( isset( $_POST['sdwd_pricing'] ) && is_array( $_POST['sdwd_pricing'] ) ) {
        $pricing = [];
        foreach ( array_slice( $_POST['sdwd_pricing'], 0, 3 ) as $tier ) {
            $name  = sanitize_text_field( wp_unslash( $tier['name'] ?? '' ) );
            $price = sanitize_text_field( wp_unslash( $tier['price'] ?? '' ) );
            $features = [];
            if ( isset( $tier['features'] ) && is_array( $tier['features'] ) ) {
                foreach ( array_slice( $tier['features'], 0, 10 ) as $f ) {
                    $val = sanitize_text_field( wp_unslash( $f ) );
                    if ( ! empty( $val ) ) {
                        $features[] = $val;
                    }
                }
            }
            if ( ! empty( $name ) || ! empty( $price ) || ! empty( $features ) ) {
                $pricing[] = [ 'name' => $name, 'price' => $price, 'features' => $features ];
            }
        }
        update_post_meta( $post_id, 'sdwd_pricing', $pricing );
    }

    // Password change.
    if ( ! empty( $_POST['sdwd_new_password'] ) ) {
        wp_set_password( $_POST['sdwd_new_password'], $user->ID );
        // Re-set auth cookie so user stays logged in.
        wp_set_auth_cookie( $user->ID );
    }

    // Couple-specific fields.
    if ( $role === 'couple' ) {
        if ( isset( $_POST['sdwd_wedding_date'] ) ) {
            update_post_meta( $post_id, 'sdwd_wedding_date', sanitize_text_field( wp_unslash( $_POST['sdwd_wedding_date'] ) ) );
        }
    }

    wp_send_json_success( [ 'message' => __( 'Profile updated.', 'sdwd-core' ) ] );
}
