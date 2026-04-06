<?php
/**
 * Wedding planning hero section.
 */
$page_id = absint( get_queried_object_id() );

$tool_name = is_page( 4180 )
    ? esc_html__( 'Wedding Planning Tools', 'sdweddingdirectory' )
    : get_the_title( $page_id );

if ( empty( $tool_name ) ) {
    $tool_name = esc_html__( 'Wedding Planning', 'sdweddingdirectory' );
}

$planning_hero_image = sdweddingdirectory_random_banner( 'wedding-planning-hero-random', 5, 'jpg' );

/* Venue-location cities for the "Getting married in…" dropdown */
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
<section class="sd-planning-hero slider-versin-two">
    <div class="slider-wrap" style="background: url(<?php echo esc_url( $planning_hero_image ); ?>) no-repeat center center;">
        <div class="container">
            <nav class="sd-hero-breadcrumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'sdweddingdirectory' ); ?>">
                <span>
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Weddings', 'sdweddingdirectory' ); ?></a>
                    <span class="sd-hero-separator"> / </span>
                    <span><?php esc_html_e( 'Wedding planning', 'sdweddingdirectory' ); ?></span>
                </span>
            </nav>

            <div class="row align-items-center sd-planning-hero-row">
                <div class="col-12 col-lg-6">
                    <div class="sd-planning-hero-content">
                        <h1><?php echo esc_html( $tool_name ); ?></h1>
                        <p class="sd-planning-hero-desc"><?php esc_html_e( 'Your Checklist, Budget, Vendors, and more! Our free wedding planning tools help you stay one step ahead.', 'sdweddingdirectory' ); ?></p>

                        <?php if ( ! is_user_logged_in() ) { ?>

                            <div id="sd-planning-register-form">

                                <form id="sdwd_planning_register_form" class="sdwd-planning-register-form" method="post" autocomplete="off" novalidate>
                                    <input type="hidden" name="action" value="sdweddingdirectory_couple_register_form_action" />
                                    <input type="hidden" name="sdweddingdirectory_couple_registration_security" value="<?php echo esc_attr( wp_create_nonce( 'sdweddingdirectory_couple_registration_security' ) ); ?>" />
                                    <input type="hidden" name="redirect_link" value="<?php echo esc_url( home_url( '/couple-dashboard/' ) ); ?>" />
                                    <!-- Auto-populated by JS before submit -->
                                    <input type="hidden" name="sdweddingdirectory_couple_register_first_name" value="" />
                                    <input type="hidden" name="sdweddingdirectory_couple_register_last_name" value="" />
                                    <input type="hidden" name="sdweddingdirectory_couple_register_username" value="" />
                                    <input type="hidden" name="sdweddingdirectory_couple_register_password" value="" />
                                    <input type="hidden" name="sdweddingdirectory_register_couple_person" value="Planning my wedding" />

                                    <div class="sdwd-planning-form-message" aria-live="polite"></div>

                                    <!-- STEP 1: Name + Email -->
                                    <div class="sdwd-planning-step is-active" data-step="1">
                                        <p class="sdwd-planning-get-started"><?php esc_html_e( 'GET STARTED:', 'sdweddingdirectory' ); ?></p>
                                        <div class="sdwd-planning-searchbar">
                                            <div class="sdwd-planning-search-fields">
                                                <div class="sdwd-planning-field">
                                                    <label class="sdwd-planning-field-label"><?php esc_html_e( 'First and last name', 'sdweddingdirectory' ); ?></label>
                                                    <input type="text" class="form-control" name="sdweddingdirectory_couple_register_full_name" placeholder="<?php esc_attr_e( 'First and last name', 'sdweddingdirectory' ); ?>" required />
                                                    <i class="fa fa-user" aria-hidden="true"></i>
                                                </div>
                                                <div class="sdwd-planning-field">
                                                    <label class="sdwd-planning-field-label"><?php esc_html_e( 'Email', 'sdweddingdirectory' ); ?></label>
                                                    <input type="email" class="form-control" name="sdweddingdirectory_couple_register_email" placeholder="<?php esc_attr_e( 'Email', 'sdweddingdirectory' ); ?>" pattern="[a-z0-9._%+\-]+@[a-z0-9.\-]+\.[a-z]{2,}$" title="<?php esc_attr_e( 'Please enter a valid email', 'sdweddingdirectory' ); ?>" required />
                                                    <i class="fa fa-envelope-o" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                            <div class="sdwd-planning-actions">
                                                <button type="button" class="btn btn-default btn-rounded" data-step-next><?php esc_html_e( 'Start planning', 'sdweddingdirectory' ); ?></button>
                                            </div>
                                        </div>
                                        <p class="sdwd-planning-login-link"><?php esc_html_e( 'You can also sign up with social.', 'sdweddingdirectory' ); ?> <?php esc_html_e( 'Already have an account?', 'sdweddingdirectory' ); ?> <a href="#" data-bs-toggle="modal" data-bs-target="#couple_login"><?php esc_html_e( 'Log in', 'sdweddingdirectory' ); ?></a></p>
                                    </div>

                                    <!-- STEP 2: Location + Wedding Date -->
                                    <div class="sdwd-planning-step" data-step="2">
                                        <button type="button" class="sdwd-step-back-top" data-step-back><i class="fa fa-chevron-left" aria-hidden="true"></i> <?php esc_html_e( 'BACK', 'sdweddingdirectory' ); ?></button>
                                        <div class="sdwd-planning-searchbar">
                                            <div class="sdwd-planning-search-fields">
                                                <div class="sdwd-planning-field sdwd-planning-field--location">
                                                    <label class="sdwd-planning-field-label"><?php esc_html_e( 'Getting married in...', 'sdweddingdirectory' ); ?></label>
                                                    <input type="text" class="form-control" name="sdweddingdirectory_couple_register_location" placeholder="<?php esc_attr_e( 'Getting married in...', 'sdweddingdirectory' ); ?>" autocomplete="off" readonly />
                                                    <i class="fa fa-search" aria-hidden="true"></i>
                                                    <?php if ( ! empty( $planning_cities ) ) : ?>
                                                        <div class="sdwd-planning-location-dropdown" id="sdwd-location-dropdown">
                                                            <?php foreach ( $planning_cities as $city ) : ?>
                                                                <div class="sdwd-planning-location-item" data-value="<?php echo esc_attr( $city->name ); ?>"><?php echo esc_html( $city->name ); ?></div>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="sdwd-planning-field sdwd-planning-field--date">
                                                    <label class="sdwd-planning-field-label"><?php esc_html_e( 'Wedding date', 'sdweddingdirectory' ); ?></label>
                                                    <input type="date" class="form-control" name="sdweddingdirectory_couple_register_wedding_date" value="<?php echo esc_attr( date( 'Y-m-d' ) ); ?>" required />
                                                    <i class="sdweddingdirectory-calendar-heart" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                            <div class="sdwd-planning-actions">
                                                <button type="submit" class="btn btn-default btn-rounded" id="sdwd-planning-register-submit"><?php esc_html_e( 'Create account', 'sdweddingdirectory' ); ?></button>
                                            </div>
                                        </div>
                                        <p class="sdwd-planning-disclaimer"><?php
                                            printf(
                                                /* translators: %1$s Privacy Policy link, %2$s Terms of Use link, %3$s CA Privacy Policy link */
                                                esc_html__( 'By clicking "Create account", I agree to %1$s, %2$s, and %3$s.', 'sdweddingdirectory' ),
                                                '<a href="' . esc_url( home_url( '/privacy-policy/' ) ) . '">' . esc_html__( "SDWeddingDirectory's Privacy Policy", 'sdweddingdirectory' ) . '</a>',
                                                '<a href="' . esc_url( home_url( '/terms-of-use/' ) ) . '">' . esc_html__( 'Terms of Use', 'sdweddingdirectory' ) . '</a>',
                                                '<a href="' . esc_url( home_url( '/ca-privacy-policy/' ) ) . '">' . esc_html__( 'CA Privacy Policy', 'sdweddingdirectory' ) . '</a>'
                                            );
                                        ?></p>
                                    </div>
                                </form>
                            </div>

                        <?php } else { ?>
                            <p class="sdwd-planning-logged-in-note">
                                <?php esc_html_e( 'You are already signed in. Continue managing your wedding plan from your dashboard.', 'sdweddingdirectory' ); ?>
                            </p>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if ( ! is_user_logged_in() ) { ?>
    <script>
        (function() {
            var form = document.getElementById('sdwd_planning_register_form');
            if (!form) return;

            var steps = Array.prototype.slice.call(form.querySelectorAll('.sdwd-planning-step'));
            var messageBox = form.querySelector('.sdwd-planning-form-message');
            var currentStep = 0;

            /* --- Location dropdown --- */
            var locationInput = form.querySelector('[name="sdweddingdirectory_couple_register_location"]');
            var locationDropdown = document.getElementById('sdwd-location-dropdown');

            if (locationInput && locationDropdown) {
                locationInput.addEventListener('click', function() {
                    locationDropdown.classList.toggle('is-open');
                });
                locationInput.addEventListener('focus', function() {
                    locationDropdown.classList.add('is-open');
                });

                locationDropdown.addEventListener('click', function(e) {
                    var item = e.target.closest('.sdwd-planning-location-item');
                    if (!item) return;
                    locationInput.value = item.getAttribute('data-value');
                    locationDropdown.classList.remove('is-open');
                });

                document.addEventListener('click', function(e) {
                    if (!e.target.closest('.sdwd-planning-field--location')) {
                        locationDropdown.classList.remove('is-open');
                    }
                });

                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        locationDropdown.classList.remove('is-open');
                    }
                });
            }

            /* --- Helpers --- */
            var setMessage = function(message, type) {
                if (!messageBox) return;
                messageBox.textContent = message || '';
                messageBox.classList.remove('is-error', 'is-success', 'is-info');
                if (message) messageBox.classList.add(type || 'is-info');
            };

            var showStep = function(index) {
                if (index < 0 || index > steps.length - 1) return;
                currentStep = index;
                steps.forEach(function(step, i) {
                    step.classList.toggle('is-active', i === currentStep);
                });
            };

            var validateStep = function(index) {
                var step = steps[index];
                if (!step) return true;
                var fields = Array.prototype.slice.call(step.querySelectorAll('input:not([type="hidden"]), select, textarea'));
                for (var i = 0; i < fields.length; i++) {
                    var f = fields[i];
                    if (f.type === 'radio' || f.hasAttribute('readonly')) continue;
                    if (!f.checkValidity()) { f.reportValidity(); return false; }
                }
                return true;
            };

            /* --- Auto-populate hidden fields before submit --- */
            var populateHiddenFields = function() {
                var fullName = (form.querySelector('[name="sdweddingdirectory_couple_register_full_name"]').value || '').trim();
                var email = (form.querySelector('[name="sdweddingdirectory_couple_register_email"]').value || '').trim();
                var parts = fullName.split(' ');
                var firstName = parts[0] || '';
                var lastName = parts.length > 1 ? parts.slice(1).join(' ') : '';

                form.querySelector('[name="sdweddingdirectory_couple_register_first_name"]').value = firstName;
                form.querySelector('[name="sdweddingdirectory_couple_register_last_name"]').value = lastName || firstName;

                /* Username from email prefix + random digits */
                var emailPrefix = email.split('@')[0] || 'user';
                var username = emailPrefix.replace(/[^a-zA-Z0-9_]/g, '_').substring(0, 28);
                username += '_' + Math.floor(1000 + Math.random() * 9000);
                form.querySelector('[name="sdweddingdirectory_couple_register_username"]').value = username;

                /* Random password */
                var chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%';
                var pass = 'Aa1!';
                for (var i = 0; i < 12; i++) pass += chars.charAt(Math.floor(Math.random() * chars.length));
                form.querySelector('[name="sdweddingdirectory_couple_register_password"]').value = pass;
            };

            /* --- Step navigation --- */
            form.addEventListener('click', function(event) {
                var control = event.target.closest('[data-step-next], [data-step-back]');
                if (!control) return;
                event.preventDefault();
                if (control.hasAttribute('data-step-next')) {
                    if (validateStep(currentStep)) { setMessage(''); showStep(currentStep + 1); }
                    return;
                }
                if (control.hasAttribute('data-step-back')) { setMessage(''); showStep(currentStep - 1); }
            });

            /* --- Form submit --- */
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                if (!validateStep(currentStep)) return;

                populateHiddenFields();

                var submitButton = form.querySelector('#sdwd-planning-register-submit');
                var originalText = submitButton ? submitButton.textContent : '';
                var formData = new FormData(form);
                var nonceValue = formData.get('sdweddingdirectory_couple_registration_security');
                formData.append('security', nonceValue ? nonceValue : '');

                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.classList.add('disabled');
                    submitButton.textContent = '<?php echo esc_js( __( 'Wait a moment...', 'sdweddingdirectory' ) ); ?>';
                }

                setMessage('<?php echo esc_js( __( 'Creating your account...', 'sdweddingdirectory' ) ); ?>', 'is-info');

                fetch('<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>', {
                    method: 'POST',
                    body: formData,
                    credentials: 'same-origin'
                })
                .then(function(response) { return response.json(); })
                .then(function(payload) {
                    if (payload && payload.message) {
                        setMessage(payload.message, payload.notice === 1 ? 'is-success' : 'is-error');
                    }
                    if (payload && payload.redirect === true) {
                        var redirectUrl = payload.redirect_link ? payload.redirect_link : '<?php echo esc_url( home_url( '/couple-dashboard/' ) ); ?>';
                        window.setTimeout(function() { window.location.href = redirectUrl; }, 350);
                    }
                })
                .catch(function() {
                    setMessage('<?php echo esc_js( __( 'Unable to submit right now. Please try again.', 'sdweddingdirectory' ) ); ?>', 'is-error');
                })
                .then(function() {
                    if (submitButton) {
                        submitButton.disabled = false;
                        submitButton.classList.remove('disabled');
                        submitButton.textContent = originalText;
                    }
                });
            });

            showStep(0);
        })();
    </script>
<?php } ?>
