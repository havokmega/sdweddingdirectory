<?php
/**
 *  SDWeddingDirectory - Filter & Hooks
 *  ---------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Couple_Website_Image_Filters' ) && class_exists( 'SDWeddingDirectory_Couple_Website_Filters' ) ){

    /**
     *  SDWeddingDirectory - Filter & Hooks
     *  ---------------------------
     */
    class SDWeddingDirectory_Couple_Website_Image_Filters extends SDWeddingDirectory_Couple_Website_Filters{

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
             *  1. SDWeddingDirectory - Wedding Website Featured Image Size
             *  ---------------------------------------------
             */
            add_filter( 'sdweddingdirectory/image-size', [ $this, 'image_size' ], absint( '80' ), absint( '1' )  );

            /**
             *  2. SDWeddingDirectory - Placeholder Filter
             *  ----------------------------------
             */
            add_filter( 'sdweddingdirectory/placeholder', [ $this, 'placeholder' ], absint( '80' ), absint( '1' ) );
        }

        /**
         *  Image Size
         *  ----------
         */
        public static function image_size( $args = [] ){

            /**
             *  Merget Size
             *  -----------
             */
            return  array_merge(

                        $args,

                        array(

                            [
                                'id'            =>      esc_attr( 'sdweddingdirectory_img_1920x700' ),

                                'name'          =>      esc_attr__( 'Website Layout 1 Slider Image', 'sdweddingdirectory-couple-website' ),

                                'width'         =>      absint( '1920' ),

                                'height'        =>      absint( '700' )
                            ],

                            [
                                'id'            =>      esc_attr( 'sdweddingdirectory_img_515x255' ),

                                'name'          =>      esc_attr__( 'Website Layout 1 Event Image', 'sdweddingdirectory-couple-website' ),

                                'width'         =>      absint( '515' ),

                                'height'        =>      absint( '255' )
                            ],

                            [
                                'id'            =>      esc_attr( 'sdweddingdirectory_img_200x200' ),

                                'name'          =>      esc_attr__( 'Website Layout 1 Groom Image', 'sdweddingdirectory-couple-website' ),

                                'width'         =>      absint( '200' ),

                                'height'        =>      absint( '200' )
                            ],

                            [
                                'id'            =>      esc_attr( 'sdweddingdirectory_img_200x200' ),

                                'name'          =>      esc_attr__( 'Website Layout 1 Bride Image', 'sdweddingdirectory-couple-website' ),

                                'width'         =>      absint( '200' ),

                                'height'        =>      absint( '200' )
                            ],

                            [
                                'id'            =>      esc_attr( 'sdweddingdirectory_img_500x600' ),

                                'name'          =>      esc_attr__( 'Website Layout 1 Gallery Image', 'sdweddingdirectory-couple-website' ),

                                'width'         =>      absint( '500' ),

                                'height'        =>      absint( '600' )
                            ],

                            [
                                'id'            =>      esc_attr( 'sdweddingdirectory_img_1920x888' ),

                                'name'          =>      esc_attr__( 'Website Layout 1 Footer Image', 'sdweddingdirectory-couple-website' ),

                                'width'         =>      absint( '1920' ),

                                'height'        =>      absint( '888' )
                            ],

                            [
                                'id'            =>      esc_attr( 'sdweddingdirectory_img_1400x500' ),

                                'name'          =>      esc_attr__( 'Website Layout 1 Testimonials Background Image', 'sdweddingdirectory-couple-website' ),

                                'width'         =>      absint( '1400' ),

                                'height'        =>      absint( '500' )
                            ],

                            [
                                'id'            =>      esc_attr( 'sdweddingdirectory_img_1500x500' ),

                                'name'          =>      esc_attr__( 'Website Layout 1 Counter Background Image', 'sdweddingdirectory-couple-website' ),

                                'width'         =>      absint( '1500' ),

                                'height'        =>      absint( '500' )
                            ],

                            [
                                'id'            =>      esc_attr( 'sdweddingdirectory_img_600x600' ),

                                'name'          =>      esc_attr__( 'Website Layout 1 Couple Frame Image', 'sdweddingdirectory-couple-website' ),

                                'width'         =>      absint( '600' ),

                                'height'        =>      absint( '600' )
                            ],

                            [
                                'id'            =>      esc_attr( 'sdweddingdirectory_img_1920x1200' ),

                                'name'          =>      esc_attr__( 'Website Layout 1 Couple RSVP Background Image', 'sdweddingdirectory-couple-website' ),

                                'width'         =>      absint( '1920' ),

                                'height'        =>      absint( '1200' )
                            ],
                        )
                    );
        }

        /**
         *  Placeholders
         *  ------------
         */
        public static function placeholder( $args = [] ){
            return $args;
        }
    }

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Couple_Website_Image_Filters:: get_instance();
}
