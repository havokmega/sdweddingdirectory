<?php
/**
 *  SDWeddingDirectory - Social Login Registration (Nextend removed)
 *  ----------------------------------------------------------------
 *  Social login via Nextend has been removed. This class is kept as
 *  a shell because provider subclasses (facebook, google, twitter)
 *  extend it. Those subclasses hook into provider-specific redirect
 *  filters that also only fire when Nextend is active, so they are
 *  effectively inert as well.
 */
if( ! class_exists( 'SDWeddingDirectory_Social_Login_Registration' ) && class_exists( 'SDWeddingDirectory_Config' ) ) {

    class SDWeddingDirectory_Social_Login_Registration extends SDWeddingDirectory_Config{

        private static $instance;

        public static function get_instance(){

            if( ! isset( self::$instance ) ){

                self::$instance = new self;
            }

            return  self::$instance;
        }

        public function __construct(){

            /**
             *  Load provider subclass files (facebook, google, twitter).
             *  These are inert without Nextend but kept for clean autoloading.
             */
            foreach ( glob( plugin_dir_path( __FILE__ ) . '/*/index.php' ) as $file ) {

                require_once $file;
            }
        }
    }

    SDWeddingDirectory_Social_Login_Registration::get_instance();
}
