<?php
/**
 *  SDWeddingDirectory - Venue Filter & Hooks
 *  -----------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Vendor_Venue_Images_Filters' ) && class_exists( 'SDWeddingDirectory_Vendor_Venue_Filters' ) ){

    /**
     *  SDWeddingDirectory - Venue Filter & Hooks
     *  -----------------------------------
     */
    class SDWeddingDirectory_Vendor_Venue_Images_Filters extends SDWeddingDirectory_Vendor_Venue_Filters{

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
        public function __construct(){

            /**
             *  1. SDWeddingDirectory - Placeholder Filter
             *  ----------------------------------
             */
            add_filter( 'sdweddingdirectory/placeholder', [ $this, 'placeholder' ], absint( '30' ), absint( '1' ) );

            /**
             *  2. Venue Image Size
             *  ---------------------
             */
            add_filter( 'sdweddingdirectory/image-size', [ $this, 'image_size' ], absint( '30' ), absint( '1' ) );
        }

        /**
         *  Image Size
         *  ----------
         */
        public static function image_size( $args = [] ){

            return  array_merge( 

                        $args,

                        array(

                            [
                                'id'            =>      esc_attr( 'sdweddingdirectory_img_600x450' ),

                                'name'          =>      esc_attr__( 'Venue Featured Image', 'sdweddingdirectory-venue' ),

                                'width'         =>      absint( '600' ),

                                'height'        =>      absint( '450' )
                            ],

                            [
                                'id'            =>      esc_attr( 'sdweddingdirectory_img_1250x600' ),

                                'name'          =>      esc_attr__( 'Venue Singular Gallery', 'sdweddingdirectory-venue' ),

                                'width'         =>      absint( '1200' ),

                                'height'        =>      absint( '600' )
                            ],

                            [
                                'id'            =>      esc_attr( 'sdweddingdirectory_img_1920x700' ),

                                'name'          =>      esc_attr__( 'Venue Singular Banner', 'sdweddingdirectory-venue' ),

                                'width'         =>      absint( '1920' ),

                                'height'        =>      absint( '700' )
                            ],

                            [
                                'id'            =>      esc_attr( 'sdweddingdirectory_img_600x385' ),

                                'name'          =>      esc_attr__( 'Venue Thumbnails', 'sdweddingdirectory-venue' ),

                                'width'         =>      absint( '600' ),

                                'height'        =>      absint( '385' )
                            ],
                        )
                    );
        }

        /**
         *  Placeholder
         *  -----------
         */
        public static function placeholder( $args = [] ){

            /**
             *  Path
             *  ----
             */
            $path       =       plugin_dir_url( __FILE__ );

            /**
             *  Add Slider Placeholder
             *  ----------------------
             */
            return  array_merge(

                        /**
                         *  Have Args ?
                         *  -----------
                         */
                        $args,

                        /**
                         *  Merge New Args
                         *  --------------
                         */
                        array(

                            [
                                'name'              =>  esc_attr__( 'Venue Post', 'sdweddingdirectory-venue' ),

                                'id'                =>  esc_attr( 'venue-post' ),

                                'placeholder'       =>  [

                                    'venue-post'      =>  esc_url(  $path  .  esc_attr( 'venue-post.jpg' ) ),

                                    'venue-banner'    =>  esc_url(  $path  .  esc_attr( 'venue-banner.jpg' ) ),

                                    'venue-gallery'   =>  esc_url(  $path  .  esc_attr( 'venue-gallery.jpg' ) ),
                                ]
                            ]
                        )
                    );
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Vendor_Venue_Images_Filters:: get_instance();
}