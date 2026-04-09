<?php
/**
 * Modal: Couple Login
 */
defined( 'ABSPATH' ) || exit;
?>
<div class="sdwd-modal-overlay" id="sdwd-modal-couple-login">
    <div class="sdwd-modal sdwd-modal--split">
        <button class="sdwd-modal__close" data-sdwd-modal-close aria-label="<?php esc_attr_e( 'Close', 'sdwd-core' ); ?>">&times;</button>

        <div class="sdwd-modal__image" style="background-image: url('<?php echo esc_url( get_theme_file_uri( 'assets/images/placeholders/modal-popup/couple-login-register.jpg' ) ); ?>');"></div>

        <div class="sdwd-modal__content">
            <div class="sdwd-modal__body">
                <h3 class="sdwd-modal__title"><?php esc_html_e( 'Log in Couple account', 'sdwd-core' ); ?></h3>
                <p class="sdwd-modal__subtitle"><?php esc_html_e( 'Not a member yet?', 'sdwd-core' ); ?> <a class="sdwd-modal__link" href="javascript:" data-sdwd-modal-switch="couple-register"><?php esc_html_e( 'Join now', 'sdwd-core' ); ?></a></p>

                <div class="sdwd-modal__alert" role="alert"></div>

                <p class="sdwd-modal__or-text"><?php esc_html_e( 'Log in with your email', 'sdwd-core' ); ?></p>

                <form method="post" data-sdwd-form="login">
                    <div class="sdwd-modal__field">
                        <input class="sdwd-modal__input" type="text" name="login" required autocomplete="username" placeholder="<?php esc_attr_e( 'Username or email address', 'sdwd-core' ); ?>">
                    </div>

                    <div class="sdwd-modal__field">
                        <input class="sdwd-modal__input" type="password" name="password" required autocomplete="current-password" placeholder="<?php esc_attr_e( 'Password', 'sdwd-core' ); ?>">
                    </div>

                    <p class="sdwd-modal__forgot">
                        <a class="sdwd-modal__link" href="javascript:" data-sdwd-modal-switch="forgot-password"><?php esc_html_e( 'Forgot your password?', 'sdwd-core' ); ?></a>
                    </p>

                    <div class="sdwd-modal__actions">
                        <button type="submit" class="sdwd-modal__submit"><?php esc_html_e( 'Log In', 'sdwd-core' ); ?></button>
                    </div>
                </form>
            </div>

            <div class="sdwd-modal__footer">
                <strong><?php esc_html_e( 'Are you a vendor?', 'sdwd-core' ); ?></strong><br>
                <a class="sdwd-modal__link" href="javascript:" data-sdwd-modal-switch="vendor-login"><?php esc_html_e( 'Vendor login', 'sdwd-core' ); ?></a>
            </div>
        </div>
    </div>
</div>
