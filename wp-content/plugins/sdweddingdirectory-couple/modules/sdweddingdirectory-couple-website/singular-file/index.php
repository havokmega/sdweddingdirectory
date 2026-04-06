<?php
/**
 *  SDWeddingDirectory - Couple Wedding Website Page
 *  ----------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Couple_Website_Files' ) && class_exists( 'SDWeddingDirectory_Couple_Website_Database' ) ){

    /**
     *  SDWeddingDirectory - Couple Wedding Website Page
     *  ----------------------------------------
     */
    class SDWeddingDirectory_Couple_Website_Files extends SDWeddingDirectory_Couple_Website_Database{

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
        }

        /**
         *  Website Data
         *  ------------
         */
        public static function get_website_meta( $meta_key = '' ){

            /**
             *  Have Data ?
             *  -----------
             */
            if( ! empty( $meta_key ) ){

                return      get_post_meta( absint( get_the_ID() ), sanitize_key( $meta_key ), true );
            }
        }

        /**
         *  Heading + Description Section
         *  -----------------------------
         */
        public static function heading_section( $heading = '', $description = '' ){

            $_have_heading      =   self:: get_website_meta( $heading );

            $_have_description  =   self:: get_website_meta( $description );

            /**
             *  Heading + Description
             *  ---------------------
             */
            printf( '<div class="section-title text-center">%1$s %2$s</div>',

                    /**
                     *  1. Heading
                     *  ----------
                     */
                    ! empty( $_have_heading )   ?  sprintf( '<h1>%1$s</h1>', $_have_heading ) : '',

                    /**
                     *  2. Description
                     *  --------------
                     */
                    ! empty( $_have_description )   ?  sprintf( '<p class="sub-head">%1$s</p>', $_have_description ) : ''
            );
        }
    }

    /**
     *  SDWeddingDirectory - Couple Wedding Website Page
     *  ----------------------------------------
     */
    SDWeddingDirectory_Couple_Website_Files::get_instance();
}