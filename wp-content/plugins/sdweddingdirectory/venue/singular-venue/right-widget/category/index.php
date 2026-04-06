<?php
/**
 *  SDWeddingDirectory - Venue Filter & Hooks
 *  -----------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Venue_Singular_Right_Side_Widget_Category' ) && class_exists( 'SDWeddingDirectory_Venue_Singular_Right_Side_Widget' ) ){

    /**
     *  SDWeddingDirectory - Venue Filter & Hooks
     *  -----------------------------------
     */
    class SDWeddingDirectory_Venue_Singular_Right_Side_Widget_Category extends SDWeddingDirectory_Venue_Singular_Right_Side_Widget {

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
             *  Venue Category Widget
             *  -----------------------
             */
            add_action( 'sdweddingdirectory/venue/singular/right-side/widget', [ $this, 'widget' ], absint( '20' ), absint( '1' ) );
        }

        /**
         *  Venue Category Widget
         *  -----------------------
         */
        public static function widget( $args = [] ){

            /**
             *  Have Data ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract Args
                 *  ------------
                 */
                extract( $args );

                /**
                 *  Term ID
                 *  -------
                 */
                $_category_id   =   apply_filters( 'sdweddingdirectory/post/term-parent', [

                                        'post_id'       =>      absint( $post_id ),

                                        'taxonomy'      =>      esc_attr( 'venue-type' )

                                    ] );
                /**
                 *  @credit - https://developer.wordpress.org/reference/functions/get_term_by/#user-contributed-notes
                 *  -------------------------------------------------------------------------------------------------
                 */
                $_get_terms_data    =   get_term_by(  esc_attr( 'id' ), absint( $_category_id ), sanitize_key( 'venue-type' ) );

                /**
                 *  Have Data ?
                 *  -----------
                 */
                if( parent:: _is_object( $_get_terms_data ) && isset( $_get_terms_data->term_id ) ){

                    printf(     '<div class="widget">

                                    <h3 class="widget-title">%1$s</h3>
                                    
                                    <div class="icon-box-style-3 sided mb-0">

                                        <i class="%2$s"></i>

                                        <a href="%3$s"> %4$s </a>

                                    </div>

                                </div>',

                                /**
                                 *  1. Translation Ready String
                                 *  ---------------------------
                                 */
                                esc_attr__( 'Categories', 'sdweddingdirectory-venue' ),

                                /**
                                 *  2. Term Icon
                                 *  ------------
                                 */
                                apply_filters( 'sdweddingdirectory/term/icon', [  'term_id'   =>   absint( $_get_terms_data->term_id )  ] ),

                                /**
                                 *  3. Term Link
                                 *  ------------
                                 */
                                esc_url( get_term_link( absint( $_get_terms_data->term_id ) ) ),

                                /**
                                 *  4. Term Name
                                 *  ------------
                                 */
                                esc_attr( $_get_terms_data->name )
                    );
                }
            }
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Venue_Singular_Right_Side_Widget_Category:: get_instance();
}