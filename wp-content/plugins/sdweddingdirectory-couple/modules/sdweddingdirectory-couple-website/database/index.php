<?php
/**
 *  Database information here
 *  -------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Couple_Website_Database' ) && class_exists( 'SDWeddingDirectory_Form_Tabs' ) ){

    /**
     *  Database information here
     *  -------------------------
     */
    class SDWeddingDirectory_Couple_Website_Database extends SDWeddingDirectory_Form_Tabs{

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
            
        }

        /**
         *  File Version
         *  ------------
         */
        public static function _file_version( $file = '' ){

            /**
             *  Is Empty ?
             *  ----------
             */
            if( empty( $file ) ){

                /**
                 *  Get Style Version
                 *  -----------------
                 */

                return      esc_attr( SDWEDDINGDIRECTORY_WEBSITE_VERSION );

            }else{

                /*
                 *  For File Save timestrap version to clear the catch auto
                 *  -------------------------------------------------------
                 *  # https://developer.wordpress.org/reference/functions/wp_enqueue_style/#comment-6386
                 *  ------------------------------------------------------------------------------------
                 */

                return      esc_attr( SDWEDDINGDIRECTORY_WEBSITE_VERSION ) . '.' . absint( filemtime(  $file ) );
            }
        }

        /**
         *  Get Key
         *  -------
         */
        public static function website_post_data( $key = '' ){

            /**
             *  Have Data ?
             *  -----------
             */
            if( parent:: _have_data( $key ) ){

                /**
                 *  Return Post Value
                 *  -----------------
                 */
                return  get_post_meta(

                            /**
                             *  1. Post ID
                             *  ----------
                             */
                            absint( parent:: website_post_id() ),

                            /**
                             *  2. Meta Key
                             *  -----------
                             */
                            sanitize_key( $key ), 

                            /**
                             *  3. TRUE
                             *  -------
                             */
                            true 
                        );
            }
        }

        /**
         *  Couple Story
         *  ------------
         */
        public static function get_couple_story( $args = [] ){

            /**
             *  Extract Args
             *  ------------
             */
            extract( wp_parse_args( $args, array(

                'post_id'           =>  absint( '0' ),

                'story_title'       =>  '',

                'story_overview'    =>  '',

                'story_date'        =>  '',

                'story_image'       =>  ''

            ) ) );

            $_get_data              =   '';

            $_body_content          =   '';

            /**
             *  Database Name + Heading Title
             *  -----------------------------
             */
            $_section_id            =   esc_attr( 'couple_story' );

            $_section_title         =   esc_attr__( 'Your Story', 'sdweddingdirectory-couple-website' );
            
            /**
             *  Translation Ready String
             *  ------------------------
             */
            $_story_title            =   esc_attr__( 'Story Title', 'sdweddingdirectory-couple-website' );

            $_story_date             =   esc_attr__( 'Story Date', 'sdweddingdirectory-couple-website' );

            $_story_overview         =   esc_attr__( 'About Your Story', 'sdweddingdirectory-couple-website' );

            $_upload_btn_text        =   esc_attr__( 'Upload Story Image', 'sdweddingdirectory-couple-website' );

            $_media_frame_btn_text   =   esc_attr__( 'Select Story Image', 'sdweddingdirectory-couple-website' );

            $_media_frame_text       =   esc_attr__( 'Upload Your Story Image', 'sdweddingdirectory-couple-website' );

            /**
             *  Is empty!
             *  ---------
             */
            if( empty( $post_id ) ){

                $_body_content       =      '';
            
                /**
                 *  Column Start
                 *  ------------
                 */
                $_body_content      .=      '<div class="col collpase_section">';

                    $_body_content      .=      '<div class="card mb-4 text-center">';

                    $_body_content      .=      self:: removed_section_icon( true );

                        $_body_content      .=      '<div class="card-body">';

                            $_body_content      .=      '<div class="row">';

                                /**
                                 *  Get Image
                                 *  ---------
                                 */
                                $_body_content      .=      apply_filters( 'sdweddingdirectory/field/single-media', [

                                                                'column'                =>      [
                                                                                                    'start'     =>  true,

                                                                                                    'end'       =>  true,

                                                                                                    'grid'      =>  sanitize_html_class( 'col-md-2' ),

                                                                                                    'class'     =>  'col-6 mb-xxl-0 mb-xl-0 mb-lg-0 mb-md-0 mb-sm-0 mb-3'
                                                                                                ],

                                                                'icon_layout'           =>      absint( '1' ),

                                                                'image_class'           =>      sanitize_html_class( 'rounded-circle' ),

                                                                'media_id'              =>      absint( $story_image ),

                                                                'image_size'            =>      esc_attr( 'thumbnail' ),

                                                                'default_img'           =>      parent:: placeholder( 'couple-bride-image' ),

                                                                'input_class'           =>      sanitize_html_class( 'store_media_ids' )

                                                            ] );

                                $_body_content      .=      '<div class="col-md-10"><div class="row">';

                                    $_body_content      .=      parent:: create_input_field( array(

                                                                        'column'        =>  array(

                                                                                                'start'     =>  true,

                                                                                                'end'       =>  true,

                                                                                                'grid'      =>  sanitize_html_class( 'col-md' )
                                                                                            ),

                                                                        'name'          =>  esc_attr( 'story_title' ),

                                                                        'id'            =>  esc_attr( 'story_title' ),

                                                                        'class'         =>  sanitize_html_class( 'story_title' ),

                                                                        'placeholder'   =>  esc_attr( $_story_title ),

                                                                        'value'         =>  $story_title
                                                                ) );

                                    $_body_content      .=      parent:: create_input_field( array(

                                                                        'column'        =>  array(

                                                                                                'start'     =>  true,

                                                                                                'end'       =>  true,

                                                                                                'grid'      =>  sanitize_html_class( 'col-md' )
                                                                                            ),

                                                                        'name'          =>  esc_attr( 'story_date' ),

                                                                        'id'            =>  esc_attr( 'story_date' ),

                                                                        'class'         =>  sanitize_html_class( 'story_date' ),

                                                                        'placeholder'   =>  esc_attr( $_story_date ),

                                                                        'type'          =>  esc_attr( 'date' ),

                                                                        'value'         =>  $story_date
                                                                ) );

                                    $_body_content      .=      parent:: create_textarea_simple( array(

                                                                    'column'        =>  array(

                                                                                            'grid'      =>      absint( '12' ),

                                                                                            'start'     =>      true,

                                                                                            'end'       =>      true,
                                                                                        ),

                                                                    'name'          =>   esc_attr( 'story_overview' ),

                                                                    'limit'         =>   absint( '250' ),

                                                                    'class'         =>   sanitize_html_class( 'story_overview' ),

                                                                    'placeholder'   =>   esc_attr( $_story_overview ),

                                                                    'value'         =>   $story_overview

                                                                ) );

                                $_body_content      .=      '</div></div>';

                            $_body_content      .=      '</div><!-- End Row -->';

                        $_body_content      .=      '</div>';

                    $_body_content      .=      '</div>';

                $_body_content      .=      '</div>';


                /**
                 *  Return Data
                 *  -----------
                 */
                return $_body_content;

            }else{

                $_have_data          =   get_post_meta( absint( $post_id ), sanitize_key( 'couple_story' ), true );

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
         *  Couple Groom
         *  ------------
         */
        public static function get_couple_groom( $args = [] ){

            /**
             *  Extract Args
             *  ------------
             */
            extract( wp_parse_args( $args, array(

                'post_id'       =>  absint( '0' ),

                'groom_name'    =>  '',

                'groom_image'   =>  ''

            ) ) );

            $_get_data              =   '';

            $_body_content          =   '';

            /**
             *  Database Name + Heading Title
             *  -----------------------------
             */
            $_section_id            =   esc_attr( 'couple_groom' );

            $_section_title         =   esc_attr__( 'Groomsmen', 'sdweddingdirectory-couple-website' );
            
            /**
             *  Translation Ready String
             *  ------------------------
             */
            $_groom_name            =   esc_attr__( 'Groom Name', 'sdweddingdirectory-couple-website' );

            $_upload_btn_text        =   esc_attr__( 'Upload Groom Image', 'sdweddingdirectory-couple-website' );

            $_media_frame_btn_text   =   esc_attr__( 'Select Groom Image', 'sdweddingdirectory-couple-website' );

            $_media_frame_text       =   esc_attr__( 'Upload Your Groom Image', 'sdweddingdirectory-couple-website' );

            /**
             *  Is empty!
             *  ---------
             */
            if( empty( $post_id ) ){

                $_body_content       =      '';
            
                /**
                 *  Column Start
                 *  ------------
                 */
                $_body_content      .=      '<div class="col collpase_section">';

                $_body_content      .=      '<div class="card mb-4 text-center"><div class="card-header">';

                $_body_content      .=      parent:: create_input_field( array(

                                                    'name'          =>  esc_attr( 'groom_name' ),

                                                    'id'            =>  esc_attr( 'groom_name' ),

                                                    'class'         =>  esc_attr( 'groom_name form-light' ),

                                                    'formgroup'     =>  false,

                                                    'placeholder'   =>  esc_attr( $_groom_name ),

                                                    'value'         =>  esc_attr( $groom_name )
                                            ) );

                $_body_content      .=      '</div>';

                $_body_content      .=      '<div class="card-body">';

                /**
                 *  Get Image
                 *  ---------
                 */
                $_body_content      .=      apply_filters( 'sdweddingdirectory/field/single-media', [

                                                'icon_layout'           =>      absint( '1' ),

                                                'media_id'              =>      absint( $groom_image ),

                                                'image_size'            =>      esc_attr( 'sdweddingdirectory_img_200x200' ),

                                                'default_img'           =>      parent:: placeholder( 'web-layout-1-groom' ),

                                                'input_class'           =>      sanitize_html_class( 'store_media_ids' ),

                                                'image_class'           =>      sanitize_html_class( 'rounded' )

                                            ] );

                $_body_content      .=      '</div>';

                $_body_content      .=      self:: removed_section_icon( true );
                
                $_body_content      .=      '</div></div>';

                /**
                 *  Create Collpase
                 *  ---------------
                 */
                return $_body_content;

            }else{

                $_have_data          =   get_post_meta( absint( $post_id ), sanitize_key( 'couple_groom' ), true );

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
         *  Couple Bride
         *  ------------
         */
        public static function get_couple_bride( $args = [] ){

            /**
             *  Extract Args
             *  ------------
             */
            extract( wp_parse_args( $args, array(

                'post_id'       =>  absint( '0' ),

                'bride_name'    =>  '',

                'bride_image'   =>  ''

            ) ) );

            $_get_data              =   '';

            $_body_content          =   '';

            /**
             *  Database Name + Heading Title
             *  -----------------------------
             */
            $_section_id            =   esc_attr( 'couple_bride' );

            $_section_title         =   esc_attr__( 'Bridesmaids', 'sdweddingdirectory-couple-website' );
            
            /**
             *  Translation Ready String
             *  ------------------------
             */
            $_bride_name             =   esc_attr__( 'Bride Name', 'sdweddingdirectory-couple-website' );

            $_upload_btn_text        =   esc_attr__( 'Upload Bride Image', 'sdweddingdirectory-couple-website' );

            $_media_frame_btn_text   =   esc_attr__( 'Select Bride Image', 'sdweddingdirectory-couple-website' );

            $_media_frame_text       =   esc_attr__( 'Upload Your Bride Image', 'sdweddingdirectory-couple-website' );

            /**
             *  Is empty!
             *  ---------
             */
            if( empty( $post_id ) ){

                $_body_content       =      '';
            
                /**
                 *  Column Start
                 *  ------------
                 */
                $_body_content      .=      '<div class="col collpase_section">';

                $_body_content      .=      '<div class="card mb-4 text-center"><div class="card-header">';

                $_body_content      .=      parent:: create_input_field( array(

                                                    'name'          =>  esc_attr( 'bride_name' ),

                                                    'id'            =>  esc_attr( 'bride_name' ),

                                                    'class'         =>  esc_attr( 'bride_name form-light' ),

                                                    'formgroup'     =>  false,

                                                    'placeholder'   =>  esc_attr( $_bride_name ),

                                                    'value'         =>  esc_attr( $bride_name )
                                            ) );

                $_body_content      .=      '</div>';

                $_body_content      .=      '<div class="card-body">';

                /**
                 *  Get Image
                 *  ---------
                 */
                $_body_content      .=      apply_filters( 'sdweddingdirectory/field/single-media', [

                                                'icon_layout'           =>      absint( '1' ),

                                                'media_id'              =>      absint( $bride_image ),

                                                'image_size'            =>      esc_attr( 'sdweddingdirectory_img_200x200' ),

                                                'default_img'           =>      parent:: placeholder( 'web-layout-1-bride' ),

                                                'input_class'           =>      sanitize_html_class( 'store_media_ids' ),

                                                'image_class'           =>      sanitize_html_class( 'rounded' )

                                            ] );

                $_body_content      .=      '</div>';

                $_body_content      .=      self:: removed_section_icon( true );
                
                $_body_content      .=      '</div></div>';

                /**
                 *  Create Collpase
                 *  ---------------
                 */
                return $_body_content;

            }else{

                $_have_data          =   get_post_meta( absint( $post_id ), sanitize_key( 'couple_bride' ), true );

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
         *  Couple Gallery
         *  --------------
         */
        public static function get_couple_gallery( $args = [] ){

            /**
             *  Extract Args
             *  ------------
             */
            extract( wp_parse_args( $args, array(

                'post_id'         =>  absint( '0' ),

                'value'           =>  '',

                'gallery_name'    =>  '',

                'gallery_image'   =>  ''

            ) ) );

            $_get_data              =   '';

            $_body_content          =   '';

            /**
             *  Database Name + Heading Title
             *  -----------------------------
             */
            $_section_id            =   esc_attr( 'couple_gallery' );

            $_section_title         =   esc_attr__( 'Couple Gallery', 'sdweddingdirectory-couple-website' );
            
            /**
             *  Translation Ready String
             *  ------------------------
             */
            $_gallery_name           =   esc_attr__( 'Gallery Name', 'sdweddingdirectory-couple-website' );

            $_upload_btn_text        =   esc_attr__( 'Upload Gallery Image', 'sdweddingdirectory-couple-website' );

            $_media_frame_btn_text   =   esc_attr__( 'Select Gallery Image', 'sdweddingdirectory-couple-website' );

            $_media_frame_text       =   esc_attr__( 'Upload Your Gallery Image', 'sdweddingdirectory-couple-website' );

            /**
             *  Is empty!
             *  ---------
             */
            if( empty( $post_id ) ){

                $_body_content       =      '';
            
                /**
                 *  Column Start
                 *  ------------
                 */
                $_body_content      .=      '<div class="col collpase_section">';

                $_body_content      .=      '<div class="card mb-4 text-center"><div class="card-header">';

                $_body_content      .=      parent:: create_input_field( array(

                                                    'name'          =>  esc_attr( 'gallery_name' ),

                                                    'id'            =>  esc_attr( 'gallery_name' ),

                                                    'class'         =>  esc_attr( 'gallery_name form-light' ),

                                                    'formgroup'     =>  false,

                                                    'value'         =>  esc_attr( $gallery_name ),

                                                    'placeholder'   =>  esc_attr( $_gallery_name )
                                            ) );

                $_body_content      .=      '</div>';

                $_body_content      .=      '<div class="card-body">';

                $_body_content      .=      apply_filters( 'sdweddingdirectory/field/gallery-upload', [

                                                'media_ids'         =>      $gallery_image

                                            ] );

                $_body_content      .=      '</div>';

                $_body_content      .=      self:: removed_section_icon( true );
                
                $_body_content      .=      '</div></div>';

                /**
                 *  Create Collpase
                 *  ---------------
                 */
                return $_body_content;

            }else{

                $_have_data          =   get_post_meta( absint( $post_id ), sanitize_key( 'couple_gallery' ), true );

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
         *  Couple Testimonial
         *  ------------------
         */
        public static function get_couple_testimonial( $args = [] ){

            /**
             *  Extract Args
             *  ------------
             */
            extract( wp_parse_args( $args, array(

                'post_id'   =>  absint( '0' ),

                'value'     =>  '',

                'name'      =>  '',

                'content'   =>  ''

            ) ) );

            $_get_data              =   '';

            $_body_content          =   '';

            /**
             *  Database Name + Heading Title
             *  -----------------------------
             */
            $_section_id            =   esc_attr( 'couple_testimonial' );

            $_section_title         =   esc_attr__( 'Testimonial', 'sdweddingdirectory-couple-website' );
            
            /**
             *  Translation Ready String
             *  ------------------------
             */
            $_name                   =   esc_attr__( 'Name', 'sdweddingdirectory-couple-website' );

            $_content                =   esc_attr__( 'Content', 'sdweddingdirectory-couple-website' );

            /**
             *  Is empty!
             *  ---------
             */
            if( empty( $post_id ) ){

                $_body_content       =      '';
            
                /**
                 *  Column Start
                 *  ------------
                 */
                $_body_content      .=      '<div class="col collpase_section">';

                    $_body_content      .=      '<div class="card mb-4 text-center"><div class="card-header">';

                        $_body_content      .=      parent:: create_input_field( array(

                                                            'name'          =>  esc_attr( 'name' ),

                                                            'id'            =>  esc_attr( 'name' ),

                                                            'class'         =>  esc_attr( 'name form-light' ),

                                                            'formgroup'     =>  false,

                                                            'placeholder'   =>  esc_attr( $_name ),

                                                            'value'         =>  esc_attr( $name )
                                                    ) );

                    $_body_content      .=      '</div>';

                $_body_content      .=      '<div class="card-body">';

                    $_body_content      .=      parent:: create_textarea_simple( array(

                                                        'name'          =>  esc_attr( 'content' ),

                                                        'id'            =>  esc_attr( 'content' ),

                                                        'class'         =>  esc_attr( 'content form-light' ),

                                                        'formgroup'     =>  false,

                                                        'placeholder'   =>  esc_attr( $_content ),

                                                        'limit'         =>  absint( '200' ),

                                                        'value'         =>  esc_attr( $content )
                                                ) );

                $_body_content      .=      '</div>';

                $_body_content      .=      self:: removed_section_icon( true );
                
                $_body_content      .=      '</div></div>';

                /**
                 *  Create Collpase
                 *  ---------------
                 */
                return $_body_content;

            }else{

                $_have_data          =   get_post_meta( absint( $post_id ), sanitize_key( 'couple_testimonial' ), true );

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
         *  Couple Testimonial
         *  ------------------
         */
        public static function get_couple_event( $args = [] ){

            /**
             *  Extract Args
             *  ------------
             */
            extract( wp_parse_args( $args, array(

                'post_id'       =>  absint( '0' ),

                'value'         =>  '',

                'image'         =>  absint( '0' ),

                'name'          =>  '',

                'date'          =>  '',

                'content'       =>  '',

                'icon'          =>  '',

                'latitude'      =>  '',

                'longitude'     =>  '',

                'map_address'   =>  '',

            ) ) );

            $_get_data              =   '';

            $_body_content          =   '';

            /**
             *  Database Name + Heading Title
             *  -----------------------------
             */
            $_section_id            =   esc_attr( 'couple_event' );

            $_section_title         =   esc_attr__( 'Event', 'sdweddingdirectory-couple-website' );
            
            /**
             *  Translation Ready String
             *  ------------------------
             */
            $_name        =   esc_attr__( 'Event Type', 'sdweddingdirectory-couple-website' );

            $_content     =   esc_attr__( 'Event Content', 'sdweddingdirectory-couple-website' );

            $_date        =   esc_attr__( 'Event Date', 'sdweddingdirectory-couple-website' );

            $_image       =   esc_attr__( 'Event Image', 'sdweddingdirectory-couple-website' );

            $_latitude    =   esc_attr__( 'Latitude', 'sdweddingdirectory-couple-website' );

            $_longitude   =   esc_attr__( 'Longitude', 'sdweddingdirectory-couple-website' );

            $_icon        =   esc_attr__( 'Icon', 'sdweddingdirectory-couple-website' );

            /**
             *  Is empty!
             *  ---------
             */
            if( empty( $post_id ) ){

                $_body_content       =      '';
            
                /**
                 *  Column Start
                 *  ------------
                 */
                $_body_content      .=      '<div class="col collpase_section">';

                    $_body_content      .=      '<div class="card mb-4 text-center">';

                        $_body_content      .=      self:: removed_section_icon( true );

                        $_body_content      .=      '<div class="row">';

                            $_body_content      .=      '<div class="col-md-12">';

                                $_body_content      .=      '<div class="row g-0">';

                                    $_body_content      .=      '<div class="col-md-12">';

                                        $_body_content      .=      '<div class="card-header p-3">';

                                        $_body_content      .=      '<div class="row">';

                                            $_body_content      .=      '<div class="col-md-12">';


                                                /**
                                                 *  Get Image
                                                 *  ---------
                                                 */
                                                $_body_content      .=      apply_filters( 'sdweddingdirectory/field/single-media', [

                                                                                'icon_layout'           =>      absint( '1' ),

                                                                                'media_id'              =>      absint( $image ),

                                                                                'image_size'            =>      esc_attr( 'full' ), // sdweddingdirectory_img_515x255

                                                                                'default_img'           =>      parent:: placeholder( 'web-layout-1-event' ),

                                                                                'input_class'           =>      sanitize_html_class( 'store_media_ids' ),

                                                                                'image_class'           =>      sanitize_html_class( 'rounded' )

                                                                            ] );


                                            $_body_content      .=      '</div>';

                                        $_body_content      .=      '</div><!-- Row -->';

                                        $_body_content      .=      '</div>';

                                    $_body_content      .=      '</div><!-- Card Header -->';



                                    $_body_content      .=      '<div class="col-md-12">';

                                        $_body_content      .=      '<div class="card-body pb-0">';

                                        $_body_content      .=      '<div class="row">';

                                            $_body_content      .=      parent:: create_input_field( array(

                                                                                'column'        =>  [

                                                                                                        'start' =>  true,

                                                                                                        'end'   =>  true,

                                                                                                        'grid'  =>  'col-lg-4 col-md-12'
                                                                                                    ],

                                                                                'name'          =>  esc_attr( 'name' ),

                                                                                'id'            =>  esc_attr( 'name' ),

                                                                                'class'         =>  esc_attr( 'name' ),

                                                                                'placeholder'   =>  esc_attr( $_name ),

                                                                                'value'         =>  esc_attr( $name ),
                                                                        ) );

                                            $_body_content      .=      parent:: create_input_field( array(

                                                                                'column'        =>  [

                                                                                                        'start' =>  true,

                                                                                                        'end'   =>  true,

                                                                                                        'grid'  =>  'col-lg-4 col-md-12'
                                                                                                    ],

                                                                                'name'          =>  esc_attr( 'date' ),

                                                                                'id'            =>  esc_attr( 'date' ),

                                                                                'class'         =>  esc_attr( 'date' ),

                                                                                'type'          =>  esc_attr( 'date' ),

                                                                                'placeholder'   =>  esc_attr( $_date ),

                                                                                'value'         =>  esc_attr( $date ),
                                                                        ) );

                                            $_body_content      .=      parent:: create_select_option( array(

                                                                                'column'        =>  [

                                                                                                        'start' =>  true,

                                                                                                        'end'   =>  true,

                                                                                                        'grid'  =>  'col-lg-4 col-md-12'
                                                                                                    ],

                                                                                'name'          =>  esc_attr( 'icon' ),

                                                                                'id'            =>  esc_attr( 'icon' ),

                                                                                'class'         =>  esc_attr( 'icon form-control' ),

                                                                                'placeholder'   =>  esc_attr( $_icon ),

                                                                                'value'         =>  esc_attr( $icon ),

                                                                                'options'       =>  apply_filters( 'sdweddingdirectory/icons', [

                                                                                                        'selected'  =>  esc_attr( $icon )

                                                                                                    ] )
                                                                        ) );

                                            $_body_content      .=      parent:: create_textarea_simple( array(

                                                                            'column'        =>  [

                                                                                                    'start' =>  true,

                                                                                                    'end'   =>  true,

                                                                                                    'grid'  =>  'col-md-12'
                                                                                                ],

                                                                                'name'          =>  esc_attr( 'content' ),

                                                                                'id'            =>  esc_attr( 'content' ),

                                                                                'class'         =>  esc_attr( 'content' ),

                                                                                'placeholder'   =>  esc_attr( $_content ),

                                                                                'value'         =>  esc_attr( $content ),

                                                                                'limit'         =>  absint( '400' ),

                                                                                'rows'          =>  absint( '4' )
                                                                        ) );

                                        $_body_content      .=      '</div><!-- Card Body -->';

                                        $_body_content      .=      '</div><!-- Row -->';

                                    $_body_content      .=      '</div><!-- card body -->';


                                $_body_content      .=      '</div><!-- row -->';

                            $_body_content      .=      '</div>';




                            $_body_content      .=      '<div class="col-md-12">';

                                $_body_content      .=      parent:: find_map_with_address( array(

                                                                /**
                                                                 *  Layout
                                                                 *  ------
                                                                 */
                                                                'layout'                =>      absint( '2' ),

                                                                /**
                                                                 *  Have Grid
                                                                 *  ---------
                                                                 */
                                                                'map_class'             =>      'couple_event_map mb-2 border border-1',

                                                                'map_address_grid'      =>      'col-12',

                                                                'map_latitude_grid'     =>      'col-12',

                                                                'map_longitude_grid'    =>      'col-12',

                                                                /**
                                                                 *  Have Value ?
                                                                 *  ------------
                                                                 */
                                                                'map_address_value'     =>      esc_attr( $map_address ),

                                                                'map_latitude_value'    =>      esc_attr( $latitude ),

                                                                'map_longitude_value'   =>      esc_attr( $longitude ),

                                                                'map_hidden_in_div'     =>      ! empty( $latitude ) && ! empty( $longitude )

                                                                                                ?       true

                                                                                                :       false,
                                                                /**
                                                                 *  Have Placeholder ?
                                                                 *  ------------------
                                                                 */
                                                                'map_address_placeholder'       =>  esc_attr__( 'Add Location Address', 'sdweddingdirectory-couple-website' ),

                                                            ) );

                            $_body_content      .=      '</div>';





                        $_body_content      .=      '</div><!-- Row -->';

                    $_body_content      .=      '</div><!-- Card -->';

                $_body_content      .=      '</div><!-- Grid -->';

                /**
                 *  Create Collpase
                 *  ---------------
                 */
                return      $_body_content;
            }

            /**
             *  Have Post ID ?
             *  --------------
             */
            else{

                $_have_data          =   get_post_meta( absint( $post_id ), sanitize_key( 'couple_event' ), true );

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

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Couple_Website_Database:: get_instance();
}