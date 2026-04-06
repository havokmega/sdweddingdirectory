<?php
/**
 *  SDWeddingDirectory - Venue Filter & Hooks
 *  -----------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Venue_Singular_Slider_Featured_Image_Section' ) && class_exists( 'SDWeddingDirectory_Venue_Singular_Slider_Section' ) ){

    /**
     *  SDWeddingDirectory - Venue Filter & Hooks
     *  -----------------------------------
     */
    class SDWeddingDirectory_Venue_Singular_Slider_Featured_Image_Section extends SDWeddingDirectory_Venue_Singular_Slider_Section {

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
             *  1. Venue Slider
             *  -----------------
             */
            add_action( 'sdweddingdirectory/venue/slider/section', [ $this, 'widget' ], absint( '10' ), absint( '1' ) ); 
        }

        /**
         *  1. Venue Slider
         *  -----------------
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

                /**
                 *  Have Content Parent Key
                 *  -----------------------
                 */
                $_is_active        =   apply_filters( 'sdweddingdirectory/venue-singular/slider-section/background-image', true );

                $id                =   sanitize_title( __CLASS__ . __FUNCTION__ );

                $icon              =   '<i class="fa fa-image"></i>';

                /**
                 *  Have Data ?
                 *  -----------
                 */
                $thumpnail_id     =  get_post_meta( absint( $post_id ), sanitize_key( '_thumbnail_id' ), true );

                /**
                 *  Have Media ?
                 *  ------------
                 */
                $_have_image = '';

                if( ! empty( $thumpnail_id ) ){
                    $_have_image    =   apply_filters( 'sdweddingdirectory/media-data', [

                                            'media_id'      =>      absint( $thumpnail_id ),

                                            'image_size'    =>      esc_attr( 'sdweddingdirectory_img_1920x700' ),

                                            'get_data'      =>      esc_attr( 'url' ),

                                        ] );
                }

                if( empty( $_have_image ) ){
                    $location_terms = wp_get_post_terms( $post_id, 'venue-location' );
                    if( ! is_wp_error( $location_terms ) && ! empty( $location_terms ) ){
                        foreach( $location_terms as $loc_term ){
                            $loc_file = get_template_directory() . '/assets/images/locations/' . sanitize_file_name( $loc_term->slug ) . '.jpg';
                            if( file_exists( $loc_file ) ){
                                $_have_image = esc_url( get_template_directory_uri() . '/assets/images/locations/' . sanitize_file_name( $loc_term->slug ) . '.jpg' );
                                break;
                            }
                        }
                    }
                    if( empty( $_have_image ) ){
                        $default_loc = get_template_directory() . '/assets/images/locations/san-diego.jpg';
                        if( file_exists( $default_loc ) ){
                            $_have_image = esc_url( get_template_directory_uri() . '/assets/images/locations/san-diego.jpg' );
                        }
                    }
                }

                if( ! empty( $_have_image ) ){

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
                            printf('<div class="single-img" style="background:url(%1$s) center center no-repeat; background-size:cover;"></div>',

                                /**
                                 *  1. Background Image
                                 *  -------------------
                                 */
                                $_have_image
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
    SDWeddingDirectory_Venue_Singular_Slider_Featured_Image_Section:: get_instance();
}