<?php
/**
 *  SDWeddingDirectory Vendor Venue Page AJAX Script Action HERE
 *  ------------------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Vendor_Venue_AJAX' ) && class_exists( 'SDWeddingDirectory_Vendor_Venue_Database' ) ){

    /**
     *  SDWeddingDirectory Vendor Venue Page AJAX Script Action HERE
     *  ------------------------------------------------------
     */
    class SDWeddingDirectory_Vendor_Venue_AJAX extends SDWeddingDirectory_Vendor_Venue_Database{

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
             *  Load one by one shortcode file
             *  ------------------------------
             */
            foreach ( glob( plugin_dir_path( __FILE__ ) . '/*/index.php' ) as $file ) {
           
                require_once $file;
            }

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
                         *  Added New Request
                         *  -----------------
                         */
                        esc_attr( 'sdweddingdirectory_venue_add_new_request_handler' ),
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

            /**
             *  Error Message
             *  -------------
             */
            die( json_encode( [

                'message'       =>      esc_attr__( 'Security issue!', 'sdweddingdirectory-venue' ),

                'notice'        =>      absint( '2' )

            ] ) );
        }

        /**
         *  Removed Media included ( Featured Image + Gallery Media )
         *  ---------------------------------------------------------
         */
        public static function _removed_media( $post_id = '' ){

            if( isset( $_post_id ) && $_post_id !== '' && $_post_id !== absint( '0' ) ){

                /**
                 *  Get Post Gallery ID and removed through WordPress Media Editor
                 *  --------------------------------------------------------------
                 */
                $_gallery_ids = get_post_meta( $_post_id, esc_attr('venue_gallery'), true );

                if( ! empty( $_gallery_ids ) ){

                    foreach ( explode( ',', $_gallery_ids ) as $key => $value ) {

                        wp_delete_attachment( absint( $value ), true );
                    }
                }

                /**
                 *  Get Post Featured Media ID and removed through WordPress Media Editor
                 *  ---------------------------------------------------------------------
                 */
                $_featured_image_id     =   get_post_thumbnail_id( absint( $_post_id ) );

                if( ! empty( $_featured_image_id ) && $_featured_image_id !== '' ){

                    wp_delete_attachment( absint( $_featured_image_id ), true );
                }
            }
        }

        /**
         *  Check Is Owner of Venue ?
         *  ---------------------------
         */
        public static function is_venue_owner( $_post_id = '' ){

            if( isset( $_post_id ) && $_post_id !== '' && $_post_id !== absint( '0' ) ){

                global $current_user, $post, $wp_query;

                $_condition_1 = isset( $_post_id ) && $_post_id !== '';

                $_venue_id  = $_condition_1 ? absint( $_post_id ) : '';

                $_venue_post_id  = get_post( absint( $_venue_id ) );

                /**
                 *   Is Venue Author ?
                 *   -------------------
                 */
                $_security_1 = (  get_post_type( absint( $_venue_id ) ) == esc_attr( 'venue' ) );

                $_security_2 = (  absint( $current_user->ID ) == absint( $_venue_post_id->post_author )  );

                /**
                 *  Check Security & Permission
                 *  ---------------------------
                 */
                if( $_security_1 && $_security_2 ){

                    return true;

                }else{

                    return false;
                }

            }else{

                self:: security_issue_found();
            }
        }

        /**
         *  New Fields Added via AJAX
         *  -------------------------
         */
        public static function sdweddingdirectory_venue_add_new_request_handler(){

            /**
             *  Allowlist callbacks that can be requested by venue repeaters
             *  --------------------------------------------------------------
             */
            $allowed_callbacks = [
                'SDWeddingDirectory_Vendor_Venue_Database::get_venue_faqs',
                'SDWeddingDirectory_Vendor_Venue_Database::get_venue_menus',
                'SDWeddingDirectory_Vendor_Venue_Database::get_venue_team',
                'SDWeddingDirectory_Vendor_Venue_Database::get_venue_facilities',
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
                    'message'   =>  esc_attr__( 'Invalid request.', 'sdweddingdirectory-venue' ),
                    'notice'    =>  absint( '0' ),
                ] );
            }

            die( json_encode( array(

                'sdweddingdirectory_ajax_data'  =>  call_user_func(

                                                [ $_class, $_member ],

                                                [ 'counter'   =>  $_counter ]
                                            )

            ) ) );
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Vendor_Venue_AJAX:: get_instance();
}
