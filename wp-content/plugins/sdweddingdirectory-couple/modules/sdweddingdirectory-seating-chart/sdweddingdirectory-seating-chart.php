<?php
/**
 *  --------------------------------
 *  SDWeddingDirectory - Seating Chart
 *  --------------------------------
 *
 *  @package     SDWeddingDirectory - Seating Chart
 *  @author      SDWeddingDirectory
 *  @copyright   2026 SDWeddingDirectory
 *  @license     GPL-2.0+
 *
 *  @wordpress-plugin
 *  -----------------
 *  Plugin Name:     SDWeddingDirectory - Seating Chart
 *  Plugin URI:      https://www.sdweddingdirectory.com
 *  Description:     Seating chart plugin using couple can create, arrange and assign seats for wedding guests.
 *  Version:         1.0.0
 *  Author:          SDWeddingDirectory
 *  Author URI:      https://www.sdweddingdirectory.com
 *  Text Domain:     sdweddingdirectory
 *  Domain Path:     /languages
 *  License:         GPL-2.0+
 *  License URI:     http://www.gnu.org/licenses/gpl-2.0.txt
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'SDWeddingDirectory_Seating_Chart_Plugin' ) ) {

    class SDWeddingDirectory_Seating_Chart_Plugin {

        const VERSION = '1.0.0';
        const SLUG    = 'sdweddingdirectory-seating-chart';

        private static $instance;
        private $files = [];

        public static function get_instance() {

            if ( null === self::$instance ) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        public function __construct() {

            register_activation_hook( __FILE__, [ $this, 'plugin_activation' ] );
            register_deactivation_hook( __FILE__, [ $this, 'plugin_deactivation' ] );

            add_action( 'plugins_loaded', [ $this, 'plugins_loaded' ], absint( '135' ) );
        }

        public static function plugin_activation() {}
        public static function plugin_deactivation() {}

        public function plugins_loaded() {

            if ( ! function_exists( 'get_plugin_data' ) ) {
                require_once ABSPATH . 'wp-admin/includes/plugin.php';
            }

            self::textdomain();
            self::sdweddingdirectory_hooks();
            self::constant();
            self::files();
        }

        public static function get_update_link() {

            return esc_url( apply_filters( 'sdweddingdirectory/plugin/update', self::SLUG ) );
        }

        public function textdomain() {

            load_plugin_textdomain( self::SLUG, false, basename( dirname( __FILE__ ) ) . '/languages' );
        }

        private function sdweddingdirectory_hooks() {

            if ( is_admin() ) {

                add_filter( 'sdweddingdirectory_plugin_update', function( $args = [] ) {

                    return array_merge( $args, [
                        [
                            'json' => esc_url( self::get_update_link() ),
                            'path' => __FILE__,
                            'slug' => self::SLUG,
                        ],
                    ] );
                } );

            } else {

                add_filter( 'sdweddingdirectory/plugin', function( $args = [] ) {

                    return array_merge( $args, [
                        apply_filters( 'sdweddingdirectory/plugin-info-data', get_plugin_data( __FILE__ ) ),
                    ] );
                } );
            }
        }

        private function constant() {

            define( 'SDWEDDINGDIRECTORY_SEATING_CHART_VERSION', self::VERSION );
            define( 'SDWEDDINGDIRECTORY_SEATING_CHART_URL', plugin_dir_url( __FILE__ ) );
            define( 'SDWEDDINGDIRECTORY_SEATING_CHART_DIR', plugin_dir_path( __FILE__ ) );
        }

        private function files() {

            $this->files[] = 'database/index.php';
            $this->files[] = 'filters-hooks/index.php';
            $this->files[] = 'admin-file/index.php';
            $this->files[] = 'couple-file/index.php';
            $this->files[] = 'ajax/index.php';

            foreach ( $this->files as $file ) {
                require_once $file;
            }
        }
    }

    SDWeddingDirectory_Seating_Chart_Plugin::get_instance();
}
