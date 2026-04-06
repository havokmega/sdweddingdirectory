<?php
/**
 *  SDWeddingDirectory - Couple Wedding Website
 *  -----------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Couple_Dashboard_Website' ) && class_exists( 'SDWeddingDirectory_Couple_Website_Database' ) ){

    /**
     *  SDWeddingDirectory - Couple Wedding Website
     *  -----------------------------------
     */
    class SDWeddingDirectory_Couple_Dashboard_Website extends SDWeddingDirectory_Couple_Website_Database{

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
             *  Load one by one shortcode file
             *  ------------------------------
             */
            foreach ( glob( plugin_dir_path( __FILE__ ) . '/*/index.php' ) as $file ) {
           
                require_once    $file;
            }

            /**
             *  1. Dashboard Scripts
             *  --------------------
             */
            add_action( 'wp_enqueue_scripts', [ $this, 'sdweddingdirectory_script' ], absint( '80' ) );

            /**
             *  2. Dashboard Page
             *  -----------------
             */
            add_action( 'sdweddingdirectory/couple-dashboard', [ $this, 'dashboard_page' ], absint( '80' ), absint( '1' ) );
        }

        /**
         *  1. Dashboard Scripts
         *  --------------------
         */
        public static function sdweddingdirectory_script(){

            /**
             *  Make sure is : Couple Tools > Website Page
             *  ------------------------------------------
             */
            if( parent:: dashboard_page_set( esc_attr( 'wedding-website' ) ) ){

                /**
                 *  Slug
                 *  ----
                 */
                $_slug =  sanitize_title( __CLASS__ );

                /**
                 *  SDWeddingDirectory - Script
                 *  -------------------
                 */
                wp_enqueue_script( 

                    /**
                     *  slug
                     *  ----
                     */
                    esc_attr( $_slug ),

                    /**
                     *  File link
                     *  ---------
                     */
                    esc_url( plugin_dir_url( __FILE__ ).'script.js' ),

                    /**
                     *  Dependancy
                     *  ----------
                     */
                    array( 'jquery', 'toastr' ),

                    /**
                     *  Version
                     *  -------
                     */
                    esc_attr( parent:: _file_version( plugin_dir_path( __FILE__ ).'script.js' ) ),

                    /**
                     *  Load Bottom
                     *  -----------
                     */
                    true
                );

                /**
                 *  Load Library Condition
                 *  ----------------------
                 */
                add_filter( 'sdweddingdirectory/enable-script/date-picker', function( $args = [] ){

                    return      array_merge( $args, [ 'couple-real-wedding'   =>  true  ] );

                } );

                /**
                 *  Load Library Condition
                 *  ----------------------
                 */
                add_filter( 'sdweddingdirectory/enable-script/media-upload', function( $args = [] ){

                    return      array_merge( $args, [ 'couple-real-wedding'   =>  true  ] );

                } );

                /**
                 *  Map Load Page Condition
                 *  -----------------------
                 */
                add_filter( 'sdweddingdirectory/enable-script/map', function( $args = [] ){

                    return      array_merge( $args, [ 'couple-real-wedding'   =>  true  ] );

                } );

                /**
                 *  Map Load Page Condition
                 *  -----------------------
                 */
                add_filter( 'sdweddingdirectory/enable-script/map-geo', function( $args = [] ){

                    return      array_merge( $args, [ 'couple-real-wedding'   =>  true  ] );

                } );
            }
        }

        /**
         *  2. Dashboard Page File
         *  ----------------------
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
                if( ! empty( $page ) && $page == esc_attr( 'wedding-website' )  ){

                    ?>
                    <div class="container">

                        <div class="row">

                            <div class="section-title d-flex justify-content-between align-items-center">
                            <?php

                                /**
                                 *  Dashboard Title
                                 *  ---------------
                                 */
                                printf('<h2>%1$s</h2>

                                        <a href="%2$s" class="text-end btn btn-primary btn-sm" target="_blank">

                                            <i class="fa fa-eye me-2" aria-hidden="true"></i> <span>%3$s</span>

                                        </a>',

                                        /**
                                         *  1. Title
                                         *  --------
                                         */
                                        esc_attr__( 'Wedding Website', 'sdweddingdirectory-couple-website' ),

                                        /**
                                         *  2. Description
                                         *  --------------
                                         */
                                        esc_url( get_the_permalink( parent:: website_post_id() ) ),

                                        /**
                                         *  3. Translation Ready String
                                         *  ---------------------------
                                         */
                                        esc_attr__( 'Preview', 'sdweddingdirectory-couple-website' )
                                );

                            ?>
                            </div>

                        </div>
                        <?php

                        /**
                         *  Load the Couple Wedding Website Content
                         *  -----------------------------
                         */
                        if( parent:: website_post_id() == '0' || parent:: website_post_id() == '' ){

                            printf( esc_attr__( 'You have\'n asssign website in %1$s. please contact admin.', 'sdweddingdirectory-couple-website' ), 

                                // 1
                                get_option( 'blogname' )
                            );

                            return;
                        }

                        /**
                         *  Else
                         *  ----
                         */
                        else{

                            /**
                             *  My Profile Tabs
                             *  ---------------
                             */
                            $_tabs  =  apply_filters( 'sdweddingdirectory/couple-wedding-website/tabs', [] );

                            /**
                             *  2.2. Load Profile Tabs
                             *  ----------------------
                             */
                            if( parent:: _is_array( $_tabs ) ){

                                parent:: _create_tabs( $_tabs, [ 'structure_layout' => absint( '3' ) ] );
                            }
                        }

                    ?></div><?php
                }
            }
        }
    }

    /**
     *  Couple Wedding Website Object Call self
     *  ---------------------------------------
     */
    SDWeddingDirectory_Couple_Dashboard_Website::get_instance();
}