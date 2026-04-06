<?php
/**
 *  SDWeddingDirectory - Facebook Login / Registration
 *  ------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Facebook_Login_Registration' ) && class_exists( 'SDWeddingDirectory_Social_Login_Registration' ) ) {

    /**
     *  SDWeddingDirectory - Facebook Login / Registration
     *  ------------------------------------------
     */
    class SDWeddingDirectory_Facebook_Login_Registration extends SDWeddingDirectory_Social_Login_Registration{

        /**
         *  Member Variable
         *  ---------------
         */
        private static $instance;

        /**
         *  Initiator
         *  ---------
         */
        public static function get_instance(){

            if( ! isset( self::$instance ) ){

                self::$instance = new self;
            }

            return  self::$instance;
        }

        /**
         *  Provider
         *  --------
         */
        public static function provider(){

            return      esc_attr( 'facebook' );
        }

        /**
         *  Construct
         *  ---------
         */
        public function __construct(){

            /**
             *  After Login - User Redirection Link
             *  -----------------------------------
             */
            add_filter( self:: provider() . '_login_redirect_url', [ $this, 'redirection' ], absint( '10' ), absint( '2' ) );

            /**
             *  After Register - User Redirection Link
             *  --------------------------------------
             */
            add_filter( self:: provider() . '_register_redirect_url', [ $this, 'redirection' ], absint( '10' ), absint( '2' ) );
        }

        /**
         *  Redirection after login
         *  -----------------------
         */
        public static function redirection( $redirectUrl, $provider ){

            /**
             *  Redirection on Couple Dashboard
             *  -------------------------------
             */
            return      apply_filters( 'sdweddingdirectory/couple-menu/page-link', esc_attr( 'couple-dashboard' ) );
        }
    }

    /**
     *  SDWeddingDirectory - Facebook Login / Registration
     *  ------------------------------------------
     */
    SDWeddingDirectory_Facebook_Login_Registration::get_instance();
}