<?php
/**
 *  SDWeddingDirectory - Couple Dashboard Image Filter & Hooks
 *  --------------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Couple_Dashboard_Image_Filters' ) && class_exists( 'SDWeddingDirectory_Couple_Dashboard_Filters' ) ){

    /**
     *  SDWeddingDirectory - Couple Dashboard Image Filter & Hooks
     *  --------------------------------------------------
     */
    class SDWeddingDirectory_Couple_Dashboard_Image_Filters extends SDWeddingDirectory_Couple_Dashboard_Filters{

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
                                'id'            =>      esc_attr( 'sdweddingdirectory_img_350x530' ),

                                'name'          =>      esc_attr__( 'Couple Dashboard Banner Image', 'sdweddingdirectory' ),

                                'width'         =>      absint( '350' ),

                                'height'        =>      absint( '530' )
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
                                'name'              =>  esc_attr__( 'Couple Dashboard', 'sdweddingdirectory' ),

                                'id'                =>  esc_attr( 'couple-dashboard' ),

                                'placeholder'       =>  [

                                    'my-profile'        =>  esc_url( SDWEDDINGDIRECTORY_THEME_MEDIA . 'logo/logo_flat.png' ),

                                    'couple-dashboard'      =>  esc_url(

                                                                    /**
                                                                     *  1. Image Path
                                                                     *  -------------
                                                                     */
                                                                    plugin_dir_url( __FILE__ ) . SDWEDDINGDIRECTORY_LIVE_IMAGE .

                                                                    /**
                                                                     *  2. Image Name
                                                                     *  -------------
                                                                     */
                                                                    esc_attr( 'couple-dashboard-banner.jpg' )
                                                                ),

                                    'couple-bride-image'  =>    esc_url(

                                                                    /**
                                                                     *  1. Image Path
                                                                     *  -------------
                                                                     */
                                                                    plugin_dir_url( __FILE__ ) . SDWEDDINGDIRECTORY_LIVE_IMAGE .

                                                                    /**
                                                                     *  2. Image Name
                                                                     *  -------------
                                                                     */
                                                                    esc_attr( 'bride.jpg' )
                                                                ),

                                    'couple-groom-image'  =>    esc_url(

                                                                    /**
                                                                     *  1. Image Path
                                                                     *  -------------
                                                                     */
                                                                    plugin_dir_url( __FILE__ ) . SDWEDDINGDIRECTORY_LIVE_IMAGE .

                                                                    /**
                                                                     *  2. Image Name
                                                                     *  -------------
                                                                     */
                                                                    esc_attr( 'groom.jpg' )
                                                                ),
                                ]
                            ],

                        ] );
        }
    }

   /**
    *  Couple Dashboard Filer Object Call
    *  ----------------------------------
    */
    SDWeddingDirectory_Couple_Dashboard_Image_Filters:: get_instance();
}
