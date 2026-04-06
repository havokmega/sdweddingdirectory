<?php
/**
 *  -----------------------------------------------
 *  SDWeddingDirectory - Couple Profile Tab Fields - Object
 *  -----------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Couple_Wishlist_Overview_Tab' ) && class_exists( 'SDWeddingDirectory_Wishlist' ) ){

    /**
     *  -----------------------------------------------
     *  SDWeddingDirectory - Couple Profile Tab Fields - Object
     *  -----------------------------------------------
     */
    class SDWeddingDirectory_Couple_Wishlist_Overview_Tab extends SDWeddingDirectory_Wishlist{

        /**
         *  A reference to an instance of this class
         *  ----------------------------------------
         */
        private static $instance;

        /**
         *  Returns an instance of this class.
         *  ----------------------------------
         */
        public static function get_instance() {

            if ( null == self::$instance ) {

                self::$instance = new self();
            }

            return self::$instance;
        }

        /**
         *  Construct
         *  ---------
         */
        public function __construct() {

            /**
             *  Add Filter
             *  ----------
             */
            add_filter( 'sdweddingdirectory/couple-wishlist/tabs', function( $args = [] ){

                return  array_merge( $args, [

                            'couple-wishlist-overview'        =>  [

                                'active'            =>  true,

                                'id'                =>  esc_attr( 'overview' ),

                                'name'              =>  esc_attr__( 'Overview', 'sdweddingdirectory-wishlist' ),

                                'callback'          =>  [ 'class' => __CLASS__, 'member' => 'tab_content' ],
                            ]

                        ] );

            }, absint( '10' ) );
        }

        /**
         *  Profile Update
         *  --------------
         */
        public static function tab_content(){

            /**
             *  Wishlist : Layout [ Box ]
             *  -------------------------
             */
            do_action( 'sdweddingdirectory/wishlist/category-widget', array(

                'row_class'  => esc_attr( 'row row-cols-1 row-cols-md-3 row-cols-sm-2 row-cols-xl-4' )

            ) );
        }
    }

    /**
     *  -----------------------------------------------
     *  SDWeddingDirectory - Couple Profile Tab Fields - Object
     *  -----------------------------------------------
     */
    SDWeddingDirectory_Couple_Wishlist_Overview_Tab::get_instance();
}