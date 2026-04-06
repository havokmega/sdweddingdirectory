<?php
/**
 *  SDWeddingDirectory - Follow Vendor Plugin Meta
 *  --------------------------------------
 *  
 *  Option Tree Plugin
 *  ------------------
 *
 *  @link( https://github.com/valendesigns/option-tree )
 *
 *  @link( https://github.com/valendesigns/option-tree-theme/blob/master/inc/meta-boxes.php, link)
 *  
 */
if( ! class_exists( 'SDWeddingDirectory_Follow_Vendor_Meta' ) ){

    /**
     *  SDWeddingDirectory - Follow Vendor Plugin Meta
     *  --------------------------------------
     */
    class SDWeddingDirectory_Follow_Vendor_Meta {

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
             *  1. Venue Post Meta in Left Side Bar
             *  -------------------------------------
             */
            add_filter( 'sdweddingdirectory/meta', [ $this, 'sdweddingdirectory_follow_vendor_couple_post_meta' ], absint( '10' ) );
        }

        /**
         *  1. Request Quote Custom Post Type Meta
         *  --------------------------------------
         */
        public static function sdweddingdirectory_follow_vendor_couple_post_meta( $args = [] ) {

            $new_args       =   array(

                'id'        =>  esc_attr( 'sdweddingdirectory_follow_vendor_metabox' ),

                'title'     =>  esc_attr__( 'Following Vendors', 'sdweddingdirectory' ),

                'pages'     =>  array( 'couple' ),

                'context'   =>  esc_attr( 'normal' ),

                'priority'  =>  esc_attr( 'high' ),

                'fields'    =>  array(

                    array(

                        'id'        =>  sanitize_key( 'sdweddingdirectory_follow_vendors' ),

                        'label'     =>  esc_attr__('Favorite Vendors', 'sdweddingdirectory'),

                        'type'      =>  esc_attr( 'list-item' ),

                        'settings'  =>  array(

                            array(

                                'id'      =>    sanitize_key( 'vendor_id' ),

                                'label'   =>    esc_attr__('Vendor ID', 'sdweddingdirectory'),

                                'type'    =>    esc_attr( 'text' ),
                            ),
                        ),
                    ),
                ),
            );

            return  array_merge( $args, array( $new_args ) );
        }
    }

    /**
     *  Kicking this off by calling 'get_instance()' method
     *  ---------------------------------------------------
     */
    SDWeddingDirectory_Follow_Vendor_Meta::get_instance();
}