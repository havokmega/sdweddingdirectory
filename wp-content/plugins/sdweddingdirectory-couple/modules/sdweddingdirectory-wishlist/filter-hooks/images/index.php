<?php
/**
 *  Filter & Hooks
 *  --------------
 */
if( ! class_exists( 'SDWeddingDirectory_WishList_Filters' ) && class_exists( 'SDWeddingDirectory_WishList_Filters' ) ){

    /**
     *  Filter & Hooks
     *  --------------
     */
    class SDWeddingDirectory_WishList_Filters extends SDWeddingDirectory_WishList_Filters{

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
             *  Placeholder
             *  -----------
             */
            add_filter( 'sdweddingdirectory/placeholder', [ $this, 'placeholder' ], absint( '30' ), absint( '1' ) );

            /**
             *  Image Size
             *  ----------
             */
            add_filter( 'sdweddingdirectory/image-size', [ $this, 'image_size' ], absint( '10' ), absint( '1' ) );
        }

        /**
         *  Placeholder
         *  -----------
         */
        public static function placeholder( $args = [] ){

            /**
             *  Add Placeholder
             *  ---------------
             */
            return      array_merge(  $args, [

                            [
                                'name'                  =>      esc_attr__( 'Wishlist', 'sdweddingdirectory-wishlist' ),

                                'id'                    =>      esc_attr( 'vendor-manager' ),

                                'placeholder'           =>      [

                                    'wishlist-box'      =>      esc_url(  plugin_dir_url( __FILE__ ) . 'images/' . 'wishlist-box.jpg' )
                                ]
                            ]

                        ] );
        }

        /**
         *  Image Size
         *  ----------
         */
        public static function image_size( $args = [] ){

            /**
             *  Add Image Size
             *  --------------
             */
            return      array_merge( $args, [

                            [
                                'id'            =>      esc_attr( 'sdweddingdirectory_img_400x330' ),

                                'name'          =>      esc_attr__( 'Wishlist Box Layout', 'sdweddingdirectory-wishlist' ),

                                'width'         =>      absint( '400' ),

                                'height'        =>      absint( '330' )
                            ]

                        ] );
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_WishList_Filters:: get_instance();
}