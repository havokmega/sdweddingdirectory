<?php
/**
 *  SDWeddingDirectory - Shortcodes Module (Core Integrated)
 *  --------------------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Shortcodes_Module' ) && class_exists( 'SDWeddingDirectory_Loader' ) ){

    /**
     *  SDWeddingDirectory - Shortcodes Module (Core Integrated)
     *  --------------------------------------------------------
     */
    class SDWeddingDirectory_Shortcodes_Module extends SDWeddingDirectory_Loader{

        /**
         *  Member Variable
         *  ---------------
         */
        private static $instance;

        /**
         *  Module version
         *  --------------
         */
        const VERSION = '1.3.7';

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

            $this->constant();

            add_filter( 'the_content', [ $this, 'the_content_filter' ], absint( '10' ), absint( '1' ) );
            add_filter( 'sdweddingdirectory_clean_shortcode', [ $this, 'sdweddingdirectory_clean_shortcode' ], absint( '10' ), absint( '1' ) );

            require_once plugin_dir_path( __FILE__ ) . 'shortcodes/index.php';
        }

        /**
         *  Define shortcode constants
         *  --------------------------
         */
        private function constant(){

            if( ! defined( 'SDWEDDINGDIRECTORY_SHORTCODE_VERSION' ) ){

                define( 'SDWEDDINGDIRECTORY_SHORTCODE_VERSION', self:: VERSION );
            }

            if( ! defined( 'SDWEDDINGDIRECTORY_SHORTCODE_URL' ) ){

                define( 'SDWEDDINGDIRECTORY_SHORTCODE_URL', plugin_dir_url( __FILE__ ) );
            }

            if( ! defined( 'SDWEDDINGDIRECTORY_SHORTCODE_DIR' ) ){

                define( 'SDWEDDINGDIRECTORY_SHORTCODE_DIR', plugin_dir_path( __FILE__ ) );
            }

            if( ! defined( 'SDWEDDINGDIRECTORY_SHORTCODE_IMAGES' ) ){

                define( 'SDWEDDINGDIRECTORY_SHORTCODE_IMAGES', SDWEDDINGDIRECTORY_SHORTCODE_URL . '/assets/images/' );
            }
        }

        /**
         *  Utility function to deal with auto formatting in shortcode content
         *  ------------------------------------------------------------------
         */
        public static function sdweddingdirectory_clean_shortcode( $content = '' ) {

            $tags       = [ '<br>', '<br/>', '<br />' ];
            $content    = str_replace( $tags, '', $content );

            $content    = strtr(
                $content,
                [
                    '<p>['      => '[',
                    ']</p>'     => ']',
                    ']<br />'   => ']',
                    ']<br>'     => ']',
                    '<br /> ['  => '[',
                    '<p></p>'   => '',
                ]
            );

            return shortcode_unautop( $content );
        }

        /**
         *  The content filter helper
         *  -------------------------
         */
        public static function the_content_filter( $content = '' ) {

            return strtr( $content, [
                '<p>['      => '[',
                ']</p>'     => ']',
                ']<br />'   => ']',
                '<p></p>'   => '',
            ] );
        }
    }

    /**
     *  SDWeddingDirectory - Shortcodes Module (Core Integrated)
     *  --------------------------------------------------------
     */
    SDWeddingDirectory_Shortcodes_Module::get_instance();
}
