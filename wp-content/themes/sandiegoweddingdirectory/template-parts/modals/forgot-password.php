<?php
/**
 * Modal: Forgot Password
 */
defined( 'ABSPATH' ) || exit;
?>
<div class="modal-overlay" id="modal-forgot-password">
    <div class="sdwd-modal">
        <button class="sdwd-modal__close" data-modal-close aria-label="<?php esc_attr_e( 'Close', 'sdweddingdirectory' ); ?>">&times;</button>
        <div class="sdwd-modal__body">
            <h3 class="sdwd-modal__title"><?php esc_html_e( 'Reset your password', 'sdweddingdirectory' ); ?></h3>
            <p class="sdwd-modal__subtitle"><?php esc_html_e( 'Enter your email address and we\'ll send you a reset link.', 'sdweddingdirectory' ); ?></p>

            <div class="sdwd-modal__alert"></div>

            <form method="post" data-ajax-action="sdweddingdirectory_forgot_password_form_action">
                <div class="sdwd-modal__field">
                    <label class="sdwd-modal__label" for="forgot-email"><?php esc_html_e( 'Email address', 'sdweddingdirectory' ); ?></label>
                    <input class="sdwd-modal__input" type="email" id="forgot-email" name="email" required autocomplete="email">
                </div>

                <?php wp_nonce_field( 'sdweddingdirectory_forgot_password_security', 'security', true, true ); ?>

                <div class="sdwd-modal__actions">
                    <button type="submit" class="btn btn--primary sdwd-modal__submit"><?php esc_html_e( 'Send Reset Link', 'sdweddingdirectory' ); ?></button>
                </div>
            </form>
        </div>

        <div class="sdwd-modal__footer">
            <a class="sdwd-modal__link" href="javascript:" data-modal-switch="couple-login"><?php esc_html_e( 'Back to Couple login', 'sdweddingdirectory' ); ?></a>
            &nbsp;&middot;&nbsp;
            <a class="sdwd-modal__link" href="javascript:" data-modal-switch="vendor-login"><?php esc_html_e( 'Vendor login', 'sdweddingdirectory' ); ?></a>
        </div>
    </div>
</div>
