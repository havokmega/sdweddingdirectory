<?php
/**
 * SDWD Core — User ↔ Post Linking
 *
 * When a user registers, auto-create a CPT post (couple, vendor, or venue)
 * linked to their user account via post_author.
 */

defined( 'ABSPATH' ) || exit;

add_action( 'sdwd_user_registered', 'sdwd_create_user_post', 10, 2 );

/**
 * Create a CPT post for a newly registered user.
 *
 * @param int    $user_id      The new user's ID.
 * @param string $account_type 'couple', 'vendor', or 'venue'.
 */
function sdwd_create_user_post( $user_id, $account_type ) {

    if ( ! in_array( $account_type, [ 'couple', 'vendor', 'venue' ], true ) ) {
        return;
    }

    $user         = get_userdata( $user_id );
    $company_name = get_user_meta( $user_id, 'sdwd_company_name', true );

    // Couples use their display name, businesses use company name.
    if ( 'couple' === $account_type ) {
        $post_title = $user->first_name . ' ' . $user->last_name;
    } else {
        $post_title = ! empty( $company_name ) ? $company_name : $user->display_name;
    }

    $post_id = wp_insert_post( [
        'post_type'   => $account_type,
        'post_title'  => trim( $post_title ),
        'post_status' => 'publish',
        'post_author' => $user_id,
    ], true );

    if ( is_wp_error( $post_id ) ) {
        return;
    }

    // Store the post ID on the user for quick lookup.
    update_user_meta( $user_id, 'sdwd_post_id', $post_id );

    // Store contact email on the post.
    update_post_meta( $post_id, 'sdwd_email', $user->user_email );

    // Couple-specific meta.
    if ( 'couple' === $account_type ) {
        $couple_keys = [ 'sdwd_wedding_date', 'sdwd_couple_type' ];
        foreach ( $couple_keys as $key ) {
            $value = get_user_meta( $user_id, $key, true );
            if ( ! empty( $value ) ) {
                update_post_meta( $post_id, $key, $value );
            }
        }
    }

    // Business-specific meta (vendor/venue).
    if ( in_array( $account_type, [ 'vendor', 'venue' ], true ) ) {
        $biz_keys = [
            'sdwd_company_name',
            'sdwd_company_address',
            'sdwd_company_website',
            'sdwd_zip_code',
            'sdwd_contact_number',
        ];
        foreach ( $biz_keys as $key ) {
            $value = get_user_meta( $user_id, $key, true );
            if ( ! empty( $value ) ) {
                update_post_meta( $post_id, $key, $value );
            }
        }
    }
}

/**
 * Helper: get the CPT post ID for a user.
 *
 * @param int $user_id
 * @return int|false Post ID or false.
 */
function sdwd_get_user_post_id( $user_id ) {
    $post_id = get_user_meta( $user_id, 'sdwd_post_id', true );
    if ( $post_id && get_post( $post_id ) ) {
        return (int) $post_id;
    }
    return false;
}

/**
 * Helper: check if current user is a specific role.
 *
 * @param string $role 'couple', 'vendor', or 'venue'.
 * @return bool
 */
function sdwd_is_role( $role ) {
    if ( ! is_user_logged_in() ) {
        return false;
    }
    $user = wp_get_current_user();
    return in_array( $role, $user->roles, true );
}
