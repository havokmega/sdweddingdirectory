<?php
/**
 *  SDWeddingDirectory - Vendor Dashboard
 *  -----------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Dashboard_Vendor' ) && class_exists( 'SDWeddingDirectory_Form_Tabs' ) ){

    /**
     *  SDWeddingDirectory - Vendor Dashboard
     *  -----------------------------
     */
    class SDWeddingDirectory_Dashboard_Vendor extends SDWeddingDirectory_Form_Tabs{

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

        /**
         *  Construct
         *  ---------
         */
        public function __construct(){

            /**
             *  1. Dashboard Scripts
             *  --------------------
             */
            add_action( 'wp_enqueue_scripts', [$this, 'sdweddingdirectory_script'], absint( '10' ) );

            /**
             *  2. Dashboard Page
             *  -----------------
             */
            add_action( 'sdweddingdirectory/vendor-dashboard', [$this, 'dashboard_page'], absint( '10' ), absint( '1' ) );
        }

        /**
         *  1. Dashboard Scripts
         *  --------------------
         */
        public static function sdweddingdirectory_script(){

            /**
             *  Is Vendor + Dashboard Page
             *  --------------------------
             */
            if( parent:: dashboard_page_set( 'vendor-dashboard' ) ){

                /**
                 *  Load Library Condition
                 *  ----------------------
                 */
                add_filter( 'sdweddingdirectory/enable-script/media-upload', function( $args = [] ){

                    return  array_merge( $args, [

                                'vendor-dashboard'   =>  true

                            ] );
                } );
            }
        }

        /**
         *  2. Dashboard Page
         *  -----------------
         */
        public static function dashboard_page( $args = [] ){

            /**
             *  Have Args
             *  ---------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( wp_parse_args( $args,[

                    'layout'        =>      absint( '1' ),

                    'post_id'       =>      '',

                    'page'          =>      ''

                ] ) );

                /**
                 *  Make sure this page is couple dashboard page
                 *  --------------------------------------------
                 */
                if( ! empty( $page ) && $page == esc_attr( 'vendor-dashboard' )  ){

                    ?><div class="container"><?php

                        $vendor_user_id = get_current_user_id();
                        $pending_claims = get_posts( array(
                            'post_type'   => 'claim-venue',
                            'post_status' => 'pending',
                            'author'      => absint( $vendor_user_id ),
                            'numberposts' => -1,
                        ) );

                        if ( ! empty( $pending_claims ) ) {
                            foreach ( $pending_claims as $claim ) {
                                $target_post_id = absint( get_post_meta( $claim->ID, 'target_post_id', true ) );
                                $target_name    = get_the_title( absint( $target_post_id ) );
                                printf(
                                    '<div class="alert alert-info" role="alert"><i class="fa fa-clock-o"></i> %s</div>',
                                    sprintf(
                                        esc_html__( 'Your claim for "%s" is pending admin approval.', 'sdweddingdirectory' ),
                                        esc_html( $target_name )
                                    )
                                );
                            }
                        }

                        $current_user = wp_get_current_user();
                        $vendor_post_id = ! empty( $current_user )

                                            ? absint( apply_filters( 'sdweddingdirectory/vendor/post-id', sanitize_email( $current_user->user_email ) ) )

                                            : 0;

                        if( $vendor_post_id === 0 ){

                            printf(
                                '<div class="alert alert-warning" role="alert"><i class="fa fa-info-circle"></i> %1$s</div>',
                                esc_html__( 'Your vendor profile is being prepared. If you submitted a claim, please wait for admin approval.', 'sdweddingdirectory' )
                            );

                            self:: render_claim_onboarding( absint( $vendor_user_id ) );

                            ?></div><?php
                            return;
                        }

                        /**
                         *  2.1 - Vendor Banner + Profile Upload Section
                         *  --------------------------------------------
                         */
                        self:: vendor_media( absint( $vendor_post_id ) );

                        /**
                         *  2.2 - Vendor Overview Widget
                         *  ----------------------------
                         */
                        self:: vendor_widget();

                        /**
                         *  One-time claim onboarding flow.
                         */
                        self:: render_claim_onboarding( absint( $vendor_user_id ) );

                    ?></div><?php
                }
            }
        }

        /**
         *  2.1 - Vendor Banner + Profile Upload Section
         *  --------------------------------------------
         */
        public static function  vendor_media( $vendor_post_id = 0 ){

            $vendor_post_id = absint( $vendor_post_id );

            if( $vendor_post_id === 0 ){

                $current_user = wp_get_current_user();

                $vendor_post_id = ! empty( $current_user ) && ! empty( $current_user->user_email )

                                    ? absint( apply_filters( 'sdweddingdirectory/vendor/post-id', sanitize_email( $current_user->user_email ) ) )

                                    : 0;
            }

            ?><div class="card-shadow"><div class="card-shadow-body p-0"><div class="vendor-banner-cover"><?php

                /**
                 *  Upload Profile Profile Upload
                 *  -----------------------------
                 */
                print 

                apply_filters( 'sdweddingdirectory/field/single-media', [

                    'post_id'           =>      absint( $vendor_post_id ),

                    'database_key'      =>      esc_attr( 'profile_banner' ),

                    'image_size'        =>      esc_attr( 'sdweddingdirectory_img_1920x600' ),

                    'is_ajax'           =>      true,

                    'default_img'       =>      esc_url( parent:: placeholder( 'vendor-brand-banner' ) )

                ] );

            ?></div><div class="vendor-profile-img"><div class="text"><?php

                print 

                apply_filters( 'sdweddingdirectory/field/single-media', [

                    'post_id'               =>      absint( $vendor_post_id ),

                    'database_key'          =>      esc_attr( 'business_icon' ),

                    'icon_layout'           =>      absint( '1' ),

                    'image_class'           =>      'rounded-circle border border-2',

                    'image_size'            =>      esc_attr( 'thumbnail' ),

                    'is_ajax'               =>      true,

                    'default_img'           =>      esc_url( parent:: placeholder( 'vendor-brand-image' ) )

                ] );

                /**
                 *  Lable
                 *  -----
                 */
                printf( '<div class="cover-text ms-4"><strong>%1$s</strong><span>%2$s</span></div>', 

                    /**
                     *  1. Translation String
                     *  ---------------------
                     */
                    esc_attr__( 'Upload Brand Image', 'sdweddingdirectory' ),

                    /**
                     *  1. Translation String
                     *  ---------------------
                     */
                    esc_attr__( 'Best image size 150 x 150', 'sdweddingdirectory' )
                );

            ?></div><?php

                /**
                 *  Edit Button
                 *  -----------
                 */
                printf( '<div class="vendor-btn">
                            <a href="%1$s" class="btn btn-outline-white btn-sm"><i class="fa fa-pencil"></i> %2$s</a>
                        </div>', 

                        /**
                         *  1. Vendor Profile Page Link
                         *  ---------------------------
                         */
                        apply_filters( 'sdweddingdirectory/vendor-menu/page-link', esc_attr( 'vendor-profile' ) ),

                        /**
                         *  2. Translation Ready String
                         *  ---------------------------
                         */
                        esc_attr__( 'Edit', 'sdweddingdirectory' )
                );

            ?></div></div></div><?php
        }

        /**
         *  2.2 - Vendor Widget
         *  -------------------
         */
        public static function vendor_widget(){

            ?><div class="row"><?php 

                /**
                 *  Vendor Overview Widget Area
                 *  ---------------------------
                 */
                do_action( 'sdweddingdirectory_vendor_overview_widget' );

            ?></div><?php

            /**
             *  Vendor Overview Widget Area
             *  ---------------------------
             */
            do_action( 'sdweddingdirectory_vendor_full_widget' );
        }

        /**
         *  Normalize Option Rows
         *  ---------------------
         */
        public static function normalize_option_rows( $rows = [], $mode = '' ){

            $options = [];

            if( parent:: _is_array( $rows ) ){

                foreach( $rows as $row ){

                    if( $mode === 'pricing' && isset( $row['min'], $row['max'] ) ){

                        $value = sprintf( '%1$s-%2$s', $row['min'], $row['max'] );
                        $label = ! empty( $row['label'] ) ? $row['label'] : $value;

                        $options[] = [
                            'value' => sanitize_text_field( $value ),
                            'label' => sanitize_text_field( $label ),
                        ];
                    }

                    elseif( isset( $row['value'] ) && $row['value'] !== '' ){

                        $options[] = [
                            'value' => sanitize_text_field( $row['value'] ),
                            'label' => sanitize_text_field( isset( $row['label'] ) ? $row['label'] : $row['value'] ),
                        ];
                    }
                }
            }

            return $options;
        }

        /**
         *  Category Filter Payload
         *  -----------------------
         */
        public static function category_filter_payload( $term_id = 0 ){

            $term_id = absint( $term_id );

            if( $term_id === 0 ){
                return [];
            }

            $pricing_rows      = get_term_meta( $term_id, sanitize_key( 'vendor_pricing_options' ), true );
            $services_rows     = get_term_meta( $term_id, sanitize_key( 'vendor_services_options' ), true );
            $style_rows        = get_term_meta( $term_id, sanitize_key( 'vendor_style_options' ), true );
            $specialties_rows  = get_term_meta( $term_id, sanitize_key( 'vendor_specialties_options' ), true );

            return [
                'vendor_pricing'      => self:: normalize_option_rows( $pricing_rows, 'pricing' ),
                'vendor_services'     => self:: normalize_option_rows( $services_rows ),
                'vendor_style'        => self:: normalize_option_rows( $style_rows ),
                'vendor_specialties'  => self:: normalize_option_rows( $specialties_rows ),
            ];
        }

        /**
         *  One-time Claim Onboarding
         *  -------------------------
         */
        public static function render_claim_onboarding( $vendor_user_id = 0 ){

            $vendor_user_id = absint( $vendor_user_id );

            if( $vendor_user_id === 0 ){
                return;
            }

            $show_onboarding = absint( get_user_meta( $vendor_user_id, sanitize_key( 'sdwd_claim_approved_welcome' ), true ) ) === 1;

            if( ! $show_onboarding ){
                return;
            }

            /**
             *  Show exactly once.
             */
            update_user_meta( $vendor_user_id, sanitize_key( 'sdwd_claim_approved_welcome' ), absint( '0' ) );

            $current_user   = get_userdata( $vendor_user_id );
            $vendor_post_id = ! empty( $current_user )

                                ? absint( apply_filters( 'sdweddingdirectory/vendor/post-id', sanitize_email( $current_user->user_email ) ) )

                                : 0;

            $categories = get_terms( [
                'taxonomy'   => esc_attr( 'vendor-category' ),
                'hide_empty' => false,
                'parent'     => 0,
            ] );

            $selected_category = 0;

            if( $vendor_post_id > 0 ){

                $assigned_terms = wp_get_post_terms( $vendor_post_id, esc_attr( 'vendor-category' ), [ 'fields' => 'ids' ] );
                $selected_category = parent:: _is_array( $assigned_terms ) ? absint( $assigned_terms[0] ) : 0;
            }

            if( $selected_category === 0 && parent:: _is_array( $categories ) ){
                $selected_category = absint( $categories[0]->term_id );
            }

            $category_payload = [];

            if( parent:: _is_array( $categories ) ){
                foreach( $categories as $category ){

                    $category_payload[ absint( $category->term_id ) ] = self:: category_filter_payload( absint( $category->term_id ) );
                }
            }

            $saved_pricing      = get_post_meta( $vendor_post_id, sanitize_key( 'vendor_pricing' ), true );
            $saved_services     = get_post_meta( $vendor_post_id, sanitize_key( 'vendor_services' ), true );
            $saved_style        = get_post_meta( $vendor_post_id, sanitize_key( 'vendor_style' ), true );
            $saved_specialties  = get_post_meta( $vendor_post_id, sanitize_key( 'vendor_specialties' ), true );

            $saved_days         = get_post_meta( $vendor_post_id, sanitize_key( 'business_open_days' ), true );
            $saved_open_time    = sanitize_text_field( get_post_meta( $vendor_post_id, sanitize_key( 'business_open_time' ), true ) );
            $saved_close_time   = sanitize_text_field( get_post_meta( $vendor_post_id, sanitize_key( 'business_close_time' ), true ) );

            $selected_values = [
                'vendor_pricing'      => parent:: _is_array( $saved_pricing ) ? array_values( $saved_pricing ) : [],
                'vendor_services'     => parent:: _is_array( $saved_services ) ? array_values( $saved_services ) : [],
                'vendor_style'        => parent:: _is_array( $saved_style ) ? array_values( $saved_style ) : [],
                'vendor_specialties'  => parent:: _is_array( $saved_specialties ) ? array_values( $saved_specialties ) : [],
            ];

            $day_options = [
                'monday'    => esc_attr__( 'Monday', 'sdweddingdirectory' ),
                'tuesday'   => esc_attr__( 'Tuesday', 'sdweddingdirectory' ),
                'wednesday' => esc_attr__( 'Wednesday', 'sdweddingdirectory' ),
                'thursday'  => esc_attr__( 'Thursday', 'sdweddingdirectory' ),
                'friday'    => esc_attr__( 'Friday', 'sdweddingdirectory' ),
                'saturday'  => esc_attr__( 'Saturday', 'sdweddingdirectory' ),
                'sunday'    => esc_attr__( 'Sunday', 'sdweddingdirectory' ),
            ];

            $saved_days = parent:: _is_array( $saved_days ) ? $saved_days : [];

            ?>
            <div class="modal fade" id="sdwd_claim_welcome_modal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><?php esc_html_e( 'Welcome to SDWeddingDirectory', 'sdweddingdirectory' ); ?></h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></button>
                        </div>
                        <div class="modal-body">
                            <p class="mb-0"><?php esc_html_e( 'Your claim was approved. Let’s complete your profile helper to power your search visibility.', 'sdweddingdirectory' ); ?></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" id="sdwd_start_profile_helper"><?php esc_html_e( 'Continue', 'sdweddingdirectory' ); ?></button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="sdwd_profile_helper_modal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><?php esc_html_e( 'Profile Helper', 'sdweddingdirectory' ); ?></h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></button>
                        </div>
                        <div class="modal-body">
                            <form id="sdwd_vendor_helper_form" method="post">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label"><?php esc_html_e( 'Vendor Category', 'sdweddingdirectory' ); ?></label>
                                        <select class="form-control" id="sdwd_helper_category" name="sdwd_helper_category">
                                            <?php if( parent:: _is_array( $categories ) ){ foreach( $categories as $category ){ ?>
                                                <option value="<?php echo esc_attr( $category->term_id ); ?>" <?php selected( absint( $selected_category ), absint( $category->term_id ) ); ?>>
                                                    <?php echo esc_html( $category->name ); ?>
                                                </option>
                                            <?php } } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label"><?php esc_html_e( 'Open Days', 'sdweddingdirectory' ); ?></label>
                                        <select class="form-control" id="sdwd_business_open_days" name="sdwd_business_open_days" multiple>
                                            <?php foreach( $day_options as $day_key => $day_label ){ ?>
                                                <option value="<?php echo esc_attr( $day_key ); ?>" <?php echo in_array( $day_key, $saved_days, true ) ? 'selected' : ''; ?>>
                                                    <?php echo esc_html( $day_label ); ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label"><?php esc_html_e( 'Open Time', 'sdweddingdirectory' ); ?></label>
                                        <select class="form-control" id="sdwd_business_open_time" name="sdwd_business_open_time">
                                            <?php for( $hour = 0; $hour < 24; $hour++ ){ $time = sprintf( '%02d:00', $hour ); ?>
                                                <option value="<?php echo esc_attr( $time ); ?>" <?php selected( $saved_open_time, $time ); ?>><?php echo esc_html( $time ); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label"><?php esc_html_e( 'Close Time', 'sdweddingdirectory' ); ?></label>
                                        <select class="form-control" id="sdwd_business_close_time" name="sdwd_business_close_time">
                                            <?php for( $hour = 0; $hour < 24; $hour++ ){ $time = sprintf( '%02d:00', $hour ); ?>
                                                <option value="<?php echo esc_attr( $time ); ?>" <?php selected( $saved_close_time, $time ); ?>><?php echo esc_html( $time ); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label"><?php esc_html_e( 'Pricing', 'sdweddingdirectory' ); ?></label>
                                        <select class="form-control" id="sdwd_vendor_pricing" name="sdwd_vendor_pricing" multiple></select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label"><?php esc_html_e( 'Services', 'sdweddingdirectory' ); ?></label>
                                        <select class="form-control" id="sdwd_vendor_services" name="sdwd_vendor_services" multiple></select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label"><?php esc_html_e( 'Style', 'sdweddingdirectory' ); ?></label>
                                        <select class="form-control" id="sdwd_vendor_style" name="sdwd_vendor_style" multiple></select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label"><?php esc_html_e( 'Specialties', 'sdweddingdirectory' ); ?></label>
                                        <select class="form-control" id="sdwd_vendor_specialties" name="sdwd_vendor_specialties" multiple></select>
                                    </div>
                                </div>
                                <input type="hidden" id="sdwd_vendor_helper_post_id" value="<?php echo esc_attr( $vendor_post_id ); ?>" />
                                <?php wp_nonce_field( 'sdwd_vendor_onboarding_save', 'sdwd_vendor_onboarding_nonce', true, true ); ?>
                                <div class="d-flex justify-content-end mt-3">
                                    <button type="submit" class="btn btn-default"><?php esc_html_e( 'Save Helper', 'sdweddingdirectory' ); ?></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                (function($){
                    var optionMap = <?php echo wp_json_encode( $category_payload ); ?>;
                    var selectedValues = <?php echo wp_json_encode( $selected_values ); ?>;
                    var initialCategory = '<?php echo esc_js( absint( $selected_category ) ); ?>';

                    function buildOptions(selectId, list, selected) {
                        var $select = $(selectId);
                        $select.empty();
                        if (!Array.isArray(list)) {
                            return;
                        }
                        list.forEach(function(item) {
                            if (!item || !item.value) { return; }
                            var isSelected = Array.isArray(selected) && selected.indexOf(item.value) !== -1;
                            var option = new Option(item.label || item.value, item.value, false, isSelected);
                            $select.append(option);
                        });
                    }

                    function renderCategoryOptions(categoryId, useInitialSelection) {
                        var group = optionMap[String(categoryId)] || {
                            vendor_pricing: [],
                            vendor_services: [],
                            vendor_style: [],
                            vendor_specialties: []
                        };

                        buildOptions('#sdwd_vendor_pricing', group.vendor_pricing, useInitialSelection ? selectedValues.vendor_pricing : []);
                        buildOptions('#sdwd_vendor_services', group.vendor_services, useInitialSelection ? selectedValues.vendor_services : []);
                        buildOptions('#sdwd_vendor_style', group.vendor_style, useInitialSelection ? selectedValues.vendor_style : []);
                        buildOptions('#sdwd_vendor_specialties', group.vendor_specialties, useInitialSelection ? selectedValues.vendor_specialties : []);
                    }

                    $(document).on('change', '#sdwd_helper_category', function(){
                        renderCategoryOptions($(this).val(), false);
                    });

                    $(document).on('click', '#sdwd_start_profile_helper', function(){
                        $('#sdwd_claim_welcome_modal').modal('hide');
                    });

                    $('#sdwd_claim_welcome_modal').on('hidden.bs.modal', function(){
                        $('#sdwd_profile_helper_modal').modal('show');
                    });

                    $(document).on('submit', '#sdwd_vendor_helper_form', function(e){
                        e.preventDefault();

                        $.ajax({
                            type: 'POST',
                            url: SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,
                            dataType: 'json',
                            data: {
                                action: 'sdwd_vendor_onboarding_save_action',
                                security: $('#sdwd_vendor_helper_form #sdwd_vendor_onboarding_nonce').val(),
                                vendor_post_id: $('#sdwd_vendor_helper_post_id').val(),
                                vendor_category: $('#sdwd_helper_category').val(),
                                vendor_pricing: $('#sdwd_vendor_pricing').val() || [],
                                vendor_services: $('#sdwd_vendor_services').val() || [],
                                vendor_style: $('#sdwd_vendor_style').val() || [],
                                vendor_specialties: $('#sdwd_vendor_specialties').val() || [],
                                business_open_days: $('#sdwd_business_open_days').val() || [],
                                business_open_time: $('#sdwd_business_open_time').val() || '',
                                business_close_time: $('#sdwd_business_close_time').val() || ''
                            },
                            success: function(response){
                                sdweddingdirectory_alert(response);
                                if(response.notice == 1){
                                    $('#sdwd_profile_helper_modal').modal('hide');
                                }
                            }
                        });
                    });

                    $(document).ready(function(){
                        renderCategoryOptions(initialCategory, true);
                        $('#sdwd_claim_welcome_modal').modal('show');
                    });
                })(jQuery);
            </script>
            <?php
        }
    }

    /**
     *  SDWeddingDirectory - Vendor Dashboard
     *  -----------------------------
     */
    SDWeddingDirectory_Dashboard_Vendor::get_instance();
}
