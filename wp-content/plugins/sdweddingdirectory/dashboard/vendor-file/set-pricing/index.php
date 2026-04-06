<?php
/**
 *  SDWeddingDirectory - Vendor Pricing Dashboard
 *  --------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Dashboard_Vendor_Pricing' ) && class_exists( 'SDWeddingDirectory_Vendor_Profile_Database' ) ){

    /**
     *  SDWeddingDirectory - Vendor Pricing Dashboard
     *  --------------------------------------------
     */
    class SDWeddingDirectory_Dashboard_Vendor_Pricing extends SDWeddingDirectory_Vendor_Profile_Database{

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
        public function __construct() {

            add_action( 'wp_enqueue_scripts', [ $this, 'sdweddingdirectory_script' ], absint( '110' ) );

            add_action( 'sdweddingdirectory/vendor-dashboard', [ $this, 'dashboard_page' ], absint( '110' ), absint( '1' ) );

            add_action( 'wp_ajax_sdwd_vendor_pricing_save_action', [ $this, 'sdwd_vendor_pricing_save_action' ] );

            /**
             *  Register as a tab under My Profile
             *  -----------------------------------
             */
            add_filter( 'sdweddingdirectory/vendor-profile/tabs', [ $this, 'get_tabs' ], absint( '22' ), absint( '1' ) );
        }

        /**
         *  Load Script
         *  -----------
         */
        public static function sdweddingdirectory_script(){

            if( parent:: dashboard_page_set( 'vendor-pricing' ) || parent:: dashboard_page_set( 'vendor-profile' ) ){

                wp_enqueue_script(
                    esc_attr( sanitize_title( __CLASS__ ) ),
                    esc_url( plugin_dir_url( __FILE__ ) . 'script.js' ),
                    [ 'jquery', 'toastr' ],
                    esc_attr( parent::_file_version( plugin_dir_path( __FILE__ ) . 'script.js' ) ),
                    true
                );
            }
        }

        /**
         *  Resolve Current Vendor Post ID
         *  ------------------------------
         */
        public static function current_vendor_post_id(){

            $post_id = absint( parent:: post_id() );

            if( $post_id > 0 ){
                return $post_id;
            }

            $current_user = wp_get_current_user();

            if( empty( $current_user ) || empty( $current_user->user_email ) ){
                return absint( '0' );
            }

            return absint( apply_filters( 'sdweddingdirectory/vendor/post-id', sanitize_email( $current_user->user_email ) ) );
        }

        /**
         *  Check Vendor Ownership
         *  ----------------------
         */
        public static function vendor_user_owns_post( $post_id = 0, $author_id = 0 ){

            $post_id = absint( $post_id );

            $author_id = absint( $author_id );

            if( $post_id === 0 || $author_id === 0 ){
                return false;
            }

            $post_data = get_post( $post_id );

            if( empty( $post_data ) ){
                return false;
            }

            if( absint( $post_data->post_author ) === $author_id ){
                return true;
            }

            $linked_user_id = absint( get_post_meta( $post_id, sanitize_key( 'user_id' ), true ) );

            return $linked_user_id > 0 && $linked_user_id === $author_id;
        }

        /**
         *  Default Tier Data
         *  -----------------
         */
        public static function default_tier_data(){

            $tiers = [];

            for( $tier = 1; $tier <= 3; $tier++ ){

                $items = [];

                for( $row = 1; $row <= 10; $row++ ){

                    $items[] = [

                        'text'      => '',

                        'included'  => absint( '0' ),
                    ];
                }

                $tiers[ $tier ] = [

                    'title' => sprintf( esc_attr__( 'Package %1$s', 'sdweddingdirectory' ), absint( $tier ) ),

                    'price' => '',

                    'hours' => '',

                    'items' => $items,
                ];
            }

            return [

                'tier_count' => absint( '3' ),

                'tiers'      => $tiers,
            ];
        }

        /**
         *  Sanitize Pricing Data
         *  ---------------------
         */
        public static function sanitize_pricing_payload( $payload = [] ){

            $defaults = self:: default_tier_data();

            $payload = parent::_is_array( $payload ) ? $payload : [];

            $tier_count = isset( $payload['tier_count'] ) ? absint( $payload['tier_count'] ) : absint( $defaults['tier_count'] );

            if( $tier_count < 1 ){ $tier_count = 1; }
            if( $tier_count > 3 ){ $tier_count = 3; }

            $tiers = isset( $payload['tiers'] ) && parent::_is_array( $payload['tiers'] ) ? $payload['tiers'] : [];

            $sanitized_tiers = [];

            for( $tier = 1; $tier <= 3; $tier++ ){

                $tier_data = isset( $tiers[ $tier ] ) && parent::_is_array( $tiers[ $tier ] ) ? $tiers[ $tier ] : [];

                $raw_title = isset( $tier_data['title'] ) ? sanitize_text_field( $tier_data['title'] ) : '';

                if( $raw_title === '' ){
                    $raw_title = sprintf( esc_attr__( 'Package %1$s', 'sdweddingdirectory' ), absint( $tier ) );
                }

                $raw_price = isset( $tier_data['price'] ) ? sanitize_text_field( $tier_data['price'] ) : '';

                $raw_price = preg_replace( '/[^0-9.\-]/', '', $raw_price );

                $rows = [];

                $row_data = isset( $tier_data['items'] ) && parent::_is_array( $tier_data['items'] ) ? $tier_data['items'] : [];

                for( $row = 1; $row <= 10; $row++ ){

                    $single_row = [];

                    if( isset( $row_data[ $row ] ) && parent::_is_array( $row_data[ $row ] ) ){
                        $single_row = $row_data[ $row ];
                    }elseif( isset( $row_data[ $row - 1 ] ) && parent::_is_array( $row_data[ $row - 1 ] ) ){
                        $single_row = $row_data[ $row - 1 ];
                    }

                    $rows[] = [

                        'text'      => isset( $single_row['text'] ) ? sanitize_text_field( $single_row['text'] ) : '',

                        'included'  => isset( $single_row['included'] ) ? absint( $single_row['included'] ) : absint( '0' ),
                    ];
                }

                $raw_hours = isset( $tier_data['hours'] ) ? sanitize_text_field( $tier_data['hours'] ) : '';
                $raw_hours = preg_replace( '/[^0-9.\-]/', '', $raw_hours );

                $sanitized_tiers[ $tier ] = [

                    'title' => $raw_title,

                    'price' => $raw_price,

                    'hours' => $raw_hours,

                    'items' => $rows,
                ];
            }

            return [

                'tier_count' => absint( $tier_count ),

                'tiers'      => $sanitized_tiers,

                'updated_at' => current_time( 'mysql' ),
            ];
        }

        /**
         *  Get Saved Pricing Data
         *  ----------------------
         */
        public static function get_saved_pricing_data( $post_id = 0 ){

            $post_id = absint( $post_id );

            $stored_data = $post_id > 0 ? get_post_meta( $post_id, sanitize_key( 'sdwd_vendor_pricing_tiers' ), true ) : [];

            if( ! parent::_is_array( $stored_data ) ){
                $stored_data = [];
            }

            return self:: sanitize_pricing_payload( array_merge( self:: default_tier_data(), $stored_data ) );
        }

        /**
         *  AJAX Save Pricing Data
         *  ----------------------
         */
        public static function sdwd_vendor_pricing_save_action(){

            $security = isset( $_POST['security'] ) ? sanitize_text_field( $_POST['security'] ) : '';

            if( empty( $security ) || ! wp_verify_nonce( $security, esc_attr( 'sdwd_vendor_set_pricing_save' ) ) ){

                die( wp_json_encode( [

                    'notice'    => absint( '2' ),

                    'message'   => esc_attr__( 'Security issue!', 'sdweddingdirectory' ),
                ] ) );
            }

            if( ! is_user_logged_in() || ( ! parent:: is_vendor() && ! current_user_can( 'administrator' ) ) ){

                die( wp_json_encode( [

                    'notice'    => absint( '2' ),

                    'message'   => esc_attr__( 'Unauthorized request.', 'sdweddingdirectory' ),
                ] ) );
            }

            $post_id = isset( $_POST['vendor_post_id'] ) ? absint( $_POST['vendor_post_id'] ) : absint( self:: current_vendor_post_id() );

            if( $post_id <= 0 || ! self:: vendor_user_owns_post( $post_id, absint( get_current_user_id() ) ) ){

                die( wp_json_encode( [

                    'notice'    => absint( '2' ),

                    'message'   => esc_attr__( 'Unable to resolve your vendor profile.', 'sdweddingdirectory' ),
                ] ) );
            }

            $payload = [

                'tier_count' => isset( $_POST['tier_count'] ) ? absint( $_POST['tier_count'] ) : absint( '1' ),

                'tiers'      => [],
            ];

            $tier_titles = isset( $_POST['tier_title'] ) && parent::_is_array( $_POST['tier_title'] ) ? $_POST['tier_title'] : [];

            $tier_prices = isset( $_POST['tier_price'] ) && parent::_is_array( $_POST['tier_price'] ) ? $_POST['tier_price'] : [];

            $tier_hours  = isset( $_POST['tier_hours'] ) && parent::_is_array( $_POST['tier_hours'] ) ? $_POST['tier_hours'] : [];

            $tier_items  = isset( $_POST['tier_items'] ) && parent::_is_array( $_POST['tier_items'] ) ? $_POST['tier_items'] : [];

            for( $tier = 1; $tier <= 3; $tier++ ){

                $payload['tiers'][ $tier ] = [

                    'title' => isset( $tier_titles[ $tier ] ) ? $tier_titles[ $tier ] : '',

                    'price' => isset( $tier_prices[ $tier ] ) ? $tier_prices[ $tier ] : '',

                    'hours' => isset( $tier_hours[ $tier ] ) ? $tier_hours[ $tier ] : '',

                    'items' => isset( $tier_items[ $tier ] ) && parent::_is_array( $tier_items[ $tier ] ) ? $tier_items[ $tier ] : [],
                ];
            }

            $sanitized = self:: sanitize_pricing_payload( $payload );

            update_post_meta( $post_id, sanitize_key( 'sdwd_vendor_pricing_tiers' ), $sanitized );

            die( wp_json_encode( [

                'notice'    => absint( '1' ),

                'message'   => esc_attr__( 'Pricing options updated successfully.', 'sdweddingdirectory' ),
            ] ) );
        }

        /**
         *  Tab Name
         *  --------
         */
        public static function tab_name(){

            return      esc_attr__( 'Set Pricing', 'sdweddingdirectory' );
        }

        /**
         *  Get Tabs
         *  --------
         */
        public static function get_tabs( $args = [] ){

            return  array_merge( $args, [

                        'set-pricing'        =>  [

                            'id'        =>  esc_attr( parent:: _rand() ),

                            'name'      =>  esc_attr( self:: tab_name() ),

                            'callback'  =>  [ 'class' => __CLASS__, 'member' => 'tab_content' ],
                        ]

                    ] );
        }

        /**
         *  Tab Content (rendered inside My Profile tabs)
         *  ---------------------------------------------
         */
        public static function tab_content(){

            $vendor_post_id = absint( self:: current_vendor_post_id() );

            if( $vendor_post_id <= 0 ){

                printf(
                    '<div class="alert alert-warning" role="alert"><i class="fa fa-info-circle"></i> %1$s</div>',
                    esc_attr__( 'Your vendor profile is not ready yet. Please try again after refreshing the page.', 'sdweddingdirectory' )
                );

            }else{

                $pricing_data = self:: get_saved_pricing_data( $vendor_post_id );

                $tier_count = absint( $pricing_data['tier_count'] );

                $tiers = parent::_is_array( $pricing_data['tiers'] ) ? $pricing_data['tiers'] : [];

                ?>
                <div class="sdwd-pricing-editor">
                    <form id="sdwd_vendor_set_pricing_form" method="post">
                        <div class="row align-items-end mb-4">
                            <div class="col-md-4">
                                <label class="form-label" for="sdwd_tier_count"><?php esc_html_e( 'How many pricing options do you want to offer?', 'sdweddingdirectory' ); ?></label>
                                <select class="form-control" id="sdwd_tier_count" name="tier_count">
                                    <option value="1" <?php selected( $tier_count, 1 ); ?>><?php esc_html_e( '1 Package', 'sdweddingdirectory' ); ?></option>
                                    <option value="2" <?php selected( $tier_count, 2 ); ?>><?php esc_html_e( '2 Packages', 'sdweddingdirectory' ); ?></option>
                                    <option value="3" <?php selected( $tier_count, 3 ); ?>><?php esc_html_e( '3 Packages', 'sdweddingdirectory' ); ?></option>
                                </select>
                            </div>
                        </div>

                        <div class="row g-4" id="sdwd_vendor_pricing_cards">
                            <?php for( $tier = 1; $tier <= 3; $tier++ ){
                                $tier_data = isset( $tiers[ $tier ] ) && parent::_is_array( $tiers[ $tier ] ) ? $tiers[ $tier ] : [];
                                $tier_title = isset( $tier_data['title'] ) ? esc_attr( $tier_data['title'] ) : '';
                                $tier_price = isset( $tier_data['price'] ) ? esc_attr( $tier_data['price'] ) : '';
                                $tier_hours = isset( $tier_data['hours'] ) ? esc_attr( $tier_data['hours'] ) : '';
                                $tier_items = isset( $tier_data['items'] ) && parent::_is_array( $tier_data['items'] ) ? $tier_data['items'] : [];
                            ?>
                            <div class="col-xl-4 col-lg-6 sdwd-pricing-tier-column<?php echo $tier > $tier_count ? ' sdwd-tier-hidden' : ''; ?>" data-tier="<?php echo esc_attr( $tier ); ?>">
                                <div class="pricing-table-wrap sdwd-tier-editor-card">
                                    <div class="sdwd-tier-head">
                                        <label class="form-label"><?php esc_html_e( 'Package Title', 'sdweddingdirectory' ); ?></label>
                                        <input type="text" class="form-control" name="tier_title[<?php echo esc_attr( $tier ); ?>]" value="<?php echo $tier_title; ?>" />
                                    </div>
                                    <div class="sdwd-tier-price mt-3">
                                        <label class="form-label"><?php esc_html_e( 'Price', 'sdweddingdirectory' ); ?></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><?php echo esc_html( sdweddingdirectory_currenty() ); ?></span>
                                            <input type="text" class="form-control" name="tier_price[<?php echo esc_attr( $tier ); ?>]" value="<?php echo $tier_price; ?>" placeholder="<?php esc_attr_e( 'e.g. 1500', 'sdweddingdirectory' ); ?>" />
                                        </div>
                                    </div>
                                    <div class="sdwd-tier-hours mt-3">
                                        <label class="form-label"><?php esc_html_e( 'Hours', 'sdweddingdirectory' ); ?></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-clock-o"></i></span>
                                            <input type="text" class="form-control" name="tier_hours[<?php echo esc_attr( $tier ); ?>]" value="<?php echo $tier_hours; ?>" placeholder="<?php esc_attr_e( 'e.g. 4', 'sdweddingdirectory' ); ?>" />
                                        </div>
                                    </div>
                                    <div class="sdwd-tier-features mt-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <strong><?php esc_html_e( 'Features', 'sdweddingdirectory' ); ?></strong>
                                            <span class="small text-muted"><?php esc_html_e( 'Included?', 'sdweddingdirectory' ); ?></span>
                                        </div>
                                        <ul class="list-unstyled mb-0 sdwd-feature-edit-list">
                                            <?php for( $row = 1; $row <= 10; $row++ ){
                                                $row_data = isset( $tier_items[ $row - 1 ] ) && parent::_is_array( $tier_items[ $row - 1 ] ) ? $tier_items[ $row - 1 ] : [];
                                                $row_text = isset( $row_data['text'] ) ? esc_attr( $row_data['text'] ) : '';
                                                $row_included = isset( $row_data['included'] ) ? absint( $row_data['included'] ) : 0;
                                            ?>
                                            <li class="sdwd-feature-edit-row">
                                                <div class="sdwd-feature-icon-preview <?php echo $row_included ? 'is-included' : 'is-excluded'; ?>">
                                                    <i class="fa <?php echo $row_included ? 'fa-check' : 'fa-times'; ?>"></i>
                                                </div>
                                                <input type="text" class="form-control" name="tier_items[<?php echo esc_attr( $tier ); ?>][<?php echo esc_attr( $row ); ?>][text]" value="<?php echo $row_text; ?>" placeholder="<?php esc_attr_e( 'Feature detail', 'sdweddingdirectory' ); ?>" />
                                                <label class="sdwd-feature-checkbox">
                                                    <input type="checkbox" name="tier_items[<?php echo esc_attr( $tier ); ?>][<?php echo esc_attr( $row ); ?>][included]" value="1" <?php checked( $row_included, 1 ); ?> />
                                                </label>
                                            </li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>

                        <input type="hidden" name="vendor_post_id" value="<?php echo esc_attr( $vendor_post_id ); ?>" />
                        <?php wp_nonce_field( 'sdwd_vendor_set_pricing_save', 'sdwd_vendor_set_pricing_nonce', true, true ); ?>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-default"><?php esc_html_e( 'Save Pricing', 'sdweddingdirectory' ); ?></button>
                        </div>
                    </form>
                </div>
                <?php
            }
        }

        /**
         *  Dashboard Page (legacy standalone — kept for backward compatibility)
         *  --------------------------------------------------------------------
         */
        public static function dashboard_page( $args = [] ){

            if( ! parent::_is_array( $args ) ){
                return;
            }

            extract( wp_parse_args( $args, [

                'layout'    => absint( '1' ),

                'post_id'   => absint( '0' ),

                'page'      => '',

            ] ) );

            if( empty( $page ) || $page !== esc_attr( 'vendor-pricing' ) ){
                return;
            }

            $vendor_post_id = absint( self:: current_vendor_post_id() );

            ?><div class="container"><?php

                SDWeddingDirectory_Dashboard::dashboard_page_header(
                    esc_attr__( 'Set Pricing', 'sdweddingdirectory' ),
                    esc_attr__( 'Build one to three pricing packages and control what is included in each offer.', 'sdweddingdirectory' )
                );

                if( $vendor_post_id <= 0 ){

                    printf(
                        '<div class="alert alert-warning" role="alert"><i class="fa fa-info-circle"></i> %1$s</div>',
                        esc_attr__( 'Your vendor profile is not ready yet. Please try again after refreshing the page.', 'sdweddingdirectory' )
                    );

                }else{

                    $pricing_data = self:: get_saved_pricing_data( $vendor_post_id );

                    $tier_count = absint( $pricing_data['tier_count'] );

                    $tiers = parent::_is_array( $pricing_data['tiers'] ) ? $pricing_data['tiers'] : [];

                    ?>
                    <div class="card-shadow sdwd-pricing-editor">
                        <div class="card-shadow-body">
                            <form id="sdwd_vendor_set_pricing_form" method="post">
                                <div class="row align-items-end mb-4">
                                    <div class="col-md-4">
                                        <label class="form-label" for="sdwd_tier_count"><?php esc_html_e( 'How many pricing options do you want to offer?', 'sdweddingdirectory' ); ?></label>
                                        <select class="form-control" id="sdwd_tier_count" name="tier_count">
                                            <option value="1" <?php selected( $tier_count, 1 ); ?>><?php esc_html_e( '1 Package', 'sdweddingdirectory' ); ?></option>
                                            <option value="2" <?php selected( $tier_count, 2 ); ?>><?php esc_html_e( '2 Packages', 'sdweddingdirectory' ); ?></option>
                                            <option value="3" <?php selected( $tier_count, 3 ); ?>><?php esc_html_e( '3 Packages', 'sdweddingdirectory' ); ?></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row g-4" id="sdwd_vendor_pricing_cards">
                                    <?php for( $tier = 1; $tier <= 3; $tier++ ){ 
                                        $tier_data = isset( $tiers[ $tier ] ) && parent::_is_array( $tiers[ $tier ] ) ? $tiers[ $tier ] : [];
                                        $tier_title = isset( $tier_data['title'] ) ? esc_attr( $tier_data['title'] ) : '';
                                        $tier_price = isset( $tier_data['price'] ) ? esc_attr( $tier_data['price'] ) : '';
                                        $tier_hours = isset( $tier_data['hours'] ) ? esc_attr( $tier_data['hours'] ) : '';
                                        $tier_items = isset( $tier_data['items'] ) && parent::_is_array( $tier_data['items'] ) ? $tier_data['items'] : [];
                                    ?>
                                    <div class="col-xl-4 col-lg-6 sdwd-pricing-tier-column<?php echo $tier > $tier_count ? ' sdwd-tier-hidden' : ''; ?>" data-tier="<?php echo esc_attr( $tier ); ?>">
                                        <div class="pricing-table-wrap sdwd-tier-editor-card">
                                            <div class="sdwd-tier-head">
                                                <label class="form-label"><?php esc_html_e( 'Package Title', 'sdweddingdirectory' ); ?></label>
                                                <input type="text" class="form-control" name="tier_title[<?php echo esc_attr( $tier ); ?>]" value="<?php echo $tier_title; ?>" />
                                            </div>
                                            <div class="sdwd-tier-price mt-3">
                                                <label class="form-label"><?php esc_html_e( 'Price', 'sdweddingdirectory' ); ?></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><?php echo esc_html( sdweddingdirectory_currenty() ); ?></span>
                                                    <input type="text" class="form-control" name="tier_price[<?php echo esc_attr( $tier ); ?>]" value="<?php echo $tier_price; ?>" placeholder="<?php esc_attr_e( 'e.g. 1500', 'sdweddingdirectory' ); ?>" />
                                                </div>
                                            </div>
                                            <div class="sdwd-tier-hours mt-3">
                                                <label class="form-label"><?php esc_html_e( 'Hours', 'sdweddingdirectory' ); ?></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fa fa-clock-o"></i></span>
                                                    <input type="text" class="form-control" name="tier_hours[<?php echo esc_attr( $tier ); ?>]" value="<?php echo $tier_hours; ?>" placeholder="<?php esc_attr_e( 'e.g. 4', 'sdweddingdirectory' ); ?>" />
                                                </div>
                                            </div>
                                            <div class="sdwd-tier-features mt-3">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <strong><?php esc_html_e( 'Features', 'sdweddingdirectory' ); ?></strong>
                                                    <span class="small text-muted"><?php esc_html_e( 'Included?', 'sdweddingdirectory' ); ?></span>
                                                </div>
                                                <ul class="list-unstyled mb-0 sdwd-feature-edit-list">
                                                    <?php for( $row = 1; $row <= 10; $row++ ){
                                                        $row_data = isset( $tier_items[ $row - 1 ] ) && parent::_is_array( $tier_items[ $row - 1 ] ) ? $tier_items[ $row - 1 ] : [];
                                                        $row_text = isset( $row_data['text'] ) ? esc_attr( $row_data['text'] ) : '';
                                                        $row_included = isset( $row_data['included'] ) ? absint( $row_data['included'] ) : 0;
                                                    ?>
                                                    <li class="sdwd-feature-edit-row">
                                                        <div class="sdwd-feature-icon-preview <?php echo $row_included ? 'is-included' : 'is-excluded'; ?>">
                                                            <i class="fa <?php echo $row_included ? 'fa-check' : 'fa-times'; ?>"></i>
                                                        </div>
                                                        <input type="text" class="form-control" name="tier_items[<?php echo esc_attr( $tier ); ?>][<?php echo esc_attr( $row ); ?>][text]" value="<?php echo $row_text; ?>" placeholder="<?php esc_attr_e( 'Feature detail', 'sdweddingdirectory' ); ?>" />
                                                        <label class="sdwd-feature-checkbox">
                                                            <input type="checkbox" name="tier_items[<?php echo esc_attr( $tier ); ?>][<?php echo esc_attr( $row ); ?>][included]" value="1" <?php checked( $row_included, 1 ); ?> />
                                                        </label>
                                                    </li>
                                                    <?php } ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </div>

                                <input type="hidden" name="vendor_post_id" value="<?php echo esc_attr( $vendor_post_id ); ?>" />
                                <?php wp_nonce_field( 'sdwd_vendor_set_pricing_save', 'sdwd_vendor_set_pricing_nonce', true, true ); ?>

                                <div class="d-flex justify-content-end mt-4">
                                    <button type="submit" class="btn btn-default"><?php esc_html_e( 'Save Pricing', 'sdweddingdirectory' ); ?></button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php
                }
            ?></div><?php
        }
    }

    /**
     *  SDWeddingDirectory - Vendor Pricing Dashboard
     *  --------------------------------------------
     */
    SDWeddingDirectory_Dashboard_Vendor_Pricing::get_instance();
}
