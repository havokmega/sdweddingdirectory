<?php
/** 
 * Exit if accessed directly
 * -------------------------
 */
defined( 'ABSPATH' ) || exit;

/**
 *  --------------------------------------------------------------
 *  SDWeddingDirectory - [ Elementor - Page Builder ] Plugin Configuration
 *  --------------------------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Elementor_Page_Builder_Configuration' ) && class_exists( 'SDWeddingDirectory' ) ){

    /**
     *  --------------------------------------------------------------
     *  SDWeddingDirectory - [ Elementor - Page Builder ] Plugin Configuration
     *  --------------------------------------------------------------
     */
    class SDWeddingDirectory_Elementor_Page_Builder_Configuration extends SDWeddingDirectory {

        /**
         *  A reference to an instance of this class
         *  ----------------------------------------
         */
        private static $instance;

        /**
         *  Returns an instance of this class.
         *  ----------------------------------
         */
        public static function get_instance() {

            if ( null == self::$instance ) {

                self::$instance = new self();
            }

            return self::$instance;
        }

        /**
         *  Constructor
         *  -----------
         */
        public function __construct() {

            /**
             *  SDWeddingDirectory - Plugin Load
             *  ------------------------
             */
            add_action( 'plugins_loaded', [ $this, 'elementor_disable_redirection' ], absint( '1' ) );

            /**
             *  Register Sidebar
             *  ----------------
             */
            add_action( 'init',  [ $this, 'elementor_init' ], absint( '1' ) );
        }

        /**
         *  Disable Elementor "Getting Started" redirect after plugin activation
         *  --------------------------------------------------------------------
         */
        public static function elementor_disable_redirection(){

            /**
             *  Make sure Elementor is active before trying to remove the action
             *  ----------------------------------------------------------------
             */
            if ( did_action( 'elementor/loaded' ) ) {

                /**
                 *  Remove the redirect hook if Elementor is loaded
                 *  -----------------------------------------------
                 */
                if ( class_exists( '\Elementor\Core\Admin\Admin' ) && isset( \Elementor\Plugin::$instance->admin ) ) {

                    remove_action( 'admin_init', [ \Elementor\Plugin::$instance->admin, 'maybe_redirect_to_getting_started' ] );
                }
            }
        }

        /**
         *  Disable Elementor "Getting Started" redirect after plugin activation
         *  --------------------------------------------------------------------
         */
        public static function elementor_init(){

            /**
             *  Make sure Elementor is active before trying to remove the action
             *  ----------------------------------------------------------------
             */
            if ( did_action( 'elementor/loaded' ) ) {
                
                /**
                 *  Elementor uses 'activated_plugin' hook to trigger the redirect
                 *  --------------------------------------------------------------
                 */
                update_option( 'elementor_activation_redirect', false );

                /**
                 *  Kill the transient so Elementor thinks no redirect is needed
                 *  ------------------------------------------------------------
                 */
                delete_transient( 'elementor_activation_redirect' );
            }
        }
    }

    /**
     *  -------------------------------------------------------
     *  SDWeddingDirectory - [ Elementor - Page Builder ] Plugin Object
     *  -------------------------------------------------------
     */
    SDWeddingDirectory_Elementor_Page_Builder_Configuration::get_instance();
}