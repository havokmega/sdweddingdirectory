<?php
/**
 *  SDWeddingDirectory Couple Real Wedding AJAX Script Action
 *  -------------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Couple_Wedding_Website_AJAX' ) && class_exists( 'SDWeddingDirectory_Couple_Website_Database' ) ){

    /**
     *  SDWeddingDirectory Couple Real Wedding AJAX Script Action
     *  -------------------------------------------------
     */
    class SDWeddingDirectory_Couple_Wedding_Website_AJAX extends SDWeddingDirectory_Couple_Website_Database{

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
                         *  AJAX Group query Handler
                         *  ------------------------
                         */
                        esc_attr( 'sdweddingdirectory_couple_website_add_new_request_handler' ),

                        /**
                         *  Header
                         *  ------
                         */
                        esc_attr( 'couple_wedding_website_header' ),

                        /**
                         *  About US
                         *  --------
                         */
                        esc_attr( 'couple_wedding_website_about_us' ),

                        /**
                         *  Our Story
                         *  ---------
                         */
                        esc_attr( 'couple_wedding_website_our_story' ),

                        /**
                         *  Countdown
                         *  ----------
                         */
                        esc_attr( 'couple_wedding_website_countdown' ),

                        /**
                         *  RSVP
                         *  ----
                         */
                        esc_attr( 'couple_wedding_website_rsvp' ),

                        /**
                         *  Groomsman
                         *  ---------
                         */
                        esc_attr( 'couple_wedding_website_groomsman' ),

                        /**
                         *  Bridesmaids
                         *  -----------
                         */
                        esc_attr( 'couple_wedding_website_bridesmaids' ),

                        /**
                         *  Testimonials
                         *  ------------
                         */
                        esc_attr( 'couple_wedding_website_testimonials' ),

                        /**
                         *  Gallery
                         *  -------
                         */
                        esc_attr( 'couple_wedding_website_gallery' ),

                        /**
                         *  When & Where Setting
                         *  --------------------
                         */
                        esc_attr( 'couple_wedding_website_when_and_where' ),

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
         *  Form Update Notice
         *  ------------------
         */
        public static function form_successfully_updated(){

            /**
             *  Form Successfully Updated
             *  -------------------------
             */
            die( json_encode( array(

                'message'   =>  esc_attr__( 'Website Data Updated Successfully!', 'sdweddingdirectory-couple-website' ),

                'notice'    =>  absint( '1' )

            ) ) );
        }

        /**
         *  Update
         *  ------
         */
        public static function form_name( $name = '' ){

            /**
             *  Make sure security not empty!
             *  -----------------------------
             */
            $_condition_1   =   isset( $_POST['security'] ) && $_POST['security'] !== '';

            /**
             *  Verify Security
             *  ---------------
             */
            $_condition_2   =   wp_verify_nonce( $_POST['security'], $name . '-security' );

            /**
             *  Security Check
             *  --------------
             */
            if( $_condition_1 && $_condition_2 ){

                /**
                 *  Real Wedding Post ID
                 *  --------------------
                 */
                $_post_id  =  absint( parent:: website_post_id() );

                /**
                 *  Group Pass
                 *  ----------
                 */
                if( isset( $_POST[ 'group_of_data' ] ) && parent:: _is_array( $_POST[ 'group_of_data' ] ) ){

                    foreach( $_POST[ 'group_of_data' ] as $key => $value ){

                        update_post_meta( absint( $_post_id ), sanitize_key( $key ), $value  );
                    }
                }

                /**
                 *  Form Update Notice
                 *  ------------------
                 */
                self:: form_successfully_updated();
            }

            /**
             *  Security Error
             *  --------------
             */
            else{

                /**
                 *  Security Error
                 *  --------------
                 */
                SDWeddingDirectory_AJAX:: security_issue_found();
            }
        }

        /**
         *  Header
         *  ------
         */
        public static function couple_wedding_website_header(){

            /**
             *  Form Update
             *  -----------
             */
            self:: form_name( __FUNCTION__ );   
        }

        /**
         *  About Us
         *  --------
         */
        public static function couple_wedding_website_about_us(){

            /**
             *  Form Update
             *  -----------
             */
            self:: form_name( __FUNCTION__ );
        }

        /**
         *  Our Story
         *  ---------
         */
        public static function couple_wedding_website_our_story(){

            /**
             *  Form Update
             *  -----------
             */
            self:: form_name( __FUNCTION__ );
        }

        /**
         *  Countdown
         *  ---------
         */
        public static function couple_wedding_website_countdown(){

            /**
             *  Form Update
             *  -----------
             */
            self:: form_name( __FUNCTION__ );
        }

        /**
         *  RSVP
         *  ----
         */
        public static function couple_wedding_website_rsvp(){

            /**
             *  Form Update
             *  -----------
             */
            self:: form_name( __FUNCTION__ );
        }

        /**
         *  Groomsman
         *  ---------
         */
        public static function couple_wedding_website_groomsman(){

            /**
             *  Form Update
             *  -----------
             */
            self:: form_name( __FUNCTION__ );
        }

        /**
         *  1. Insert Realwedding data through couple login : read wedding page ( backend )
         *  -------------------------------------------------------------------------------
         */
        public static function couple_wedding_website_bridesmaids(){

            /**
             *  Form Update
             *  -----------
             */
            self:: form_name( __FUNCTION__ );
        }

        /**
         *  Testimonials
         *  ------------
         */
        public static function couple_wedding_website_testimonials(){

            /**
             *  Form Update
             *  -----------
             */
            self:: form_name( __FUNCTION__ );
        }        

        /**
         *  Gallery
         *  -------
         */
        public static function couple_wedding_website_gallery(){

            /**
             *  Form Update
             *  -----------
             */
            self:: form_name( __FUNCTION__ );
        }

        /**
         *  When And Where
         *  --------------
         */
        public static function couple_wedding_website_when_and_where(){

            /**
             *  Form Update
             *  -----------
             */
            self:: form_name( __FUNCTION__ );
        }

        /**
         *  New Fields Added via AJAX
         *  -------------------------
         */
        public static function sdweddingdirectory_couple_website_add_new_request_handler(){

            /**
             *  Allowlist callbacks that can be requested by website repeaters
             *  --------------------------------------------------------------
             */
            $allowed_callbacks = [
                'SDWeddingDirectory_Couple_Website_Database::get_couple_event',
                'SDWeddingDirectory_Couple_Website_Database::get_couple_bride',
                'SDWeddingDirectory_Couple_Website_Database::get_couple_testimonial',
                'SDWeddingDirectory_Couple_Website_Database::get_couple_gallery',
                'SDWeddingDirectory_Couple_Website_Database::get_couple_groom',
                'SDWeddingDirectory_Couple_Website_Database::get_couple_story',
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
                    'message'   =>  esc_attr__( 'Invalid request.', 'sdweddingdirectory-couple-website' ),
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


    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Couple_Wedding_Website_AJAX:: get_instance();
}
