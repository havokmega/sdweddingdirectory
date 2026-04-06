<?php
/**
 * Modal: Couple Registration
 */
defined( 'ABSPATH' ) || exit;
?>
<div class="modal-overlay" id="modal-couple-register">
    <div class="modal modal--wide">
        <button class="modal__close" data-modal-close aria-label="<?php esc_attr_e( 'Close', 'sdweddingdirectory' ); ?>">&times;</button>
        <div class="modal__body">
            <h3 class="modal__title"><?php esc_html_e( 'Create your Couple account', 'sdweddingdirectory' ); ?></h3>
            <p class="modal__subtitle"><?php esc_html_e( 'Start planning your San Diego wedding today.', 'sdweddingdirectory' ); ?></p>

            <div class="modal__alert"></div>

            <form method="post" data-ajax-action="sdweddingdirectory_couple_register_form_action">
                <div class="modal__row">
                    <div class="modal__field">
                        <label class="modal__label" for="couple-reg-first-name"><?php esc_html_e( 'First name', 'sdweddingdirectory' ); ?></label>
                        <input class="modal__input" type="text" id="couple-reg-first-name" name="sdweddingdirectory_couple_register_first_name" required>
                    </div>
                    <div class="modal__field">
                        <label class="modal__label" for="couple-reg-last-name"><?php esc_html_e( 'Last name', 'sdweddingdirectory' ); ?></label>
                        <input class="modal__input" type="text" id="couple-reg-last-name" name="sdweddingdirectory_couple_register_last_name" required>
                    </div>
                </div>

                <div class="modal__row">
                    <div class="modal__field">
                        <label class="modal__label" for="couple-reg-username"><?php esc_html_e( 'Username', 'sdweddingdirectory' ); ?></label>
                        <input class="modal__input" type="text" id="couple-reg-username" name="sdweddingdirectory_couple_register_username" required autocomplete="username">
                    </div>
                    <div class="modal__field">
                        <label class="modal__label" for="couple-reg-email"><?php esc_html_e( 'Email address', 'sdweddingdirectory' ); ?></label>
                        <input class="modal__input" type="email" id="couple-reg-email" name="sdweddingdirectory_couple_register_email" required autocomplete="email">
                    </div>
                </div>

                <div class="modal__row">
                    <div class="modal__field">
                        <label class="modal__label" for="couple-reg-password"><?php esc_html_e( 'Password', 'sdweddingdirectory' ); ?></label>
                        <input class="modal__input" type="password" id="couple-reg-password" name="sdweddingdirectory_couple_register_password" required autocomplete="new-password">
                    </div>
                    <div class="modal__field">
                        <label class="modal__label" for="couple-reg-wedding-date"><?php esc_html_e( 'Wedding date', 'sdweddingdirectory' ); ?></label>
                        <input class="modal__input" type="date" id="couple-reg-wedding-date" name="sdweddingdirectory_couple_register_wedding_date" required>
                    </div>
                </div>

                <div class="modal__field">
                    <span class="modal__label"><?php esc_html_e( 'I am...', 'sdweddingdirectory' ); ?></span>
                    <div class="modal__radio-group">
                        <label class="modal__radio-label">
                            <input type="radio" name="sdweddingdirectory_register_couple_person" value="planning_wedding" checked>
                            <?php esc_html_e( 'Planning my wedding', 'sdweddingdirectory' ); ?>
                        </label>
                        <label class="modal__radio-label">
                            <input type="radio" name="sdweddingdirectory_register_couple_person" value="wedding_guest">
                            <?php esc_html_e( 'A wedding guest', 'sdweddingdirectory' ); ?>
                        </label>
                    </div>
                </div>

                <?php wp_nonce_field( 'sdweddingdirectory_couple_registration_security', 'security', true, true ); ?>
                <input type="hidden" name="redirect_link" value="<?php echo esc_url( home_url( '/couple-dashboard/' ) ); ?>">

                <div class="modal__actions">
                    <button type="submit" class="btn btn--cta modal__submit"><?php esc_html_e( 'Create Account', 'sdweddingdirectory' ); ?></button>
                </div>
            </form>
        </div>

        <div class="modal__footer">
            <?php esc_html_e( 'Already have an account?', 'sdweddingdirectory' ); ?>
            <a class="modal__link" href="javascript:" data-modal-switch="couple-login"><?php esc_html_e( 'Log in', 'sdweddingdirectory' ); ?></a>
            <br>
            <?php esc_html_e( 'Are you a vendor?', 'sdweddingdirectory' ); ?>
            <a class="modal__link" href="javascript:" data-modal-switch="vendor-login"><?php esc_html_e( 'Vendor login', 'sdweddingdirectory' ); ?></a>
        </div>
    </div>
</div>
