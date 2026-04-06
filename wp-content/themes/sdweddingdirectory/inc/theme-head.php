<?php
/**
 *  SDWeddingDirectory - Header Action / Hooks
 *  ----------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Head' ) && class_exists( 'SDWeddingDirectory' ) ){

    /**
     *  SDWeddingDirectory - Header Action / Hooks
     *  ----------------------------------
     */
    class SDWeddingDirectory_Head extends SDWeddingDirectory {

        private static $instance;

        public static function get_instance() {

            if ( null == self::$instance ) {

                self::$instance = new self();
            }

            return self::$instance;
        }

        /**
         *  Construct
         *  ---------
         */
        public function __construct() {

            /**
             *  1. SDWeddingDirectory - <Head> Tag Section
             *  ----------------------------------
             */ 
            add_action( 'sdweddingdirectory/head',  [ $this, 'sdweddingdirectory_head_markup'    ] );
        }

        /**
         *  SDWeddingDirectory - <Head> Tag Section
         *  -------------------------------
         */
        public static function sdweddingdirectory_head_markup(){

            ?>

            <meta charset="<?php bloginfo( 'charset' ); ?>">

            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

            <link rel="preload" href="<?php echo esc_url( get_theme_file_uri( 'assets/fonts/WorkSans-VariableFont_wght.woff2' ) ); ?>" as="font" type="font/woff2" crossorigin>

            <link rel="preload" href="<?php echo esc_url( get_theme_file_uri( 'assets/fonts/Inter-VariableFont_slnt,wght.woff2' ) ); ?>" as="font" type="font/woff2" crossorigin>

            <link rel="profile" href="http://gmpg.org/xfn/11">

            <?php

            if ( is_singular() && pings_open() ) {
                
                printf( '<link rel="pingback" href="%s">' . "\n", esc_url( get_bloginfo( 'pingback_url' ) ) );
            }
        }
    }   

    /**
     *  SDWeddingDirectory - Header Markup Object
     *  ---------------------------------
     */
    SDWeddingDirectory_Head:: get_instance();
}
