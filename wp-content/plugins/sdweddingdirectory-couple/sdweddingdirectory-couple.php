<?php
/**
 *  --------------------------
 *  SDWeddingDirectory - Couple
 *  --------------------------
 *
 *  @package     SDWeddingDirectory - Couple
 *  @author      SDWeddingDirectory
 *
 *  @wordpress-plugin
 *  -----------------
 *  Plugin Name:     SDWeddingDirectory - Couple
 *  Plugin URI:      https://www.sdweddingdirectory.com
 *  Description:     Consolidated couple dashboard and planning tools module loader.
 *  Version:         1.0.0
 *  Author:          SDWeddingDirectory
 *  Author URI:      https://www.sdweddingdirectory.com
 *  Text Domain:     sdweddingdirectory
 *  Domain Path:     /languages
 *  License:         GPL-2.0+
 *  License URI:     http://www.gnu.org/licenses/gpl-2.0.txt
 */

defined( 'ABSPATH' ) || exit;

if( ! class_exists( 'SDWeddingDirectory_Couple_Plugin' ) ){

    /**
     *  SDWeddingDirectory - Couple
     *  --------------------------
     */
    class SDWeddingDirectory_Couple_Plugin {

        /**
         *  Module entrypoint files
         *  -----------------------
         */
        private $module_files = [];

        /**
         *  Singleton instance
         *  ------------------
         */
        private static $instance;

        /**
         *  Init
         *  ----
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

            if( ! defined( 'SDWEDDINGDIRECTORY_COUPLE_PLUGIN' ) ){

                define( 'SDWEDDINGDIRECTORY_COUPLE_PLUGIN', '1.0.0' );
            }

            $this->module_files = [
                plugin_dir_path( __FILE__ ) . 'modules/sdweddingdirectory-budget-calculator/sdweddingdirectory-budget-calculator.php',
                plugin_dir_path( __FILE__ ) . 'modules/sdweddingdirectory-couple-website/sdweddingdirectory-couple-website.php',
                plugin_dir_path( __FILE__ ) . 'modules/sdweddingdirectory-guest-list/sdweddingdirectory-guest-list.php',
                plugin_dir_path( __FILE__ ) . 'modules/sdweddingdirectory-rsvp/sdweddingdirectory-rsvp.php',
                plugin_dir_path( __FILE__ ) . 'modules/sdweddingdirectory-reviews/sdweddingdirectory-reviews.php',
                plugin_dir_path( __FILE__ ) . 'modules/sdweddingdirectory-request-quote/sdweddingdirectory-request-quote.php',
                plugin_dir_path( __FILE__ ) . 'modules/sdweddingdirectory-seating-chart/sdweddingdirectory-seating-chart.php',
                plugin_dir_path( __FILE__ ) . 'modules/sdweddingdirectory-todo-list/sdweddingdirectory-todo-list.php',
                plugin_dir_path( __FILE__ ) . 'modules/sdweddingdirectory-wishlist/sdweddingdirectory-wishlist.php',
            ];

            $this->load_modules();
        }

        /**
         *  Load modules
         *  ------------
         */
        private function load_modules(){

            foreach( $this->module_files as $file ){

                if( file_exists( $file ) ){

                    require_once $file;
                }
            }
        }
    }

    /**
     *  SDWeddingDirectory - Couple
     *  --------------------------
     */
    SDWeddingDirectory_Couple_Plugin::get_instance();
}
