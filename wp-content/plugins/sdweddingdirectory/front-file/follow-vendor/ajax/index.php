<?php
/**
 *  SDWeddingDirectory Venue Request Quote AJAX Script Action HERE
 *  --------------------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Follow_Vendor_AJAX' ) && class_exists( 'SDWeddingDirectory_Follow_Vendor_Database' ) ){

    /**
     *  SDWeddingDirectory Venue Request Quote AJAX Script Action HERE
     *  --------------------------------------------------------
     */
    class SDWeddingDirectory_Follow_Vendor_AJAX extends SDWeddingDirectory_Follow_Vendor_Database{

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
             *  1. Follow Vendor
             *  ----------------
             */
            add_action( 'wp_ajax_sdweddingdirectory_follow_vendor_action',  [$this, 'sdweddingdirectory_follow_vendor_action'] );

            add_action( 'wp_ajax_nopriv_sdweddingdirectory_follow_vendor_action', [$this, 'sdweddingdirectory_follow_vendor_action'] );

            /**
             *  2. UnFollow Vendor
             *  ------------------
             */
            add_action( 'wp_ajax_sdweddingdirectory_unfollow_vendor_action',  [$this, 'sdweddingdirectory_unfollow_vendor_action'] );

            add_action( 'wp_ajax_nopriv_sdweddingdirectory_unfollow_vendor_action', [$this, 'sdweddingdirectory_unfollow_vendor_action'] );
        }

        /**
         *  1. Follow Vendor Script
         *  -----------------------
         */
        public static function sdweddingdirectory_follow_vendor_action(){

            global $current_user, $post, $wp_query;

            /**
             *  Couple Security
             *  ---------------
             */
            $_condition_1       =   	( isset( $_POST[ 'security' ] ) && $_POST[ 'security' ] !== '' )

                                ?   	wp_verify_nonce( $_POST[ 'security' ],

                                            esc_attr( "sdweddingdirectory_follow_vendor_security-" . absint( parent:: post_id() ) )
                                        )

                                :   	false;

            /**
             *  Current User is ( COUPLE ) ?
             *  ----------------------------
             */
            $_condition_2           =   parent:: is_couple();

            /**
             *  Security Check after process to submit request quote
             *  ----------------------------------------------------
             */
            if( $_condition_1 && $_condition_2 ){

				/**
				 *  Get Request Data
				 *  ----------------
				 */
				$_REQUEST_DATA        =   array( array(

				   	'title'           			=>  	sprintf(   esc_attr__( 'Favorite Vendor : %1$s', 'sdweddingdirectory' ), 

															/**
															 *  1. Title
															 *  --------
															 */
															esc_attr(   get_the_title( $_POST[ 'vendor_id' ] )  )
					                                    ),

					'vendor_id'         		=>  	absint( $_POST[ 'vendor_id' ] ),
				) );

                /**
                 *  Have Backend Data ?
                 *  -------------------
                 */
                $_BACKEND_DATA 		= 	get_post_meta(

                							/**
                							 *  1. Couple Post ID
                							 *  -----------------
                							 */
                							absint( parent:: post_id() ),

                							/**
                							 *  2. Meta Key
                							 *  -----------
                							 */
                							sanitize_key( 'sdweddingdirectory_follow_vendors' ),

                							/**
                							 *  3. TRUE
                							 *  -------
                							 */
                							TRUE
                						);

                /**
                 *  Update Vendor ID in Database
                 *  ----------------------------
                 */
                $_STORE_DATA 		=	[];

                /**
                 *  Have Backend Data ?
                 *  -------------------
                 */
                if( parent:: _is_array( $_BACKEND_DATA ) ){

                    /**
                     *  Check Couple Already Follow This Vendor ?
                     *  -----------------------------------------
                     */
                    foreach ( $_BACKEND_DATA as $key => $value) {
                        
                        /**
                         *  Already Follow This Vendor ?
                         *  ----------------------------
                         */
                        if( $value[ 'vendor_id' ] == absint( $_POST[ 'vendor_id' ] ) ){

                            /**
                             *  Couple Already Submit Request for this venue
                             *  ----------------------------------------------
                             */
                            die( json_encode( array(

                              'notice'    =>  absint( '2' ),

                              'message'   =>  sprintf(

                                                    /**
                                                     *  1. Translation Ready String
                                                     *  ---------------------------
                                                     */
                                                    esc_attr__('Hello %1$s, You already follow this vendor.','sdweddingdirectory' ),

                                                    /**
                                                     *  2. Login username
                                                     *  -----------------
                                                     */
                                                    esc_attr( parent:: user_login() )
                                              )
                            ) ) );
                        }
                    }

                    /**
                     *  Merge Database Data with Request New Data ?
                     *  -------------------------------------------
                     */
                	$_STORE_DATA 	=	array_merge_recursive( $_BACKEND_DATA, $_REQUEST_DATA );

                }else{

                	$_STORE_DATA 	=	$_REQUEST_DATA;
                }

                /**
                 *  Update Follow Vendors List
                 *  --------------------------
                 */
                update_post_meta(

                    /**
                     *  Post ID
                     *  -------
                     */
                    absint( parent:: post_id() ),

                    /**
                     *  Meta Key
                     *  --------
                     */
                    sanitize_key( 'sdweddingdirectory_follow_vendors' ),

                    /**
                     *  Value
                     *  -----
                     */
                    $_STORE_DATA
                );

                /**
                 *  Successfully  Request Quote Form
                 *  ----------------------------------------
                 */
                die( json_encode( array(

                    'notice'        =>   absint( '1' ),
                  
                    'message'       =>   esc_attr__( 'Following Vendor Process is Successfully!', 'sdweddingdirectory' )

                ) ) );

            }else{
                
                /**
                 *   Security Issue Found
                 *   --------------------
                 */
                parent:: security_issue_found();
            }
        }

        /**
         *  2. UnFollow Vendor Script
         *  -------------------------
         */
        public static function sdweddingdirectory_unfollow_vendor_action(){

            global $current_user, $post, $wp_query;

            /**
             *  Couple Security
             *  ---------------
             */
            $_condition_1       =       ( isset( $_POST[ 'security' ] ) && $_POST[ 'security' ] !== '' )

                                ?       wp_verify_nonce( $_POST[ 'security' ],

                                            esc_attr( "sdweddingdirectory_follow_vendor_security-" . absint( parent:: post_id() ) )
                                        )

                                :       false;

            /**
             *  Current User is ( COUPLE ) ?
             *  ----------------------------
             */
            $_condition_2           =   parent:: is_couple();

            /**
             *  Security Check after process to submit request quote
             *  ----------------------------------------------------
             */
            if( $_condition_1 && $_condition_2 ){

                /**
                 *  Have Backend Data ?
                 *  -------------------
                 */
                $_BACKEND_DATA      =   get_post_meta(

                                            /**
                                             *  1. Couple Post ID
                                             *  -----------------
                                             */
                                            absint( parent:: post_id() ),

                                            /**
                                             *  2. Meta Key
                                             *  -----------
                                             */
                                            sanitize_key( 'sdweddingdirectory_follow_vendors' ),

                                            /**
                                             *  3. TRUE
                                             *  -------
                                             */
                                            TRUE
                                        );

                /**
                 *  Have Backend Data ?
                 *  -------------------
                 */
                if( parent:: _is_array( $_BACKEND_DATA ) ){

                    /**
                     *  Check Couple Already Follow This Vendor ?
                     *  -----------------------------------------
                     */
                    foreach ( $_BACKEND_DATA as $key => $value) {
                        
                        /**
                         *  Already Follow This Vendor ?
                         *  ----------------------------
                         */
                        if( $value[ 'vendor_id' ] == absint( $_POST[ 'vendor_id' ] ) ){

                            /**
                             *  Removed Vendor in Favorite ( Following ) List
                             *  ---------------------------------------------
                             */
                            unset( $_BACKEND_DATA[ absint( $key ) ] );

                            /**
                             *  Update Follow Vendors List
                             *  --------------------------
                             */
                            update_post_meta(

                                /**
                                 *  Post ID
                                 *  -------
                                 */
                                absint( parent:: post_id() ),

                                /**
                                 *  Meta Key
                                 *  --------
                                 */
                                sanitize_key( 'sdweddingdirectory_follow_vendors' ),

                                /**
                                 *  Value
                                 *  -----
                                 */
                                $_BACKEND_DATA
                            );

                            /**
                             *  Successfully Removed 
                             *  --------------------
                             */
                            die( json_encode( array(

                                'notice'        =>   absint( '2' ),
                              
                                'message'       =>   esc_attr__( 'UnFollowing Successfully!', 'sdweddingdirectory' )

                            ) ) );
                        }
                    }

                }else{

                    /**
                     *   Security Issue Found
                     *   --------------------
                     */
                    parent:: security_issue_found();
                }

            }else{
                
                /**
                 *   Security Issue Found
                 *   --------------------
                 */
                parent:: security_issue_found();
            }
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Follow_Vendor_AJAX:: get_instance();
}