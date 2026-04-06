<?php
/**
 *  SDWeddingDirectory - Vendor Singular Page Rating
 *  ----------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Vendor_Singular_Page_Reviews' ) && class_exists( 'SDWeddingDirectory_Review_Filters' ) ){

    /**
     *  SDWeddingDirectory - Vendor Singular Page Rating
     *  ----------------------------------------
     */
    class SDWeddingDirectory_Vendor_Singular_Page_Reviews extends SDWeddingDirectory_Review_Filters{

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
             *  Vendor's Ratings
             *  ----------------
             */
            add_action( 'sdweddingdirectory/vendor-singular/tabs', [ $this, 'vendor_rating' ], absint( '30' ), absint( '1' ) );
        }

        /**
         *  3. Vendor's Rating
         *  ------------------
         */
        public static function vendor_rating( $args = [] ){

            /**
             *  Is Array ?
             *  ----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( wp_parse_args( $args, [

                    'post_id'   =>  absint( '0' ),

                    'layout'    =>  absint( '1' )

                ] ) );

                /**
                 *  Make sure post id not empty!
                 *  ----------------------------
                 */
                if( empty( $post_id ) ){

                    return;
                }

                /**
                 *  Have Content Parent Key
                 *  -----------------------
                 */
                $_active            =   apply_filters( 'sdweddingdirectory/vendor/singular/active-tab/review', false );

                $_tab_id            =   sanitize_title( __FUNCTION__ );

                $_icon              =   '<i class="fa fa-star-half-o"></i>';

                $_name              =   esc_attr__( 'Reviews', 'sdweddingdirectory-reviews' );

                /**
                 *  Layout 1
                 *  --------
                 */
                if( $layout == absint( '1' ) ){

                    /**
                     *  Vendor Tab
                     *  ----------
                     */
                    printf( '<li class="nav-item">

                                <a  class="nav-link %1$s" id="%2$s-tab" href="#%2$s" aria-controls="%2$s" 

                                    data-bs-toggle="pill" role="tab" aria-selected="true">%4$s %3$s</a>

                            </li>',

                            /**
                             *  1. Tab Active
                             *  -------------
                             */
                            $_active ?  sanitize_html_class( 'active' )  :  '',

                            /**
                             *  2. Tab ID
                             *  ---------
                             */
                            esc_attr( $_tab_id ),

                            /**
                             *  3. Tab Name
                             *  -----------
                             */
                            esc_attr( $_name ),

                            /**
                             *  4. Tab Icon
                             *  -----------
                             */
                            $_icon
                    );
                }

                /**
                 *  Layout 2
                 *  --------
                 */
                if( $layout == absint( '2' ) ){

                    /**
                     *  Tab Start
                     *  ---------
                     */
                    printf('<div class="tab-pane fade %1$s" id="%2$s" role="tabpanel" aria-labelledby="%2$s-tab">',

                            /**
                             *  1. Is Active
                             *  ------------
                             */
                            $_active     ?   esc_attr( 'active show' )   : '',

                            /**
                             *  2. Tab ID
                             *  ---------
                             */
                            esc_attr( $_tab_id ),

                            /**
                             *  3. Tab Name
                             *  -----------
                             */
                            esc_attr( $_name )
                    );

                    ?>

                    <!-- Reviews -->
                    <div class="card-shadow position-relative">

                        <a id="reviews" class="anchor-fake"></a>

                        <div class="card-shadow-header d-md-flex justify-content-between align-items-center">

                            <h3><i class="fa fa-star-o"></i>

                            <?php

                                printf( esc_attr__( 'Reviews For %1$s', 'sdweddingdirectory-reviews' ),

                                      /**
                                       *  1. Get The Venue Name
                                       *  -----------------------
                                       */
                                      get_post_meta( absint( $post_id ), sanitize_key( 'company_name' ), true )
                                );
                            ?>

                            </h3>

                       </div>

                       <div class="card-shadow-body border-bottom">

                          <div class="row g-0">

                             <div class="col-md-auto">

                                <div class="review-count">
                                  <?php

                                      /**
                                       *  Venue Review Overview
                                       *  -----------------------
                                       */
                                      printf('<span>%1$s</span>

                                              <small>%2$s</small>

                                              <div class="sdweddingdirectory_review stars" data-review="%1$s"></div>',

                                            /**
                                             *  1. Get Average Rating
                                             *  ---------------------
                                             */
                                            apply_filters( 'sdweddingdirectory/rating/average', absint( '0' ), [

                                                'vendor_id'        =>      absint( $post_id ),

                                            ] ),

                                            /**
                                             *  2. Translation String
                                             *  ---------------------
                                             */
                                            esc_attr__( 'out of 5.0', 'sdweddingdirectory-reviews' )
                                      );
                                  ?>

                                </div>

                             </div>

                             <div class="col">

                                <div class="row mt-3 mt-md-0">
                                  <?php

                                    /**
                                     *  Quality Review
                                     *  --------------
                                     */
                                    parent:: venue_review_status( array(

                                        'print'         =>      true,

                                        'icon'          =>      apply_filters( 'sdweddingdirectory/rating/icon/quality-of-service',

                                                                    '<i class="fa fa-smile-o"></i>'
                                                                ),

                                        'rating'        =>      apply_filters( 'sdweddingdirectory/rating/average', absint( '0' ), [

                                                                    'vendor_id'     =>  absint( $post_id ),

                                                                    'meta_key'      =>  sanitize_key( 'quality_service' )

                                                                ] ),

                                        'lable'         =>      esc_attr__( 'Quality of Service', 'sdweddingdirectory-reviews' )

                                    ) );

                                    /**
                                     *  Facilities Review
                                     *  -----------------
                                     */
                                    parent:: venue_review_status( array(

                                        'print'         =>      true,

                                        'icon'          =>      apply_filters( 'sdweddingdirectory/rating/icon/responsiveness',

                                                                    '<i class="fa fa-exchange"></i>'
                                                                ),

                                        'rating'        =>      apply_filters( 'sdweddingdirectory/rating/average', absint( '0' ), [

                                                                    'vendor_id'     =>  absint( $post_id ),

                                                                    'meta_key'      =>  sanitize_key( 'facilities' )

                                                                ] ),

                                        'lable'         =>      esc_attr__( 'Responsiveness', 'sdweddingdirectory-reviews' )

                                    ) );

                                    /**
                                     *  Staff Review
                                     *  ------------
                                     */
                                    parent:: venue_review_status( array(

                                        'print'         =>      true,

                                        'icon'          =>      apply_filters( 'sdweddingdirectory/rating/icon/professionalism',

                                                                    '<i class="fa fa-male"></i>'
                                                                ),

                                        'rating'        =>      apply_filters( 'sdweddingdirectory/rating/average', absint( '0' ), [

                                                                    'vendor_id'     =>  absint( $post_id ),

                                                                    'meta_key'      =>  sanitize_key( 'staff' )

                                                                ] ),

                                        'lable'         =>      esc_attr__( 'Professionalism', 'sdweddingdirectory-reviews' )

                                    ) );

                                    /**
                                     *  Flexibility Review
                                     *  ------------------
                                     */
                                    parent:: venue_review_status( array(

                                        'print'         =>      true,

                                        'icon'          =>      apply_filters( 'sdweddingdirectory/rating/icon/flexibility',

                                                                    '<i class="fa fa-sliders"></i>'
                                                                ),

                                        'rating'        =>      apply_filters( 'sdweddingdirectory/rating/average', absint( '0' ), [

                                                                    'vendor_id'     =>  absint( $post_id ),

                                                                    'meta_key'      =>  sanitize_key( 'flexibility' )

                                                                ] ),

                                        'lable'         =>      esc_attr__( 'Flexibility', 'sdweddingdirectory-reviews' )

                                    ) );

                                    /**
                                     *  Value of money Review
                                     *  ---------------------
                                     */
                                    parent:: venue_review_status( array(

                                        'print'         =>      true,

                                        'icon'          =>      apply_filters( 'sdweddingdirectory/rating/icon/value-for-money',

                                                                    '<i class="fa fa-dollar"></i>'
                                                                ),

                                        'rating'        =>      apply_filters( 'sdweddingdirectory/rating/average', absint( '0' ), [

                                                                    'vendor_id'     =>  absint( $post_id ),

                                                                    'meta_key'      =>  sanitize_key( 'value_of_money' )

                                                                ] ),

                                        'lable'         =>      esc_attr__( 'Value for Money', 'sdweddingdirectory-reviews' )

                                    ) );

                                  ?>
                                </div>

                             </div>

                          </div>

                       </div>

                       <?php

                        /**
                         *  Count publish review
                         *  --------------------
                         */
                        $_have_review =     parent:: have_publish_review( array( 

                                                  /**
                                                   *  2. Vendor ID
                                                   *  ------------
                                                   */
                                                  'vendor_id'  => absint( $post_id )

                                            ) );

                        /**
                         *  Have at least 1 review for this vendor ?
                         *  ----------------------------------------
                         */
                        if( absint( $_have_review ) >= absint( '1' ) ){ 

                            printf(     '<div class="card-shadow-body d-md-flex justify-content-between align-items-center py-3">

                                            <strong>%1$s %3$s %2$s</strong>

                                        </div>',

                                        /**
                                         *  1. Get Counter For Current Venue Review
                                         *  -----------------------------------------
                                         */
                                        absint( $_have_review ),

                                        /**
                                         *  2. Get The Venue Name
                                         *  -----------------------
                                         */
                                        get_post_meta( absint( $post_id ), sanitize_key( 'company_name' ), true ),

                                        /**
                                         *  3. Translation Ready String
                                         *  ---------------------------
                                         */
                                        esc_attr__( 'Reviews For', 'sdweddingdirectory-reviews' )
                                  );


                            ?><div class="card-shadow-body border-top"><?php

                                /**
                                 *  Number of Review For Venue
                                 *  ----------------------------
                                 */
                                parent:: get_review_comment( array(

                                    'vendor_id'    =>  absint( $post_id )

                                ) );

                            ?></div><?php

                        } // end if 


                    ?></div><!-- Reviews -->

                    <?php

                    /**
                     *  Tab End
                     *  -------
                     */
                    print '</div>';
                }
            }
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Vendor_Singular_Page_Reviews:: get_instance();
}