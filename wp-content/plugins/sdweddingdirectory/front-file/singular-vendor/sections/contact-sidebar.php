<?php
/**
 * Vendor Singular - Section: Message + Review Sidebar
 */

$post_id = isset( $post_id ) ? absint( $post_id ) : absint( get_the_ID() );

if( empty( $post_id ) ){
    return;
}

$current_user = wp_get_current_user();
$default_name = '';

if( is_user_logged_in() ){
    $first_name = sanitize_text_field( get_user_meta( absint( $current_user->ID ), sanitize_key( 'first_name' ), true ) );
    $last_name  = sanitize_text_field( get_user_meta( absint( $current_user->ID ), sanitize_key( 'last_name' ), true ) );
    $default_name = trim( $first_name . ' ' . $last_name );
    if( $default_name === '' ){
        $default_name = sanitize_text_field( $current_user->display_name );
    }
}

$default_email = is_user_logged_in() ? sanitize_email( $current_user->user_email ) : '';
$default_message = esc_html__( "We were excited to find you on SD Wedding Directory! We'd love to get more information about your services and availability.", 'sdweddingdirectory' );
?>
<div class="sd-profile-contact-sticky">
    <div class="sd-profile-message-card mb-3">
        <h4 class="sd-profile-message-title"><?php esc_html_e( 'Message vendor', 'sdweddingdirectory' ); ?></h4>

        <form class="sd-profile-message-form" data-post-id="<?php echo esc_attr( $post_id ); ?>" data-post-type="vendor">
            <div class="mb-2">
                <label class="form-label small mb-1"><?php esc_html_e( 'Message', 'sdweddingdirectory' ); ?></label>
                <textarea class="form-control" name="message_body" rows="5" required><?php echo esc_textarea( $default_message ); ?></textarea>
            </div>

            <div class="mb-2">
                <label class="form-label small mb-1"><?php esc_html_e( 'First and last name', 'sdweddingdirectory' ); ?></label>
                <input type="text" class="form-control" name="full_name" value="<?php echo esc_attr( $default_name ); ?>" required>
            </div>

            <div class="mb-2">
                <label class="form-label small mb-1"><?php esc_html_e( 'Email', 'sdweddingdirectory' ); ?></label>
                <input type="email" class="form-control" name="email" value="<?php echo esc_attr( $default_email ); ?>" required>
            </div>

            <div class="mb-2">
                <label class="form-label small mb-1"><?php esc_html_e( 'Phone number (Optional)', 'sdweddingdirectory' ); ?></label>
                <input type="text" class="form-control" name="phone" value="">
            </div>

            <div class="mb-3">
                <label class="form-label small mb-1"><?php esc_html_e( 'Event date', 'sdweddingdirectory' ); ?></label>
                <input type="date" class="form-control" name="event_date" required>
            </div>

            <p class="small text-muted mb-2">
                <?php esc_html_e( "By clicking 'Send', you accept and agree to our ", 'sdweddingdirectory' ); ?>
                <!-- TODO: Replace # with actual Terms of Use URL -->
                <a href="#"><?php esc_html_e( 'Terms of Use', 'sdweddingdirectory' ); ?></a>,
                <!-- TODO: Replace # with actual Privacy Policy URL -->
                <a href="#"><?php esc_html_e( 'Privacy Policy', 'sdweddingdirectory' ); ?></a>,
                <?php esc_html_e( 'and', 'sdweddingdirectory' ); ?>
                <!-- TODO: Replace # with actual CA Privacy Policy URL -->
                <a href="#"><?php esc_html_e( 'CA Privacy Policy', 'sdweddingdirectory' ); ?></a>.
            </p>

            <p class="small mb-3 sd-profile-message-response">
                <i class="fa fa-clock-o" aria-hidden="true"></i>
                <?php esc_html_e( 'Responds within 24 hours', 'sdweddingdirectory' ); ?>
            </p>

            <?php if( is_user_logged_in() ){ ?>
                <input type="hidden" name="nonce" value="<?php echo esc_attr( wp_create_nonce( 'sdwd_profile_actions' ) ); ?>">
                <button type="submit" class="btn sd-profile-message-send w-100"><?php esc_html_e( 'Send', 'sdweddingdirectory' ); ?></button>
            <?php }else{ ?>
                <a class="btn sd-profile-message-send w-100" <?php echo apply_filters( 'sdweddingdirectory/couple-login/attr', '' ); ?>>
                    <?php esc_html_e( 'Send', 'sdweddingdirectory' ); ?>
                </a>
            <?php } ?>
        </form>
    </div>

    <?php if( is_user_logged_in() ){ ?>
        <a href="javascript:" class="sd-profile-write-review-btn sdweddingdirectory-request-quote-popup" data-venue-id="<?php echo esc_attr( $post_id ); ?>" data-event-call="0" id="<?php echo esc_attr( wp_unique_id( 'sdwd_sidebar_review_' ) ); ?>">
            <?php esc_html_e( 'Write a Review', 'sdweddingdirectory' ); ?>
        </a>
    <?php }else{ ?>
        <a class="sd-profile-write-review-btn" <?php echo apply_filters( 'sdweddingdirectory/couple-login/attr', '' ); ?>>
            <?php esc_html_e( 'Write a Review', 'sdweddingdirectory' ); ?>
        </a>
    <?php } ?>
</div>
