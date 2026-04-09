<?php
/**
 * Modal: Forgot Password
 */
defined( 'ABSPATH' ) || exit;
?>
<div class="sdwd-modal-overlay" id="sdwd-modal-forgot-password">
    <div class="sdwd-modal sdwd-modal--split">
        <button class="sdwd-modal__close" data-sdwd-modal-close aria-label="<?php esc_attr_e( 'Close', 'sdwd-core' ); ?>">&times;</button>

        <div class="sdwd-modal__image" style="background-image: url('<?php echo esc_url( get_theme_file_uri( 'assets/images/placeholders/modal-popup/forgot-password.jpg' ) ); ?>');"></div>

        <div class="sdwd-modal__content">
            <div class="sdwd-modal__body">
                <h3 class="sdwd-modal__title"><?php esc_html_e( 'Reset your password', 'sdwd-core' ); ?></h3>
                <p class="sdwd-modal__subtitle"><?php esc_html_e( 'Enter your email and we\'ll send you a reset link.', 'sdwd-core' ); ?></p>

                <div class="sdwd-modal__alert" role="alert"></div>

                <form method="post" data-sdwd-form="forgot_password">
                    <div class="sdwd-modal__field">
                        <input class="sdwd-modal__input" type="email" name="email" required autocomplete="email" placeholder="<?php esc_attr_e( 'Email address', 'sdwd-core' ); ?>">
                    </div>

                    <div class="sdwd-modal__actions">
                        <button type="submit" class="sdwd-modal__submit"><?php esc_html_e( 'Send Reset Link', 'sdwd-core' ); ?></button>
                    </div>
                </form>
            </div>

            <div class="sdwd-modal__footer">
                <a class="sdwd-modal__link" href="javascript:" data-sdwd-modal-switch="couple-login"><?php esc_html_e( 'Couple login', 'sdwd-core' ); ?></a>
                &nbsp;&middot;&nbsp;
                <a class="sdwd-modal__link" href="javascript:" data-sdwd-modal-switch="vendor-login"><?php esc_html_e( 'Vendor login', 'sdwd-core' ); ?></a>
            </div>
        </div>
    </div>
</div>
