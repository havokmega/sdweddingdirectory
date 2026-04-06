<?php
/**
 *  SDWeddingDirectory - Add New Venue
 *  ----------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Dashboard_Venue_Update' ) && class_exists( 'SDWeddingDirectory_Vendor_Venue_Files' ) ){

    /**
     *  SDWeddingDirectory - Add New Venue
     *  ----------------------------
     */
    class SDWeddingDirectory_Dashboard_Venue_Update extends SDWeddingDirectory_Vendor_Venue_Files{

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
         *  Venue Post ID
         *  ---------------
         */
        public static function venue_post_ID(){

            return      isset( $_GET[ 'venue_id' ] ) && $_GET[ 'venue_id' ] !== ''

                        ?   absint( $_GET[ 'venue_id' ] )

                        :   absint( '0' );
        }

        /**
         *  Construct
         *  ---------
         */
        public function __construct() {

            /**
             *  Load one by one shortcode file
             *  ------------------------------
             */
            foreach ( glob( plugin_dir_path( __FILE__ ) . '/*/index.php' ) as $file ) {
           
                require_once $file;
            }

            /**
             *  1. Dashboard Scripts
             *  --------------------
             */
            add_action( 'wp_enqueue_scripts', [$this, 'sdweddingdirectory_script'], absint( '30' ) );

            /**
             *  Dashboard Page
             *  --------------
             */
            add_action( 'sdweddingdirectory/vendor-dashboard', [$this, 'dashboard_page_update_venue'], absint( '30' ), absint( '1' ) );

            /**
             *  Venue Mandatory Fields
             *  ------------------------
             */
            add_filter( 'sdweddingdirectory/venue-fields/required', [ $this, 'venue_form_fields_validation' ], absint( '10' ), absint( '1' ) );
        }

        /**
         *  1. Load Add Venue Script
         *  --------------------------
         */
        public static function sdweddingdirectory_script(){

            /**
             *  Is Add Venue Page ?
             *  ---------------------
             */
            if( parent:: dashboard_page_set( 'add-venue' ) || parent:: dashboard_page_set( 'update-venue' ) ){

                /**
                 *  Load Script
                 *  -----------
                 */
                wp_enqueue_script( 

                    /**
                     *  1. File Name
                     *  ------------
                     */
                    esc_attr( sanitize_title( __CLASS__ ) ),

                    /**
                     *  2. File Path
                     *  ------------
                     */
                    esc_url(   plugin_dir_url( __FILE__ )   .   'script.js'   ),

                    /**
                     *  3. Load After "JQUERY" Load
                     *  ---------------------------
                     */
                    array( 'jquery', 'toastr', 'summernote' ),

                    /**
                     *  4. Bootstrap - Library Version
                     *  ------------------------------
                     */
                    esc_attr( parent:: _file_version( plugin_dir_path( __FILE__ )   .   'script.js' ) ),

                    /**
                     *  5. Load in Footer
                     *  -----------------
                     */
                    true
                );

                /**
                 *  Load Library Condition
                 *  ----------------------
                 */
                add_filter( 'sdweddingdirectory/enable-script/media-upload', function( $args = [] ){

                    return  array_merge( $args, [ 'add-venue'   =>  true ] );

                } );

                /**
                 *  Load Library Condition
                 *  ----------------------
                 */
                add_filter( 'sdweddingdirectory/enable-script/summary-editor', function( $args = [] ){

                    return  array_merge( $args, [ 'add-venue'   =>  true ] );

                } );

                /**
                 *  Map Load Page Condition
                 *  -----------------------
                 */
                add_filter( 'sdweddingdirectory/enable-script/map', function( $args = [] ){

                    return  array_merge( $args, [ 'add-venue'   =>  true ] );

                } );

                /**
                 *  Map Load Page Condition
                 *  -----------------------
                 */
                add_filter( 'sdweddingdirectory/enable-script/map-geo', function( $args = [] ){

                    return  array_merge( $args, [ 'add-venue'   =>  true ] );

                } );

                /**
                 *  Load Library Condition
                 *  -----------------------
                 */
                add_filter( 'sdweddingdirectory/enable-script/input-dropdown', function( $args = [] ){

                    return  array_merge( $args, [ 'add-venue'   =>  true ] );
                    
                } );
            }
        }

        /**
         *  Dashboard Page
         *  --------------
         */
        public static function dashboard_page_update_venue( $args = [] ){

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

                    'layout'            =>      absint( '1' ),

                    'post_id'           =>      '',

                    'page'              =>      '',

                    'page_title'        =>      '',

                ] ) );

                /**
                 *  Make sure this page is couple dashboard page
                 *  --------------------------------------------
                 */
                if( ! empty( $page ) && in_array( $page, [ 'add-venue', 'update-venue' ] ) ){

                    /**
                     *  Add Venue
                     *  -----------
                     */
                    if( $page == 'add-venue' ){

                        $page_title         =       esc_attr__( 'Add New Venue', 'sdweddingdirectory-venue' );
                    }

                    /**
                     *  Update Venue
                     *  --------------
                     */
                    elseif( $page == 'update-venue' ){

                        $page_title         =       esc_attr__( 'Update Venue', 'sdweddingdirectory-venue' );
                    }

                    /**
                     *  Page Load
                     *  ---------
                     */
                    ?><div class="container"><?php

                        /**
                         *  1. Page Title
                         *  -------------
                         */
                        SDWeddingDirectory_Dashboard:: dashboard_page_header( $page_title );

                        /**
                         *  Make sure permission
                         *  --------------------
                         */
                        if(  !  parent:: _have_venue_permission()  ){

                            /**
                             *  My Profile Tabs
                             *  ---------------
                             */
                            $_tabs          =   apply_filters( 'sdweddingdirectory/add-update-venue/tabs', [] );

                            $_form_args     =   [
                                                    'form_before'   =>  '',

                                                    'form_after'    =>  '',

                                                    'form_id'       =>  esc_attr( 'venue_submit' ),

                                                    'form_class'    =>  sanitize_html_class( 'sdweddingdirectory_add_venue_form' ),

                                                    'button_before' =>  '',

                                                    'button_after'  =>  sprintf( '<input autocomplete="off" type="hidden" id="venue_id" value="%1$s">', 

                                                                            absint( parent:: venue_post_ID() )
                                                                        ),

                                                    'button_id'     =>  esc_attr( 'venue_update_btn' ),

                                                    'button_class'  =>  '',

                                                    'button_name'   =>  esc_attr__( 'Submit Venue','sdweddingdirectory-venue' ),

                                                    'security'      =>  esc_attr( 'sdweddingdirectory_update_venue' ),
                                                ];



                            /**
                             *  2.2. Load Profile Tabs
                             *  ----------------------
                             */
                            if( parent:: _is_array( $_tabs ) ){

                                parent:: form_start( $_form_args );

                                    parent:: _create_tabs( $_tabs, [ 'structure_layout' => absint( '3' ) ] );

                                ?><div class="text-end mt-4"><?php

                                    parent:: form_end( $_form_args );

                                ?></div><?php
                            }
                        }

                    ?></div><?php   
                }
            }
        }

        /**
         *  Venue Mandatory Fields
         *  ------------------------
         */
        public static function venue_form_fields_validation( $args = [] ){

            /**
             *  Post Title Not Emtpy !
             *  ----------------------
             */
            $args[  'post_title'  ]    =       [

                'message'   =>      esc_attr__( 'Write Your Venue Title', 'sdweddingdirectory-venue' ),

                'notice'    =>      absint( '2' )
            ];

            /**
             *  Post Content
             *  ------------
             */
            $args[  'post_content'  ]    =       [

                'message'   =>      esc_attr__( 'Write Your Venue Information', 'sdweddingdirectory-venue' ),

                'notice'    =>      absint( '2' )
            ];

            /**
             *  Venue Category
             *  ----------------
             */
            $args[  'venue_category'  ]    =       [

                'message'   =>      esc_attr__( 'Select Venue Category', 'sdweddingdirectory-venue' ),

                'notice'    =>      absint( '2' )
            ];

            /**
             *  Venue Location
             *  ----------------
             */
            $args[  'venue_location'  ]    =       [

                'message'   =>      esc_attr__( 'Select Venue Location', 'sdweddingdirectory-venue' ),

                'notice'    =>      absint( '2' )
            ];

            /**
             *  Venue Min Price
             *  -----------------
             */
            $args[  'venue_min_price'  ]    =       [

                'message'   =>      esc_attr__( 'Write Minimum Price', 'sdweddingdirectory-venue' ),

                'notice'    =>      absint( '2' )
            ];

            /**
             *  Venue Max Price
             *  -----------------
             */
            $args[  'venue_max_price'  ]    =       [

                'message'   =>      esc_attr__( 'Write Maxinum Price', 'sdweddingdirectory-venue' ),

                'notice'    =>      absint( '2' )
            ];

            /**
             *  Venue Address
             *  ---------------
             */
            $args[  'venue_address'  ]    =       [

                'message'   =>      esc_attr__( 'Write Your Venue Address', 'sdweddingdirectory-venue' ),

                'notice'    =>      absint( '1' )
            ];

            /**
             *  Venue Map Address
             *  -------------------
             */
            $args[  'map_address'  ]    =       [

                'message'   =>      esc_attr__( 'Write Your Venue Map Address', 'sdweddingdirectory-venue' ),

                'notice'    =>      absint( '1' )
            ];

            /**
             *  Venue Map Latitude
             *  --------------------
             */
            $args[  'venue_latitude'  ]    =       [

                'message'   =>      esc_attr__( 'Write Your Venue Map Latitude', 'sdweddingdirectory-venue' ),

                'notice'    =>      absint( '1' )
            ];

            /**
             *  Venue Map Longitude
             *  ---------------------
             */
            $args[  'venue_longitude'  ]    =       [

                'message'   =>      esc_attr__( 'Write Your Venue Map Longitude', 'sdweddingdirectory-venue' ),

                'notice'    =>      absint( '1' )
            ];

            /**
             *  Venue PinCode
             *  ---------------
             */
            $args[  'venue_pincode'  ]    =       [

                'message'   =>      esc_attr__( 'Write Your Venue Area Code / PinCode', 'sdweddingdirectory-venue' ),

                'notice'    =>      absint( '1' )
            ];

            /**
             *  Venue Featured Image
             *  ----------------------
             */
            $args[  '_thumbnail_id'  ]    =       [

                'message'   =>      esc_attr__( 'Upload Your Venue Featured Image ', 'sdweddingdirectory-venue' ),

                'notice'    =>      absint( '2' )
            ];

            /**
             *  Venue Gallery
             *  ---------------
             */
            $args[  'venue_gallery'  ]    =       [

                'message'   =>      esc_attr__( 'Upload Your Venue Gallery', 'sdweddingdirectory-venue' ),

                'notice'    =>      absint( '2' )
            ];

            /**
             *  Return Args
             *  -----------
             */
            return          $args;
        }
    }

    /**
     *  SDWeddingDirectory - Add New Venue
     *  ----------------------------
     */
    SDWeddingDirectory_Dashboard_Venue_Update::get_instance();
}
