<?php
/**
 *  SDWeddingDirectory - Couple Dashboard Image Filter & Hooks
 *  --------------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Vendor_Profile_Image_Filters' ) && class_exists( 'SDWeddingDirectory_Vendor_Profile_Filters' ) ){

    /**
     *  SDWeddingDirectory - Couple Dashboard Image Filter & Hooks
     *  --------------------------------------------------
     */
    class SDWeddingDirectory_Vendor_Profile_Image_Filters extends SDWeddingDirectory_Vendor_Profile_Filters{

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
             *  1. SDWeddingDirectory - Couple Dashboard Banner Image Size
             *  --------------------------------------------------
             */
            add_filter( 'sdweddingdirectory/image-size', [ $this, 'image_size' ], absint( '10' ), absint( '1' ) );

            /**
             *  2. SDWeddingDirectory - Couple Dashboard Banner Image Placeholder
             *  ---------------------------------------------------------
             */
            add_filter( 'sdweddingdirectory/placeholder', [ $this, 'image_placeholder' ], absint( '10' ), absint( '1' ) );
        }

        /**
         *  1. Image Size
         *  -------------
         */
        public static function image_size( $args = [] ){

            /**
             *  Image Size Merge
             *  ----------------
             */
            return      array_merge( $args, [

                            [   
                                'id'            =>      esc_attr( 'sdweddingdirectory_img_1920x600' ),

                                'name'          =>      esc_attr__( 'Vendor Dashboard Banner Image', 'sdweddingdirectory' ),

                                'width'         =>      absint( '1920' ),

                                'height'        =>      absint( '600' )
                            ],

                        ] );
        }

        /**
         *  2. Placeholder Image
         *  --------------------
         */
        public static function image_placeholder( $args = [] ){

            /**
             *  Placeholder Merge
             *  -----------------
             */
            return      array_merge( $args, [

                            [
                                'name'              =>  esc_attr__( 'Vendor Dashboard', 'sdweddingdirectory' ),

                                'id'                =>  esc_attr( 'vendor-dashboard' ),

                                'placeholder'       =>  [

                                    'vendor-profile'        =>  esc_url( SDWEDDINGDIRECTORY_THEME_MEDIA . 'logo/favicon.png' ),

                                    'vendor-brand-image'    =>  esc_url( SDWEDDINGDIRECTORY_THEME_MEDIA . 'logo/favicon.png' ),

                                    'vendor-brand-banner'   =>  esc_url(
                                                                    get_theme_file_uri( 'assets/images/placeholders/vendor-dashboard/vendor-brand-banner.png' )
                                                                ),

                                ],
                            ]

                        ] );
        }
    }

   /**
    *  Couple Dashboard Filer Object Call
    *  ----------------------------------
    */
    SDWeddingDirectory_Vendor_Profile_Image_Filters:: get_instance();
}
