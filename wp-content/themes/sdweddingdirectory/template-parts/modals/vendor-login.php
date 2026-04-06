<?php
/**
 * Modal: Vendor / Venue Login
 */
defined( 'ABSPATH' ) || exit;
?>
<div class="modal-overlay" id="modal-vendor-login">
    <div class="modal">
        <button class="modal__close" data-modal-close aria-label="<?php esc_attr_e( 'Close', 'sdweddingdirectory' ); ?>">&times;</button>
        <div class="modal__body">
            <h3 class="modal__title"><?php esc_html_e( 'Vendor / Venue Login', 'sdweddingdirectory' ); ?></h3>
            <p class="modal__subtitle"><?php esc_html_e( 'Manage your business listing, reviews, and leads.', 'sdweddingdirectory' ); ?></p>

            <div class="modal__alert"></div>

            <form method="post" data-ajax-action="sdweddingdirectory_vendor_login_action">
                <div class="modal__field">
                    <label class="modal__label" for="vendor-login-username"><?php esc_html_e( 'Username or email', 'sdweddingdirectory' ); ?></label>
                    <input class="modal__input" type="text" id="vendor-login-username" name="sdweddingdirectory_vendor_login_username" required autocomplete="username">
                </div>

                <div class="modal__field">
                    <label class="modal__label" for="vendor-login-password"><?php esc_html_e( 'Password', 'sdweddingdirectory' ); ?></label>
                    <input class="modal__input" type="password" id="vendor-login-password" name="sdweddingdirectory_vendor_login_password" required autocomplete="current-password">
                </div>

                <?php wp_nonce_field( 'sdweddingdirectory_vendor_login_security', 'security', true, true ); ?>
                <input type="hidden" name="redirect_link" value="<?php echo esc_url( home_url( '/vendor-dashboard/' ) ); ?>">

                <div class="modal__actions">
                    <button type="submit" class="btn btn--primary modal__submit"><?php esc_html_e( 'Log In', 'sdweddingdirectory' ); ?></button>
                </div>
            </form>
        </div>

        <div class="modal__footer">
            <a class="modal__link" href="javascript:" data-modal-switch="forgot-password"><?php esc_html_e( 'Forgot your password?', 'sdweddingdirectory' ); ?></a>
            <div class="modal__divider"><?php esc_html_e( 'or', 'sdweddingdirectory' ); ?></div>
            <?php esc_html_e( 'Not a member yet?', 'sdweddingdirectory' ); ?>
            <a class="modal__link" href="javascript:" data-modal-switch="vendor-register"><?php esc_html_e( 'Join now', 'sdweddingdirectory' ); ?></a>
            <br>
            <?php esc_html_e( 'Are you a couple?', 'sdweddingdirectory' ); ?>
            <a class="modal__link" href="javascript:" data-modal-switch="couple-login"><?php esc_html_e( 'Couple login', 'sdweddingdirectory' ); ?></a>
        </div>
    </div>
</div>
