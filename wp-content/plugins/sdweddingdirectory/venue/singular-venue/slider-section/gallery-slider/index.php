<?php
/**
 *  SDWeddingDirectory - Venue Filter & Hooks
 *  -----------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Venue_Singular_Slider_Gallery_Section' ) && class_exists( 'SDWeddingDirectory_Venue_Singular_Slider_Section' ) ){

    /**
     *  SDWeddingDirectory - Venue Filter & Hooks
     *  -----------------------------------
     */
    class SDWeddingDirectory_Venue_Singular_Slider_Gallery_Section extends SDWeddingDirectory_Venue_Singular_Slider_Section {

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
             *  Venue Gallery
             *  ---------------
             */
            add_action( 'sdweddingdirectory/venue/slider/section', [ $this, 'widget' ], absint( '20' ), absint( '1' ) );
        }

        /**
         *  2. Venue Gallery
         *  ------------------
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

                $_is_active        =   apply_filters( 'sdweddingdirectory/venue-singular/slider-section/gallery', false );

                $id                =   sanitize_title( __CLASS__ . __FUNCTION__ );

                $icon              =   '<i class="fa fa-th-large"></i>';

                /**
                 *  Gallery Data
                 *  ------------
                 */
                $_have_gallery      =   get_post_meta( absint( $post_id ), sanitize_key( 'venue_gallery' ), true );

                $_condition_1       =   parent:: _have_data( $_have_gallery );

                $_condition_2       =   $_condition_1 && parent:: _is_array( array_filter( parent:: _coma_to_array( $_have_gallery ) ) );

                /**
                 *  Have Media ?
                 *  ------------
                 */
                if( $_condition_2 ){

                    /**
                     *  Have Data ?
                     *  -----------
                     */
                    $gallery_data      =   parent:: _filter_media_ids( array_filter( parent:: _coma_to_array( $_have_gallery ) ) );

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
                             *  Load Gallery
                             *  ------------
                             */
                            ?><div class="owl-carousel owl-theme" id="sdweddingdirectory-venue-singular-gallery-tab-carousel"><?php

                                foreach ( $gallery_data as $_key => $_value) {
                                    
                                    printf('<div class="item" style="background-image: url(%1$s);"></div>',

                                            /**
                                             *  1. Image Source
                                             *  ---------------
                                             */
                                            apply_filters( 'sdweddingdirectory/media-data', [

                                                'media_id'      =>      absint( $_value ),

                                                'image_size'    =>      esc_attr( 'sdweddingdirectory_img_1250x600' ),

                                                'get_data'      =>      esc_attr( 'url' ),

                                            ] )
                                    );

                                }

                            ?></div><?php

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
    SDWeddingDirectory_Venue_Singular_Slider_Gallery_Section:: get_instance();
}