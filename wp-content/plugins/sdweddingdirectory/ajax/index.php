<?php
/**
 *  SDWeddingDirectory Vendor Venue Page AJAX Script Action HERE
 *  ------------------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_AJAX' ) && class_exists( 'SDWeddingDirectory_Config' ) ){

    /**
     *  SDWeddingDirectory Vendor Venue Page AJAX Script Action HERE
     *  ------------------------------------------------------
     */
    class SDWeddingDirectory_AJAX extends SDWeddingDirectory_Config{

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
             *  1. Have AJAX action ?
             *  ---------------------
             */
            if(  wp_doing_ajax()  ){

                /**
                 *  Have AJAX action ?
                 *  ------------------
                 */
                if( isset( $_POST['action'] ) && $_POST['action'] !== '' ){

                    /**
                     *  Have AJAX action ?
                     *  ------------------
                     */
                    $action     =   esc_attr( trim( $_POST['action'] ) );

                    /**
                     *  1.  Bit of security
                     *  -------------------
                     */
                    $allowed_actions = array(

                        /**
                         *  1. Added New FAQ's
                         *  ------------------
                         */
                        esc_attr( 'sdweddingdirectory_add_new_request_handler' ),

                        /**
                         *  2. Share Post Model Script
                         *  --------------------------
                         */
                        esc_attr( 'sdweddingdirectory_share_post_model' )
                    );

                    /**
                     *  If have action then load this object members
                     *  --------------------------------------------
                     */
                    if( in_array( $action, $allowed_actions) ) {

                        /**
                         *  Check the AJAX action with login user
                         *  -------------------------------------
                         */
                        if( is_user_logged_in() ){

                            /**
                             *  1. If user already login then AJAX action
                             *  -----------------------------------------
                             */
                            add_action( 'wp_ajax_' . $action, [ $this, $action ] );

                        }else{

                            /**
                             *  2. If user not login that mean is front end AJAX action
                             *  -------------------------------------------------------
                             */
                            add_action( 'wp_ajax_nopriv_' . $action, [ $this, $action ] );
                        }
                    }
                }
            }
        }

        /**
         *  If Found security issue
         *  -----------------------
         */
        public static function security_issue_found(){

            die( json_encode( [

                'message'       =>      esc_attr__( 'Security issue found!', 'sdweddingdirectory' ),

                'notice'        =>      absint( '2' )

            ] ) );
        }

        /**
         *  New Fields Added via AJAX
         *  -------------------------
         */
        public static function sdweddingdirectory_add_new_request_handler(){

            /**
             *  Allowlist callbacks that can be requested by front-end repeaters
             *  ----------------------------------------------------------------
             */
            $allowed_callbacks = [
                'SDWeddingDirectory_Vendor_Profile_Database::get_vendor_social_media',
                'SDWeddingDirectory_Couple_Profile_Database::get_couple_social_media',
                'SDWeddingDirectory_Guest_List_Database::add_guest_fields',
                'SDWeddingDirectory_Real_Wedding_Database::get_outside_vendor',
            ];

            /**
             *  Requested callback
             *  ------------------
             */
            $_class         = isset( $_POST['class'] ) ? sanitize_text_field( wp_unslash( $_POST['class'] ) ) : '';
            $_member        = isset( $_POST['member'] ) ? sanitize_text_field( wp_unslash( $_POST['member'] ) ) : '';
            $_counter       = isset( $_POST['counter'] ) ? absint( $_POST['counter'] ) : absint( '0' );
            $_requested     = $_class . '::' . $_member;

            /**
             *  Reject invalid callback requests
             *  -------------------------------
             */
            if( ! in_array( $_requested, $allowed_callbacks, true ) || ! is_callable( [ $_class, $_member ] ) ){

                wp_send_json_error( [
                    'message'   =>  esc_attr__( 'Invalid request.', 'sdweddingdirectory' ),
                    'notice'    =>  absint( '0' ),
                ] );
            }

            /**
             *  Handler Request Data
             *  --------------------
             */
            die( json_encode( [

                'sdweddingdirectory_ajax_data'  =>  call_user_func(

                                                [ $_class, $_member ],

                                                [ 'counter'   =>  $_counter ]
                                            )
            ] ) );
        }

        /**
         *  Share Post Button click to open Model
         *  -------------------------------------
         *  PENDING is AJAX
         *  ---------------
         */
        public static function sdweddingdirectory_share_post_model(){

            /**
             *  Have Post ID ?
             *  --------------
             */
            $_post_id   =   isset( $_POST['post_id'] ) && ! empty( $_POST['post_id'] )

                        ?   absint( $_POST['post_id'] )

                        :   absint( '0' );

            /**
             *  Make sure post id not empty!
             *  ----------------------------
             */
            if( ! empty( $_post_id ) ){

                /**
                 *  Model ID
                 *  --------
                 */
                $_model_id      =   esc_attr( parent:: _rand() );

                /**
                 *  Model Data
                 *  ----------
                 */
                die( json_encode( [

                    'model_id'      =>  esc_attr( $_model_id ),

                    'model_data'    =>  sprintf(   '<div class="modal fade bd-example-modal-sm sdweddingdirectory_post_share_model" id="%1$s" 

                                                        tabindex="-1" role="dialog" aria-labelledby="%1$s" aria-hidden="true">

                                                        <div class="modal-dialog modal-dialog-centered modal-md">

                                                            <!-- Model -->
                                                            <div class="modal-content">

                                                                <!-- Header -->
                                                                <div class="modal-header">

                                                                    <h3 class="modal-title">%3$s</h3>

                                                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></button>

                                                                </div>
                                                                <!-- Header -->

                                                                <!-- Body -->
                                                                <div class="modal-body">

                                                                    <p>%6$s</p>

                                                                    <div class="sdweddingdirectory-social-media-share-model">%2$s</div>

                                                                </div>
                                                                <!-- Body -->

                                                                <!-- Footer -->
                                                                <div class="p-3">

                                                                    <div class="col-md-12">

                                                                        <p class="mb-2">%7$s <small id="%1$s_message" class="copy_success_message"></small></p>

                                                                    </div>

                                                                    <div class="col-md-12">

                                                                        <div class="input-group mb-3 input_copy_group">
                                                                          
                                                                            <input autocomplete="off" type="text" class="form-control" id="%1$s_text" value="%4$s" readonly />

                                                                            <div class="input-group-append d-grid">

                                                                                <button class="input-group-text btn btn-primary social_link_copy" id="button_%1$s"

                                                                                    data-clipboard-target="#%1$s_text"

                                                                                    data-message-id="#%1$s_message" 

                                                                                    data-success-string="%5$s">

                                                                                    <i class="fa fa-clone" aria-hidden="true"></i>

                                                                                </button>

                                                                          </div>

                                                                        </div>

                                                                    </div>

                                                                </div>
                                                                <!-- Footer -->

                                                            </div>
                                                            <!-- Model -->

                                                        </div>

                                                    </div>',

                                                    /**
                                                     *  1. Model ID
                                                     *  -----------
                                                     */
                                                    $_model_id,

                                                    /**
                                                     *  2. Social Media Load with Post Link
                                                     *  -----------------------------------
                                                     */
                                                    apply_filters( 'sdweddingdirectory/post-share', [ 'post_id' => $_post_id ] ),

                                                    /**
                                                     *  3. Translation Ready String
                                                     *  ---------------------------
                                                     */
                                                    esc_attr__( 'Share With Family and Friends', 'sdweddingdirectory' ),

                                                    /**
                                                     *  4. Page Link
                                                     *  ------------
                                                     */
                                                    esc_url( get_the_permalink( $_post_id ) ),

                                                    /**
                                                     *  5. Translation Ready String
                                                     *  ---------------------------
                                                     */
                                                    esc_attr__( 'link copied', 'sdweddingdirectory' ),

                                                    /**
                                                     *  6. Translation Ready String
                                                     *  ---------------------------
                                                     */
                                                    esc_attr__( 'Share this link via', 'sdweddingdirectory' ),

                                                    /**
                                                     *  7. Translation Ready String
                                                     *  ---------------------------
                                                     */
                                                    esc_attr__( 'Page link', 'sdweddingdirectory' )
                                        )
                ] ) );
            }
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_AJAX:: get_instance();
}
