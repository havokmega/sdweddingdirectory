<?php
/**
 *  SDWeddingDirectory - Venue Filter & Hooks
 *  -----------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Venue_Singular_Slider_Section' ) && class_exists( 'SDWeddingDirectory_Singular_Venue' ) ){

    /**
     *  SDWeddingDirectory - Venue Filter & Hooks
     *  -----------------------------------
     */
    class SDWeddingDirectory_Venue_Singular_Slider_Section extends SDWeddingDirectory_Singular_Venue {

        /**
         *  Member Variable
         *  ---------------
         */
        private static $instance;

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

            /**
             *  Load one by one shortcode file
             *  ------------------------------
             */
            foreach ( glob( plugin_dir_path( __FILE__ ) . '/*/index.php' ) as $file ) {
           
                require_once    $file;
            }
        }

        /**
         *  [Helper] = Venue Post Slider Helper for each loop
         *  ---------------------------------------------------
         */
        public static function slider_tab_start( $id = '', $is_active = false ){

            /**
             *  Load the Icon
             *  -------------
             */
            printf('<div class="tab-pane %1$s" id="%2$s" role="tabpanel" aria-labelledby="%2$s-tab">',

                    /**
                     *  1. Is Active ?
                     *  --------------
                     */
                    ( $is_active )

                    ?   esc_attr( 'show active' )

                    :   '',

                    /**
                     *  2. Id
                     *  -----
                     */
                    sanitize_title( $id )
            );
        }

        /**
         *  [Helper] = Venue Post Slider icon section Helper for each loop
         *  ----------------------------------------------------------------
         */
        public static function slider_tab_icon_list( $_is_active = false, $id = '', $icon = ''  ){

            /**
             *  Load the Icon
             *  -------------
             */
            printf('<li class="nav-item" role="presentation">
                        <a class="nav-link %1$s" id="%2$s-tab" data-bs-toggle="pill" href="#%2$s" role="tab" aria-controls="%2$s" aria-selected="true">%3$s</a>
                    </li>', 

                    /**
                     *  1. Is Active ?
                     *  --------------
                     */
                    ( $_is_active )

                    ?   sanitize_html_class( 'active' )

                    :   '',

                    /**
                     *  2. Id
                     *  -----
                     */
                    sanitize_title( $id ),

                    /**
                     *  3. Icon
                     *  -------
                     */
                    $icon
            );
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Venue_Singular_Slider_Section:: get_instance();
}