<?php
/**
 *  SDWeddingDirectory - Page Header Banner
 *  -------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Page_Header_Banner' ) && class_exists( 'SDWeddingDirectory' ) ){

    /**
     *  SDWeddingDirectory - Page Header Banner
     *  -------------------------------
     */
    class SDWeddingDirectory_Page_Header_Banner extends SDWeddingDirectory {

        /**
         *  Member Variable
         *  ---------------
         */
        private static $instance;

        /**
         *  Initiator
         *  ---------
         */
        public static function get_instance() {
            
            if ( ! isset( self::$instance ) ) {

                self::$instance = new self;

            }

            return self::$instance;
        }

        public function __construct(){

            /**
             *  1. Load Page Header Banner
             *  --------------------------
             */
            add_action( 'sdweddingdirectory/page-header-banner',  [ $this, 'sdweddingdirectory_default_page_header' ], absint( '10' ) );

            /**
             *  Enable dropdown search script for banner search forms
             *  (must run before wp_enqueue_scripts)
             *  -----------------------------------------------------
             */
            add_filter( 'sdweddingdirectory/enable-script/input-dropdown', function( $args = [] ){
                if( is_front_page() || is_page( 'venues' ) || is_page( 'vendors' ) || is_tax( 'vendor-category' ) || is_page_template( 'user-template/vendor-category.php' ) || is_tax( 'venue-type' ) ){
                    return array_merge( $args, [ 'page_header_banner' => true ] );
                }
                return $args;
            } );

            /**
             *  Archive Title Filter
             *  --------------------
             */
            add_filter( 'get_the_archive_title', [ $this, 'archive_title' ], absint( '10' ), absint( '1' ) );
        }

        /**
         *  Archive Filter
         *  --------------
         *  @credit - https://developer.wordpress.org/reference/functions/get_the_archive_title/#user-contributed-notes
         *  -----------------------------------------------------------------------------------------------------------
         */
        public static function archive_title( $title ) {

            /**
             *  Only removed prefix on selected tax
             *  -----------------------------------
             */
            if ( is_category() ) {

                $title = single_cat_title( '', false );

            } elseif ( is_tag() ) {

                $title = single_tag_title( '', false );

            } elseif ( is_author() ) {

                $title = '<span class="vcard">' . get_the_author() . '</span>';

            } elseif ( is_post_type_archive() ) {

                $title = post_type_archive_title( '', false );

            } elseif ( is_tax() ) {

                $title = single_term_title( '', false );
            }

            /**
             *  Return Title
             *  ------------
             */
            return      $title;
        }

        /**
         *  Image + Page Header Banner
         *  --------------------------
         */
        public static function sdweddingdirectory_default_page_header(){

            /**
             *  Front page + Home page not allowed page header banner
             *  -----------------------------------------------------
             */
            if( is_front_page() && is_home() ){

                return;
            }

            /**
             *  Custom Banner for Venues + Vendor Category Pages
             *  ------------------------------------------------
             *  Venues pages get venue-banner, vendor pages get vendor-banner
             */
            if( is_page( 'venues' ) || is_page( 'vendors' ) || is_tax( 'vendor-category' ) || is_page_template( 'user-template/vendor-category.php' ) || is_tax( 'venue-type' ) ){

                $search_heading = esc_html__( 'Wedding Venues', 'sdweddingdirectory' );

                // Determine which banner to use based on page type.
                if( is_page( 'vendors' ) || is_tax( 'vendor-category' ) || is_page_template( 'user-template/vendor-category.php' ) ){

                    $banner_url = esc_url( get_theme_file_uri( 'assets/images/banners/vendors-search.png' ) );

                    $search_heading = esc_html__( 'Wedding Vendors', 'sdweddingdirectory' );

                } else {

                    $banner_url = esc_url( get_theme_file_uri( 'assets/images/banners/venues-search.png' ) );
                }

                ?>
                <section class="sd-search-banner" style="background: #fff url(<?php echo $banner_url; ?>) no-repeat right center / cover;">

                    <div class="container">

                        <div class="row align-items-center">

                            <div class="col-12 col-lg-7 position-relative">

                                <h1 class="h3 fw-bold text-dark position-absolute m-0 pe-3 sd-search-banner-heading"><?php echo esc_html( $search_heading ); ?></h1>

                                <?php
                                    if( class_exists( 'SDWeddingDirectory_Shortcode_Find_Venue_Form' ) ){

                                        print \SDWeddingDirectory_Shortcode_Find_Venue_Form:: page_builder( [

                                            'layout'                =>  absint( '2' ),

                                            'category_placeholder'  =>  esc_attr__( 'Search vendor category or name', 'sdweddingdirectory' ),

                                            'location_placeholder'  =>  esc_attr__( 'Location', 'sdweddingdirectory' ),

                                            'search_button_text'    =>  esc_attr__( 'Search Now', 'sdweddingdirectory' ),
                                        ] );

                                        \SDWeddingDirectory_Shortcode_Find_Venue_Form:: load_script();
                                    }
                                ?>

                            </div>

                        </div>

                    </div>

                </section>
                <?php

                return;
            }

            /**
             *  Extract Args
             *  ------------
             */
            extract( wp_parse_args( [

                'default_layout'    => SDW_DEFAULT_PAGE_HEADER_LAYOUT,

                'page_header_image' => SDW_DEFAULT_PAGE_HEADER_IMAGE,

                'default_bg_color'  => SDW_DEFAULT_PAGE_HEADER_BG_COLOR,

            ] ) );

            /**
             *  Planning Tools Hero (Parent + Child pages)
             *  ------------------------------------------
             */
            $page_id     = absint( get_queried_object_id() );
            $parent_id   = absint( wp_get_post_parent_id( $page_id ) );
            $is_planning = is_page( 4180 ) || $parent_id === 4180;

            if( $is_planning ){

                $tool_name = is_page( 4180 )
                            ? esc_html__( 'Wedding Planning Tools', 'sdweddingdirectory' )
                            : get_the_title( $page_id );

                if( empty( $tool_name ) ){
                    $tool_name = esc_html__( 'Wedding Planning', 'sdweddingdirectory' );
                }

                $planning_page_url = esc_url( home_url( '/wedding-planning/' ) );

                $planning_hero_image = sdweddingdirectory_random_banner( 'wedding-planning-hero-random', 5 );

                ?>
                <section class="sd-planning-hero slider-versin-two">
                    <div class="slider-wrap" style="background-image: url(<?php echo esc_url( $planning_hero_image ); ?>); background-position: right center; background-size: cover;">
                        <div class="container">
                            <nav class="sd-hero-breadcrumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'sdweddingdirectory' ); ?>">
                                <span>
                                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Weddings', 'sdweddingdirectory' ); ?></a>
                                    <span class="sd-hero-separator"> / </span>
                                    <a href="<?php echo $planning_page_url; ?>"><?php esc_html_e( 'Wedding Planning', 'sdweddingdirectory' ); ?></a>
                                    <span class="sd-hero-separator"> / </span>
                                    <span><?php echo esc_html( $tool_name ); ?></span>
                                </span>
                            </nav>

                            <div class="row align-items-center sd-planning-hero-row">
                                <div class="col-12 col-lg-6">
                                    <div class="sd-planning-hero-content">
                                        <h1><?php echo esc_html( $tool_name ); ?></h1>

                                        <?php if( ! is_user_logged_in() ){ ?>

                                            <div id="sd-planning-register-form">

                                                <form id="sdwd_planning_register_form" class="sdwd-planning-register-form" method="post" autocomplete="off" novalidate>
                                                    <input type="hidden" name="action" value="sdweddingdirectory_couple_register_form_action" />
                                                    <input type="hidden" name="sdweddingdirectory_couple_registration_security" value="<?php echo esc_attr( wp_create_nonce( 'sdweddingdirectory_couple_registration_security' ) ); ?>" />
                                                    <input type="hidden" name="redirect_link" value="<?php echo esc_url( home_url( '/couple-dashboard/' ) ); ?>" />

                                                    <div class="sdwd-planning-form-message" aria-live="polite"></div>

                                                    <div class="sdwd-planning-step is-active" data-step="1">
                                                        <div class="sdwd-planning-searchbar">
                                                            <div class="sdwd-planning-search-fields">
                                                                <input type="text" class="form-control" name="sdweddingdirectory_couple_register_first_name" placeholder="<?php esc_attr_e( 'First Name', 'sdweddingdirectory' ); ?>" required />
                                                                <input type="email" class="form-control" name="sdweddingdirectory_couple_register_email" placeholder="<?php esc_attr_e( 'Email ID', 'sdweddingdirectory' ); ?>" pattern="[a-z0-9._%+\-]+@[a-z0-9.\-]+\.[a-z]{2,}$" title="<?php esc_attr_e( 'Please enter valid email ID', 'sdweddingdirectory' ); ?>" required />
                                                            </div>
                                                            <div class="sdwd-planning-actions">
                                                                <button type="button" class="btn btn-default btn-rounded" data-step-next><?php esc_html_e( 'Get Started', 'sdweddingdirectory' ); ?></button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="sdwd-planning-step" data-step="2">
                                                        <div class="row g-2">
                                                            <div class="col-12 col-md-6">
                                                                <input type="text" class="form-control" name="sdweddingdirectory_couple_register_username" placeholder="<?php esc_attr_e( 'Username', 'sdweddingdirectory' ); ?>" pattern="^[a-zA-Z0-9_]{4,35}$" title="<?php esc_attr_e( 'Allowed Character ( a-z, A-Z, 0-9, _ ) and length 4 mandatory', 'sdweddingdirectory' ); ?>" required />
                                                            </div>
                                                            <div class="col-12 col-md-6">
                                                                <input type="password" class="form-control" name="sdweddingdirectory_couple_register_password" placeholder="<?php esc_attr_e( 'Password', 'sdweddingdirectory' ); ?>" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="<?php esc_attr_e( 'Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters', 'sdweddingdirectory' ); ?>" required />
                                                            </div>
                                                        </div>
                                                        <div class="sdwd-planning-actions">
                                                            <button type="button" class="btn btn-link sdwd-step-back" data-step-back><?php esc_html_e( 'Back', 'sdweddingdirectory' ); ?></button>
                                                            <button type="button" class="btn btn-default btn-rounded" data-step-next><?php esc_html_e( 'Continue', 'sdweddingdirectory' ); ?></button>
                                                        </div>
                                                        <p class="sdwd-planning-login-link"><?php esc_html_e( 'Already have an account?', 'sdweddingdirectory' ); ?> <a href="#" data-bs-toggle="modal" data-bs-target="#couple_login"><?php esc_html_e( 'Log In', 'sdweddingdirectory' ); ?></a></p>
                                                    </div>

                                                    <div class="sdwd-planning-step" data-step="3">
                                                        <div class="row g-2">
                                                            <div class="col-12 col-md-6">
                                                                <input type="text" class="form-control" name="sdweddingdirectory_couple_register_last_name" placeholder="<?php esc_attr_e( 'Last Name', 'sdweddingdirectory' ); ?>" required />
                                                            </div>
                                                            <div class="col-12 col-md-6">
                                                                <input type="date" class="form-control" name="sdweddingdirectory_couple_register_wedding_date" value="<?php echo esc_attr( date( 'Y-m-d' ) ); ?>" required />
                                                            </div>
                                                        </div>
                                                        <div class="sdwd-planning-actions">
                                                            <button type="button" class="btn btn-link sdwd-step-back" data-step-back><?php esc_html_e( 'Back', 'sdweddingdirectory' ); ?></button>
                                                            <button type="button" class="btn btn-default btn-rounded" data-step-next><?php esc_html_e( 'Continue', 'sdweddingdirectory' ); ?></button>
                                                        </div>
                                                        <p class="sdwd-planning-login-link"><?php esc_html_e( 'Already have an account?', 'sdweddingdirectory' ); ?> <a href="#" data-bs-toggle="modal" data-bs-target="#couple_login"><?php esc_html_e( 'Log In', 'sdweddingdirectory' ); ?></a></p>
                                                    </div>

                                                    <div class="sdwd-planning-step" data-step="4">
                                                        <div class="sdwd-planning-radio-group">
                                                            <p class="mb-2"><strong><?php esc_html_e( 'I am', 'sdweddingdirectory' ); ?></strong></p>
                                                            <label class="form-check-label">
                                                                <input type="radio" class="form-check-input" name="sdweddingdirectory_register_couple_person" value="<?php esc_attr_e( 'Planning my wedding', 'sdweddingdirectory' ); ?>" required />
                                                                <?php esc_html_e( 'Planning my wedding', 'sdweddingdirectory' ); ?>
                                                            </label>
                                                            <label class="form-check-label">
                                                                <input type="radio" class="form-check-input" name="sdweddingdirectory_register_couple_person" value="<?php esc_attr_e( 'A wedding guest', 'sdweddingdirectory' ); ?>" />
                                                                <?php esc_html_e( 'A wedding guest', 'sdweddingdirectory' ); ?>
                                                            </label>
                                                        </div>
                                                        <div class="sdwd-planning-actions">
                                                            <button type="button" class="btn btn-link sdwd-step-back" data-step-back><?php esc_html_e( 'Back', 'sdweddingdirectory' ); ?></button>
                                                            <button type="submit" class="btn btn-default btn-rounded" id="sdwd-planning-register-submit"><?php esc_html_e( 'Sign Up', 'sdweddingdirectory' ); ?></button>
                                                        </div>
                                                        <p class="sdwd-planning-login-link"><?php esc_html_e( 'Already have an account?', 'sdweddingdirectory' ); ?> <a href="#" data-bs-toggle="modal" data-bs-target="#couple_login"><?php esc_html_e( 'Log In', 'sdweddingdirectory' ); ?></a></p>
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

                <?php if( ! is_user_logged_in() ){ ?>
                    <script>
                        (function() {
                            var form = document.getElementById('sdwd_planning_register_form');

                            if (!form) {
                                return;
                            }

                            var steps = Array.prototype.slice.call(form.querySelectorAll('.sdwd-planning-step'));
                            var messageBox = form.querySelector('.sdwd-planning-form-message');
                            var currentStep = 0;

                            var setMessage = function(message, type) {
                                if (!messageBox) {
                                    return;
                                }

                                messageBox.textContent = message || '';
                                messageBox.classList.remove('is-error', 'is-success', 'is-info');

                                if (message) {
                                    messageBox.classList.add(type || 'is-info');
                                }
                            };

                            var showStep = function(index) {
                                if (index < 0 || index > steps.length - 1) {
                                    return;
                                }

                                currentStep = index;

                                steps.forEach(function(step, stepIndex) {
                                    step.classList.toggle('is-active', stepIndex === currentStep);
                                });
                            };

                            var validateStep = function(index) {
                                var step = steps[index];

                                if (!step) {
                                    return true;
                                }

                                var fields = Array.prototype.slice.call(step.querySelectorAll('input, select, textarea'));

                                for (var i = 0; i < fields.length; i++) {
                                    var field = fields[i];

                                    if (field.type === 'radio') {
                                        continue;
                                    }

                                    if (!field.checkValidity()) {
                                        field.reportValidity();
                                        return false;
                                    }
                                }

                                var radioNames = [];

                                fields.forEach(function(field) {
                                    if (field.type === 'radio' && field.name && radioNames.indexOf(field.name) === -1) {
                                        radioNames.push(field.name);
                                    }
                                });

                                for (var r = 0; r < radioNames.length; r++) {
                                    var radios = step.querySelectorAll('input[type="radio"][name="' + radioNames[r] + '"]');
                                    var checked = false;

                                    for (var j = 0; j < radios.length; j++) {
                                        if (radios[j].checked) {
                                            checked = true;
                                            break;
                                        }
                                    }

                                    if (!checked && radios.length) {
                                        radios[0].reportValidity();
                                        return false;
                                    }
                                }

                                return true;
                            };

                            form.addEventListener('click', function(event) {
                                var control = event.target.closest('[data-step-next], [data-step-back]');

                                if (!control) {
                                    return;
                                }

                                event.preventDefault();

                                if (control.hasAttribute('data-step-next')) {
                                    if (validateStep(currentStep)) {
                                        setMessage('');
                                        showStep(currentStep + 1);
                                    }
                                    return;
                                }

                                if (control.hasAttribute('data-step-back')) {
                                    setMessage('');
                                    showStep(currentStep - 1);
                                }
                            });

                            form.addEventListener('submit', function(event) {
                                event.preventDefault();

                                if (!validateStep(currentStep)) {
                                    return;
                                }

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
                                .then(function(response) {
                                    return response.json();
                                })
                                .then(function(payload) {
                                    if (payload && payload.message) {
                                        setMessage(payload.message, payload.notice === 1 ? 'is-success' : 'is-error');
                                    }

                                    if (payload && payload.redirect === true) {
                                        var redirectUrl = payload.redirect_link ? payload.redirect_link : '<?php echo esc_url( home_url( '/couple-dashboard/' ) ); ?>';
                                        window.setTimeout(function() {
                                            window.location.href = redirectUrl;
                                        }, 350);
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
                <?php

                return;
            }

            /**
             *  Image BG Selected
             *  -----------------
             */
            if( $default_layout ==  esc_attr( 'layout-one' ) ){

                ?>
                <section class="breadcrumbs-page" style="<?php printf( 'background:url(%1$s);', esc_url( $page_header_image )  ); ?>">

                    <div class="container">

                        <h1 class="page-title"><?php echo self:: sdweddingdirectory_page_header_title(); ?></h1>

                        <?php

                            /**
                             *  1. Taxonomy Description
                             *  -----------------------
                             */
                            if( is_tax() ){

                                /**
                                 *  Description
                                 *  -----------
                                 */
                                $description    =  get_term_field( 'description', get_queried_object_id() );

                                /**
                                 *  Have Description
                                 *  ----------------
                                 */
                                if( ! empty( $description ) ){

                                    printf( $description );
                                }
                            }

                        ?>

                        <?php self:: page_breadcrumbs(); ?>

                    </div>

                </section>
                <?php
            }

            /**
             *  Load Solid BG
             *  -------------
             */
            else{

                ?>
                <section class="breadcrumbs-page" style="<?php printf( 'background: %1$s;', $default_bg_color ); ?>">

                    <div class="container">

                        <h1 class="page-title"><?php echo self:: sdweddingdirectory_page_header_title(); ?></h1>

                        <?php self:: page_breadcrumbs(); ?>

                    </div>

                </section>
                <?php
            }
        }

        /**
         *  SDWeddingDirectory - Post / Page Heading Title
         *  --------------------------------------
         */
        public static function sdweddingdirectory_page_header_title(){

            global $wp_query, $post;

            /**
             *  Find Page Title
             *  ---------------
             */
            if( is_home() && ! is_front_page() ){

                /**
                 *  Is Blog Post Page ?
                 *  -------------------
                 */
                return  esc_attr(   get_the_title( get_option( 'page_for_posts' ) )  );


            }elseif( is_archive() ) {

                /**
                 *  Is Archive Page ?
                 *  -----------------
                 */
                return  get_the_archive_title();


            }elseif( is_search() ) {

                /**
                 *  Is Search page ?
                 *  ----------------
                 */
                return      sprintf(    esc_attr__( 'Search : %1$s', 'sdweddingdirectory' ),

                                        /**
                                         *  1. Search Query
                                         *  ---------------
                                         */
                                        esc_attr(  get_search_query() )
                            );


            }elseif( is_404() ){

                /**
                 *  Is 404 Error Page ?
                 *  -------------------
                 */
                return  esc_attr__( 'Page Not Found', 'sdweddingdirectory'  );


            }else {

                /**
                 *  Default Get Page Title
                 *  ----------------------
                 */
                return  get_the_title();
            }
        }

        /**
         *  Have Breadcrumbs ?
         *  ------------------
         */
        public static function page_breadcrumbs(){

            if( is_page( 'venues' ) || is_page_template( 'page-venues.php' ) ){

                $location_name = '';

                if( isset( $_GET['location'] ) && $_GET['location'] !== '' ){

                    $location_slug = sanitize_title( wp_unslash( $_GET['location'] ) );

                    $location_obj  = get_term_by( 'slug', $location_slug, esc_attr( 'venue-location' ) );

                    if( ! empty( $location_obj ) && ! is_wp_error( $location_obj ) ){

                        $location_name = $location_obj->name;
                    }
                }

                elseif( isset( $_GET['state_id'] ) && $_GET['state_id'] !== '' ){

                    $location_obj = get_term( absint( $_GET['state_id'] ), esc_attr( 'venue-location' ) );

                    if( ! empty( $location_obj ) && ! is_wp_error( $location_obj ) ){

                        $location_name = $location_obj->name;
                    }
                }

                ?>
                <section class="sdweddingdirectory-page-breadcrumbs">
                    <ol class="breadcrumb sdwd-breadcrumb" typeof="BreadcrumbList" vocab="https://schema.org/">
                        <li class="breadcrumb-item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'sdweddingdirectory' ); ?></a></li>
                        <li class="breadcrumb-item">
                            <a href="<?php echo esc_url( home_url( '/venues/' ) ); ?>"><?php esc_html_e( 'Wedding Venues', 'sdweddingdirectory' ); ?></a>
                        </li>
                        <?php if( ! empty( $location_name ) ){ ?>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo esc_html( $location_name ); ?></li>
                        <?php } ?>
                    </ol>
                </section>
                <?php

                return;
            }

            $crumbs = array();

            $crumbs[] = sprintf(
                '<a href="%1$s">%2$s</a>',
                esc_url( home_url( '/' ) ),
                esc_html__( 'Weddings', 'sdweddingdirectory' )
            );

            if( is_page() ){

                $current_page_id = absint( get_queried_object_id() );
                $ancestors       = array_reverse( get_post_ancestors( $current_page_id ) );

                foreach( $ancestors as $ancestor_id ){
                    $crumbs[] = sprintf(
                        '<a href="%1$s">%2$s</a>',
                        esc_url( get_permalink( $ancestor_id ) ),
                        esc_html( get_the_title( $ancestor_id ) )
                    );
                }

                $crumbs[] = sprintf( '<span>%1$s</span>', esc_html( get_the_title( $current_page_id ) ) );
            }

            elseif( is_single() ){

                $categories = get_the_category();

                if( ! empty( $categories ) ){
                    $crumbs[] = sprintf(
                        '<a href="%1$s">%2$s</a>',
                        esc_url( get_category_link( $categories[0]->term_id ) ),
                        esc_html( $categories[0]->name )
                    );
                }

                $crumbs[] = sprintf( '<span>%1$s</span>', esc_html( get_the_title() ) );
            }

            elseif( is_category() ){
                $crumbs[] = sprintf( '<span>%1$s</span>', esc_html( single_cat_title( '', false ) ) );
            }

            elseif( is_tax() ){

                $term = get_queried_object();

                if( ! empty( $term ) && ! is_wp_error( $term ) ){

                    if( ! empty( $term->parent ) ){

                        $parent = get_term( $term->parent, $term->taxonomy );

                        if( ! empty( $parent ) && ! is_wp_error( $parent ) ){
                            $crumbs[] = sprintf(
                                '<a href="%1$s">%2$s</a>',
                                esc_url( get_term_link( $parent ) ),
                                esc_html( $parent->name )
                            );
                        }
                    }

                    $crumbs[] = sprintf( '<span>%1$s</span>', esc_html( $term->name ) );
                }
            }

            elseif( is_search() ){
                $crumbs[] = sprintf( '<span>%1$s</span>', esc_html__( 'Search Results', 'sdweddingdirectory' ) );
            }

            elseif( is_archive() ){
                $crumbs[] = sprintf( '<span>%1$s</span>', esc_html( get_the_archive_title() ) );
            }

            ?>
            <section class="sdweddingdirectory-page-breadcrumbs">
                <nav class="breadcrumb sdwd-breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'sdweddingdirectory' ); ?>">
                    <?php echo wp_kses_post( implode( '<span class="sdwd-breadcrumb-sep"> / </span>', $crumbs ) ); ?>
                </nav>
            </section>
            <?php
        }
    }

    /**
     *  SDWeddingDirectory - Page Header Banner
     *  -------------------------------
     */
    SDWeddingDirectory_Page_Header_Banner::get_instance();
}
