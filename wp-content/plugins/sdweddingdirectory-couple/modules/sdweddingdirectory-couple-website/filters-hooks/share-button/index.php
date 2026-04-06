<?php
/**
 *  SDWeddingDirectory - Share Button
 *  -------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Website_Share_Button' ) && class_exists( 'SDWeddingDirectory_Couple_Website_Filters' ) ){

    /**
     *  SDWeddingDirectory - Share Button
     *  -------------------------
     */
    class SDWeddingDirectory_Website_Share_Button extends SDWeddingDirectory_Couple_Website_Filters {

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
             *  SDWeddingDirectory - User Configuration Meta
             *  ------------------------------------
             */
            add_filter( 'sdweddingdirectory/couple-dashboard/share-button', [ $this, 'share_website' ], absint( '10' ), absint( '1' ) );
        }

        /**
         *  SDWeddingDirectory - User Configuration Meta
         *  ------------------------------------
         */
        public static function share_website( $args = [] ){

            /**
             *  Extract Args
             *  ------------
             */
            extract( wp_parse_args( $args, [

                'post_id'       =>      absint( '0' )

            ] ) );

            /**
             *  Make sure post id not empty!
             *  ----------------------------
             */
            if( empty( $post_id ) ){

                return;
            }

            /**
             *  Have Social Media Share Object ?
             *  --------------------------------
             */
            printf( '<a class="btn btn-outline-white btn-sm sdweddingdirectory-share-post-model" 

                        data-post-id="%1$s" href="javascript:"><i class="fa fa-share-alt"></i> %2$s</a>',

                    /**
                     *  1. Post ID
                     *  ----------
                     */
                    absint( parent:: website_post_id() ),

                    /**
                     *  2. Translation Ready String
                     *  ---------------------------
                     */
                    esc_attr__( 'Share', 'sdweddingdirectory-couple-website' )
            );
        }
    }

    /**
     *  SDWeddingDirectory - Share Button
     *  -------------------------
     */
    SDWeddingDirectory_Website_Share_Button:: get_instance();
}