<?php
/**
 * Modal: Couple Registration
 */
defined( 'ABSPATH' ) || exit;
?>
<div class="sdwd-modal-overlay" id="sdwd-modal-couple-register">
    <div class="sdwd-modal sdwd-modal--split">
        <button class="sdwd-modal__close" data-sdwd-modal-close aria-label="<?php esc_attr_e( 'Close', 'sdwd-core' ); ?>">&times;</button>

        <div class="sdwd-modal__image" style="background-image: url('<?php echo esc_url( get_theme_file_uri( 'assets/images/placeholders/modal-popup/couple-login-register.jpg' ) ); ?>');"></div>

        <div class="sdwd-modal__content">
            <div class="sdwd-modal__body">
                <h3 class="sdwd-modal__title"><?php esc_html_e( 'Register Couple Account', 'sdwd-core' ); ?></h3>

                <div class="sdwd-modal__alert" role="alert"></div>

                <form method="post" data-sdwd-form="register">
                    <input type="hidden" name="account_type" value="couple">

                    <div class="sdwd-modal__row">
                        <div class="sdwd-modal__field">
                            <input class="sdwd-modal__input" type="text" name="first_name" required placeholder="<?php esc_attr_e( 'First Name', 'sdwd-core' ); ?>">
                        </div>
                        <div class="sdwd-modal__field">
                            <input class="sdwd-modal__input" type="text" name="last_name" required placeholder="<?php esc_attr_e( 'Last Name', 'sdwd-core' ); ?>">
                        </div>
                    </div>

                    <div class="sdwd-modal__field">
                        <input class="sdwd-modal__input" type="email" name="email" required autocomplete="email" placeholder="<?php esc_attr_e( 'Email', 'sdwd-core' ); ?>">
                    </div>

                    <div class="sdwd-modal__row">
                        <div class="sdwd-modal__field">
                            <input class="sdwd-modal__input" type="password" name="password" required autocomplete="new-password" minlength="8" placeholder="<?php esc_attr_e( 'Password', 'sdwd-core' ); ?>">
                        </div>
                        <div class="sdwd-modal__field">
                            <input class="sdwd-modal__input" type="date" name="wedding_date" placeholder="<?php esc_attr_e( 'Wedding Date', 'sdwd-core' ); ?>">
                        </div>
                    </div>

                    <div class="sdwd-modal__field">
                        <div class="sdwd-modal__inline-radio">
                            <strong><?php esc_html_e( 'I am', 'sdwd-core' ); ?></strong>
                            <label class="sdwd-modal__radio-inline">
                                <input type="radio" name="couple_type" value="planning_wedding" checked>
                                <?php esc_html_e( 'Planning my wedding', 'sdwd-core' ); ?>
                            </label>
                            <label class="sdwd-modal__radio-inline">
                                <input type="radio" name="couple_type" value="wedding_guest">
                                <?php esc_html_e( 'A wedding guest', 'sdwd-core' ); ?>
                            </label>
                        </div>
                    </div>

                    <div class="sdwd-modal__actions">
                        <button type="submit" class="sdwd-modal__submit"><?php esc_html_e( 'Sign Up', 'sdwd-core' ); ?></button>
                    </div>

                    <p class="sdwd-modal__switch-text">
                        <?php esc_html_e( 'Already have an account?', 'sdwd-core' ); ?>
                        <a class="sdwd-modal__link" href="javascript:" data-sdwd-modal-switch="couple-login"><?php esc_html_e( 'Log in', 'sdwd-core' ); ?></a>
                    </p>
                </form>
            </div>

            <div class="sdwd-modal__footer">
                <strong><?php esc_html_e( 'Are you a vendor?', 'sdwd-core' ); ?></strong><br>
                <a class="sdwd-modal__link" href="javascript:" data-sdwd-modal-switch="vendor-login"><?php esc_html_e( 'Vendor login', 'sdwd-core' ); ?></a>
            </div>
        </div>
    </div>
</div>
