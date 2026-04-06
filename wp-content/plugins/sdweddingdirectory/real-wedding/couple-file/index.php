<?php
/**
 *  SDWeddingDirectory Couple Real Wedding
 *  ------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Dashboard_Realwedding' ) && class_exists( 'SDWeddingDirectory_Real_Wedding_Database' ) ){

    /**
     *  SDWeddingDirectory Couple Real Wedding
     *  ------------------------------
     */
    class SDWeddingDirectory_Dashboard_Realwedding extends SDWeddingDirectory_Real_Wedding_Database{

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
            add_action( 'wp_enqueue_scripts', [ $this, 'sdweddingdirectory_script' ], absint( '60' ) );

            /**
             *  2. Dashboard Page
             *  -----------------
             */
            add_action( 'sdweddingdirectory/couple-dashboard', [ $this, 'dashboard_page' ], absint( '60' ), absint( '1' ) );
        }

        /**
         *  1. Load script / style
         *  ----------------------
         */
        public static function sdweddingdirectory_script(){

            $_condition_1   =   parent:: dashboard_page_set( 'real-wedding' );

            $_condition_2   =   parent:: dashboard_page_set( 'couple-dashboard' );

            /**
             *  Is Dashboard || Todo
             *  --------------------
             */
            if( $_condition_1 || $_condition_2 ){

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
                    array( 'jquery', 'toastr' ),

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
                 *  If Real Wedding page
                 *  --------------------
                 */
                if( $_condition_1 ){

                    /**
                     *  Load Library Condition
                     *  ----------------------
                     */
                    add_filter( 'sdweddingdirectory/enable-script/date-picker', function( $args = [] ){

                        return  array_merge( $args, [ 'real-wedding'   =>  true ] );

                    } );

                    /**
                     *  Load Library Condition
                     *  ----------------------
                     */
                    add_filter( 'sdweddingdirectory/enable-script/summary-editor', function( $args = [] ){

                        return  array_merge( $args, [ 'real-wedding'   =>  true ] );

                    } );

                    /**
                     *  Load Library Condition
                     *  ----------------------
                     */
                    add_filter( 'sdweddingdirectory/enable-script/media-upload', function( $args = [] ){

                        return  array_merge( $args, [ 'real-wedding'   =>  true ] );

                    } );

                    /**
                     *  Load Library Condition
                     *  -----------------------
                     */
                    add_filter( 'sdweddingdirectory/enable-script/input-dropdown', function( $args = [] ){

                        return  array_merge( $args, [ 'real-wedding'   =>  true ] );
                        
                    } );
                }
            }
        }

        /**
         *  3. Dashboard Page
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
                if( ! empty( $page ) && $page == esc_attr( 'real-wedding' )  ){

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
                                        esc_attr__( 'Real Wedding', 'sdweddingdirectory-real-wedding' ),

                                        /**
                                         *  2. Description
                                         *  --------------
                                         */
                                        esc_url( get_the_permalink( parent:: realwedding_post_id() ) ),

                                        /**
                                         *  3. Translation Ready String
                                         *  ---------------------------
                                         */
                                        esc_attr__( 'Preview', 'sdweddingdirectory-real-wedding' )
                                );

                            ?>
                            </div>

                        </div>

                        <div class="card-shadow mb-3">

                            <div class="card-shadow-body">
                            <?php

                                /**
                                 *  Translation Ready String
                                 *  ------------------------
                                 */
                                printf( '<p><strong>%1$s</strong></p> <p class="mb-0"><strong>%2$s</strong></p>',

                                    esc_attr__( 'Real Wedding posts will only go live online once all required fields have been completed.', 'sdweddingdirectory-real-wedding' ),

                                    esc_attr__( 'These fields include the names of the bride and groom, the event date, a featured image, and a minimum of three gallery images.', 'sdweddingdirectory-real-wedding' )
                                );
                                
                            ?>
                            </div>  

                        </div>


                        <?php

                        /**
                         *  Load the Couple Wedding Website Content
                         *  -----------------------------
                         */
                        if( parent:: realwedding_post_id() == '0' || parent:: realwedding_post_id() == '' ){

                            printf( esc_attr__( 'You have\'n asssign realwedding in %1$s. please contact admin.', 'sdweddingdirectory-real-wedding' ), 

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
                            $_tabs  =  apply_filters( 'sdweddingdirectory/couple-real-wedding/tabs', [] );

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
     *  RealWedding Object Call self
     *  ----------------------------
     */
    SDWeddingDirectory_Dashboard_Realwedding::get_instance();
}