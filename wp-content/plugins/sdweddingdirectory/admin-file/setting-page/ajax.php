<?php
/**
 *  SDWeddingDirectory - Setting Page AJAX
 *  ------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Setting_Page_AJAX' ) && class_exists( 'SDWeddingDirectory_Setting_Page' ) ){

	/**
	 *  SDWeddingDirectory - Setting Page AJAX
	 *  ------------------------------
	 */
    class SDWeddingDirectory_Setting_Page_AJAX extends SDWeddingDirectory_Setting_Page{

        /**
         *	 Member Variable
         *	 ---------------
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
                    $allowed_actions    =   array(

                        /**
                         *  Vendor + Couple - Social Profile Update
                         *  ---------------------------------------
                         */
                        esc_attr( 'sdweddingdirectory_migration_social_media' ),

                        /**
                         *  License Verification Process
                         *  -------------------------------
                         */
                        esc_attr( 'sdweddingdirectory_license_verification' ),

                        /**
                         *  AJAX - Declain Migration Task
                         *  -----------------------------
                         */
                        esc_attr( 'sdweddingdirectory_declain_migration_task' ),
                    );

                    /**
                     *  If have action then load this object members
                     *  --------------------------------------------
                     */
                    if( in_array( $action, $allowed_actions, true ) ) {

                        /**
                         *  Admin setting endpoints must only run for authenticated admin AJAX
                         *  -----------------------------------------------------------------
                         */
                        add_action( 'wp_ajax_' . $action, [ $this, $action ] );
                    }
                }
            }
        }

        /**
         *  Validate admin AJAX request
         *  ---------------------------
         */
        private static function require_admin_request( $nonce_action = 'sdweddingdirectory_admin_settings_security' ){

            if( ! is_user_logged_in() || ! current_user_can( 'manage_options' ) ){

                wp_send_json_error( [
                    'message'   =>  esc_attr__( 'Unauthorized.', 'sdweddingdirectory' ),
                    'notice'    =>  absint( '0' ),
                ] );
            }

            $_security = isset( $_POST['security'] ) ? sanitize_text_field( wp_unslash( $_POST['security'] ) ) : '';

            if( empty( $_security ) || ! wp_verify_nonce( $_security, $nonce_action ) ){

                wp_send_json_error( [
                    'message'   =>  esc_attr__( 'Invalid security token.', 'sdweddingdirectory' ),
                    'notice'    =>  absint( '0' ),
                ] );
            }
        }

        /**
         *  SDWeddingDirectory - Plugin
         *  -------------------
         */
		public static function sdweddingdirectory_revoke_license_process(){

			if( defined( 'SDWEDDINGDIRECTORY_SKIP_LICENSE' ) && SDWEDDINGDIRECTORY_SKIP_LICENSE ){

				return;
			}

			/**
			 *  Make sure registration key removed
			 *  ----------------------------------
			 */
			if( empty( get_option( 'SDWeddingDirectory_Theme_Registration' ) ) ){
			
				/**
				 *  List of Plugins
				 *  ---------------
				 */
				$plugins_list 		= 	get_option( 'SDWeddingDirectory_Theme_Plugins' );

				/**
				 *  Have Plugins in Database Store Removed action
				 *  ---------------------------------------------
				 */
				if( ! empty( $plugins_list ) ){

					$_data 	=	json_decode( $plugins_list, true );

					if( parent:: _is_array( $_data ) ){

				        foreach ( $_data as $key => $value) {

				            deactivate_plugins( $key . '/' . $key . '.php' );

				            delete_plugins( array( $key . '/' . $key . '.php' ) );
				        }
					}
				}

				/**
				 *  Again Empty!
				 *  ------------
				 */
	        	update_option( 'SDWeddingDirectory_Theme_Registration', '' );

	        	update_option( 'SDWeddingDirectory_Theme_Plugins', '' );

	        	update_option( 'SDWeddingDirectory_Plugins_Update', '' );

	        	update_option( 'SDWeddingDirectory_Theme_XML', '' );

	        	update_option( 'SDWeddingDirectory_Widget_File', '' );
	        }
		}

		/**
		 *  License Verification
		 *  --------------------
		 */
		public static function sdweddingdirectory_license_verification(){

            /**
             *  Admin + nonce check
             *  -------------------
             */
            self:: require_admin_request( esc_attr( 'sdweddingdirectory_license_security' ) );

			if( defined( 'SDWEDDINGDIRECTORY_SKIP_LICENSE' ) && SDWEDDINGDIRECTORY_SKIP_LICENSE ){

				update_option( 'SDWeddingDirectory_Theme_Registration', 'local' );

				update_option( 'SDWeddingDirectory_Theme_Plugins', '[]' );

				update_option( 'SDWeddingDirectory_Plugins_Update', '' );

				update_option( 'SDWeddingDirectory_Theme_XML', '' );

				update_option( 'SDWeddingDirectory_Widget_File', '' );

				die( json_encode( array(

					'response'   	=> 		absint( '1' ),

					'get_result' 	=> 		sprintf( '	<div class="sdweddingdirectory-notice sdweddingdirectory-msg-success">

															<p><span class="dashicons dashicons-yes"></span> %1$s - %2$s : %3$s</p>

														</div>',

												esc_attr__( 'Verification skipped for local build', 'sdweddingdirectory' ),

												esc_attr__( 'Response Code :', 'sdweddingdirectory' ),

												esc_attr( '200' )
									)
				) ) );
			}

			/**
			 *  Have Post Data ?
			 *  ----------------
			 */
			$_condition_1   = 	isset( $_POST[ 'purchase_code' ] ) && $_POST[ 'purchase_code' ] !== '';

			$_condition_2   =	wp_verify_nonce( $_POST[ 'security' ], esc_attr( "sdweddingdirectory_license_security" ) );

			/**
			 *  Check Purchase Code Data + Security Set to start Process
			 *  --------------------------------------------------------
			 */
			if( $_condition_1 && $_condition_2 ){

				/**
				 *. Envato Purchase Code Process Article
				 *  ------------------------------------
				 * 	@link - https://code.tutsplus.com/tutorials/a-look-at-the-wordpress-http-api-a-practical-example-of-wp_remote_post--wp-32425
				 * 	----------------------------------------------------------------------------------------------------------------------------
				 */
				add_option( 'SDWeddingDirectory_Theme_Registration', '', '', 'yes' );

				add_option( 'SDWeddingDirectory_Theme_Plugins', '', '', 'yes' );

				add_option( 'SDWeddingDirectory_Plugins_Update', '', '', 'yes' );

				add_option( 'SDWeddingDirectory_Theme_XML', '', '', 'yes' );

				add_option( 'SDWeddingDirectory_Widget_File', '', '', 'yes' );

				/**
				 *  Check Connection with Response
				 *  ------------------------------
				 */
		        $response 	= 	wp_remote_post(

		        	/**
		        	 *  Verificaiton Link
		        	 *  -----------------
		        	 */
		            esc_url( 'https://sdweddingdirectory.net/wp-content/plugins/verify-buyer/verify-buyer.php' ),

		            /**
		             *  Arguments
		             *  ---------
		             */
		            array(

		            	/**
		            	 *  @credit  - https://stackoverflow.com/questions/44632619/wordpress-curl-error-60-ssl-certificate#answers-header
		            	 *  --------------------------------------------------------------------------------------------------------------
		            	 */
		            	'sslverify'     =>  true,

						'method' 		=> 	esc_attr( 	'POST'	),

						'timeout' 		=> 	absint( '45' ),

						'redirection' 	=> 	absint( '5' ),

						'httpversion' 	=> 	esc_attr( '1.0' ),

						'blocking' 		=> 	true,

						'headers' 		=> 	[],

		                'body' 			=> 	array(

		                    'purchase_code'   	=> 	esc_attr( 	$_POST[ 'purchase_code' ] 	),

		                    'security'     		=> 	esc_attr( 	$_POST[ 'security' ] 	),

		                    'domain' 			=> 	esc_url( 	home_url( '/' )		),

		                    'process'			=> 	esc_attr( 	$_POST[ 'process' ]		),

		                    'email'				=>	sanitize_email( get_bloginfo( 'admin_email' ) )
		                )
		            )
		        );

		        /**
		         *  Have any WP Error ?
		         *  -------------------
		         */
				if ( is_wp_error( $response ) ) {

					$error_string 	= 	$response->get_error_message();

					/**
					 *  Successfully Revoke
					 *  -------------------
					 */
					die( json_encode( array(

						'response'   	=> 		absint( '0' ),

						'get_result' 	=> 		sprintf( '	<div class="sdweddingdirectory-notice">

																<p><span class="dashicons dashicons-no-alt"></span> %1$s - %2$s</p>

															</div>',

													/**
													 *  Response Status Code
													 *  --------------------
													 */
													esc_attr( 'Wordpress Error' ),

													/**
													 *  SDWeddingDirectory - License Revoke Response Successfully Done
													 *  ------------------------------------------------------
													 */
													esc_attr( $error_string )
											)
					) ) );
				}

				/**
				 *  No any WP Error Found!
				 *  ----------------------
				 */
				else{

			        /**
			         *  Have Response ?
			         *  ---------------
			         */
			        if( ! empty( $response[ 'body' ] ) ){

			        	/**
			        	 *  Collect Response
			        	 *  ----------------
			        	 */
			        	$response		=	json_decode( $response[ 'body' ], true );

			        	$_message 		=	$response[ 'message' ];
			        	
			        	$_status 		=	$response[ 'status_code' ];

			        	$_plugins 		=	isset( $response[ 'plugins' ] ) && $response[ 'plugins' ] !== ''

			        					? 	$response[ 'plugins' ]

			        					:	[];

			        	$_response 		=	isset( $response[ 'response' ] ) && $response[ 'response' ] !== ''

			        					? 	$response[ 'response' ]

			        					: 	absint( '0' );

			        	$update_plugin 	=	isset( $response[ 'update_plugin' ] ) && ! empty( $response[ 'update_plugin' ] )

			        					?	$response[ 'update_plugin' ]

			        					:	'';

				        /**
				         *  License Not Match
				         *  -----------------
				         */
				        if( $_response == absint( '0' ) ){

							die( json_encode( array(

								'response'   	=> 		absint( $_response ),

								'get_result' 	=> 		sprintf( '	<div class="sdweddingdirectory-notice sdweddingdirectory-msg-error">

																		<p><span class="dashicons dashicons-no-alt"></span> %1$s - %2$s : %3$s</p>

																	</div>',

																/**
																 *  SDWeddingDirectory - License Verification Response
																 *  ------------------------------------------
																 */
																esc_attr( $_message ),

																/**
																 *  Translation Ready String
																 *  ------------------------
																 */
																esc_attr__( 'Response Code :', 'sdweddingdirectory' ),

																/**
																 *  Response Status Code
																 *  --------------------
																 */
																esc_attr( $_status )
													)
							) ) );
				        }

				        /**
				         *  [ Verification Process OR Refresh License ]
				         *  -------------------------------------------
				         */
				        elseif( $_response == absint( '1' ) ){
				        	
				        	/**
				        	 *  Update Verification Data in Database
				        	 *  ------------------------------------
				        	 */
				        	update_option( 'SDWeddingDirectory_Theme_Registration',

				        		/**
				        		 *  WordPress Saved This Purchase Code
				        		 *  ----------------------------------
				        		 */
				        		esc_attr( 	$_POST[ 'purchase_code' ]	)
				        	);

				        	/**
				        	 *  Install Plugins
				        	 *  ---------------
				        	 */
				        	update_option( 'SDWeddingDirectory_Theme_Plugins',

				        		/**
				        		 *  WordPress Saved This Purchase Code
				        		 *  ----------------------------------
				        		 */
				        		json_encode( $_plugins )
				        	);

				        	/**
				        	 *  Update Plugins
				        	 *  --------------
				        	 */
				        	update_option( 'SDWeddingDirectory_Plugins_Update',

				        		/**
				        		 *  WordPress Saved This Purchase Code
				        		 *  ----------------------------------
				        		 */
				        		$update_plugin
				        	);

							/**
							 *  Successfully Verify
							 *  -------------------
							 */
							die( json_encode( array(

								'response'   	=> 		absint( $_response ),

								'get_result' 	=> 		sprintf( '	<div class="sdweddingdirectory-notice sdweddingdirectory-msg-success">

																		<p><span class="dashicons dashicons-yes"></span> %1$s - %2$s : %3$s</p>

																	</div>',

																/**
																 *  SDWeddingDirectory - License Verification Response
																 *  ------------------------------------------
																 */
																esc_attr( $_message ),

																/**
																 *  Translation Ready String
																 *  ------------------------
																 */
																esc_attr__( 'Response Code :', 'sdweddingdirectory' ),

																/**
																 *  Response Status Code
																 *  --------------------
																 */
																esc_attr( $_status )
													)
							) ) );
				        }

				        /**
				         *  Revoke Process
				         *  --------------
				         */
				        elseif( $_response == absint( '2' ) ){

				        	/**
				        	 *  Is Demo File ?
				        	 *  --------------
				        	 */
				        	if( SDWEDDINGDIRECTORY_DEV == absint( '0' ) ){

					        	/**
					        	 *  Purchase Code Removed in Database
					        	 *  ---------------------------------
					        	 */
					        	update_option( 'SDWeddingDirectory_Theme_Registration', '' );

					        	/**
					        	 *  Deactivate Plugins
					        	 *  ------------------
					        	 *  https://wordpress.stackexchange.com/questions/5933/how-to-delete-the-hello-dolly-plugin-automatically#answers-header
					        	 *  --------------------------------------------------------------------------------------------------------------------
					        	 */
					        	self:: sdweddingdirectory_revoke_license_process();
				        	}

							/**
							 *  Successfully Revoke
							 *  -------------------
							 */
							die( json_encode( array(

								'response'   	=> 		absint( $_response ),

								'get_result' 	=> 		sprintf( '	<div class="sdweddingdirectory-notice sdweddingdirectory-msg-success">

																		<p><span class="dashicons dashicons-yes"></span> %1$s - %2$s : %3$s</p>

																	</div>',

															/**
															 *  SDWeddingDirectory - License Revoke Response Successfully Done
															 *  ------------------------------------------------------
															 */
															esc_attr( $_message ),

															/**
															 *  Translation Ready String
															 *  ------------------------
															 */
															esc_attr__( 'Response Code :', 'sdweddingdirectory' ),

															/**
															 *  Response Status Code
															 *  --------------------
															 */
															esc_attr( $_status )
													)
							) ) );
				        }

				        /**
				         *  Revoke Process
				         *  --------------
				         */
				        else{

				        	/**
				        	 *  Is Demo File ?
				        	 *  --------------
				        	 */
				        	if( SDWEDDINGDIRECTORY_DEV == absint( '0' ) ){

					        	/**
					        	 *  Purchase Code Removed in Database
					        	 *  ---------------------------------
					        	 */
					        	update_option( 'SDWeddingDirectory_Theme_Registration', '' );

					        	/**
					        	 *  Deactivate Plugins
					        	 *  ------------------
					        	 *  https://wordpress.stackexchange.com/questions/5933/how-to-delete-the-hello-dolly-plugin-automatically#answers-header
					        	 *  --------------------------------------------------------------------------------------------------------------------
					        	 */
					        	self:: sdweddingdirectory_revoke_license_process();
				        	}

							/**
							 *  Successfully Revoke
							 *  -------------------
							 */
							die( json_encode( array(

								'response'   	=> 		absint( $_response ),

								'get_result' 	=> 		sprintf( '	<div class="sdweddingdirectory-notice">

																		<p><span class="dashicons dashicons-no-alt"></span> %1$s - %2$s : %3$s</p>

																	</div>',

															/**
															 *  SDWeddingDirectory - License Revoke Response Successfully Done
															 *  ------------------------------------------------------
															 */
															esc_attr( $_message ),

															/**
															 *  Translation Ready String
															 *  ------------------------
															 */
															esc_attr__( 'Response Code :', 'sdweddingdirectory' ),

															/**
															 *  Response Status Code
															 *  --------------------
															 */
															esc_attr( $_status )
													)
							) ) );
				        }
			        }

			        /**
			         *  Invalid Response
			         *  ----------------
			         */
			        else{

						/**
						 *  Response Not Found!
						 *  -------------------
						 */
						die( json_encode( array(

							'response'   	=> 		absint( $_response ),

							'get_result' 	=> 		esc_attr__( 'Data invalide..', 'sdweddingdirectory' )

						) ) );
		        	}					
				}
		    }
		}

        /**
         *  SDWeddingDirectory - Plugin
         *  -------------------
         */
		public static function sdweddingdirectory_migration_social_media(){

            /**
             *  Admin + nonce check
             *  -------------------
             */
            self:: require_admin_request();

			/**
			 *  Option Name
			 *  -----------
			 */
			$_option_name 	=	esc_attr( $_POST[ 'option' ] );

			/**
			 *  Make sure this wizard first time
			 *  --------------------------------
			 */
			if( get_option( $_option_name ) == absint( '0' ) ){

				global $post, $wp_query;

	            /**
	             *  Import : Demo Hire Vendor in Real Wedding
	             *  -----------------------------------------
	             */
	            $query    = new WP_Query( array(

	                'post_type'         => 	[ 'vendor', 'couple' ],

	                'post_status'       => 	esc_attr( 'publish' ),

	                'posts_per_page'    => 	-1,

	            ) );

	            /**
	             *  Have Post ?
	             *  -----------
	             */
	            if ( $query->have_posts() ){

	                while ( $query->have_posts() ){  $query->the_post(); 

	                    $post_id    		=   absint( get_the_ID() );

	                    $_social_media 		=	[];

						/**
						 *  Have Facebook ?
						 *  ---------------
						 */
						$_facebook	 	=	get_post_meta( absint( $post_id ), sanitize_key( 'facebook' ), true );

						if( ! empty( $_facebook ) ){

							$_social_media[] 	= array(

								'title'		=>	esc_attr( 'fa-facebook' ),

								'platform'	=>	esc_attr( 'fa-facebook' ),

								'link'		=>	esc_url( $_facebook ),
							);
						}

						/**
						 *  Have twitter ?
						 *  ---------------
						 */
						$_twitter	 =	get_post_meta( absint( $post_id ), sanitize_key( 'twitter' ), true );

						if( ! empty( $_twitter ) ){

							$_social_media[] 	= array(

								'title'		=>	esc_attr( 'fa-twitter' ),

								'platform'	=>	esc_attr( 'fa-twitter' ),

								'link'		=>	esc_url( $_twitter ),
							);
						}

						/**
						 *  Have instagram ?
						 *  ----------------
						 */
						$_instagram	 	=	get_post_meta( absint( $post_id ), sanitize_key( 'instagram' ), true );

						if( $_instagram !== '' ){

							$_social_media[] 	= array(

								'title'		=>	esc_attr( 'fa-instagram' ),

								'platform'	=>	esc_attr( 'fa-instagram' ),

								'link'		=>	esc_url( $_instagram ),
							);
						}

						/**
						 *  Have youtube ?
						 *  --------------
						 */
						$_youtube	 =	get_post_meta( absint( $post_id ), sanitize_key( 'youtube' ), true );

						if( ! empty( $_youtube ) ){

							$_social_media[] 	= array(

								'title'		=>	esc_attr( 'fa-youtube-play' ),

								'platform'	=>	esc_attr( 'fa-youtube-play' ),

								'link'		=>	esc_url( $_youtube ),
							);
						}

	                    /**
	                     *  Have Social Media ?
	                     *  -------------------
	                     */
	                    if( parent:: _is_array( $_social_media ) ){

	                    	update_post_meta( $post_id, sanitize_key( 'social_profile' ), $_social_media );
	                    }
	                }
	            }

	            /**
	             *  Reset Query
	             *  -----------
	             */
	            if ( isset( $query ) ) {

	                wp_reset_postdata();
	            }

	            /**
	             *  Social Migratrion Task DONE
	             *  ---------------------------
	             */
	        	update_option( $_option_name, absint( '1' ) );

	        	/**
	        	 *  Status
	        	 *  ------
	        	 */
	        	die( json_encode( [

	        		'message'	=>	esc_attr__( 'Migration Done!', 'sdweddingdirectory' ),

	        	] ) );

	        }else{

	        	/**
	        	 *  Status
	        	 *  ------
	        	 */
	        	die( json_encode( [

	        		'message'	=>	esc_attr__( 'Error!', 'sdweddingdirectory' )

	        	] ) );
	        }
		}

		/**
		 *  Declain Migration Task
		 *  ----------------------
		 */
		public static function sdweddingdirectory_declain_migration_task(){

            /**
             *  Admin + nonce check
             *  -------------------
             */
            self:: require_admin_request();

            /**
             *  Option Name
             *  -----------
             */
            $_option_name   =   esc_attr( $_POST[ 'option' ] );

            /**
             *  Make sure this wizard first time
             *  --------------------------------
             */
            if( get_option( $_option_name ) == absint( '0' ) || get_option( $_option_name ) == absint( '1' ) ){

                /**
                 *  Social Migratrion Task DONE
                 *  ---------------------------
                 */
                update_option( $_option_name, absint( '2' ) );

                /**
                 *  Status
                 *  ------
                 */
                die( json_encode( [

                    'message'   =>  esc_attr__( 'Migration Declain!', 'sdweddingdirectory' ),

                ] ) );

            }else{

                /**
                 *  Status
                 *  ------
                 */
                die( json_encode( [

                    'message'   =>  esc_attr__( 'Error!', 'sdweddingdirectory' )

                ] ) );
            }
		}
    }

	/**
	 *  SDWeddingDirectory - Setting Page AJAX
	 *  ------------------------------
	 */
    SDWeddingDirectory_Setting_Page_AJAX::get_instance();
}
