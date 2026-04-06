<?php
/**
 *  SDWeddingDirectory - Review Script
 *  --------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Review_Scripts' ) && class_exists( 'SDWeddingDirectory_Config' ) ){
    
    /**
     *  SDWeddingDirectory - Review Script
     *  --------------------------
     */
    class SDWeddingDirectory_Review_Scripts extends SDWeddingDirectory_Config{

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
             *  SDWeddingDirectory - Script / Style
             *  ---------------------------
             */
            add_action( 'wp_enqueue_scripts', [ $this, 'sdweddingdirectory_script' ], absint( '1200' ) );
        }

        /**
         *  SDWeddingDirectory - Script / Style
         *  ---------------------------
         */
        public static function sdweddingdirectory_script(){

            /**
             *  Have Request ?
             *  --------------
             */
            if( parent:: _load_script( 'sdweddingdirectory/enable-script/review' ) ){

                /**
                 *  Load Review Library
                 *  -------------------
                 */
                wp_enqueue_style( 

                    /**
                     *  1. Slug
                     *  -------
                     */
                    esc_attr( 'review' ),

                    /**
                     *  2. Link
                     *  --------
                     */
                    esc_url( SDWEDDINGDIRECTORY_REVIEW_PLUGIN_LIB . 'review/jquery.rateyo.min.css' ),

                    /**
                     *  3. Dependancy
                     *  -------------
                     */
                    [],

                    /**
                     *  4. Version
                     *  ----------
                     */
                    esc_attr(   SDWEDDINGDIRECTORY_REVIEW_PLUGIN    ), 

                    /**
                     *  5. All Media ?
                     *  --------------
                     */
                    'all' 
                );

                /**
                 *  Review Library Script
                 *  ---------------------
                 */
                wp_enqueue_script( 

                    esc_attr( 'review' ),

                    /**
                     *  2. Link
                     *  --------
                     */
                    esc_url(    SDWEDDINGDIRECTORY_REVIEW_PLUGIN_LIB . 'review/jquery.rateyo.min.js'    ),

                    /**
                     *  3. Dependancy
                     *  -------------
                     */
                    array( 'jquery' ), 

                    /**
                     *  4. Version
                     *  ----------
                     */
                    esc_attr(   SDWEDDINGDIRECTORY_REVIEW_PLUGIN    ), 

                    /**
                     *  5. Load in Footer ?
                     *  -------------------
                     */
                    true 
                );

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
                    array( 'jquery', 'review' ),

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
                 *  Review Object 
                 *  -------------
                 */
                wp_localize_script( 

                    /**
                     *  1. Slug
                     *  -------
                     */
                    esc_attr( sanitize_title( __CLASS__ ) ),

                    /**
                     *  2. Object handler
                     *  -----------------
                     */
                    esc_attr( 'SDWEDDINGDIRECTORY_REVIEW_AJAX' ),

                    /**
                     *  3. Object Args
                     *  --------------
                     */
                    [
                        'normalFill' => sdweddingdirectory_option( 'normalFill' ) ? sdweddingdirectory_option( 'normalFill' ) : esc_attr( '#d9d5d4' ),

                        'ratedFill'  => sdweddingdirectory_option( 'ratedFill' ) ? sdweddingdirectory_option( 'ratedFill' ) : esc_attr( '#ff775a' ),
                    ]
                );
            }
        }
    }   

    /**
     *  SDWeddingDirectory - Review Script Object
     *  ---------------------------------
     */
    SDWeddingDirectory_Review_Scripts:: get_instance();
}