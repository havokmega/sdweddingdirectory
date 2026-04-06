<?php
/**
 *  SDWeddingDirectory - Wishlist plugin meta
 *  ---------------------------------
 *
 *  @link( https://github.com/valendesigns/option-tree )
 *
 *  @link( https://github.com/valendesigns/option-tree-theme/blob/master/inc/meta-boxes.php, link)
 *  
 */
if( ! class_exists( 'SDWeddingDirectory_Wishlist_MetaBox' ) && class_exists( 'SDWeddingDirectory_WishList_Plugin_Admin_Files' ) ){

    /**
     *  SDWeddingDirectory Wishlist Metabox
     *  ---------------------------
     */
    class SDWeddingDirectory_Wishlist_MetaBox extends SDWeddingDirectory_WishList_Plugin_Admin_Files{

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
             *  1. Wishlist Meta Filter
             *  -----------------------
             */
            add_filter( 'sdweddingdirectory/meta', [ $this, 'sdweddingdirectory_couple_meta' ], absint('10') );
        }

        public static function sdweddingdirectory_couple_meta( $args = [] ){

            /**
             *  New Meta Merge
             *  --------------
             */
            $new_args       =           array(

                'id'            =>      esc_attr( 'sdweddingdirectory_couple_wishlist' ),

                'title'         =>      esc_attr__( 'Wishlist Data', 'sdweddingdirectory-wishlist' ),

                'pages'         =>      array( 'couple' ),

                'context'       =>      esc_attr( 'normal' ),

                'priority'      =>      esc_attr( 'high' ),

                'fields'        =>      array(

                    array(

                        'id'            =>      sanitize_key( 'sdweddingdirectory_wishlist' ),

                        'label'         =>      esc_attr__( 'Wishlist', 'sdweddingdirectory-wishlist' ),

                        'type'          =>      esc_attr( 'list-item' ),

                        'settings'      =>      array(

                            array(

                                'id'          =>    sanitize_key( 'wishlist_venue_id' ),

                                'label'       =>    esc_attr__('Venue', 'sdweddingdirectory-wishlist'),

                                'type'        =>    esc_attr( 'text' ),

                                // 'post_type'   =>    esc_attr( 'venue' ),

                                // 'type'        =>    esc_attr( 'custom-post-type-select' ),
                            ),

                            array(

                                'id'          =>    sanitize_key( 'wishlist_venue_category' ),

                                'label'       =>    esc_attr__('Venue Category', 'sdweddingdirectory-wishlist'),

                                'type'        =>    esc_attr( 'text' ),

                                // 'type'        =>    esc_attr( 'taxonomy-select' ),

                                // 'taxonomy'    =>    esc_attr( 'venue-type' ),
                            ),

                            array(
                                
                                'id'          =>    sanitize_key( 'wishlist_vendor_id' ),

                                'label'       =>    esc_attr__('Venue Owner', 'sdweddingdirectory-wishlist'),

                                'type'        =>    esc_attr( 'custom-post-type-select' ),

                                'post_type'   =>    esc_attr( 'vendor' ),
                            ),

                            array(
                                
                                'id'          =>    sanitize_key( 'wishlist_timestrap' ),

                                'label'       =>    esc_attr__('Timestrap', 'sdweddingdirectory-wishlist'),

                                'type'        =>    esc_attr( 'text' ),
                            ),

                            array(
                                
                                'id'          =>    sanitize_key( 'wishlist_rating' ),

                                'label'       =>    esc_attr__('Rating', 'sdweddingdirectory-wishlist'),

                                'type'        =>    esc_attr( 'text' ),
                            ),

                            array(
                                
                                'id'          =>    sanitize_key( 'wishlist_estimate_price' ),

                                'label'       =>    esc_attr__('Estimate Price', 'sdweddingdirectory-wishlist'),

                                'type'        =>    esc_attr( 'text' ),
                            ),

                            array(
                                
                                'id'          =>    sanitize_key( 'wishlist_note' ),

                                'label'       =>    esc_attr__('Note', 'sdweddingdirectory-wishlist'),

                                'type'        =>    esc_attr( 'textarea-simple' ),
                            ),

                            array(
                                
                                'id'          =>    sanitize_key( 'wishlist_unique_id' ),

                                'label'       =>    esc_attr__('Wishlist Unique ID', 'sdweddingdirectory-wishlist'),

                                'type'        =>    esc_attr( 'text' ),
                            ),

                            array(
                                
                                'id'          =>    sanitize_key( 'wishlist_hire_vendor' ),

                                'label'       =>    esc_attr__( 'Hire Vendor', 'sdweddingdirectory-wishlist' ),

                                'std'         =>    esc_attr( 'evaluating' ),

                                'type'        =>    esc_attr( 'select' ),

                                'choices'     =>    apply_filters( 'sdweddingdirectory/ot-tree/options',

                                                        apply_filters( 'sdweddingdirectory_wishlist_choose_dropdown', [] )
                                                    )
                            ),
                        ),
                    ),
                )
            );

            /**
             *  Metabox - Merge
             *  ---------------
             */
            return      array_merge( $args, [ $new_args ] );
        }
    }

    /**
     *  Kicking this off by calling 'get_instance()' method
     *  ---------------------------------------------------
     */
    SDWeddingDirectory_Wishlist_MetaBox::get_instance();
}