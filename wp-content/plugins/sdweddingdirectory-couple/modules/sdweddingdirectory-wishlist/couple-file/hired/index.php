<?php
/**
 *  -----------------------------------------------
 *  SDWeddingDirectory - Couple Profile Tab Fields - Object
 *  -----------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Couple_Wishlist_Hired_Tab' ) && class_exists( 'SDWeddingDirectory_Wishlist' ) ){

    /**
     *  -----------------------------------------------
     *  SDWeddingDirectory - Couple Profile Tab Fields - Object
     *  -----------------------------------------------
     */
    class SDWeddingDirectory_Couple_Wishlist_Hired_Tab extends SDWeddingDirectory_Wishlist{

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

                            'couple-wishlist-hired'        =>  [

                                'active'            =>  false,

                                'id'                =>  esc_attr( 'hired' ),

                                'name'              =>  esc_attr__( 'Hired', 'sdweddingdirectory-wishlist' ),

                                'callback'          =>  [ 'class' => __CLASS__, 'member' => 'tab_content' ],
                            ]

                        ] );

            }, absint( '30' ) );
        }

        /**
         *  Profile Update
         *  --------------
         */
        public static function tab_content(){

            $hired_ids = function_exists( 'sdwd_profile_user_items' )
                       ? sdwd_profile_user_items( 'sdwd_hired_profiles' )
                       : [];

            if( empty( $hired_ids ) ){

                printf( '<div class="text-center py-5 text-muted"><p>%s</p></div>',
                    esc_html__( 'You haven\'t hired any vendors yet. Visit a vendor profile and click Hired to add them here.', 'sdweddingdirectory-wishlist' )
                );
                return;
            }

            print '<div class="row row-cols-xxl-4 row-cols-xl-4 row-cols-lg-3 row-cols-md-2 row-cols-sm-2 row-cols-1">';

            foreach( $hired_ids as $post_id ){

                $post = get_post( absint( $post_id ) );

                if( ! $post || $post->post_status !== 'publish' ){
                    continue;
                }

                $filter = $post->post_type === 'vendor'
                        ? 'sdweddingdirectory/vendor/post'
                        : 'sdweddingdirectory/venue/post';

                print apply_filters( $filter, [

                    'layout'    =>  absint( '1' ),

                    'post_id'   =>  absint( $post_id ),

                    'unique_id' =>  absint( $post_id )

                ] );
            }

            print '</div>';
        }
    }

    /**
     *  -----------------------------------------------
     *  SDWeddingDirectory - Couple Profile Tab Fields - Object
     *  -----------------------------------------------
     */
    SDWeddingDirectory_Couple_Wishlist_Hired_Tab::get_instance();
}