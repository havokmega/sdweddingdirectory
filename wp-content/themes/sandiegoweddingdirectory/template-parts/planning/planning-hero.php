<?php
/**
 * Planning: Hero Banner
 *
 * Responsive hero with random background, diagonal image split,
 * breadcrumbs, heading, description, and 2-step registration form.
 * Mirrors the homepage hero responsive pattern.
 */

$page_id   = absint( get_queried_object_id() );
$tool_name = is_page( 4180 )
    ? esc_html__( 'Wedding Planning Tools', 'sdweddingdirectory' )
    : get_the_title( $page_id );

if ( empty( $tool_name ) ) {
    $tool_name = esc_html__( 'Wedding Planning', 'sdweddingdirectory' );
}

// Random banner image (6 variants in v2 theme)
$banner_files = glob( get_template_directory() . '/assets/images/banners/wedding-planning-hero-random-*.jpg' );
$banner_image = ! empty( $banner_files )
    ? get_template_directory_uri() . '/assets/images/banners/' . basename( $banner_files[ array_rand( $banner_files ) ] )
    : get_theme_file_uri( 'assets/images/banners/wedding-planning-hero-random-1.jpg' );

// Venue-location cities for the "Getting married in…" dropdown
$planning_cities = [];
$california      = get_term_by( 'slug', 'california', 'venue-location' );
if ( $california && ! is_wp_error( $california ) ) {
    $planning_cities = get_terms( [
        'taxonomy'   => 'venue-location',
        'hide_empty' => false,
        'parent'     => absint( $california->term_id ),
        'orderby'    => 'name',
        'order'      => 'ASC',
    ] );
    if ( is_wp_error( $planning_cities ) ) {
        $planning_cities = [];
    }
}
?>
<section class="planning-hero">
    <div class="planning-hero__breadcrumb-bar">
        <div class="container">
            <nav class="planning-hero__breadcrumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'sdweddingdirectory' ); ?>">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Weddings', 'sdweddingdirectory' ); ?></a>
                <span class="planning-hero__breadcrumb-sep" aria-hidden="true">/</span>
                <span><?php esc_html_e( 'Wedding Planning', 'sdweddingdirectory' ); ?></span>
            </nav>
        </div>
    </div>

    <div class="planning-hero__media" style="--planning-hero-image: url('<?php echo esc_url( $banner_image ); ?>');" aria-hidden="true"></div>

    <div class="planning-hero__panel">
        <div class="container planning-hero__inner">

            <h1 class="planning-hero__title"><?php echo esc_html( $tool_name ); ?></h1>
            <p class="planning-hero__subtitle"><?php esc_html_e( 'Your Checklist, Budget, Vendors, and more! Our free wedding planning tools help you stay one step ahead.', 'sdweddingdirectory' ); ?></p>

            <div class="planning-hero__form-wrap">
                <form id="sdwd-planning-register-form" class="planning-hero__form" method="post" autocomplete="off" novalidate>
                    <input type="hidden" name="action" value="sdweddingdirectory_couple_register_form_action" />
                    <input type="hidden" name="sdweddingdirectory_couple_registration_security" value="<?php echo esc_attr( wp_create_nonce( 'sdweddingdirectory_couple_registration_security' ) ); ?>" />
                    <input type="hidden" name="redirect_link" value="<?php echo esc_url( home_url( '/couple-dashboard/' ) ); ?>" />
                    <input type="hidden" name="sdweddingdirectory_couple_register_first_name" value="" />
                    <input type="hidden" name="sdweddingdirectory_couple_register_last_name" value="" />
                    <input type="hidden" name="sdweddingdirectory_couple_register_username" value="" />
                    <input type="hidden" name="sdweddingdirectory_couple_register_password" value="" />
                    <input type="hidden" name="sdweddingdirectory_register_couple_person" value="Planning my wedding" />

                    <div class="planning-hero__form-message" aria-live="polite"></div>

                    <!-- STEP 1: Name + Email -->
                    <div class="planning-hero__step is-active" data-step="1">
                        <p class="planning-hero__step-label"><?php esc_html_e( 'GET STARTED:', 'sdweddingdirectory' ); ?></p>
                        <div class="planning-hero__searchbar">
                            <div class="planning-hero__field-wrap" id="planning-name-wrap">
                                <div class="planning-hero__field" id="planning-name-field">
                                    <input id="planning-name" class="planning-hero__input" type="text" name="sdweddingdirectory_couple_register_full_name" placeholder=" " minlength="2" required />
                                    <label class="planning-hero__float-label" for="planning-name"><?php esc_html_e( 'First and last name', 'sdweddingdirectory' ); ?></label>
                                    <span class="planning-hero__field-icon icon-user" aria-hidden="true"></span>
                                </div>
                                <p class="planning-hero__field-error" id="planning-name-error"><?php esc_html_e( 'Enter your full name.', 'sdweddingdirectory' ); ?></p>
                            </div>
                            <div class="planning-hero__field-wrap" id="planning-email-wrap">
                                <div class="planning-hero__field" id="planning-email-field">
                                    <input id="planning-email" class="planning-hero__input" type="email" name="sdweddingdirectory_couple_register_email" placeholder=" " pattern="[a-z0-9._%+\-]+@[a-z0-9.\-]+\.[a-z]{2,}$" title="<?php esc_attr_e( 'Please enter a valid email', 'sdweddingdirectory' ); ?>" required />
                                    <label class="planning-hero__float-label" for="planning-email"><?php esc_html_e( 'Email', 'sdweddingdirectory' ); ?></label>
                                    <span class="planning-hero__field-icon icon-envelope-o" aria-hidden="true"></span>
                                </div>
                                <p class="planning-hero__field-error" id="planning-email-error"><?php esc_html_e( 'Check that the email is correct.', 'sdweddingdirectory' ); ?></p>
                            </div>
                            <button class="btn btn--cta planning-hero__action" type="button" data-step-next><?php esc_html_e( 'Start planning', 'sdweddingdirectory' ); ?></button>
                        </div>
                        <p class="planning-hero__login-hint">
                            <?php esc_html_e( 'Already have an account?', 'sdweddingdirectory' ); ?>
                            <a href="<?php echo esc_url( home_url( '/dashboard/' ) ); ?>"><?php esc_html_e( 'Log in', 'sdweddingdirectory' ); ?></a>
                        </p>
                    </div>

                    <!-- STEP 2: Location + Wedding Date -->
                    <div class="planning-hero__step" data-step="2">
                        <button class="planning-hero__back" type="button" data-step-back>
                            <span class="icon-chevron-left" aria-hidden="true"></span>
                            <?php esc_html_e( 'BACK', 'sdweddingdirectory' ); ?>
                        </button>
                        <div class="planning-hero__searchbar">
                            <div class="planning-hero__fields">
                                <div class="planning-hero__field planning-hero__field--location">
                                    <label class="screen-reader-text" for="planning-location"><?php esc_html_e( 'Getting married in...', 'sdweddingdirectory' ); ?></label>
                                    <span class="planning-hero__field-icon icon-search" aria-hidden="true"></span>
                                    <input id="planning-location" class="planning-hero__input" type="text" name="sdweddingdirectory_couple_register_location" placeholder="<?php esc_attr_e( 'Getting married in...', 'sdweddingdirectory' ); ?>" autocomplete="off" readonly />
                                    <?php if ( ! empty( $planning_cities ) ) : ?>
                                        <div class="planning-hero__dropdown" id="sdwd-location-dropdown">
                                            <?php foreach ( $planning_cities as $city ) : ?>
                                                <div class="planning-hero__dropdown-item" data-value="<?php echo esc_attr( $city->name ); ?>"><?php echo esc_html( $city->name ); ?></div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="planning-hero__field">
                                    <label class="screen-reader-text" for="planning-date"><?php esc_html_e( 'Wedding date', 'sdweddingdirectory' ); ?></label>
                                    <span class="planning-hero__field-icon icon-calendar-heart" aria-hidden="true"></span>
                                    <input id="planning-date" class="planning-hero__input" type="date" name="sdweddingdirectory_couple_register_wedding_date" required />
                                </div>
                            </div>
                            <button class="btn btn--cta planning-hero__action" type="submit" id="sdwd-planning-register-submit"><?php esc_html_e( 'Create account', 'sdweddingdirectory' ); ?></button>
                        </div>
                        <p class="planning-hero__disclaimer"><?php
                            printf(
                                esc_html__( 'By clicking "Create account", I agree to %1$s, %2$s, and %3$s.', 'sdweddingdirectory' ),
                                '<a href="' . esc_url( home_url( '/privacy-policy/' ) ) . '">' . esc_html__( 'Privacy Policy', 'sdweddingdirectory' ) . '</a>',
                                '<a href="' . esc_url( home_url( '/terms-of-use/' ) ) . '">' . esc_html__( 'Terms of Use', 'sdweddingdirectory' ) . '</a>',
                                '<a href="' . esc_url( home_url( '/ca-privacy-policy/' ) ) . '">' . esc_html__( 'CA Privacy Policy', 'sdweddingdirectory' ) . '</a>'
                            );
                        ?></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
(function () {
    var form = document.getElementById('sdwd-planning-register-form');
    if (!form) return;

    var steps = Array.prototype.slice.call(form.querySelectorAll('.planning-hero__step'));
    var messageBox = form.querySelector('.planning-hero__form-message');
    var currentStep = 0;

    /* --- Location dropdown --- */
    var locationInput = form.querySelector('[name="sdweddingdirectory_couple_register_location"]');
    var locationDropdown = document.getElementById('sdwd-location-dropdown');

    if (locationInput && locationDropdown) {
        locationInput.addEventListener('click', function () {
            locationDropdown.classList.toggle('is-open');
        });
        locationInput.addEventListener('focus', function () {
            locationDropdown.classList.add('is-open');
        });
        locationDropdown.addEventListener('click', function (e) {
            var item = e.target.closest('.planning-hero__dropdown-item');
            if (!item) return;
            locationInput.value = item.getAttribute('data-value');
            locationDropdown.classList.remove('is-open');
        });
        document.addEventListener('click', function (e) {
            if (!e.target.closest('.planning-hero__field--location')) {
                locationDropdown.classList.remove('is-open');
            }
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') locationDropdown.classList.remove('is-open');
        });
    }

    /* --- Helpers --- */
    var setMessage = function (msg, type) {
        if (!messageBox) return;
        messageBox.textContent = msg || '';
        messageBox.classList.remove('is-error', 'is-success', 'is-info');
        if (msg) messageBox.classList.add(type || 'is-info');
    };

    var showStep = function (index) {
        if (index < 0 || index > steps.length - 1) return;
        currentStep = index;
        steps.forEach(function (step, i) {
            step.classList.toggle('is-active', i === currentStep);
        });
    };

    var validateStep = function (index) {
        var step = steps[index];
        if (!step) return true;
        if (index === 0) {
            var nameValid = true;
            var emailValid = true;
            if (nameInput && nameInput.value.trim().length < 2) {
                setFieldInvalid(nameField, nameError, true);
                nameValid = false;
            }
            if (emailInput && (emailInput.value.trim().length === 0 || !emailInput.checkValidity())) {
                setFieldInvalid(emailField, emailError, true);
                emailValid = false;
            }
            return nameValid && emailValid;
        }
        var fields = Array.prototype.slice.call(step.querySelectorAll('input:not([type="hidden"]), select, textarea'));
        for (var i = 0; i < fields.length; i++) {
            var f = fields[i];
            if (f.type === 'radio' || f.hasAttribute('readonly')) continue;
            if (!f.checkValidity()) { f.reportValidity(); return false; }
        }
        return true;
    };

    /* --- Auto-populate hidden fields before submit --- */
    var populateHiddenFields = function () {
        var fullName = (form.querySelector('[name="sdweddingdirectory_couple_register_full_name"]').value || '').trim();
        var email = (form.querySelector('[name="sdweddingdirectory_couple_register_email"]').value || '').trim();
        var parts = fullName.split(' ');
        var firstName = parts[0] || '';
        var lastName = parts.length > 1 ? parts.slice(1).join(' ') : '';

        form.querySelector('[name="sdweddingdirectory_couple_register_first_name"]').value = firstName;
        form.querySelector('[name="sdweddingdirectory_couple_register_last_name"]').value = lastName || firstName;

        var emailPrefix = email.split('@')[0] || 'user';
        var username = emailPrefix.replace(/[^a-zA-Z0-9_]/g, '_').substring(0, 28);
        username += '_' + Math.floor(1000 + Math.random() * 9000);
        form.querySelector('[name="sdweddingdirectory_couple_register_username"]').value = username;

        var chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%';
        var pass = 'Aa1!';
        for (var i = 0; i < 12; i++) pass += chars.charAt(Math.floor(Math.random() * chars.length));
        form.querySelector('[name="sdweddingdirectory_couple_register_password"]').value = pass;
    };

    /* --- Field validation helpers --- */
    var nameInput = document.getElementById('planning-name');
    var nameField = document.getElementById('planning-name-field');
    var nameError = document.getElementById('planning-name-error');
    var emailInput = document.getElementById('planning-email');
    var emailField = document.getElementById('planning-email-field');
    var emailError = document.getElementById('planning-email-error');

    var setFieldInvalid = function (field, error, invalid) {
        if (!field || !error) return;
        field.classList.toggle('planning-hero__field--invalid', invalid);
        error.classList.toggle('is-visible', invalid);
    };

    var checkName = function () {
        if (!nameInput || !nameField || !nameError) return true;
        var val = nameInput.value.trim();
        if (val.length === 0 || val.length >= 2) {
            setFieldInvalid(nameField, nameError, false);
            return true;
        }
        setFieldInvalid(nameField, nameError, true);
        return false;
    };

    var checkEmail = function () {
        if (!emailInput || !emailField || !emailError) return true;
        var val = emailInput.value.trim();
        if (val.length === 0 || emailInput.checkValidity()) {
            setFieldInvalid(emailField, emailError, false);
            return true;
        }
        setFieldInvalid(emailField, emailError, true);
        return false;
    };

    if (nameInput) {
        nameInput.addEventListener('blur', checkName);
        nameInput.addEventListener('input', function () {
            if (nameField.classList.contains('planning-hero__field--invalid')) {
                checkName();
            }
        });
    }

    if (emailInput) {
        emailInput.addEventListener('blur', checkEmail);
        emailInput.addEventListener('input', function () {
            if (emailField.classList.contains('planning-hero__field--invalid')) {
                checkEmail();
            }
        });
    }

    /* --- Step navigation --- */
    form.addEventListener('click', function (e) {
        var control = e.target.closest('[data-step-next], [data-step-back]');
        if (!control) return;
        e.preventDefault();
        if (control.hasAttribute('data-step-next')) {
            if (validateStep(currentStep)) { setMessage(''); showStep(currentStep + 1); }
            return;
        }
        if (control.hasAttribute('data-step-back')) { setMessage(''); showStep(currentStep - 1); }
    });

    /* --- Form submit via AJAX --- */
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        if (!validateStep(currentStep)) return;

        populateHiddenFields();

        var submitBtn = form.querySelector('#sdwd-planning-register-submit');
        var originalText = submitBtn ? submitBtn.textContent : '';
        var formData = new FormData(form);
        var nonceValue = formData.get('sdweddingdirectory_couple_registration_security');
        formData.append('security', nonceValue ? nonceValue : '');

        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.textContent = '<?php echo esc_js( __( 'Wait a moment...', 'sdweddingdirectory' ) ); ?>';
        }

        setMessage('<?php echo esc_js( __( 'Creating your account...', 'sdweddingdirectory' ) ); ?>', 'is-info');

        fetch('<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>', {
            method: 'POST',
            body: formData,
            credentials: 'same-origin'
        })
        .then(function (response) { return response.json(); })
        .then(function (payload) {
            if (payload && payload.message) {
                setMessage(payload.message, payload.notice === 1 ? 'is-success' : 'is-error');
            }
            if (payload && payload.redirect === true) {
                var redirectUrl = payload.redirect_link ? payload.redirect_link : '<?php echo esc_url( home_url( '/couple-dashboard/' ) ); ?>';
                window.setTimeout(function () { window.location.href = redirectUrl; }, 350);
            }
        })
        .catch(function () {
            setMessage('<?php echo esc_js( __( 'Unable to submit right now. Please try again.', 'sdweddingdirectory' ) ); ?>', 'is-error');
        })
        .then(function () {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        });
    });

    showStep(0);
})();
</script>
