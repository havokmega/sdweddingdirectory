<?php
/**
 * SDWD Core — Authentication
 *
 * AJAX handlers for registration, login, and forgot password.
 * All three account types (couple, vendor, venue) use these endpoints.
 */

defined( 'ABSPATH' ) || exit;

/**
 * Note: sdwd_ajax is localized by the theme in functions.php
 * where the sdwd-modals script is enqueued.
 */

// ── Registration ────────────────────────────────────────────

add_action( 'wp_ajax_nopriv_sdwd_register', 'sdwd_handle_register' );

function sdwd_handle_register() {
    check_ajax_referer( 'sdwd_auth_nonce', 'nonce' );

    $account_type = sanitize_text_field( wp_unslash( $_POST['account_type'] ?? '' ) );
    $first_name   = sanitize_text_field( wp_unslash( $_POST['first_name'] ?? '' ) );
    $last_name    = sanitize_text_field( wp_unslash( $_POST['last_name'] ?? '' ) );
    $email        = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
    $password     = $_POST['password'] ?? '';

    // Validate account type.
    $valid_types = [ 'couple', 'vendor', 'venue' ];
    if ( ! in_array( $account_type, $valid_types, true ) ) {
        wp_send_json_error( [ 'message' => __( 'Please select an account type.', 'sdwd-core' ) ] );
    }

    // Required fields.
    if ( empty( $first_name ) || empty( $last_name ) || empty( $email ) || empty( $password ) ) {
        wp_send_json_error( [ 'message' => __( 'All fields are required.', 'sdwd-core' ) ] );
    }

    // Validate email.
    if ( ! is_email( $email ) ) {
        wp_send_json_error( [ 'message' => __( 'Please enter a valid email address.', 'sdwd-core' ) ] );
    }

    // Check duplicates — email is the username.
    if ( email_exists( $email ) ) {
        wp_send_json_error( [ 'message' => __( 'An account with that email already exists.', 'sdwd-core' ) ] );
    }

    // Password strength.
    if ( strlen( $password ) < 8 ) {
        wp_send_json_error( [ 'message' => __( 'Password must be at least 8 characters.', 'sdwd-core' ) ] );
    }

    // Block self-signup into invitation-only vendor categories (e.g. DJs).
    // Even though the registration modal omits these from its dropdown,
    // a determined user could POST a forged term_id directly. Reject it here.
    if ( 'vendor' === $account_type
        && ! empty( $_POST['vendor_category'] )
        && function_exists( 'sdwd_invitation_only_vendor_categories' )
    ) {
        $submitted_term_id = absint( $_POST['vendor_category'] );
        $submitted_term    = $submitted_term_id ? get_term( $submitted_term_id, 'vendor-category' ) : null;
        $excluded_slugs    = sdwd_invitation_only_vendor_categories();
        if ( $submitted_term && ! is_wp_error( $submitted_term ) && in_array( $submitted_term->slug, $excluded_slugs, true ) ) {
            wp_send_json_error( [ 'message' => __( 'That category is by invitation only. Please choose another, or contact us directly.', 'sdwd-core' ) ] );
        }
    }

    // Create user — email is used as the username.
    $user_id = wp_create_user( $email, $password, $email );
    if ( is_wp_error( $user_id ) ) {
        wp_send_json_error( [ 'message' => $user_id->get_error_message() ] );
    }

    // Set role and meta.
    $user = new WP_User( $user_id );
    $user->set_role( $account_type );

    wp_update_user( [
        'ID'         => $user_id,
        'first_name' => $first_name,
        'last_name'  => $last_name,
    ] );

    // Business-specific fields (vendor/venue).
    if ( in_array( $account_type, [ 'vendor', 'venue' ], true ) ) {
        $business_fields = [
            'company_name'    => 'sdwd_company_name',
            'company_address' => 'sdwd_company_address',
            'company_website' => 'sdwd_company_website',
            'zip_code'        => 'sdwd_zip_code',
            'contact_number'  => 'sdwd_contact_number',
        ];
        foreach ( $business_fields as $post_key => $meta_key ) {
            $value = sanitize_text_field( wp_unslash( $_POST[ $post_key ] ?? '' ) );
            if ( ! empty( $value ) ) {
                update_user_meta( $user_id, $meta_key, $value );
            }
        }

        // Auto-create CPT post.
        do_action( 'sdwd_user_registered', $user_id, $account_type );
    }

    // Couple-specific fields.
    if ( 'couple' === $account_type ) {
        $wedding_date = sanitize_text_field( wp_unslash( $_POST['wedding_date'] ?? '' ) );
        if ( ! empty( $wedding_date ) ) {
            update_user_meta( $user_id, 'sdwd_wedding_date', $wedding_date );
        }
        $couple_type = sanitize_text_field( wp_unslash( $_POST['couple_type'] ?? '' ) );
        if ( ! empty( $couple_type ) ) {
            update_user_meta( $user_id, 'sdwd_couple_type', $couple_type );
        }

        // Auto-create couple CPT post.
        do_action( 'sdwd_user_registered', $user_id, $account_type );
    }

    // Log the user in.
    wp_set_current_user( $user_id );
    wp_set_auth_cookie( $user_id, true );

    // Redirect URL based on role.
    $redirects = [
        'couple' => home_url( '/couple-dashboard/' ),
        'vendor' => home_url( '/vendor-dashboard/' ),
        'venue'  => home_url( '/venue-dashboard/' ),
    ];

    wp_send_json_success( [
        'message'  => __( 'Account created successfully!', 'sdwd-core' ),
        'redirect' => $redirects[ $account_type ] ?? home_url(),
    ] );
}

// ── Login ───────────────────────────────────────────────────

add_action( 'wp_ajax_nopriv_sdwd_login', 'sdwd_handle_login' );

function sdwd_handle_login() {
    check_ajax_referer( 'sdwd_auth_nonce', 'nonce' );

    $login    = sanitize_text_field( wp_unslash( $_POST['login'] ?? '' ) );
    $password = $_POST['password'] ?? '';

    if ( empty( $login ) || empty( $password ) ) {
        wp_send_json_error( [ 'message' => __( 'Username and password are required.', 'sdwd-core' ) ] );
    }

    // Try login by email or username.
    $user = is_email( $login ) ? get_user_by( 'email', $login ) : get_user_by( 'login', $login );

    if ( ! $user || ! wp_check_password( $password, $user->user_pass, $user->ID ) ) {
        wp_send_json_error( [ 'message' => __( 'Invalid username or password.', 'sdwd-core' ) ] );
    }

    wp_set_current_user( $user->ID );
    wp_set_auth_cookie( $user->ID, true );

    // Redirect based on role.
    $redirect = home_url();
    if ( in_array( 'vendor', $user->roles, true ) ) {
        $redirect = home_url( '/vendor-dashboard/' );
    } elseif ( in_array( 'venue', $user->roles, true ) ) {
        $redirect = home_url( '/venue-dashboard/' );
    } elseif ( in_array( 'couple', $user->roles, true ) ) {
        $redirect = home_url( '/couple-dashboard/' );
    }

    wp_send_json_success( [
        'message'  => __( 'Logged in successfully!', 'sdwd-core' ),
        'redirect' => $redirect,
    ] );
}

// ── Forgot Password ─────────────────────────────────────────

add_action( 'wp_ajax_nopriv_sdwd_forgot_password', 'sdwd_handle_forgot_password' );

function sdwd_handle_forgot_password() {
    check_ajax_referer( 'sdwd_auth_nonce', 'nonce' );

    $email = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );

    if ( empty( $email ) || ! is_email( $email ) ) {
        wp_send_json_error( [ 'message' => __( 'Please enter a valid email address.', 'sdwd-core' ) ] );
    }

    $user = get_user_by( 'email', $email );

    // Always show success to prevent email enumeration.
    if ( $user ) {
        retrieve_password( $user->user_login );
    }

    wp_send_json_success( [
        'message' => __( 'If an account exists with that email, a reset link has been sent.', 'sdwd-core' ),
    ] );
}

// ── Block wp-admin & redirect login for frontend roles ─────

/**
 * Redirect vendor/venue/couple away from wp-admin.
 */
add_action( 'admin_init', function () {
    if ( wp_doing_ajax() ) {
        return;
    }
    $user = wp_get_current_user();
    if ( ! $user->ID ) {
        return;
    }
    $frontend_roles = [ 'vendor', 'venue', 'couple' ];
    if ( ! array_intersect( $frontend_roles, $user->roles ) ) {
        return;
    }
    wp_redirect( sdwd_get_dashboard_url( $user ) );
    exit;
} );

/**
 * Hide the admin bar for frontend roles.
 */
add_filter( 'show_admin_bar', function ( $show ) {
    $user = wp_get_current_user();
    if ( ! $user->ID ) {
        return $show;
    }
    $frontend_roles = [ 'vendor', 'venue', 'couple' ];
    if ( array_intersect( $frontend_roles, $user->roles ) ) {
        return false;
    }
    return $show;
} );

/**
 * Redirect wp-login.php to the right dashboard after login.
 */
add_filter( 'login_redirect', function ( $redirect_to, $requested, $user ) {
    if ( is_wp_error( $user ) || ! $user instanceof WP_User ) {
        return $redirect_to;
    }
    $frontend_roles = [ 'vendor', 'venue', 'couple' ];
    if ( array_intersect( $frontend_roles, $user->roles ) ) {
        return sdwd_get_dashboard_url( $user );
    }
    return $redirect_to;
}, 10, 3 );

/**
 * Get the frontend dashboard URL for a user.
 */
function sdwd_get_dashboard_url( $user ) {
    if ( in_array( 'vendor', $user->roles, true ) ) {
        return home_url( '/vendor-dashboard/' );
    }
    if ( in_array( 'venue', $user->roles, true ) ) {
        return home_url( '/venue-dashboard/' );
    }
    if ( in_array( 'couple', $user->roles, true ) ) {
        return home_url( '/couple-dashboard/' );
    }
    return home_url();
}
