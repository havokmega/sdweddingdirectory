<?php
/**
 *  SDWeddingDirectory - Real Wedding Image Filter
 *  --------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Real_Wedding_Image_Filter' ) && class_exists( 'SDWeddingDirectory_Real_Wedding_Filters' ) ){

    /**
     *  SDWeddingDirectory - Real Wedding Image Filter
     *  --------------------------------------
     */
    class SDWeddingDirectory_Real_Wedding_Image_Filter extends SDWeddingDirectory_Real_Wedding_Filters{

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
             *  SDWeddingDirectory - Real Wedding Featured Image Size
             *  ---------------------------------------------
             */
            add_filter( 'sdweddingdirectory/image-size', [ $this, 'image_size' ], absint( '10' ), absint( '1' ) );

            /**
             *  3. SDWeddingDirectory - Placeholder Filter
             *  ----------------------------------
             */
            add_filter( 'sdweddingdirectory/placeholder', [ $this, 'placeholder' ], absint( '10' ), absint( '1' ) );
        }

        /**
         *  Image Size
         *  ----------
         */
        public static function image_size( $args = [] ){

            /**
             *  Add Images Size
             *  ---------------
             */
            return  array_merge(

                        /**
                         *  Have Args ?
                         *  -----------
                         */
                        $args,

                        /**
                         *  Add New Args
                         *  ------------
                         */
                        array(

                            [
                                'id'            =>      esc_attr( 'sdweddingdirectory_img_1920x600' ),

                                'name'          =>      esc_attr__( 'RealWedding Page Header Banner', 'sdweddingdirectory-real-wedding' ),

                                'width'         =>      absint( '1920' ),

                                'height'        =>      absint( '600' )
                            ],

                            [
                                'id'            =>      esc_attr( 'sdweddingdirectory_img_500x515' ),

                                'name'          =>      esc_attr__( 'RealWedding Featured', 'sdweddingdirectory-real-wedding' ),

                                'width'         =>      absint( '500' ),

                                'height'        =>      absint( '515' )
                            ],

                            [
                                'id'            =>      esc_attr( 'sdweddingdirectory_img_600x385' ),

                                'name'          =>      esc_attr__( 'RealWedding Gallery Thumbnail', 'sdweddingdirectory-real-wedding' ),

                                'width'         =>      absint( '600' ),

                                'height'        =>      absint( '385' )
                            ],
                        )
                    );
        }

        /**
         *  Placeholder Image
         *  -----------------
         */
        public static function placeholder( $args = [] ){

            /**
             *  Path
             *  ----
             */
            $_image_path    =   SDWEDDINGDIRECTORY_DEV 

                            ?   plugin_dir_url( __FILE__ )  .   '/live/'

                            :   plugin_dir_url( __FILE__ )  .   '/demo/';

            /**
             *  Add Placeholder
             *  ---------------
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
                                'name'              =>  esc_attr__( 'Real Wedding', 'sdweddingdirectory-real-wedding' ),

                                'id'                =>  esc_attr( 'real-wedding' ),

                                'placeholder'       =>  [

                                    'real-wedding'                  =>  esc_url(   $_image_path     .   esc_attr( 'featured.jpg' )  ),

                                    'real-wedding-banner'           =>  esc_url(   $_image_path     .   esc_attr( 'page-banner.jpg' )   ),

                                    'real-wedding-featured'         =>  esc_url(   $_image_path     .   esc_attr( 'featured.jpg' )  ),

                                    'real-wedding-gallery'          =>  esc_url(   $_image_path     .   esc_attr( 'gallery.jpg' )   ),

                                    'real-wedding-gallery-thumb'    =>  esc_url(   $_image_path     .   esc_attr( 'gallery-thumb.jpg' ) ),
                                ]
                            ]
                        )
                    );
        }

    } /* class end **/

    /**
     *  SDWeddingDirectory - Real Wedding Image Filter
     *  --------------------------------------
     */
    SDWeddingDirectory_Real_Wedding_Image_Filter:: get_instance();
}