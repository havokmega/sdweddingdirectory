<?php
/**
 *  SDWeddingDirectory - Venue Filter & Hooks
 *  -----------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Venue_Singular_Slider_Map_Section' ) && class_exists( 'SDWeddingDirectory_Venue_Singular_Slider_Section' ) ){

    /**
     *  SDWeddingDirectory - Venue Filter & Hooks
     *  -----------------------------------
     */
    class SDWeddingDirectory_Venue_Singular_Slider_Map_Section extends SDWeddingDirectory_Venue_Singular_Slider_Section {

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
             *  Venue Map Slider
             *  ------------------
             */
            add_action( 'sdweddingdirectory/venue/slider/section', [ $this, 'widget' ], absint( '30' ), absint( '1' ) );
        }

        /**
         *  3. Venue Map
         *  ---------------
         */
        public static function widget( $args = [] ){

            /**
             *  Is array ?
             *  ----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract the data
                 *  ----------------
                 */
                extract( $args );

                $_is_active        =   apply_filters( 'sdweddingdirectory/venue-singular/slider-section/map', false );

                $id                =   sanitize_title( __CLASS__ . __FUNCTION__ );

                $icon              =   '<i class="fa fa-map-marker"></i>';

                /**
                 *  Have Data ?
                 *  -----------
                 */
                $venue_latitude   =  get_post_meta( absint( $post_id ), sanitize_key( 'venue_latitude' ), true );

                $venue_longitude  =  get_post_meta( absint( $post_id ), sanitize_key( 'venue_longitude' ), true );

                /**
                 *  Have Data ?
                 *  -----------
                 */
                if( ! empty( $venue_latitude ) && ! empty( $venue_longitude ) ){

                    /**
                     *  Layout 1
                     *  --------
                     */
                    if( $layout == absint( '1' ) ){

                        /**
                         *  Tab Start
                         *  ---------
                         */
                        parent:: slider_tab_start( $id, $_is_active );

                            /**
                             *  Load the Map
                             *  ------------
                             */
                            printf('<div id="%1$s" class="marker_show_on_map is-hidden-map" 

                                        data-latitude="%2$s" 

                                        data-longitude="%3$s" 

                                        data-marker="%4$s" 

                                        data-zoom="9"></div>',

                                    /**
                                     *  1. Random ID
                                     *  ------------
                                     */
                                    esc_attr( parent:: _rand() ),

                                    /**
                                     *  2. Latitude
                                     *  -----------
                                     */
                                    $venue_latitude,

                                    /**
                                     *  3. Longitude
                                     *  ------------
                                     */
                                    $venue_longitude,

                                    /**
                                     *  4. Map Icon
                                     *  ------------
                                     */
                                    apply_filters( 'sdweddingdirectory/term/marker', [

                                        'post_id'   =>   absint( $post_id ),

                                    ] ),

                                    /**
                                     *  5. Zoom Level
                                     *  -------------
                                     */
                                    absint( '5' )
                            );

                        /**
                         *  Tab Content End
                         *  ---------------
                         */
                        print '</div>';
                    }

                    /**
                     *  Layout 2
                     *  --------
                     */
                    if( $layout == absint( '2' ) ){

                        /**
                         *  Load icon
                         *  ---------
                         */
                        parent:: slider_tab_icon_list(

                            $_is_active, sanitize_title( $id ), $icon
                        );
                    }
                }
            }
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Venue_Singular_Slider_Map_Section:: get_instance();
}