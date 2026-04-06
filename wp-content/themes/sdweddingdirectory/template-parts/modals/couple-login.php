<?php
/**
 * Modal: Couple Login
 */
defined( 'ABSPATH' ) || exit;
?>
<div class="modal-overlay" id="modal-couple-login">
    <div class="modal">
        <button class="modal__close" data-modal-close aria-label="<?php esc_attr_e( 'Close', 'sdweddingdirectory' ); ?>">&times;</button>
        <div class="modal__body">
            <h3 class="modal__title"><?php esc_html_e( 'Log in to your Couple account', 'sdweddingdirectory' ); ?></h3>
            <p class="modal__subtitle"><?php esc_html_e( 'Access your wedding planning tools, guest list, and more.', 'sdweddingdirectory' ); ?></p>

            <div class="modal__alert"></div>

            <form method="post" data-ajax-action="sdweddingdirectory_couple_login_form_action">
                <div class="modal__field">
                    <label class="modal__label" for="couple-login-username"><?php esc_html_e( 'Username or email', 'sdweddingdirectory' ); ?></label>
                    <input class="modal__input" type="text" id="couple-login-username" name="sdweddingdirectory_couple_login_username" required autocomplete="username">
                </div>

                <div class="modal__field">
                    <label class="modal__label" for="couple-login-password"><?php esc_html_e( 'Password', 'sdweddingdirectory' ); ?></label>
                    <input class="modal__input" type="password" id="couple-login-password" name="sdweddingdirectory_couple_login_password" required autocomplete="current-password">
                </div>

                <?php wp_nonce_field( 'sdweddingdirectory_couple_login_security', 'security', true, true ); ?>
                <input type="hidden" name="redirect_link" value="<?php echo esc_url( home_url( '/couple-dashboard/' ) ); ?>">

                <div class="modal__actions">
                    <button type="submit" class="btn btn--cta modal__submit"><?php esc_html_e( 'Log In', 'sdweddingdirectory' ); ?></button>
                </div>
            </form>
        </div>

        <div class="modal__footer">
            <a class="modal__link" href="javascript:" data-modal-switch="forgot-password"><?php esc_html_e( 'Forgot your password?', 'sdweddingdirectory' ); ?></a>
            <div class="modal__divider"><?php esc_html_e( 'or', 'sdweddingdirectory' ); ?></div>
            <?php esc_html_e( 'Not a member yet?', 'sdweddingdirectory' ); ?>
            <a class="modal__link" href="javascript:" data-modal-switch="couple-register"><?php esc_html_e( 'Join now', 'sdweddingdirectory' ); ?></a>
            <br>
            <?php esc_html_e( 'Are you a vendor?', 'sdweddingdirectory' ); ?>
            <a class="modal__link" href="javascript:" data-modal-switch="vendor-login"><?php esc_html_e( 'Vendor login', 'sdweddingdirectory' ); ?></a>
        </div>
    </div>
</div>
