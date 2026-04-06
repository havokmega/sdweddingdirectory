<?php
/**
 *  Filter & Hooks
 *  --------------
 */
if( ! class_exists( 'SDWeddingDirectory_Request_Quote_Form_Filters' ) && class_exists( 'SDWeddingDirectory_Request_Quote_Filters' ) ){

    /**
     *  Filter & Hooks
     *  --------------
     */
    class SDWeddingDirectory_Request_Quote_Form_Filters extends SDWeddingDirectory_Request_Quote_Filters{

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
             *  4. Request Quote Button Load
             *  ----------------------------
             */
            add_action( 'sdweddingdirectory/venue/singular/header-button/bottom', [ $this, 'venue_singular_request_button' ], absint( '50' ), absint( '1' ) );

            /**
             *  Venue Post Layout 2 : Request Quote Features Filter
             *  ----------------------------------------------------- 
             */
            add_filter( 'sdweddingdirectory/venue/post/request-quote', [ $this, 'venue_request_quote' ], absint( '10' ), absint( '2' ) );
        }

        /**
         *  Venue Post Request Quote
         *  --------------------------
         */
        public static function venue_request_quote( $collection = '', $args = [] ){

            /**
             *  Extract
             *  -------
             */
            extract( wp_parse_args( $args, [

                'handler'           =>      '',

                'post_id'           =>      absint( '0' ),

                'button_class'      =>      apply_filters( 'sdweddingdirectory/class', [ 'btn', 'btn-primary', 'btn-block' ] ),

                'button_string'     =>      apply_filters( 'sdweddingdirectory/venue/post/request-quote/button-name',

                                                /**
                                                 *  1. Translation Ready String
                                                 *  ---------------------------
                                                 */
                                                esc_attr__( 'Request Pricing', 'sdweddingdirectory-request-quote' )
                                            )

            ] ) );

            /**
             *   Make sure post id not empty!
             *   ----------------------------
             */
            if(  empty( $post_id )  ){

                return          $collection;
            }

            /**
             *  Extract
             *  -------
             */
            extract( [

                'is_venue_post'   =>   parent:: is_venue_post(  absint( $post_id )  ),

                'owner_is_vendor'   =>   parent:: venue_author_is_vendor(  absint( $post_id )  ),

            ] );

            /**
             *  Check all value is valid ? that mean this venue is created [ VENDOR ] author
             *  ------------------------------------------------------------------------------
             */
            if( $is_venue_post && $owner_is_vendor ){
                
                /**
                 *  If User Not Login then showing login popup
                 *  ------------------------------------------
                 */
                if( ! is_user_logged_in() ){

                    /**
                     *  Handling
                     *  --------
                     */
                    $handler    .=      sprintf(   '<div class="sdweddingdirectory-request-quote-btn d-grid">

                                                        <a class="%1$s" %4$s>%3$s</a>

                                                    </div>',

                                                    /**
                                                     *  1. Button Style
                                                     *  ---------------
                                                     */
                                                    esc_attr( $button_class ),

                                                    /**
                                                     *  2. Venue Post ID
                                                     *  ------------------
                                                     */
                                                    absint( $post_id ),

                                                    /**
                                                     *  3. Translation String
                                                     *  ---------------------
                                                     */
                                                    esc_attr( $button_string ),

                                                    /**
                                                     *  4. Couple Login Popup
                                                     *  ---------------------
                                                     */
                                                    apply_filters( 'sdweddingdirectory/couple-login/attr', '' )
                                        );
                }

                /**
                 *  Now this user is login
                 *  ----------------------
                 */
                else{

                    /**
                     *  Extract
                     *  -------
                     */
                    extract( [

                        'is_admin'              =>      current_user_can('administrator'),

                        'is_vendor'             =>      parent:: is_vendor(),

                        'is_couple'             =>      parent:: is_couple(),

                    ] );

                    /**
                     *  Is Couple not admin + vendor role
                     *  ---------------------------------
                     */
                    if( $is_couple && ! ( $is_vendor || $is_admin ) ){

                        /**
                         *  Handling
                         *  --------
                         */
                        $handler    .=      sprintf(   '<div class="sdweddingdirectory-request-quote-btn d-grid">

                                                            <a href="javascript:" id="%5$s" class="%1$s sdweddingdirectory-request-quote-popup" 

                                                                data-venue-id="%2$s" 

                                                                data-event-call="0">%3$s</a>

                                                        </div>',

                                                        /**
                                                         *  1. Button Style
                                                         *  ---------------
                                                         */
                                                        esc_attr( $button_class ),

                                                        /**
                                                         *  2. Venue Post ID
                                                         *  ------------------
                                                         */
                                                        absint( $post_id ),

                                                        /**
                                                         *  3. Translation String
                                                         *  ---------------------
                                                         */
                                                        esc_attr( $button_string),

                                                        /**
                                                         *  4. Request Quote Popup
                                                         *  ----------------------
                                                         */
                                                        esc_attr( parent:: popup_id( 'request_quote' ) ),

                                                        /**
                                                         *  5. ID
                                                         *  -----
                                                         */
                                                        esc_attr( parent:: _rand() )
                                            );
                    }

                    /**
                     *  Is Admin / Is Vendor
                     *  --------------------
                     */
                    elseif( $is_vendor || $is_admin ){

                        /**
                         *  Handling
                         *  --------
                         */
                        $handler    .=      sprintf(   '<div class="sdweddingdirectory-request-quote-btn d-grid">

                                                            <button type="button" class="%1$s" disabled="">%2$s</button>

                                                        </div>',

                                                        /**
                                                         *  1. Button Style
                                                         *  ---------------
                                                         */
                                                        esc_attr( $button_class ),

                                                        /**
                                                         *  2. Translation String
                                                         *  ---------------------
                                                         */
                                                        esc_attr( $button_string )
                                            );
                    }
                }
            }

            /**
             *  Return Collection
             *  -----------------
             */
            return          $collection  .  $handler;
        }

        /**
         *  Request Quote Button Load
         *  -------------------------
         */
        public static function venue_singular_request_button( $args = [] ){

            /**
             *  Have Data ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract Args
                 *  ------------
                 */
                extract( wp_parse_args( $args, [

                    'post_id'   =>  absint( '0' )

                ] ) );

                /** 
                 *  Is Empty!
                 *  ---------
                 */
                if( empty( $post_id ) ){

                    return;
                }

                /**
                 *  Translation Ready String
                 *  ------------------------
                 */
                $_request_quote     =   esc_attr__( 'Request Pricing', 'sdweddingdirectory-request-quote' );

                $_have_extra_class  =   apply_filters( 'sdweddingdirectory/venue/singular/header-button/request-quote/class', '' );

                /**
                 *  User Not Login
                 *  --------------
                 */
                if( ! is_user_logged_in() ){

                    printf( '<a class="btn btn-sm btn-primary %3$s" %1$s><i class="fa fa-envelope-o me-1"></i><span>%2$s</span></a>',

                            /**
                             *  1. Vendor Login Model ID
                             *  ------------------------
                             */
                            apply_filters( 'sdweddingdirectory/couple-login/attr', '' ),

                            /**
                             *  2. Translation Ready String
                             *  ---------------------------
                             */
                            esc_attr( $_request_quote ),

                            /**
                             *  3. Have Extra Button
                             *  --------------------
                             */
                            $_have_extra_class
                    );
                }

                /**
                 *  Is Couple
                 *  ---------
                 */
                elseif( is_singular( 'venue' ) && parent:: is_couple() ){

                    printf( '<a href="javascript:" class="btn btn-sm btn-primary sdweddingdirectory-request-quote-popup %4$s" 

                                data-event-call="0"  data-venue-id="%2$s" id="%3$s">

                                <i class="fa fa-envelope-o me-1"></i> 

                                <span>%1$s</span>

                            </a>',

                        /**
                         *  1. Translation Ready String
                         *  ---------------------------
                         */
                        esc_attr( $_request_quote ),

                        /**
                         *  2. venue id
                         *  -------------
                         */
                        absint( $post_id ),

                        /**
                         *  3. ID
                         *  -----
                         */
                        esc_attr( parent:: _rand() ),

                        /**
                         *  4. Have Extra Button
                         *  --------------------
                         */
                        $_have_extra_class
                    );
                }

                /**
                 *  Else
                 *  ----
                 */
                else{

                    printf( '<button type="button" class="btn btn-sm btn-primary  %2$s" disabled>

                                <i class="fa fa-envelope-o me-1"></i>

                                <span>%1$s</span>

                            </button>',

                            /**
                             *  1. Translation Ready String
                             *  ---------------------------
                             */
                            esc_attr( $_request_quote ),

                            /**
                             *  2. Have Extra Button
                             *  --------------------
                             */
                            $_have_extra_class
                    );
                }
            }
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Request_Quote_Form_Filters:: get_instance();
}