<?php
/**
 * Modal: Couple Login
 */
defined( 'ABSPATH' ) || exit;
?>
<div class="modal-overlay" id="modal-couple-login">
    <div class="sdwd-modal">
        <button class="sdwd-modal__close" data-modal-close aria-label="<?php esc_attr_e( 'Close', 'sdweddingdirectory' ); ?>">&times;</button>
        <div class="sdwd-modal__body">
            <h3 class="sdwd-modal__title"><?php esc_html_e( 'Log in to your Couple account', 'sdweddingdirectory' ); ?></h3>
            <p class="sdwd-modal__subtitle"><?php esc_html_e( 'Access your wedding planning tools, guest list, and more.', 'sdweddingdirectory' ); ?></p>

            <div class="sdwd-modal__alert"></div>

            <form method="post" data-ajax-action="sdweddingdirectory_couple_login_form_action">
                <div class="sdwd-modal__field">
                    <label class="sdwd-modal__label" for="couple-login-username"><?php esc_html_e( 'Username or email', 'sdweddingdirectory' ); ?></label>
                    <input class="sdwd-modal__input" type="text" id="couple-login-username" name="sdweddingdirectory_couple_login_username" required autocomplete="username">
                </div>

                <div class="sdwd-modal__field">
                    <label class="sdwd-modal__label" for="couple-login-password"><?php esc_html_e( 'Password', 'sdweddingdirectory' ); ?></label>
                    <input class="sdwd-modal__input" type="password" id="couple-login-password" name="sdweddingdirectory_couple_login_password" required autocomplete="current-password">
                </div>

                <?php wp_nonce_field( 'sdweddingdirectory_couple_login_security', 'security', true, true ); ?>
                <input type="hidden" name="redirect_link" value="<?php echo esc_url( home_url( '/couple-dashboard/' ) ); ?>">

                <div class="sdwd-modal__actions">
                    <button type="submit" class="btn btn--cta sdwd-modal__submit"><?php esc_html_e( 'Log In', 'sdweddingdirectory' ); ?></button>
                </div>
            </form>
        </div>

        <div class="sdwd-modal__footer">
            <a class="sdwd-modal__link" href="javascript:" data-modal-switch="forgot-password"><?php esc_html_e( 'Forgot your password?', 'sdweddingdirectory' ); ?></a>
            <div class="sdwd-modal__divider"><?php esc_html_e( 'or', 'sdweddingdirectory' ); ?></div>
            <?php esc_html_e( 'Not a member yet?', 'sdweddingdirectory' ); ?>
            <a class="sdwd-modal__link" href="javascript:" data-modal-switch="couple-register"><?php esc_html_e( 'Join now', 'sdweddingdirectory' ); ?></a>
            <br>
            <?php esc_html_e( 'Are you a vendor?', 'sdweddingdirectory' ); ?>
            <a class="sdwd-modal__link" href="javascript:" data-modal-switch="vendor-login"><?php esc_html_e( 'Vendor login', 'sdweddingdirectory' ); ?></a>
        </div>
    </div>
</div>
