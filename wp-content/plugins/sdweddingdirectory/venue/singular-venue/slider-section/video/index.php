<?php
/**
 *  SDWeddingDirectory - Venue Filter & Hooks
 *  -----------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Venue_Singular_Slider_Video_Section' ) && class_exists( 'SDWeddingDirectory_Venue_Singular_Slider_Section' ) ){

    /**
     *  SDWeddingDirectory - Venue Filter & Hooks
     *  -----------------------------------
     */
    class SDWeddingDirectory_Venue_Singular_Slider_Video_Section extends SDWeddingDirectory_Venue_Singular_Slider_Section {

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
             *  Venue Video Slider
             *  --------------------
             */
            add_action( 'sdweddingdirectory/venue/slider/section', [ $this, 'widget' ], absint( '40' ), absint( '1' ) );
        }

        /**
         *  4. Venue Video
         *  ----------------
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

                $_is_active        =   apply_filters( 'sdweddingdirectory/venue-singular/slider-section/video', false );

                $id                =   sanitize_title( __CLASS__ . __FUNCTION__ );

                $icon              =   '<i class="fa fa-video-camera"></i>';

                /**
                 *  Have Data ?
                 *  -----------
                 */
                $venue_video      =   get_post_meta( absint( $post_id ), sanitize_key( 'venue_video' ), true );

                /**
                 *  Have Media ?
                 *  ------------
                 */
                if( ! empty( $venue_video ) ){

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
                             *  Load Slider
                             *  -----------
                             */
                            printf( '<div class="sdweddingdirectory-post-formate ratio ratio-16x9">%1$s</div>',

                                parent:: sdweddingdirectory_video_embed( wp_oembed_get( $venue_video ) )
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
    SDWeddingDirectory_Venue_Singular_Slider_Video_Section:: get_instance();
}