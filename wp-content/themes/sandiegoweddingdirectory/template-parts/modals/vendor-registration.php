<?php
/**
 * Modal: Vendor / Venue Registration
 */
defined( 'ABSPATH' ) || exit;

$vendor_categories = get_terms( [
    'taxonomy'   => 'vendor-category',
    'hide_empty' => false,
    'orderby'    => 'name',
] );

if ( is_wp_error( $vendor_categories ) ) {
    $vendor_categories = [];
}
?>
<div class="modal-overlay" id="modal-vendor-register">
    <div class="sdwd-modal sdwd-modal--wide">
        <button class="sdwd-modal__close" data-modal-close aria-label="<?php esc_attr_e( 'Close', 'sdweddingdirectory' ); ?>">&times;</button>
        <div class="sdwd-modal__body">
            <h3 class="sdwd-modal__title"><?php esc_html_e( 'Create your Business account', 'sdweddingdirectory' ); ?></h3>
            <p class="sdwd-modal__subtitle"><?php esc_html_e( 'List your business on the San Diego Wedding Directory.', 'sdweddingdirectory' ); ?></p>

            <div class="sdwd-modal__alert"></div>

            <form method="post" data-ajax-action="sdweddingdirectory_vendor_register_form_action">
                <div class="sdwd-modal__row">
                    <div class="sdwd-modal__field">
                        <label class="sdwd-modal__label" for="vendor-reg-first-name"><?php esc_html_e( 'First name', 'sdweddingdirectory' ); ?></label>
                        <input class="sdwd-modal__input" type="text" id="vendor-reg-first-name" name="first_name" required>
                    </div>
                    <div class="sdwd-modal__field">
                        <label class="sdwd-modal__label" for="vendor-reg-last-name"><?php esc_html_e( 'Last name', 'sdweddingdirectory' ); ?></label>
                        <input class="sdwd-modal__input" type="text" id="vendor-reg-last-name" name="last_name" required>
                    </div>
                </div>

                <div class="sdwd-modal__row">
                    <div class="sdwd-modal__field">
                        <label class="sdwd-modal__label" for="vendor-reg-username"><?php esc_html_e( 'Username', 'sdweddingdirectory' ); ?></label>
                        <input class="sdwd-modal__input" type="text" id="vendor-reg-username" name="username" required autocomplete="username">
                    </div>
                    <div class="sdwd-modal__field">
                        <label class="sdwd-modal__label" for="vendor-reg-email"><?php esc_html_e( 'Email address', 'sdweddingdirectory' ); ?></label>
                        <input class="sdwd-modal__input" type="email" id="vendor-reg-email" name="email" required autocomplete="email">
                    </div>
                </div>

                <div class="sdwd-modal__field">
                    <label class="sdwd-modal__label" for="vendor-reg-password"><?php esc_html_e( 'Password', 'sdweddingdirectory' ); ?></label>
                    <input class="sdwd-modal__input" type="password" id="vendor-reg-password" name="password" required autocomplete="new-password">
                </div>

                <div class="sdwd-modal__row">
                    <div class="sdwd-modal__field">
                        <label class="sdwd-modal__label" for="vendor-reg-company"><?php esc_html_e( 'Company name', 'sdweddingdirectory' ); ?></label>
                        <input class="sdwd-modal__input" type="text" id="vendor-reg-company" name="company_name" required>
                    </div>
                    <div class="sdwd-modal__field">
                        <label class="sdwd-modal__label" for="vendor-reg-website"><?php esc_html_e( 'Website', 'sdweddingdirectory' ); ?></label>
                        <input class="sdwd-modal__input" type="url" id="vendor-reg-website" name="company_website" placeholder="https://">
                    </div>
                </div>

                <div class="sdwd-modal__field">
                    <label class="sdwd-modal__label" for="vendor-reg-phone"><?php esc_html_e( 'Contact number', 'sdweddingdirectory' ); ?></label>
                    <input class="sdwd-modal__input" type="tel" id="vendor-reg-phone" name="contact_number">
                </div>

                <div class="sdwd-modal__field">
                    <span class="sdwd-modal__label"><?php esc_html_e( 'Account type', 'sdweddingdirectory' ); ?></span>
                    <div class="sdwd-modal__radio-group">
                        <label class="sdwd-modal__radio-label">
                            <input type="radio" name="account_type" value="vendor" checked>
                            <?php esc_html_e( 'Vendor', 'sdweddingdirectory' ); ?>
                        </label>
                        <label class="sdwd-modal__radio-label">
                            <input type="radio" name="account_type" value="venue">
                            <?php esc_html_e( 'Venue', 'sdweddingdirectory' ); ?>
                        </label>
                    </div>
                </div>

                <?php if ( ! empty( $vendor_categories ) ) : ?>
                    <div class="sdwd-modal__field" id="vendor-category-field">
                        <label class="sdwd-modal__label" for="vendor-reg-category"><?php esc_html_e( 'Vendor category', 'sdweddingdirectory' ); ?></label>
                        <select class="sdwd-modal__select" id="vendor-reg-category" name="vendor_category">
                            <option value=""><?php esc_html_e( 'Select a category', 'sdweddingdirectory' ); ?></option>
                            <?php foreach ( $vendor_categories as $cat ) : ?>
                                <option value="<?php echo absint( $cat->term_id ); ?>"><?php echo esc_html( $cat->name ); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>

                <?php wp_nonce_field( 'sdweddingdirectory_vendor_registration_form_security', 'security', true, true ); ?>
                <input type="hidden" name="redirect_link" value="<?php echo esc_url( home_url( '/vendor-dashboard/' ) ); ?>">

                <div class="sdwd-modal__actions">
                    <button type="submit" class="btn btn--primary sdwd-modal__submit"><?php esc_html_e( 'Create Account', 'sdweddingdirectory' ); ?></button>
                </div>
            </form>
        </div>

        <div class="sdwd-modal__footer">
            <?php esc_html_e( 'Already have an account?', 'sdweddingdirectory' ); ?>
            <a class="sdwd-modal__link" href="javascript:" data-modal-switch="vendor-login"><?php esc_html_e( 'Log in', 'sdweddingdirectory' ); ?></a>
            <br>
            <?php esc_html_e( 'Are you a couple?', 'sdweddingdirectory' ); ?>
            <a class="sdwd-modal__link" href="javascript:" data-modal-switch="couple-register"><?php esc_html_e( 'Join as a Couple', 'sdweddingdirectory' ); ?></a>
        </div>
    </div>
</div>
