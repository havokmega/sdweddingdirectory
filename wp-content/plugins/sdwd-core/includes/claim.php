<?php
/**
 * SDWD Core — Claim System
 *
 * Allows users to claim unclaimed vendor/venue profiles.
 * Flow: user submits claim → stored as pending → admin approves/rejects → post_author updated.
 */

defined( 'ABSPATH' ) || exit;

// AJAX handlers.
add_action( 'wp_ajax_sdwd_submit_claim', 'sdwd_handle_submit_claim' );
add_action( 'wp_ajax_nopriv_sdwd_submit_claim', 'sdwd_handle_submit_claim' );

// Admin UI.
add_action( 'add_meta_boxes', 'sdwd_claim_meta_box' );
add_action( 'wp_ajax_sdwd_approve_claim', 'sdwd_handle_approve_claim' );
add_action( 'wp_ajax_sdwd_reject_claim', 'sdwd_handle_reject_claim' );

/**
 * Check if a post is unclaimed (author is admin or 0).
 */
function sdwd_is_unclaimed( $post_id ) {
    $author_id = (int) get_post_field( 'post_author', $post_id );
    if ( ! $author_id ) {
        return true;
    }
    $author = get_userdata( $author_id );
    if ( ! $author ) {
        return true;
    }
    // If the author is an administrator, it's unclaimed (admin-created).
    return in_array( 'administrator', $author->roles, true );
}

/**
 * Get pending claim for a post.
 */
function sdwd_get_pending_claim( $post_id ) {
    return get_post_meta( $post_id, 'sdwd_claim', true );
}

/**
 * Submit a claim (AJAX).
 */
function sdwd_handle_submit_claim() {
    check_ajax_referer( 'sdwd_claim_nonce', 'nonce' );

    $post_id = absint( $_POST['post_id'] ?? 0 );
    $message = sanitize_textarea_field( wp_unslash( $_POST['message'] ?? '' ) );

    if ( ! $post_id || ! in_array( get_post_type( $post_id ), [ 'vendor', 'venue' ], true ) ) {
        wp_send_json_error( [ 'message' => __( 'Invalid profile.', 'sdwd-core' ) ] );
    }

    if ( ! sdwd_is_unclaimed( $post_id ) ) {
        wp_send_json_error( [ 'message' => __( 'This profile has already been claimed.', 'sdwd-core' ) ] );
    }

    $existing = sdwd_get_pending_claim( $post_id );
    if ( $existing ) {
        wp_send_json_error( [ 'message' => __( 'A claim is already pending for this profile.', 'sdwd-core' ) ] );
    }

    // User must be logged in.
    if ( ! is_user_logged_in() ) {
        wp_send_json_error( [ 'message' => __( 'Please log in or register first.', 'sdwd-core' ) ] );
    }

    $user = wp_get_current_user();
    $post_type = get_post_type( $post_id );
    $required_role = $post_type; // 'vendor' or 'venue'

    if ( ! in_array( $required_role, $user->roles, true ) ) {
        wp_send_json_error( [ 'message' => sprintf( __( 'You must have a %s account to claim this profile.', 'sdwd-core' ), $required_role ) ] );
    }

    // Store the claim.
    update_post_meta( $post_id, 'sdwd_claim', [
        'user_id'    => $user->ID,
        'user_email' => $user->user_email,
        'user_name'  => $user->display_name,
        'message'    => $message,
        'date'       => current_time( 'mysql' ),
        'status'     => 'pending',
    ] );

    wp_send_json_success( [ 'message' => __( 'Your claim has been submitted and is pending review.', 'sdwd-core' ) ] );
}

/**
 * Admin meta box showing pending claims.
 */
function sdwd_claim_meta_box() {
    foreach ( [ 'vendor', 'venue' ] as $cpt ) {
        add_meta_box(
            'sdwd-claim',
            __( 'Business Claim', 'sdwd-core' ),
            'sdwd_claim_meta_box_cb',
            $cpt,
            'side',
            'high'
        );
    }
}

function sdwd_claim_meta_box_cb( $post ) {
    $claim = sdwd_get_pending_claim( $post->ID );

    if ( sdwd_is_unclaimed( $post->ID ) ) {
        echo '<p style="color:#b32d2e;font-weight:600;">' . esc_html__( 'Unclaimed', 'sdwd-core' ) . '</p>';
    } else {
        $owner = get_userdata( $post->post_author );
        echo '<p style="color:#1a7a42;font-weight:600;">' . esc_html__( 'Claimed by: ', 'sdwd-core' ) . esc_html( $owner ? $owner->display_name : '#' . $post->post_author ) . '</p>';
    }

    if ( $claim && $claim['status'] === 'pending' ) {
        $nonce = wp_create_nonce( 'sdwd_claim_action' );
        ?>
        <hr>
        <p><strong><?php esc_html_e( 'Pending Claim', 'sdwd-core' ); ?></strong></p>
        <p><?php echo esc_html( $claim['user_name'] ); ?> (<?php echo esc_html( $claim['user_email'] ); ?>)</p>
        <p><em><?php echo esc_html( $claim['date'] ); ?></em></p>
        <?php if ( ! empty( $claim['message'] ) ) : ?>
            <p><?php echo esc_html( $claim['message'] ); ?></p>
        <?php endif; ?>
        <p>
            <button type="button" class="button button-primary" onclick="sdwdClaimAction('approve', <?php echo $post->ID; ?>, '<?php echo $nonce; ?>')"><?php esc_html_e( 'Approve', 'sdwd-core' ); ?></button>
            <button type="button" class="button" onclick="sdwdClaimAction('reject', <?php echo $post->ID; ?>, '<?php echo $nonce; ?>')"><?php esc_html_e( 'Reject', 'sdwd-core' ); ?></button>
        </p>
        <script>
        function sdwdClaimAction(action, postId, nonce) {
            var data = new FormData();
            data.append('action', 'sdwd_' + action + '_claim');
            data.append('post_id', postId);
            data.append('nonce', nonce);
            fetch(ajaxurl, { method: 'POST', body: data })
                .then(function(r) { return r.json(); })
                .then(function(res) {
                    alert(res.data.message);
                    location.reload();
                });
        }
        </script>
        <?php
    } elseif ( ! $claim && sdwd_is_unclaimed( $post->ID ) ) {
        echo '<p>' . esc_html__( 'No claims submitted.', 'sdwd-core' ) . '</p>';
    }
}

/**
 * Approve a claim — transfer post ownership.
 */
function sdwd_handle_approve_claim() {
    check_ajax_referer( 'sdwd_claim_action', 'nonce' );

    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( [ 'message' => __( 'Unauthorized.', 'sdwd-core' ) ] );
    }

    $post_id = absint( $_POST['post_id'] ?? 0 );
    $claim = sdwd_get_pending_claim( $post_id );

    if ( ! $claim || $claim['status'] !== 'pending' ) {
        wp_send_json_error( [ 'message' => __( 'No pending claim found.', 'sdwd-core' ) ] );
    }

    $user_id = (int) $claim['user_id'];

    // Transfer ownership.
    wp_update_post( [
        'ID'          => $post_id,
        'post_author' => $user_id,
    ] );

    // Link the user to this post.
    // Remove old post link if user had one.
    $old_post = get_user_meta( $user_id, 'sdwd_post_id', true );
    if ( $old_post && $old_post != $post_id ) {
        // Optionally trash the auto-created empty post.
        wp_trash_post( $old_post );
    }
    update_user_meta( $user_id, 'sdwd_post_id', $post_id );

    // Mark claim as approved.
    update_post_meta( $post_id, 'sdwd_claim', array_merge( $claim, [ 'status' => 'approved' ] ) );

    wp_send_json_success( [ 'message' => __( 'Claim approved. Ownership transferred.', 'sdwd-core' ) ] );
}

/**
 * Reject a claim.
 */
function sdwd_handle_reject_claim() {
    check_ajax_referer( 'sdwd_claim_action', 'nonce' );

    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( [ 'message' => __( 'Unauthorized.', 'sdwd-core' ) ] );
    }

    $post_id = absint( $_POST['post_id'] ?? 0 );
    $claim = sdwd_get_pending_claim( $post_id );

    if ( ! $claim || $claim['status'] !== 'pending' ) {
        wp_send_json_error( [ 'message' => __( 'No pending claim found.', 'sdwd-core' ) ] );
    }

    delete_post_meta( $post_id, 'sdwd_claim' );

    wp_send_json_success( [ 'message' => __( 'Claim rejected.', 'sdwd-core' ) ] );
}
