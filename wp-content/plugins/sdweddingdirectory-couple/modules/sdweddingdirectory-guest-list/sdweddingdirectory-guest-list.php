<?php
/**
 *  -----------------------
 *  SDWeddingDirectory - Guest List
 *  -----------------------
 *
 *  @package     SDWeddingDirectory - Guest List
 *  @author      Hitesh Mahavar <hiteshmahavar22@gmail.com>, <sdweddingdirectorypro@gmail.com>
 *  @copyright   2022 Hitesh Mahavar 
 *  @license     GPL-2.0+
 *
 *  @wordpress-plugin
 *  -----------------
 *  Plugin Name:     SDWeddingDirectory - Guest List
 *  Plugin URI:      https://www.sdweddingdirectory.com
 *  Description:     Guest List plugin used for couple can easy to maintain number of guest invited with email notification.
 *  Version:         1.0.0
 *  Author:          SDWeddingDirectory
 *  Author URI:      https://www.sdweddingdirectory.com
 *  Text Domain:     sdweddingdirectory
 *  Domain Path:     /languages
 *  License:         GPL-2.0+
 *  License URI:     http://www.gnu.org/licenses/gpl-2.0.txt
 */

/** 
 * Exit if accessed directly
 * -------------------------
 */
defined( 'ABSPATH' ) || exit;

/**
 *  ------------------------------
 *  SDWeddingDirectory - Guest List Object
 *  ------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Guest_List_Plugin' ) ){

    /**
     *  ------------------------------
     *  SDWeddingDirectory - Guest List Object
     *  ------------------------------
     */
    class SDWeddingDirectory_Guest_List_Plugin {

        /**
         *  Plugin - Version
         *  ================
         */
        const   VERSION     =       '1.3.0';

        /**
         *  Plugin - Slug
         *  =============
         */
        const   SLUG        =       'sdweddingdirectory-guest-list';

        /**
         *  A reference to an instance of this class
         *  ----------------------------------------
         */
        private static $instance;

        /**
         *  Member variable
         *  ---------------
         *  @var array
         *  ----------
         */
        private $files = [];

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
             *  SDWeddingDirectory - Plugin Activate
             *  ----------------------------
             */
            register_activation_hook( __FILE__, [ $this, 'plugin_activation' ] );

            /**
             *  SDWeddingDirectory - Plugin Deactivate
             *  ------------------------------
             */
            register_deactivation_hook( __FILE__, [ $this, 'plugin_deactivation' ] );

            /**
             *  SDWeddingDirectory - Plugin Load
             *  ------------------------
             */
            add_action( 'plugins_loaded', [ $this, 'plugins_loaded' ], absint( '140' ) );
        }

        /**
         *  Activatation Process
         *  --------------------
         */
        public static function plugin_activation(){}

        /**
         *  Deactivatation Process
         *  ----------------------
         */
        public static function plugin_deactivation(){}

        /**
         *  Load Plugin
         *  -----------
         */
        public function plugins_loaded(){

            /**
             *  Load language file
             *  ------------------
             */
            self:: textdomain();

            /**
             *  SDWeddingDirectory Hooks
             *  ----------------
             */
            self:: sdweddingdirectory_hooks();

            /**
             *  Define Constant
             *  ---------------
             */
            self:: constant();

            /**
             *  Required Files
             *  --------------
             */
            self:: files();
        }

        /**
         *  Check Update Link
         *  -----------------
         */
        public static function get_update_link(){

            return  esc_url( apply_filters( 'sdweddingdirectory/plugin/update', self:: SLUG ) );
        }

        /**
         *  Load language file
         *  ------------------
         */
        public function textdomain(){

            load_plugin_textdomain( self:: SLUG, false, basename( dirname( __FILE__ ) ) . '/languages' );
        }

        /**
         *  SDWeddingDirectory Hooks
         *  ----------------
         */
        private function sdweddingdirectory_hooks(){

            /**
             *  Is Admin ?
             *  ----------
             */
            if(  is_admin()  ){

                /**
                 *  Plugin Update Checker
                 *  ---------------------
                 */
                add_filter( 'sdweddingdirectory_plugin_update', function( $args = [] ){

                    return      array_merge( $args, [

                                    [   'json'  =>  esc_url( self:: get_update_link() ),

                                        'path'  =>  __FILE__,

                                        'slug'  =>  self:: SLUG
                                    ]

                                ] );
                } );

            }else{

                /**
                 *  Plugin information filter
                 *  -------------------------
                 */
                add_filter( 'sdweddingdirectory/plugin', function( $args = [] ){

                    return      array_merge( $args, [

                                    apply_filters( 'sdweddingdirectory/plugin-info-data', get_plugin_data( __FILE__ ) )

                                ] );
                } );

                /**
                 *  Guest-list AJAX nonce for authenticated couple actions
                 *  ------------------------------------------------------
                 */
                add_filter( 'sdweddingdirectory/localize_script', function( $args = [] ){

                    if( ! is_array( $args ) ){

                        $args = [];
                    }

                    $args['sdweddingdirectory_guest_list_security'] = wp_create_nonce( 'sdweddingdirectory_guest_list_security' );

                    return $args;
                } );
            }
        }

        /**
         *  Define Constant
         *  ---------------
         */
        private function constant(){

            /**
             *  Plugin Version
             *  --------------
             */
            define( 'SDWEDDINGDIRECTORY_GUEST_LIST_PLUGIN_VERSION', self:: VERSION );

            /**
             *  Plugin URL
             *  ----------
             */
            define( 'SDWEDDINGDIRECTORY_GUEST_LIST_URL', plugin_dir_url(__FILE__) );

            /**
             *  Plugin DIR
             *  ----------
             */
            define( 'SDWEDDINGDIRECTORY_GUEST_LIST_DIR', plugin_dir_path(__FILE__) );

            /**
             *  Plugin Library
             *  --------------
             */
            define( 'SDWEDDINGDIRECTORY_GUEST_LIST_LIB', SDWEDDINGDIRECTORY_GUEST_LIST_URL . '/assets/library/');
        }

        /**
         *  Required Files
         *  --------------
         */
        private function files(){

            /**
             *  Database Files
             *  --------------
             */
            $this->files[] = 'database/index.php';

            /**
             *  Filter Files
             *  ------------
             */
            $this->files[] = 'filters-hooks/index.php';

            /**
             *  Admin Files
             *  -----------
             */
            $this->files[] = 'admin-file/index.php';

            /**
             *  Couple Access
             *  -------------
             */
            $this->files[] = 'couple-file/index.php';

            /**
             *  Form Files
             *  ----------
             */
            $this->files[] = 'forms/index.php';

            /**
             *  AJAX Scripts
             *  ------------
             */
            $this->files[] = 'ajax/index.php';

            /**
             *  Load Files
             *  ----------
             */
            if( class_exists( 'SDWeddingDirectory_Loader' ) ){

                if( SDWeddingDirectory_Loader:: _is_array( $this->files ) ){

                    foreach ( $this->files as $key => $value) {
                        
                        require_once $value;
                    }
                }
            }
        }
    }  

    /**
     *  ------------------------------
     *  SDWeddingDirectory - Guest List Object
     *  ------------------------------
     */
    SDWeddingDirectory_Guest_List_Plugin:: get_instance();
}
