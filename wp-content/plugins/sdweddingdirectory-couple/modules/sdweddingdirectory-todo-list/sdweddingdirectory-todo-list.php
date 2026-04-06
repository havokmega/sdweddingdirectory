<?php
/**
 *  ----------------------
 *  SDWeddingDirectory - Todo List
 *  ----------------------
 *
 *  @package     SDWeddingDirectory - Couple Todo List
 *  @author      Jeff Petaja <jeffpetaja@gmail.com>,<sdweddingdirectorypro@gmail.com>
 *  @copyright   2026 Jeff Petaja
 *  @license     GPL-2.0+
 *
 *  @wordpress-plugin
 *  -----------------
 *  Plugin Name:     SDWeddingDirectory - Todo List
 *  Plugin URI:      https://www.sdweddingdirectory.com
 *  Description:     Couple Todo List plugin using  couple can create own checklist each month task with alert on summary
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
 *  ------------------------
 *  SDWeddingDirectory - Todo Object
 *  ------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Todo_Plugin' ) ) {

    /**
     *  ------------------------
     *  SDWeddingDirectory - Todo Object
     *  ------------------------
     */
    class SDWeddingDirectory_Todo_Plugin {

        /**
         *  Plugin - Version
         *  ================
         */
        const   VERSION     =       '1.2.5';

        /**
         *  Plugin - Slug
         *  =============
         */
        const   SLUG        =       'sdweddingdirectory-todo-list';

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
            add_action( 'plugins_loaded', [ $this, 'plugins_loaded' ], absint( '130' ) );
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
             *  Read Plugin Files
             *  -----------------
             */
            if( ! function_exists('get_plugin_data') ){

                require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
            }

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
            }
        }

        /**
         *  Define Constant
         *  ---------------
         */
        private function constant(){

            /**
             *  Plugin version
             *  --------------
             */
            define( 'SDWEDDINGDIRECTORY_TODO_VERSION', self:: VERSION );
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
            $this->files[]  =   'database/index.php';

            /**
             *  Filter Files
             *  ------------
             */
            $this->files[]  =   'filters-hooks/index.php';

            /**
             *  Functions Files
             *  ---------------
             */
            $this->files[]  =   'functions/index.php';

            /**
             *  Admin Files
             *  -----------
             */
            $this->files[]  =   'admin-file/index.php';

            /**
             *  Couple Files
             *  ------------
             */
            $this->files[]  =   'couple-file/index.php';

            /**
             *  AJAX Scripts
             *  ------------
             */
            $this->files[]  =   'ajax/index.php';

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
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Todo_Plugin:: get_instance();
}
