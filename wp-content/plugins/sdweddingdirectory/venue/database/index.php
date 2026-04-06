<?php
/**
 *  Database information here
 *  -------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Vendor_Venue_Database' ) && class_exists( 'SDWeddingDirectory_Form_Tabs' ) ){

    /**
     *  Database information here
     *  -------------------------
     */
    class SDWeddingDirectory_Vendor_Venue_Database extends SDWeddingDirectory_Form_Tabs{

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

            /**
             *  22. Venue Front End Page [ Menu Show in Popup ]
             *  -------------------------------------------------
             */
            add_action( 'wp_footer', [ $this, 'venue_menu_popup_content' ] );
        }

        /**
         *  Get FAQ's
         *  ---------
         */
        public static function get_venue_faqs( $args = [] ){

            /**
             *  Extract Args
             *  ------------
             */
            extract( wp_parse_args( $args, array(

                'post_id'           =>  absint( '0' ),

                'faq_title'         =>  '',

                'faq_description'   =>  '',

            ) ) );

            $_get_data              =   '';

            $_body_content          =   '';

            $_create_collapse       =   false;

            $_get_data              =   '';

            $_body_content          =   '';

            $_section_id            =   esc_attr( 'venue_faqs' );

            $_section_title         =   esc_attr__( 'Write FAQ', 'sdweddingdirectory-venue' );

            $_faq_title             =   esc_attr__( 'Faq Title', 'sdweddingdirectory-venue' );

            $_faq_description       =   esc_attr__( 'Faq Description', 'sdweddingdirectory-venue' );

            /**
             *  Is empty!
             *  ---------
             */
            if( empty( $post_id ) ){

                $_body_content      .=      '<div class="col collpase_section">';

                    $_body_content      .=      '<div class="card mb-4">';

                        $_body_content      .=      self:: removed_section_icon( true );

                            $_body_content      .=      '<div class="card-body">';

                            $_body_content      .=      parent:: create_input_field( array(

                                                                'row'           =>  array(

                                                                                        'start'     =>  true,

                                                                                        'end'       =>  false,
                                                                                    ),

                                                                'column'        =>  array(

                                                                                        'start'     =>  true,

                                                                                        'end'       =>  true,

                                                                                        'grid'      =>  sanitize_html_class( 'col-md' )
                                                                                    ),

                                                                'name'          =>  esc_attr( 'faq_title' ),

                                                                'id'            =>  esc_attr( 'faq_title' ),

                                                                'class'         =>  sanitize_html_class( 'faq_title' ),

                                                                'placeholder'   =>  esc_attr( $_faq_title ),

                                                                'value'         =>   $faq_title
                                                        ) );

                            $_body_content      .=      parent:: create_textarea( array(

                                                            'row'           =>  array(

                                                                                    'end'     =>  true,

                                                                                    'start'   =>  false,
                                                                                ),

                                                            'column'        =>  array(

                                                                                    'grid'      =>      absint( '12' ),

                                                                                    'start'     =>      true,

                                                                                    'end'       =>      true,
                                                                                ),

                                                            'name'          =>   esc_attr( 'faq_description' ),

                                                            'class'         =>   sanitize_html_class( 'faq_description' ),

                                                            'placeholder'   =>   esc_attr( $_faq_description ),

                                                            'height'        =>   absint( '130' ),

                                                            'value'         =>   $faq_description

                                                        ) );

                        $_body_content      .=      '</div>';

                    $_body_content      .=      '</div>';

                $_body_content      .=      '</div>';

                /**
                 *  Return Data
                 *  -----------
                 */
                return $_body_content;

            }else{

                $_have_data          =   get_post_meta( absint( $post_id ), sanitize_key( 'venue_faq' ), true );

                /**
                 *  Have Data ?
                 *  -----------
                 */
                if( parent:: _is_array( $_have_data ) ){

                    foreach ( $_have_data as $key => $value ) {
                    
                        /**
                         *  Create Collpase
                         *  ---------------
                         */
                        $_get_data  .=  call_user_func( [ __CLASS__, __FUNCTION__ ], $value );
                    }
                }
            }

            /**
             *  Return : Data
             *  -------------
             */
            return  $_get_data;
        }

        /**
         *  -------------------------------
         *  Get Venue Team Section Fields
         *  -------------------------------
         */
        public static function get_venue_team( $args = [] ){

            /**
             *  Extract Args
             *  ------------
             */
            extract( wp_parse_args( $args, array(

                'post_id'       =>  absint( '0' ),

                'image'         =>  '',

                'first_name'    =>  '',

                'last_name'     =>  '',

                'position'      =>  '',

                'bio'           =>  '',

            ) ) );

            $_create_collapse       =   false;

            $_get_data              =   '';

            $_body_content          =   '';

            /**
             *  Database Name + Heading Title
             *  -----------------------------
             */
            $_section_id            =   esc_attr( 'venue_team' );

            $_section_title         =   esc_attr__( 'Meet Our Team', 'sdweddingdirectory-venue' );
            
            /**
             *  Translation Ready String
             *  ------------------------
             */
            $_first_name            =   esc_attr__( 'First Name', 'sdweddingdirectory-venue' );

            $_last_name             =   esc_attr__( 'Last Name', 'sdweddingdirectory-venue' );

            $_position              =   esc_attr__( 'Team Position', 'sdweddingdirectory-venue' );

            $_bio                   =   esc_attr__( 'About Your Team', 'sdweddingdirectory-venue' );

            $_upload_btn_text       =   esc_attr__( 'Upload Image', 'sdweddingdirectory-venue' );

            $_media_frame_btn_text  =   esc_attr__( 'Select Team Image', 'sdweddingdirectory-venue' );

            $_media_frame_text      =   esc_attr__( 'Upload Your Team Image', 'sdweddingdirectory-venue' );

            /**
             *  Is empty!
             *  ---------
             */
            if( empty( $post_id ) ){

                $_body_content      .=      '<div class="col collpase_section">';

                    $_body_content      .=      '<div class="card mb-4">';

                    $_body_content      .=      self:: removed_section_icon( true );

                        $_body_content      .=      '<div class="card-body">';

                            $_body_content      .=      '<div class="row">';

                                /**
                                 *  Get Image
                                 *  ---------
                                 */
                                $_body_content      .=      apply_filters( 'sdweddingdirectory/field/single-media', [

                                                                'column'                =>      array(

                                                                                                    'start'     =>  true,

                                                                                                    'end'       =>  true,

                                                                                                    'grid'      =>  esc_attr( 'col-lg-2 col-md-12 col-6' ),

                                                                                                    'class'     =>  sanitize_html_class( 'mb-3' )
                                                                                                ),

                                                                'icon_layout'           =>      absint( '1' ),

                                                                'image_class'           =>      sanitize_html_class( 'rounded-circle' ),

                                                                'media_id'              =>      absint( $image ),

                                                                'image_size'            =>      esc_attr( 'thumbnail' ),

                                                                'default_img'           =>      parent:: placeholder( 'couple-bride-image' ),

                                                                'input_class'           =>      sanitize_html_class( 'store_media_ids' )

                                                            ] );

                                $_body_content      .=      '<div class="col-lg-10 col-md-12">';

                                    $_body_content      .=      parent:: create_input_field( array(

                                                                        'row'           =>  array(

                                                                                                'start'     =>  true,

                                                                                                'end'       =>  false,
                                                                                            ),

                                                                        'column'        =>  array(

                                                                                                'start'     =>  true,

                                                                                                'end'       =>  true,

                                                                                                'grid'      =>  esc_attr( 'col-md-4 col-12' )
                                                                                            ),

                                                                        'name'          =>  esc_attr( 'first_name' ),

                                                                        'id'            =>  esc_attr( 'first_name' ),

                                                                        'class'         =>  sanitize_html_class( 'first_name' ),

                                                                        'placeholder'   =>  esc_attr( $_first_name ),

                                                                        'value'         =>  $first_name
                                                                ) );

                                    $_body_content      .=      parent:: create_input_field( array(

                                                                        'column'        =>  array(

                                                                                                'start'     =>  true,

                                                                                                'end'       =>  true,

                                                                                                'grid'      =>  esc_attr( 'col-md-4 col-12' )
                                                                                            ),

                                                                        'name'          =>  esc_attr( 'last_name' ),

                                                                        'id'            =>  esc_attr( 'last_name' ),

                                                                        'class'         =>  sanitize_html_class( 'last_name' ),

                                                                        'placeholder'   =>  esc_attr( $_last_name ),

                                                                        'value'         =>  $last_name
                                                                ) );

                                    $_body_content      .=      parent:: create_input_field( array(

                                                                        'column'        =>  array(

                                                                                                'start'     =>  true,

                                                                                                'end'       =>  true,

                                                                                                'grid'      =>  esc_attr( 'col-md-4 col-12' )
                                                                                            ),

                                                                        'name'          =>  esc_attr( 'position' ),

                                                                        'id'            =>  esc_attr( 'position' ),

                                                                        'class'         =>  sanitize_html_class( 'position' ),

                                                                        'placeholder'   =>  esc_attr( $_position ),

                                                                        'value'         =>  $position
                                                                ) );

                                    $_body_content      .=      parent:: create_textarea_simple( array(

                                                                    'row'           =>  array(

                                                                                            'start'     =>  false,

                                                                                            'end'       =>  true,
                                                                                        ),

                                                                    'column'        =>  array(

                                                                                            'grid'      =>  esc_attr( 'col-12' ),

                                                                                            'start'     =>  true,

                                                                                            'end'       =>  true,
                                                                                        ),

                                                                    'name'          =>   esc_attr( 'bio' ),

                                                                    'rows'          =>   absint( '3' ),

                                                                    'limit'         =>   absint( '400' ),

                                                                    'class'         =>   sanitize_html_class( 'bio' ),

                                                                    'placeholder'   =>   esc_attr( $_bio ),

                                                                    'value'         =>   $bio

                                                                ) );

                                $_body_content      .=      '</div>';

                            $_body_content      .=      '</div>';

                        $_body_content      .=      '</div>';

                    $_body_content      .=      '</div>';

                $_body_content      .=      '</div>';


                /**
                 *  Return Data
                 *  -----------
                 */
                return $_body_content;

            }else{

                $_have_data          =   get_post_meta( absint( $post_id ), sanitize_key( 'venue_team' ), true );

                /**
                 *  Have Data ?
                 *  -----------
                 */
                if( parent:: _is_array( $_have_data ) ){

                    foreach ( $_have_data as $key => $value ) {
                    
                        /**
                         *  Create Collpase
                         *  ---------------
                         */
                        $_get_data  .=  call_user_func( [ __CLASS__, __FUNCTION__ ], $value );
                    }
                }
            }

            /**
             *  Return : Data
             *  -------------
             */
            return  $_get_data;
        }

        /**
         *  Get Menu's
         *  ----------
         */
        public static function get_venue_menus( $args = [] ){

            /**
             *  Extract Args
             *  ------------
             */
            extract( wp_parse_args( $args, array(

                'post_id'           =>  absint( '0' ),

                'menu_title'        =>  '',

                'menu_price'        =>  '',

                'menu_file'         =>  ''

            ) ) );

            $_create_collapse       =   false;

            $_get_data              =   '';

            $_body_content          =   '';

            $_section_id            =   esc_attr( 'venue_menu' );

            $_section_title         =   esc_attr__( 'Venue Menu', 'sdweddingdirectory-venue' );

            $_menu_title            =   esc_attr__( 'Name', 'sdweddingdirectory-venue' );

            $_menu_description      =   esc_attr__( 'Overview', 'sdweddingdirectory-venue' );

            $_menu_price            =   esc_attr__( 'Price', 'sdweddingdirectory-venue' );

            /**
             *  Is empty!
             *  ---------
             */
            if( empty( $post_id ) ){

                $_body_content      .=      '<div class="col collpase_section">';

                    $_body_content      .=      '<div class="card mb-4">';

                    $_body_content      .=      self:: removed_section_icon( true );

                        $_body_content      .=      '<div class="card-body">';

                            $_body_content      .=      parent:: create_input_field( array(

                                                            'row'           =>  array(

                                                                                    'start'     =>  true,

                                                                                    'end'       =>  false,
                                                                                ),

                                                            'column'        =>  array(

                                                                                    'start'     =>  true,

                                                                                    'end'       =>  true,

                                                                                    'grid'      =>  sanitize_html_class( 'col-md-12' )
                                                                                ),

                                                            'name'          =>  esc_attr( 'menu_title' ),

                                                            'id'            =>  esc_attr( 'menu_title' ),

                                                            'class'         =>  sanitize_html_class( 'menu_title' ),

                                                            'placeholder'   =>  esc_attr( $_menu_title ),

                                                            'limit'         =>  absint( '30' ),

                                                            'value'         =>  $menu_title

                                                        ) );

                            $_body_content      .=      parent:: create_input_field( array(

                                                            'column'        =>  array(

                                                                                    'start'     =>  true,

                                                                                    'end'       =>  true,

                                                                                    'grid'      =>  sanitize_html_class( 'col-md-12' )
                                                                                ),

                                                            'name'          =>  esc_attr( 'menu_price' ),

                                                            'id'            =>  esc_attr( 'menu_price' ),

                                                            'class'         =>  sanitize_html_class( 'menu_price' ),

                                                            'limit'         =>  absint( '10' ),

                                                            'placeholder'   =>  esc_attr( $_menu_price ),

                                                            'value'         =>  $menu_price

                                                        ) );

                            $_body_content      .=      parent:: pdf_upload_field( array(

                                                            'row'           =>  array(

                                                                                    'start'     =>  false,

                                                                                    'end'       =>  true,
                                                                                ),

                                                            'column'        =>  array(

                                                                                    'start'     =>  true,

                                                                                    'end'       =>  true,

                                                                                    'grid'      =>  sanitize_html_class( 'col-md-12' ),
                                                                                ),

                                                            'menu_file'     =>      absint( $menu_file ),

                                                        ) );

                        $_body_content      .=      '</div>';

                    $_body_content      .=      '</div>';

                $_body_content      .=      '</div>';


                /**
                 *  Return Data
                 *  -----------
                 */
                return $_body_content;

            }else{

                $_have_data          =   get_post_meta( absint( $post_id ), sanitize_key( 'venue_menu' ), true );

                /**
                 *  Have Data ?
                 *  -----------
                 */
                if( parent:: _is_array( $_have_data ) ){

                    foreach ( $_have_data as $key => $value ) {
                    
                        /**
                         *  Create Collpase
                         *  ---------------
                         */
                        $_get_data  .=  call_user_func( [ __CLASS__, __FUNCTION__ ], $value );
                    }
                }
            }

            /**
             *  Return : Data
             *  -------------
             */
            return  $_get_data;
        }

        /**
         *  Get Facilities
         *  --------------
         */
        public static function get_venue_facilities( $args = [] ){

           /**
             *  Extract Args
             *  ------------
             */
            extract( wp_parse_args( $args, array(

                'post_id'               =>  absint( '0' ),

                'facilities_cat'        =>  '',

                'facilities_name'       =>  '',

                'facilities_seating'    =>  '',

                'facilities_price'      =>  '',

                'facilities_desc'       =>  '',

                'facilities_gallery'    =>  '',

            ) ) );

            $_create_collapse       =   false;

            $_get_data              =   '';

            $_body_content          =   '';

            $_section_id            =   esc_attr( 'venue_facilities' );

            $_section_title         =   esc_attr__( 'Facilities Title', 'sdweddingdirectory-venue' );

            $_section_description   =   esc_attr__( 'Facilities Description', 'sdweddingdirectory-venue' );

            /**
             *  Fields
             *  ------
             */
            $_facilities_cat        =   esc_attr__( 'Room Category', 'sdweddingdirectory-venue' );

            $_facilities_name       =   esc_attr__( 'Room Name', 'sdweddingdirectory-venue' );

            $_facilities_seating    =   esc_attr__( 'Seating Capacity', 'sdweddingdirectory-venue' );

            $_facilities_price      =   esc_attr__( 'Price', 'sdweddingdirectory-venue' );

            $_facilities_desc       =   esc_attr__( 'Overview', 'sdweddingdirectory-venue' );

            /**
             *  Is empty!
             *  ---------
             */
            if( empty( $post_id ) ){

                /**
                 *  Column Start
                 *  ------------
                 */
                $_body_content      .=      '<div class="col collpase_section">';

                    $_body_content      .=      '<div class="card mb-4 text-center">';

                    $_body_content      .=      self:: removed_section_icon( true );

                        $_body_content      .=      '<div class="card-body">';

                            $_body_content      .=      '<div class="row">';

                                $_body_content      .=      '<div class="col-md-12">';

                                    $_body_content      .=      parent:: create_input_field( array(

                                                                    'row'           =>  array(

                                                                                            'start'     =>  true,

                                                                                            'end'       =>  false,
                                                                                        ),

                                                                    'column'        =>  array(

                                                                                            'start'     =>  true,

                                                                                            'end'       =>  true,

                                                                                            'grid'      =>  esc_attr( 'col-lg-3 col-md-12' ),
                                                                                        ),

                                                                    'name'          =>  esc_attr( 'facilities_cat' ),

                                                                    'id'            =>  esc_attr( 'facilities_cat' ),

                                                                    'class'         =>  sanitize_html_class( 'facilities_cat' ),

                                                                    'placeholder'   =>  esc_attr( $_facilities_cat ),

                                                                    'value'         =>  $facilities_cat

                                                                ) );

                                    $_body_content      .=      parent:: create_input_field( array(

                                                                    'column'        =>  array(

                                                                                            'start'     =>  true,

                                                                                            'end'       =>  true,

                                                                                            'grid'      =>  esc_attr( 'col-lg-3 col-md-12' ),
                                                                                        ),

                                                                    'name'          =>  esc_attr( 'facilities_name' ),

                                                                    'id'            =>  esc_attr( 'facilities_name' ),

                                                                    'class'         =>  sanitize_html_class( 'facilities_name' ),

                                                                    'placeholder'   =>  esc_attr( $_facilities_name ),

                                                                    'value'         =>  $facilities_name

                                                                ) );

                                    $_body_content      .=      parent:: create_input_field( array(

                                                                    'column'        =>  array(

                                                                                            'start'     =>  true,

                                                                                            'end'       =>  true,

                                                                                            'grid'      =>  esc_attr( 'col-lg-3 col-md-12' ),
                                                                                        ),

                                                                    'name'          =>  esc_attr( 'facilities_seating' ),

                                                                    'id'            =>  esc_attr( 'facilities_seating' ),

                                                                    'class'         =>  sanitize_html_class( 'facilities_seating' ),

                                                                    'placeholder'   =>  esc_attr( $_facilities_seating ),

                                                                    'value'         =>  $facilities_seating

                                                                ) );

                                    $_body_content      .=      parent:: create_input_field( array(

                                                                    'column'        =>  array(

                                                                                            'start'     =>  true,

                                                                                            'end'       =>  true,

                                                                                            'grid'      =>  esc_attr( 'col-lg-3 col-md-12' ),
                                                                                        ),

                                                                    'name'          =>  esc_attr( 'facilities_price' ),

                                                                    'id'            =>  esc_attr( 'facilities_price' ),

                                                                    'class'         =>  sanitize_html_class( 'facilities_price' ),

                                                                    'placeholder'   =>  esc_attr( $_facilities_price ),

                                                                    'value'         =>  $facilities_price

                                                                ) );

                                    $_body_content      .=      parent:: create_textarea_simple( array(

                                                                    'row'           =>  array(

                                                                                            'start'     =>  false,

                                                                                            'end'       =>  true,
                                                                                        ),

                                                                    'column'        =>  array(

                                                                                            'grid'      =>      absint( '12' ),

                                                                                            'start'     =>      true,

                                                                                            'end'       =>      true,
                                                                                        ),

                                                                    'name'          =>   esc_attr( 'facilities_desc' ),

                                                                    'class'         =>   sanitize_html_class( 'facilities_desc' ),

                                                                    'placeholder'   =>   esc_attr( $_facilities_desc ),

                                                                    'value'         =>   esc_attr( $facilities_desc ),

                                                                    'rows'          =>   absint( '4' ),

                                                                    'limit'         =>   absint( '500' )

                                                                ) );

                                $_body_content      .=      '</div>';

                                $_body_content      .=      '<div class="col-md-12">';

                                    $_body_content      .=      '<div class="row text-start">';

                                        $_body_content      .=      apply_filters( 'sdweddingdirectory/field/gallery-upload', [

                                                                        'media_ids'     =>  $facilities_gallery,

                                                                        'button_class'  =>  'btn-primary btn-sm'

                                                                    ] );

                                        $_body_content      .=      '</div>';
                                
                                $_body_content      .=      '</div>';

                            $_body_content      .=      '</div><!-- End Row -->';

                        $_body_content      .=      '</div><!-- Card Body -->';

                    $_body_content      .=      '</div><!-- Card -->';

                $_body_content      .=      '</div><!-- col -->';

                /**
                 *  Return Data
                 *  -----------
                 */
                return $_body_content;

            }else{

                $_have_data          =   get_post_meta( absint( $post_id ), sanitize_key( 'venue_facilities' ), true );

                /**
                 *  Have Data ?
                 *  -----------
                 */
                if( parent:: _is_array( $_have_data ) ){

                    foreach ( $_have_data as $key => $value ) {
                    
                        /**
                         *  Create Collpase
                         *  ---------------
                         */
                        $_get_data  .=  call_user_func( [ __CLASS__, __FUNCTION__ ], $value );
                    }
                }
            }

            /**
             *  Return : Data
             *  -------------
             */
            return  $_get_data;
        }

        /**
         *  Post Update Status Check with Setting Option Using
         *  --------------------------------------------------
         */
        public static function sdweddingdirectory_lisitng_post_status(){

            $_status_option = sdweddingdirectory_option( 'new_venue_status' );

            if( $_status_option == absint( '0' ) ){

                return esc_attr( 'pending' );

            }elseif( $_status_option == absint( '1' ) ){

                return esc_attr( 'publish' );
                
            }else{

                return esc_attr( 'publish' );
            }
        }

        /**
         *  Post Status Badge
         *  -----------------
         */
        public static function venue_status_badget( $post_id = '' ){

            if( empty( $post_id ) ){
                return;
            }

            $_string = get_post_status( $post_id );

            if( empty( $_string ) ){
                return;
            }

            if( $_string == esc_attr( 'trash' ) ){

                return sprintf( '<span class="badge badge-danger">%1$s</span>', esc_attr__( 'Deactivated', 'sdweddingdirectory-venue' ) );

            }elseif( $_string ==  esc_attr( 'pending' ) ){

                return sprintf( '<span class="badge badge-pending">%1$s</span>', esc_attr__( 'Waiting for approval', 'sdweddingdirectory-venue' ) );

            }elseif( $_string == esc_attr( 'publish' ) ){

                return sprintf( '<span class="badge badge-success">%1$s</span>', esc_attr__( 'Approved', 'sdweddingdirectory-venue' ) );

            }elseif( $_string == esc_attr( 'draft' ) ){

                return sprintf( '<span class="badge badge-info">%1$s</span>', esc_attr__( 'In progress', 'sdweddingdirectory-venue' ) );
            }
        }

        /**
         *  Dropdown list for action ( My Venue Page )
         *  --------------------------------------------
         */
        public static function dropdown_list( $post_id = '', $args = '' ){

            $_condition_1  = parent:: _is_array( $args );

            $_condition_2  = isset( $post_id ) && $post_id !== '' && $post_id !== absint( '0' );

            $output        = '';

            /**
             *  Condition Check
             *  ---------------
             */
            if( $_condition_1 && $_condition_2 ){

                extract( $args );

                /**
                 *  1. Edit
                 *  -------
                 */
                if( isset( $edit ) && $edit == true ){
                    
                    $output .=

                    sprintf( '<a class="dropdown-item" href="%1$s"><i class="fa fa-pencil"></i> %2$s</a>',

                            /**
                             *  1. Edit Page Link
                             *  -----------------
                             */
                            esc_url( 

                                add_query_arg(

                                    array( 'venue_id' => absint( $post_id )  ),

                                    apply_filters( 'sdweddingdirectory/vendor-menu/page-link', esc_attr( 'update-venue' ) ),
                                )   
                            ),

                            /**
                             *  2. Translation Ready String.
                             *  ----------------------------
                             */
                            esc_attr__( 'Edit', 'sdweddingdirectory-venue' )
                    );
                }

                /**
                 *  2. Preview
                 *  ----------
                 */
                if( isset( $preview ) && $preview == true ){

                    $output .=

                    sprintf( '<a class="dropdown-item" target="_blank" href="%1$s"><i class="fa fa-eye"></i> %2$s</a>',

                            /**
                             *  1. Edit Page Link
                             *  -----------------
                             */
                            esc_url( get_the_permalink( absint( $post_id ) ) ),

                            /**
                             *  2. Translation Ready String.
                             *  ----------------------------
                             */
                            esc_attr__( 'Preview', 'sdweddingdirectory-venue' )
                    );
                }

                /**
                 *  3. Duplicate
                 *  ------------
                 */
                if( isset( $duplicate ) && $duplicate == true ){

                    $output .=

                    sprintf( '<a class="dropdown-item duplicate" data-security="%4$s" data-post-id="%3$s" href="%1$s"><i class="fa fa-clone"></i> %2$s</a>',

                        // 1
                        esc_attr( 'javascript:' ),

                        /**
                         *  2. Translation Ready String.
                         *  ----------------------------
                         */
                        esc_attr__( 'Duplicate', 'sdweddingdirectory-venue' ),

                         /**
                         *  3. Post ID
                         *  ----------
                         */
                        absint( $post_id ),

                        /**
                         *  4. Duplicate Post Action Security
                         *  ---------------------------------
                         */
                        wp_create_nonce( 'duplicate_post-' . absint( $post_id ) )
                    );
                }

                /**
                 *  4. Publish
                 *  ----------
                 */
                if( isset( $publish ) && $publish == true ){

                    $output .=

                    sprintf( '<a class="dropdown-item publish" data-security="%4$s" data-post-id="%3$s" href="%1$s"><i class="fa fa-paper-plane"></i> %2$s</a>',

                        /**
                         *  1. Click ( No Redirection )
                         *  ---------------------------
                         */
                        esc_attr( 'javascript:' ),

                        /**
                         *  2. Translation Ready String.
                         *  ----------------------------
                         */
                        esc_attr__( 'Publish', 'sdweddingdirectory-venue' ),

                        /**
                         *  3. Post ID
                         *  ---------
                         */
                        absint( $post_id ),

                        /**
                         *  4. Publish Post Action Security
                         *  -------------------------------
                         */
                        wp_create_nonce( 'publish_post-' . absint( $post_id ) )
                    );
                }

                /**
                 *  5. Deactivate
                 *  -------------
                 */
                if( isset( $deactivate ) && $deactivate == true ){

                    $output .=  

                    sprintf( '<a class="dropdown-item deactivate" href="%1$s" data-security="%4$s" data-post-id="%3$s"><i class="fa fa-trash"></i> %2$s</a>',

                        /**
                         *  1. Click ( No Redirection )
                         *  ---------------------------
                         */
                        esc_attr( 'javascript:' ),

                        /**
                         *  2. Translation Ready String.
                         *  ----------------------------
                         */
                        esc_attr__( 'Deactivate', 'sdweddingdirectory-venue' ),

                        /**
                         *  3. Post ID
                         *  ---------
                         */
                        absint( $post_id ),

                        /**
                         *  4. Deactivate Post Action Security
                         *  ----------------------------------
                         */
                        wp_create_nonce( 'deactivate_post-' . absint( $post_id ) )
                    );
                }

                /**
                 *  5. Removed
                 *  -------------
                 */
                if( isset( $removed ) && $removed == true ){

                    $output .=  

                    sprintf( '<a class="dropdown-item delete" href="%1$s" data-security="%4$s" data-alert="%5$s" data-post-id="%3$s"><i class="fa fa-trash"></i> %2$s</a>',

                        /**
                         *  1. Click ( No Redirection )
                         *  ---------------------------
                         */
                        esc_attr( 'javascript:' ),

                        /**
                         *  2. Translation Ready String.
                         *  ----------------------------
                         */
                        esc_attr__( 'Delete', 'sdweddingdirectory-venue' ),

                        /**
                         *  3. Post ID
                         *  ----------
                         */
                        absint( $post_id ),

                        /**
                         *  4. Delete Post Action Security
                         *  ------------------------------
                         */
                        wp_create_nonce( 'delete_post-' . absint( $post_id ) ),

                        /**
                         *  5. Before Removed Confirm Alert + Translation Ready String
                         *  ----------------------------------------------------------
                         */
                        esc_attr__( '"By selecting "Delete Permanently",\' your venue will be removed permanently and cannot be undone. Are you sure you want to proceed?"', 'sdweddingdirectory-venue' ),
                    );
                }

                /**
                 *  5. Restore
                 *  ----------
                 */
                if( isset( $restore ) && $restore == true ){

                    $output .=  

                    sprintf( '<a class="dropdown-item restore" href="%1$s" data-security="%4$s" data-post-id="%3$s"><i class="fa fa-reply" aria-hidden="true"></i> %2$s</a>',

                        /**
                         *  1. Click ( No Redirection )
                         *  ---------------------------
                         */
                        esc_attr( 'javascript:' ),

                        /**
                         *  2. Translation Ready String.
                         *  ----------------------------
                         */
                        esc_attr__( 'Restore', 'sdweddingdirectory-venue' ),

                        /**
                         *  3. Post ID
                         *  ---------
                         */
                        absint( $post_id ),

                        /**
                         *  4. Restore Post Action Security
                         *  -------------------------------
                         */
                        wp_create_nonce( 'restore_post-' . absint( $post_id ) )
                    );
                }
            }

            /**
             *  Return Dropdown list
             *  --------------------
             */
            if( isset( $output ) && $output !== '' ){

                return

                sprintf( '  <div class="dropdown hover_out venue-action">
                                <button type="button" class="btn venue-action-link" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">...</button>

                                <div class="dropdown-menu">%1$s</div>
                            </div>', 

                            /**
                             *  1. Get Dropdown list
                             *  --------------------
                             */
                            $output
                );

            }else{

                return;
            }
        }

        /**
         *  Post Status to Update Buttons
         *  -----------------------------
         */
        public static function get_action_button( $post_id = '' ){

            if( empty( $post_id ) )
                return;

            $_string = get_post_status( $post_id );

            if( empty( $_string ) ){

                return;
            }

            if( $_string == esc_attr( 'trash' ) ){

                /**
                 *  Dropdown list showing actions
                 *  -----------------------------
                 */
                return  array(

                    'restore'       =>  true,

                    'removed'       =>  true,
                );

            }elseif( $_string ==  esc_attr( 'pending' ) ){

                /**
                 *  Dropdown list showing actions
                 *  -----------------------------
                 */
                return  array(

                    'edit'          =>  true,

                    'preview'       =>  false,

                    'duplicate'     =>  false,

                    'publish'       =>  true,

                    'removed'       =>  true,
                );

            }elseif( $_string == esc_attr( 'publish' ) ){

                /**
                 *  Dropdown list showing actions
                 *  -----------------------------
                 */
                return  array(

                    'edit'          =>  true,

                    'preview'       =>  true,

                    'duplicate'     =>  true,

                    'publish'       =>  false,

                    'deactivate'    =>  true,
                );

            }elseif( $_string == esc_attr( 'draft' ) ){

                /**
                 *  Dropdown list showing actions
                 *  -----------------------------
                 */
                return  array(

                    'edit'          =>  true,

                    'preview'       =>  false,

                    'duplicate'     =>  false,

                    'publish'       =>  true,

                    'removed'       =>  true
                );
            }
        }

        /**
         *  Venue Featured Image
         *  ----------------------
         */
        public static function venue_image( $post_id = '' ){

            if( empty( $post_id ) ){
                return;
            }

            /**
             *  Venue Image Size
             *  ------------------
             */
            $_venue_image_size    =   sanitize_key( 'sdweddingdirectory_img_600x450' );

            $_venue_image         =   get_the_post_thumbnail_url( absint( $post_id ), sanitize_key( $_venue_image_size ) );

            /**
             *  5. Get Venue Thumbnails
             *  -------------------------
             */
            if( $_venue_image != '' && ! empty( $_venue_image ) && $_venue_image !== absint( '0' ) ){

                return  esc_url( $_venue_image );

            }else{

                return  esc_url( parent:: placeholder( 'venue-post' ) );
            }
        }

        /**
         *  Display Venue
         *  ---------------
         */
        public static function display_venue( $post_query = [] ){
        
            if( empty( $post_query ) ){

                return;
            }

            $item = new WP_Query( $post_query );

            if( $item->have_posts() ){

                ?><ul class="list-unstyled my-venue"><?php

                    while ( $item->have_posts() ){  $item->the_post();

                        /**
                         *  Post ID
                         *  -------
                         */
                        $post_id    =   absint( get_the_ID() );

                        printf('<li>

                                    <!-- row -->
                                    <div class="row align-items-center" data-post-id="%1$s" id="venue_id_%6$s">

                                        <div class="col-md-3">

                                            <div class="d-flex">

                                                <a href="%3$s" target="_blank">

                                                    <img class="rounded" src="%4$s" alt="%2$s" />

                                                </a>

                                                <div class="have-badge">%7$s</div>

                                            </div>

                                        </div>

                                        <div class="col-md-4">

                                            <div class="title-venue">

                                                <h3 class="mb10"><a href="%3$s" target="_blank" class="title">%2$s</a></h3>

                                                %5$s

                                            </div>

                                        </div>

                                        <div class="col-md-5">

                                            <div class="info-venue">

                                                <div class="badge-wrap">

                                                    <div>%10$s</div>

                                                    <span class="badge badge btn-outline-secondary">%11$s</span>

                                                </div>

                                                <div class="badge-wrap">

                                                    <div>%12$s</div>
                                                    
                                                    %8$s <!-- Post Status -->

                                                </div>


                                                <div class="badge-wrap text-center">

                                                    <div>%13$s</div>
                                                    
                                                    %9$s <!-- Post Status -->

                                                </div>

                                            </div>

                                        </div>


                                    </div>
                                    <!-- / row -->

                                </li>',

                                /**
                                 *  1. Venue Post ID
                                 *  ------------------
                                 */
                                absint( $post_id ),

                                /**
                                 *  2. Venue Post Title
                                 *  ---------------------
                                 */
                                esc_attr( get_the_title( $post_id ) ),

                                /**
                                 *  3. Venue Page Permalink
                                 *  -------------------------
                                 */
                                esc_url( get_the_permalink( $post_id ) ),

                                /**
                                 *  4. image link here
                                 *  ------------------
                                 */
                                self:: venue_image( absint( $post_id ) ),

                                /**
                                 *  5. Venue Location here
                                 *  ------------------------
                                 */
                                apply_filters( 'sdweddingdirectory/post/location', [

                                    'post_id'       =>      absint( $post_id ),

                                    'taxonomy'      =>      esc_attr( 'venue-location' ),

                                    'before'        =>      '<p class="mb-0">',

                                    'after'         =>      '</p>'

                                ] ),

                                /**
                                 *  6. Unique random id
                                 *  -------------------
                                 */
                                esc_attr( parent:: _rand() ),

                                /**
                                 *  7. Venue Badge
                                 *  ----------------
                                 */
                                apply_filters(  'sdweddingdirectory/venue/badge', [

                                    'post_id'       =>      absint( $post_id ),

                                    'layout'        =>      absint( '2' )

                                ]  ),

                                /**
                                 *  8. Post Status
                                 *  --------------
                                 */
                                self:: venue_status_badget( $post_id ),

                                /**
                                 *  9. Show Dropdown list
                                 *  ---------------------
                                 */
                                self:: dropdown_list(

                                    /**
                                     *  1. Get The Post ID
                                     *  ------------------
                                     */
                                    absint( $post_id ),

                                    /**
                                     *  2. Show button list
                                     *  -------------------
                                     */
                                    self:: get_action_button( $post_id )
                                ),

                                /**
                                 *  10. Task Date
                                 *  -------------
                                 */
                                esc_attr__( 'Task Date', 'sdweddingdirectory-venue' ),

                                /**
                                 *  11. Get Post Date
                                 *  -----------------
                                 */
                                get_the_date(),

                                /**
                                 *  12. Translation Ready String
                                 *  ----------------------------
                                 */
                                esc_attr__( 'Status', 'sdweddingdirectory-venue' ),

                                /**
                                 *  13. Translation Ready String
                                 *  ----------------------------
                                 */
                                esc_attr( 'Action', 'sdweddingdirectory-venue' )
                        );

                    } // while ending

                ?></ul><?php

                if( isset( $item ) ){

                    wp_reset_postdata();
                }

            }else{
                
                sprintf('<li><div class="dashboard-pageheader">
                            <div class="row">
                                <div class="col-xl-12">%1$s</div>
                            </div>
                        </li>',

                        /**
                         *  1. Translation Ready String
                         *  ---------------------------
                         */
                        esc_attr__( 'Welcome Vendor, You dont have any venue. Add your first venue free.', 'sdweddingdirectory-venue' )
                );
            }
        }

        /**
         *  Get Post Counter
         *  ----------------
         */
        public static function _count_venue( $_post_status = [] ){

            if( parent:: _is_array( $_post_status ) ){

                $args = array(

                        'post_type'         => esc_attr( 'venue' ),

                        'post_status'       =>  $_post_status,

                        'posts_per_page'    =>  -1,

                        'orderby'           => 'menu_order ID',

                        'order'             => esc_attr( 'post_date' ),

                        'author'            => absint( parent:: author_id() )
                );

                $item = new WP_Query( $args );

                return absint( $item->found_posts );
            }
        }

        /**
         *  Count Request Quote
         *  -------------------
         */
        public static function number_of_request_quote_for_venue(){

            $args = array(

                    'post_type'         =>  esc_attr( 'venue' ),

                    'post_status'       =>  array( 'publish' ),

                    'posts_per_page'    =>  -1,

                    'author'            =>  absint( parent:: author_id() )
            );

            $item = new WP_Query( $args );

            $_requests = absint( '0' );

            if( $item->have_posts() ){

                while ( $item->have_posts() ){  $item->the_post();

                    $_data = get_post_meta( absint( get_the_ID() ), sanitize_key( 'venue_request' ), true );

                    if( parent:: _is_array( $_data ) ){

                        $_requests += count( $_data );
                    }
                }

                wp_reset_postdata();
            }

            return absint( $_requests );
        }

        /**
         *  Get Number Of Featured Venue
         *  ------------------------------
         */
        public static function number_of_featured_venue_counter(){ 

            $args = array(

                    'post_type'         => 'venue',

                    'post_status'       =>  array( 'publish', 'pending', 'trash', 'draft' ),

                    'posts_per_page'    =>  -1,

                    'author'            => parent:: author_id(),

                    'meta_query'        => array(

                        'relation' => 'AND',

                        array(

                            'key' => 'is_featured_venue',

                            'value' => 'on',
                        ),
                    )
            );

            $item = new WP_Query( $args );

            return absint( $item->found_posts);
        }

        /**
         *  Video Iframe
         *  ------------
         */
        public static function sdweddingdirectory_video_embed( $embed_code ){

            $embed_code = str_replace('webkitallowfullscreen','',$embed_code);
            $embed_code = str_replace('mozallowfullscreen','',$embed_code);
            $embed_code = str_replace('frameborder="0"','',$embed_code);
            $embed_code = str_replace('frameborder="no"','',$embed_code);
            $embed_code = str_replace('scrolling="no"','',$embed_code);
            $embed_code = str_replace('&','&amp;',$embed_code);
            
            return $embed_code;
        }

        /**
         *  Venue Have Badge Capacity ?
         *  -----------------------------
         *  $return [ 0 ] = counter value
         *  -----------------------------
         *  $return [ 1 ] = string value
         *  ----------------------------
         */
        public static function sdweddingdirectory_have_venue_badge( $post_id = 0 ){

            $_collection    =   [];

            $badge          =   array(

                                    'spotlight'     =>  array(

                                                            'string'    =>  esc_attr__( 'Spotlight Badge', 'sdweddingdirectory-venue' ),

                                                            'meta_key'  =>  esc_attr( 'spotlight_venue_capacity' )
                                                        ),

                                    'featured'      =>  array(

                                                            'string'    =>  esc_attr__( 'Featured Badge', 'sdweddingdirectory-venue' ),

                                                            'meta_key'  =>  esc_attr( 'featured_venue_capacity' )
                                                        ),

                                    'professional'  =>  array(

                                                            'string'    =>  esc_attr__( 'Professional Badge', 'sdweddingdirectory-venue' ),

                                                            'meta_key'  =>  esc_attr( 'professional_venue_capacity' )
                                                        ),
                                );
            /**
             *  Check Data
             *  ----------
             */
            foreach( $badge as $key => $value ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( $value );

                /**
                 *  Capacity
                 *  --------
                 */
                $_capacity      =   absint( get_post_meta(

                                        /**
                                         *  1. Vendor Post ID
                                         *  -----------------
                                         */
                                        absint( parent:: post_id() ),

                                        /**
                                         *  2. Meta Key
                                         *  -----------
                                         */
                                        sanitize_key( $meta_key ),

                                        /**
                                         *  3. TRUE
                                         *  -------
                                         */
                                        TRUE

                                    ) );
                /**
                 *  Used Capacity
                 *  -------------
                 */
                $_capacity_use  =   self:: _count_venue_badge( $key );

                $_have_badge    =   '';

                /**
                 *  Have Post ?
                 *  -----------
                 */
                if( ! empty( $post_id ) ){

                    $_have_badge    =   get_post_meta(

                                            /**
                                             *  1. Venue Post ID
                                             *  ------------------
                                             */
                                            absint( $post_id ),

                                            /**
                                             *  2. Meta key
                                             *  -----------
                                             */
                                            sanitize_key( 'venue_badge' ),

                                            /**
                                             *  3. True
                                             *  -------
                                             */
                                            true
                                        );
                }

                /**
                 *  Make sure vendor have at least 1 capacity
                 *  -----------------------------------------
                 */
                if( $_capacity >= $_capacity_use && absint( absint( $_capacity ) - absint( $_capacity_use ) ) >= absint( '1' ) || $_have_badge == $key ){

                    $_collection[ 'counter' ][ $key ]    =   absint( absint( $_capacity ) - absint( $_capacity_use ) );

                    $_collection[ 'string' ][ $key ]     =   esc_attr( $string );
                }
            }

            return  $_collection;
        }

        /**
         *  Count Venues Badge Used
         *  -------------------------
         */
        public static function _count_venue_badge( $meta_value = '' ){

            global $wp_query, $post;

            /**
             *  Make sure meta value is not empty
             *  ---------------------------------
             */
            if( empty( $meta_value ) ){

                return;
            }

            $meta_query     =   [];

            /**
             *  Venue Badge
             *  -------------
             */
            $meta_query[]   =   array(

                'key'       =>  esc_attr( 'venue_badge' ),

                'type'      =>  esc_attr( 'string' ),

                'compare'   =>  esc_attr( '=' ),

                'value'     =>  $meta_value
            );

            /**
             *  Venue Query
             *  -------------
             */
            $args           =   array_merge(

                /**
                 *  Default args
                 *  ------------
                 */
                array(

                    'post_type'         =>  esc_attr( 'venue' ),

                    'post_status'       =>  esc_attr( 'publish' ),

                    'posts_per_page'    =>  -1,

                    'author'            =>  absint( parent:: author_id() ),
                ),

                /**
                 *  2. If Have Meta Query ?
                 *  -----------------------
                 */
                parent:: _is_array( $meta_query ) 

                ?   array(

                        'meta_query'        => array(

                            'relation'  => 'AND',

                            $meta_query,
                        )
                    )

                :   []
            );

            /**
             *  WordPress to Find Query
             *  -----------------------
             */
            $item  =  new WP_Query( $args );
            
            /**
             *  Found Total Number of Venue
             *  -----------------------------
             */
            return  $item->found_posts;
        }

        /**
         *  Menu
         *  ----
         */
        public static function venue_menu_popup_content(){

            /**
             *  Make sure it's venue page
             *  ---------------------------
             */
            if( is_singular( 'venue' ) ){

                $_have_menu    =   get_post_meta( absint( get_the_ID() ), sanitize_key( 'venue_menu' ), true );

                /**
                 *  Have Description ?
                 *  ------------------
                 */
                if( parent:: _is_array( $_have_menu ) ){

                    $counter    =   absint( '1' );

                    foreach( $_have_menu as $key => $value ){

                        /**
                         *  Extract
                         *  -------
                         */
                        extract( $value );

                        printf( '<div class="modal fade bd-example-modal-lg" id="%1$s" tabindex="-1" aria-labelledby="%1$sLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header p-0">
                                                <div class="singular-menu-slider">
                                                    <div class="top-head">
                                                        <div class="head">
                                                            <div>
                                                                <span>%2$s</span>%3$s
                                                            </div>
                                                        </div>
                                                        <div class="price">%4$s</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-body h-100">

                                                <div class="singular-menu-modal h-100">

                                                    <iframe webkitallowfullscreen mozallowfullscreen frameborder="0" frameborder="no" scrolling="no" src="%5$s"></iframe>

                                                </div>

                                            </div>

                                            <div class="modal-footer">

                                                <a class="btn btn-primary" href="%5$s" download><i class="fa fa-download"></i> &nbsp; %6$s</a>

                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa fa-close"></i> %7$s</button>

                                            </div>

                                        </div>
                                    </div>
                                </div>', 

                                /**
                                 *  1. Menu Title
                                 *  -------------
                                 */
                                parent:: _prefix( sanitize_title( $menu_title .'_'. $counter ) ),

                                /**
                                 *  2. Translation Ready String
                                 *  ---------------------------
                                 */
                                esc_attr__( 'Cuisine Offer', 'sdweddingdirectory-venue' ),

                                /**
                                 *  3. Title
                                 *  --------
                                 */
                                esc_attr( $menu_title ),

                                /**
                                 *  4. Price
                                 *  --------
                                 */
                                sdweddingdirectory_pricing_possition( $menu_price ),

                                /**
                                 *  5. File Path
                                 *  ------------
                                 */
                                esc_url( wp_get_attachment_url( $menu_file ) ),

                                /**
                                 *  6. Translation Ready String
                                 *  ---------------------------
                                 */
                                esc_attr__( 'Download Menu', 'sdweddingdirectory-venue' ),

                                /**
                                 *  7. Translation Ready String
                                 *  ---------------------------
                                 */
                                esc_attr__( 'Close', 'sdweddingdirectory-venue' )
                        );

                        /**
                         *  Counter
                         *  -------
                         */
                        $counter++;
                    }
                }
            }
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Vendor_Venue_Database:: get_instance();
}