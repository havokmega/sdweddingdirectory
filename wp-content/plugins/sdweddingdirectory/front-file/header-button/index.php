<?php
/**
 *  SDWeddingDirectory - Login Registration Model Popup
 *  -------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Login_Model_Popup' ) && class_exists( 'SDWeddingDirectory_Front_End_Modules' ) ){

    /**
     *  SDWeddingDirectory - Login Registration Model Popup
     *  -------------------------------------------
     */
    class SDWeddingDirectory_Login_Model_Popup extends SDWeddingDirectory_Front_End_Modules {

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
             *  1. In header section add login button
             *  -------------------------------------
             */
            add_action( 'sdweddingdirectory/header/button', [ $this, 'header_button' ], absint( '10' ), absint( '1' ) );
        }

        /**
         *  1. In header section add login button
         *  -------------------------------------
         */
        public static function header_button( $args = [] ){

            if( is_user_logged_in() && ( parent:: is_vendor() || parent:: is_couple() ) ){

                return;
            }

            /**
             *  Have Args and Is Array ?
             *  ------------------------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extact Args
                 *  -----------
                 */
                extract( $args );

                /**
                 *  Is Layout One ( Header versio one )
                 *  -----------------------------------
                 */
                $couple_attr = apply_filters( 'sdweddingdirectory/couple-register/attr', '' );
                $vendor_attr = apply_filters( 'sdweddingdirectory/vendor-register/attr', '' );

                if( $layout == absint( '1' ) ){

                    printf(     '<span class="order-xl-last d-inline-flex ms-xxl-3 ms-xl-3 ms-lg-3 ms-md-3 ms-0">

                                    <a class="btn btn-primary btn-sm" %1$s>

                                        <i class="fa fa-user-o">%3$s</i>

                                        <span class="ms-1">%2$s</span>

                                    </a>

                                    <a class="btn btn-default btn-sm ms-2" %4$s>

                                        <i class="fa fa-plus"></i>

                                        <span class="ms-1">%5$s</span>

                                    </a>

                                </span>',

                                /**
                                 *  1. Couple Register Model ID
                                 *  ---------------------------
                                 */
                                $couple_attr,

                                /**
                                 *  2. Translation Ready String
                                 *  ---------------------------
                                 */
                                esc_attr__( 'Join as a Couple', 'sdweddingdirectory' ),

                                /**
                                 *  3. Have Mobile Text
                                 *  -------------------
                                 */
                                apply_filters( 'sdweddingdirectory/header-couple-button/mobile/string', '' ),

                                /**
                                 *  4. Vendor Register Model ID
                                 *  ---------------------------
                                 */
                                $vendor_attr,

                                /**
                                 *  5. Translation Ready String
                                 *  ---------------------------
                                 */
                                esc_attr__( 'Join as a Vendor', 'sdweddingdirectory' )
                    );
                }

                /**
                 *  Is Layout Two ( Header versio two )
                 *  -----------------------------------
                 */
                if( $layout == absint( '2' ) ){

                    printf(    '<span class="order-xl-last d-inline-flex ms-xxl-3 ms-xl-3 ms-lg-3 ms-md-3 ms-0">

                                    <a class="btn btn-default btn-sm sd-header-btn-full" %1$s>

                                        <i class="fa fa-user-o"></i>

                                        <span class="ms-1">%2$s</span>

                                    </a>

                                    <a class="btn btn-primary d-sm-block btn-sm ms-2 sd-header-btn-full" %3$s>

                                        <i class="fa fa-plus"></i>

                                        <span class="ms-1">%4$s</span>

                                    </a>

                                    <a class="btn btn-link sd-header-btn-icon" %1$s aria-label="%2$s">
                                        <i class="fa fa-user-o" style="font-size:1.3rem;color:#333;"></i>
                                    </a>

                                </span>', 

                                /**
                                 *  1. Couple Model ID
                                 *  ------------------
                                 */
                                $couple_attr,

                                /**
                                 *  2. Translation Ready String
                                 *  ---------------------------
                                 */
                                esc_attr__( 'Join as a Couple', 'sdweddingdirectory' ),

                                /**
                                 *  3. Vendor Login Model ID
                                 *  ------------------------
                                 */
                                $vendor_attr,

                                /**
                                 *  4. Translation Ready String
                                 *  ---------------------------
                                 */
                                esc_attr__( 'Join as a Vendor', 'sdweddingdirectory' )
                    );
                }
            }
        }
    }   

    /**
     *  Login Button Markup Object
     *  --------------------------
     */
    SDWeddingDirectory_Login_Model_Popup:: get_instance();
}
