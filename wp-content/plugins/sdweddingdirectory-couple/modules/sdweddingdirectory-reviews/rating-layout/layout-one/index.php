<?php
/**
 *  Filter & Hooks
 *  --------------
 */
if( ! class_exists( 'SDWeddingDirectory_Review_Layout_One' ) && class_exists( 'SDWeddingDirectory_Review_Layout' ) ){

    /**
     *  Filter & Hooks
     *  --------------
     */
    class SDWeddingDirectory_Review_Layout_One extends SDWeddingDirectory_Review_Layout{

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
             *  1. Rating post load 
             *  -------------------
             */
            add_filter( 'sdweddingdirectory/rating', [ $this, 'rating_post_layout' ], absint( '10' ), absint( '1' ) );
        }

        /**
         *  Load Reviews
         *  ------------
         */
        public static function rating_post_layout( $args = [] ){

            /**
             *  Have Args ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract Args
                 *  ------------
                 */
                extract( wp_parse_args( $args, [

                    'post_id'       =>      absint( '0' ),

                ] ) );

                /**
                 *  Make sure post id not empty!
                 *  ----------------------------
                 */
                if( empty( $post_id ) ){

                    return;
                }

                /**
                 *  Args
                 *  ----
                 */
                $rating_post_obj       =       apply_filters( 'sdweddingdirectory/rating/post-data', $args );

                /**
                 *  Extract Args
                 *  ------------
                 */
                extract( $rating_post_obj );

                /**
                 *  Return Review Media
                 *  -------------------
                 */
                return

                sprintf( '  <div class="reviews-media">

                                <div class="media">

                                    <img class="thumb" src="%1$s" alt="user image">

                                    <div class="media-body">

                                        %2$s <!-- Rating Toggle -->

                                        <h3 class="fw-7">%3$s</h3>

                                        <p>%4$s</p>

                                        %5$s <!-- Vendor Have Response -->

                                    </div>

                                </div>

                            </div>', 

                            /**
                             *  1. Couple Image
                             *  ---------------
                             */
                            esc_url( $couple_profile_img ),

                            /**
                             *  2. Show Review Toggle
                             *  ---------------------
                             */
                            apply_filters( 'sdweddingdirectory/rating/show-toggle', $rating_post_obj ),

                            /** 
                             *  3. Review Title
                             *  ---------------
                             */
                            esc_attr( get_the_title( $post_id ) ),

                            /**
                             *  4. Review Content
                             *  -----------------
                             */
                            esc_textarea( $couple_comment ),

                            /**
                             *  5. Vendor Have Comment
                             *  ----------------------
                             */
                            self:: vendor_response(  $rating_post_obj  )
                );
            }
        }

        /**
         *  Vendor Response
         *  ---------------
         */
        public static function vendor_response( $args = [] ){

            /**
             *  Have Args ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract Args
                 *  ------------
                 */
                extract( wp_parse_args( $args, [

                    'post_id'       =>      absint( '0' ),

                ] ) );

                /**
                 *  Make sure post id not empty!
                 *  ----------------------------
                 */
                if( empty( $post_id ) ){

                    return;
                }

                /**
                 *  Make sure vendor comment exists
                 *  -------------------------------
                 */
                if( !  empty( trim( $vendor_comment  ) )  ){

                    /**
                     *  Return Vendor's Comment
                     *  -----------------------
                     */
                    return      sprintf(   '<div class="media reply-box">

                                                <img src="%1$s" alt="user image" class="thumb">

                                                <div class="media-body">
                                                    
                                                    <div class="d-md-flex justify-content-between mb-3">

                                                        <h4 class="mb-0">%2$s</h4>

                                                        <small class="txt-blue">%3$s</small>

                                                    </div>
                                                 
                                                 %4$s

                                                </div>

                                            </div>',

                                           /**
                                            *  1. Vendor Image
                                            *  ---------------
                                            */
                                            esc_url( $vendor_business_img ),

                                           /**
                                            *  2. Vendor Full Name
                                            *  -------------------
                                            */
                                            esc_attr( $vendor_name ),

                                           /**
                                            *  3. Vendor Publish Review Time / Date
                                            *  -------------------------------------
                                            */
                                            apply_filters( 'sdweddingdirectory/date-format', [

                                                'date'      =>      esc_attr( $vendor_response_time )

                                            ] ),

                                           /**
                                            *  4. Vendor Comment
                                            *  -----------------
                                            */
                                           esc_attr( $vendor_comment )
                                );
                }
            }
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Review_Layout_One:: get_instance();
}