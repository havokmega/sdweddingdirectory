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

// Drop invitation-only categories (e.g. DJs — founder curates that list manually).
if ( function_exists( 'sdwd_invitation_only_vendor_categories' ) ) {
    $excluded = sdwd_invitation_only_vendor_categories();
    if ( ! empty( $excluded ) ) {
        $vendor_categories = array_values( array_filter( $vendor_categories, function ( $term ) use ( $excluded ) {
            return ! in_array( $term->slug, $excluded, true );
        } ) );
    }
}

$venue_types = get_terms( [
    'taxonomy'   => 'venue-type',
    'hide_empty' => false,
    'orderby'    => 'name',
] );
if ( is_wp_error( $venue_types ) ) {
    $venue_types = [];
}
?>
<div class="sdwd-modal-overlay" id="sdwd-modal-vendor-register">
    <div class="sdwd-modal sdwd-modal--split">
        <button class="sdwd-modal__close" data-sdwd-modal-close aria-label="<?php esc_attr_e( 'Close', 'sdwd-core' ); ?>">&times;</button>

        <div class="sdwd-modal__image" style="background-image: url('<?php echo esc_url( get_theme_file_uri( 'assets/images/placeholders/modal-popup/vendor-login-register.jpg' ) ); ?>');"></div>

        <div class="sdwd-modal__content">
            <div class="sdwd-modal__body">
                <h3 class="sdwd-modal__title"><?php esc_html_e( 'Register Vendor / Venue Account', 'sdwd-core' ); ?></h3>

                <div class="sdwd-modal__alert" role="alert"></div>

                <form method="post" data-sdwd-form="register" id="sdwd-vendor-register-form">
                    <!-- Venue / Vendor toggle -->
                    <div class="sdwd-modal__field">
                        <div class="sdwd-modal__inline-radio">
                            <strong><?php esc_html_e( 'I am a', 'sdwd-core' ); ?></strong>
                            <label class="sdwd-modal__radio-inline">
                                <input type="radio" name="account_type" value="venue" checked>
                                <?php esc_html_e( 'Venue', 'sdwd-core' ); ?>
                            </label>
                            <label class="sdwd-modal__radio-inline">
                                <input type="radio" name="account_type" value="vendor">
                                <?php esc_html_e( 'Vendor', 'sdwd-core' ); ?>
                            </label>
                        </div>
                    </div>

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
                            <input class="sdwd-modal__input" type="text" name="company_name" placeholder="<?php esc_attr_e( 'Company Name', 'sdwd-core' ); ?>">
                        </div>
                    </div>

                    <!-- Venue-only: address fields -->
                    <div class="sdwd-modal__row" id="sdwd-venue-address-row">
                        <div class="sdwd-modal__field">
                            <input class="sdwd-modal__input" type="text" name="company_address" placeholder="<?php esc_attr_e( 'Company Address', 'sdwd-core' ); ?>">
                        </div>
                        <div class="sdwd-modal__field">
                            <input class="sdwd-modal__input" type="url" name="company_website" placeholder="<?php esc_attr_e( 'Company Website', 'sdwd-core' ); ?>">
                        </div>
                    </div>

                    <div class="sdwd-modal__row" id="sdwd-venue-zip-row">
                        <div class="sdwd-modal__field">
                            <input class="sdwd-modal__input" type="text" name="zip_code" placeholder="<?php esc_attr_e( 'Zip Code', 'sdwd-core' ); ?>">
                        </div>
                        <div class="sdwd-modal__field">
                            <input class="sdwd-modal__input" type="tel" name="contact_number" placeholder="<?php esc_attr_e( 'Contact Number', 'sdwd-core' ); ?>">
                        </div>
                    </div>

                    <!-- Category dropdown -->
                    <div class="sdwd-modal__field" id="sdwd-vendor-category-field">
                        <?php if ( ! empty( $vendor_categories ) ) : ?>
                            <select class="sdwd-modal__input" name="vendor_category">
                                <option value=""><?php esc_html_e( 'Choose Category', 'sdwd-core' ); ?></option>
                                <?php foreach ( $vendor_categories as $cat ) : ?>
                                    <option value="<?php echo absint( $cat->term_id ); ?>"><?php echo esc_html( $cat->name ); ?></option>
                                <?php endforeach; ?>
                            </select>
                        <?php endif; ?>
                    </div>

                    <div class="sdwd-modal__field" id="sdwd-venue-type-field" style="display:none;">
                        <?php if ( ! empty( $venue_types ) ) : ?>
                            <select class="sdwd-modal__input" name="venue_type">
                                <option value=""><?php esc_html_e( 'Choose Venue Type', 'sdwd-core' ); ?></option>
                                <?php foreach ( $venue_types as $type ) : ?>
                                    <option value="<?php echo absint( $type->term_id ); ?>"><?php echo esc_html( $type->name ); ?></option>
                                <?php endforeach; ?>
                            </select>
                        <?php endif; ?>
                    </div>

                    <div class="sdwd-modal__actions">
                        <button type="submit" class="sdwd-modal__submit"><?php esc_html_e( 'Sign Up', 'sdwd-core' ); ?></button>
                    </div>

                    <p class="sdwd-modal__switch-text">
                        <?php esc_html_e( 'Already have an account?', 'sdwd-core' ); ?>
                        <a class="sdwd-modal__link" href="javascript:" data-sdwd-modal-switch="vendor-login"><?php esc_html_e( 'Log in', 'sdwd-core' ); ?></a>
                    </p>
                </form>
            </div>

            <div class="sdwd-modal__footer">
                <strong><?php esc_html_e( 'Are you a couple?', 'sdwd-core' ); ?></strong><br>
                <a class="sdwd-modal__link" href="javascript:" data-sdwd-modal-switch="couple-login"><?php esc_html_e( 'Couple login', 'sdwd-core' ); ?></a>
            </div>
        </div>
    </div>
</div>
