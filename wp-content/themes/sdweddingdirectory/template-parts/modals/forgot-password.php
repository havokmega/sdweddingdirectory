<?php
/**
 * Modal: Forgot Password
 */
defined( 'ABSPATH' ) || exit;
?>
<div class="modal-overlay" id="modal-forgot-password">
    <div class="modal">
        <button class="modal__close" data-modal-close aria-label="<?php esc_attr_e( 'Close', 'sdweddingdirectory' ); ?>">&times;</button>
        <div class="modal__body">
            <h3 class="modal__title"><?php esc_html_e( 'Reset your password', 'sdweddingdirectory' ); ?></h3>
            <p class="modal__subtitle"><?php esc_html_e( 'Enter your email address and we\'ll send you a reset link.', 'sdweddingdirectory' ); ?></p>

            <div class="modal__alert"></div>

            <form method="post" data-ajax-action="sdweddingdirectory_forgot_password_form_action">
                <div class="modal__field">
                    <label class="modal__label" for="forgot-email"><?php esc_html_e( 'Email address', 'sdweddingdirectory' ); ?></label>
                    <input class="modal__input" type="email" id="forgot-email" name="email" required autocomplete="email">
                </div>

                <?php wp_nonce_field( 'sdweddingdirectory_forgot_password_security', 'security', true, true ); ?>

                <div class="modal__actions">
                    <button type="submit" class="btn btn--primary modal__submit"><?php esc_html_e( 'Send Reset Link', 'sdweddingdirectory' ); ?></button>
                </div>
            </form>
        </div>

        <div class="modal__footer">
            <a class="modal__link" href="javascript:" data-modal-switch="couple-login"><?php esc_html_e( 'Back to Couple login', 'sdweddingdirectory' ); ?></a>
            &nbsp;&middot;&nbsp;
            <a class="modal__link" href="javascript:" data-modal-switch="vendor-login"><?php esc_html_e( 'Vendor login', 'sdweddingdirectory' ); ?></a>
        </div>
    </div>
</div>
