<?php
/**
 *  SDWeddingDirectory - Seating Chart Filter Hooks
 *  ----------------------------------------------
 */
if ( ! class_exists( 'SDWeddingDirectory_Seating_Chart_Filters' ) && class_exists( 'SDWeddingDirectory_Seating_Chart_Database' ) ) {

    class SDWeddingDirectory_Seating_Chart_Filters extends SDWeddingDirectory_Seating_Chart_Database {

        private static $instance;

        public static function get_instance() {

            if ( ! isset( self::$instance ) ) {
                self::$instance = new self;
            }

            return self::$instance;
        }

        public function __construct() {

            foreach ( glob( plugin_dir_path( __FILE__ ) . '/*/index.php' ) as $file ) {
                require_once $file;
            }
        }
    }

    SDWeddingDirectory_Seating_Chart_Filters::get_instance();
}
