<?php
/**
 *  SDWeddingDirectory - Real Wedding Left Widget Filter
 *  --------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Real_Wedding_Left_Widget_Filter' ) && class_exists( 'SDWeddingDirectory_Real_Wedding_Filters' ) ){

    /**
     *  SDWeddingDirectory - Real Wedding Left Widget Filter
     *  --------------------------------------------
     */
    class SDWeddingDirectory_Real_Wedding_Left_Widget_Filter extends SDWeddingDirectory_Real_Wedding_Filters{

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
            foreach ( glob( plugin_dir_path( __FILE__ ) . '*/index.php' ) as $file ) {
           
                require_once $file;
            }

            /**
             *  1. Dashboard Scripts
             *  --------------------
             */
            add_action( 'wp_enqueue_scripts', [$this, 'sdweddingdirectory_script'], absint( '60' ) );

            // Removed — Wedding details widget eliminated from couple dashboard.
            // add_action( 'sdweddingdirectory/couple/dashboard/widget/left-side', [ $this, 'widget' ], absint( '50' ) );
        }

        /**
         *  1. Load script / style
         *  ----------------------
         */
        public static function sdweddingdirectory_script(){

            /**
             *  Is Couple Dashboard
             *  -------------------
             */
            if( parent:: dashboard_page_set( 'couple-dashboard' ) ){

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
                 *  Load Library Condition
                 *  -----------------------
                 */
                add_filter( 'sdweddingdirectory/enable-script/input-dropdown', function( $args = [] ){

                    return  array_merge( $args, [ 'real-wedding'   =>  true ] );
                    
                } );
            }
        }

        /**
         *  1. Dashboard / Left Side / Widget Start
         *  ---------------------------------------
         */
        public static function widget(){

            ?>
            <div class="card-shadow">

                <div class="card-shadow-header">

                    <div class="dashboard-head">

                        <h3><?php esc_attr_e( 'Wedding details', 'sdweddingdirectory-real-wedding' ); ?></h3>

                    </div>

                </div>

                <div class="card-shadow-body">

                    <div class="wedding-detail-wrap row">

                        <?php

                            /**
                             *  Real Wedding Main Features Hook
                             *  -------------------------------
                             */
                            do_action( 'sdweddingdirectory/couple/dashboard/real-wedding/left-widget', [

                                'post_id'       =>      absint( parent:: realwedding_post_id() )

                            ] );

                        ?>

                    </div>

                </div>
                
            </div>
            <?php
        }

        /**
         *  Widget Value Not Found
         *  ----------------------
         */
        public static function real_wedding_widget_value_not_found(){

            ?>
            <div class="question">

                <i class="fa fa-question"></i>

            </div>
            <?php
        }

        /**
         *  Found Taxonomy Term ID Count
         *  ----------------------------
         *  @link - https://developer.wordpress.org/reference/functions/get_term_by/#comment-content-3730
         *  ---------------------------------------------------------------------------------------------
         */
        public static function get_taxonomy_term_count( $taxonomy, $term_id = 0 ){

            /**
             *  Make sure term id not empty
             *  ---------------------------
             */
            if( empty( $term_id ) ){

                return;
            }

            /**
             *  Object
             *  ------
             */
            $obj    =   get_term_by(

                            /**
                             *  1. Venue Name
                             *  ---------------
                             */
                            esc_attr( 'id' ),

                            /**
                             *  2. Get Title
                             *  ------------
                             */
                            $term_id, 

                            /**
                             *  3. Categroy Slug
                             *  ----------------
                             */
                            $taxonomy
                        );

            /**
             *  Return Count
             *  ------------
             */
            return      absint( $obj->count );
        }

        /**
         *  Found Taxonomy Term ID Count
         *  ----------------------------
         *  @link - https://developer.wordpress.org/reference/functions/get_term_by/#comment-content-3730
         *  ---------------------------------------------------------------------------------------------
         */
        public static function get_taxonomy_term_name( $taxonomy, $term_id = 0 ){

            /**
             *  Make sure term id not empty
             *  ---------------------------
             */
            if( empty( $term_id ) ){

                return;
            }

            /**
             *  Object
             *  ------
             */
            $obj    =   get_term_by(

                            /**
                             *  1. Venue Name
                             *  ---------------
                             */
                            esc_attr( 'id' ),

                            /**
                             *  2. Get Title
                             *  ------------
                             */
                            $term_id, 

                            /**
                             *  3. Categroy Slug
                             *  ----------------
                             */
                            $taxonomy
                        );

            /**
             *  Return Count
             *  ------------
             */
            return      esc_attr( $obj->name );
        }

    } /* class end **/

    /**
     *  SDWeddingDirectory - Real Wedding Left Widget Filter
     *  --------------------------------------------
     */
    SDWeddingDirectory_Real_Wedding_Left_Widget_Filter:: get_instance();
}