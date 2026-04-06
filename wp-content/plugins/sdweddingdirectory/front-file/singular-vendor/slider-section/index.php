<?php
/**
 *  SDWeddingDirectory - Vendor Slider Section
 *  -----------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Vendor_Singular_Slider_Section' ) && class_exists( 'SDWeddingDirectory_Singular_Vendor' ) ){

    /**
     *  SDWeddingDirectory - Vendor Slider Section
     *  -----------------------------------------
     */
    class SDWeddingDirectory_Vendor_Singular_Slider_Section extends SDWeddingDirectory_Singular_Vendor{

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
             *  Vendor Banner
             *  -------------
             */
            add_action( 'sdweddingdirectory/vendor/slider/section', [ $this, 'vendor_banner' ], absint( '10' ), absint( '1' ) );
        }

        /**
         *  Vendor Banner
         *  -------------
         */
        public static function vendor_banner( $args = [] ){

            if( parent:: _is_array( $args ) ){

                extract( wp_parse_args( $args, [

                    'post_id'   =>  absint( '0' )

                ] ) );

                if( empty( $post_id ) ){

                    return;
                }

                $banner_id = absint( get_post_meta( absint( $post_id ), sanitize_key( 'profile_banner' ), true ) );

                $banner_url = parent:: _have_media( $banner_id )

                    ?   apply_filters( 'sdweddingdirectory/media-data', [

                            'media_id'      =>  absint( $banner_id ),

                            'image_size'    =>  esc_attr( 'sdweddingdirectory_img_1920x600' )

                        ] )

                    :   esc_url( parent:: placeholder( 'vendor-brand-banner' ) );

                printf( '<div class="tab-pane show active" id="vendor-banner">
                            <section class="vendor-bg" style="background: url(%1$s) no-repeat center; background-size: cover;"> &nbsp; </section>
                        </div>',

                    esc_url( $banner_url )
                );
            }
        }
    }

    /**
     *  SDWeddingDirectory - Vendor Slider Section
     *  -----------------------------------------
     */
    SDWeddingDirectory_Vendor_Singular_Slider_Section::get_instance();
}
