<?php 
/**
 *  SDWeddingDirectory - Admin Filter
 *  -------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Admin_Filter' ) && class_exists( 'SDWeddingDirectory_Admin_Settings' ) ) {

	/**
	 *  SDWeddingDirectory - Admin Filter
	 *  -------------------------
	 */
	class SDWeddingDirectory_Admin_Filter extends SDWeddingDirectory_Admin_Settings {

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

	    public function __construct(){

	    	add_filter( 'display_post_states', [ $this, 'sdweddingdirectory_add_post_state' ], 10, 2 );

	    	/**
	    	 *  Venue : Author Dropdown list : Quick Edit
	    	 *  -------------------------------------------
	    	 *  @credit - https://developer.wordpress.org/reference/hooks/wp_dropdown_users_args/#user-contributed-notes
	    	 *  --------------------------------------------------------------------------------------------------------
	    	 */
	    	add_filter( 'wp_dropdown_users_args', [ $this, 'sdweddingdirectory_venue_author_dropdown' ], absint( '10' ), absint( '2' ) );

	    	add_action( 'admin_head', [$this, 'sdweddingdirectory_admin_style' ] );

	    	add_filter( 'views_edit-couple', [$this, 'sdweddingdirectory_couple_post_tabs'], absint( '10' ), absint('1') );

	    	add_filter( 'views_edit-vendor', [$this, 'sdweddingdirectory_vendor_post_tabs'], absint( '10' ), absint('1') );

	    	/**
	    	 *  Admin File Init
	    	 *  ---------------
	    	 */
	    	add_action( 'admin_init', function(){

		    	/**
		    	 *  Post Type Show Configuration Email ID
		    	 *  -------------------------------------
		    	 */
		    	foreach( apply_filters( 'sdweddingdirectory/user/configuration/meta', [] ) as $key => $value ){

		            add_filter( 'manage_edit-'. $value .'_columns', [$this, 'display_column'] );

		            add_action( 'manage_'. $value .'_posts_custom_column', [$this, 'display_data'], 10, 2 );
		    	}

		    	/**
		    	 *  User Column
		    	 *  -----------
		    	 */
		    	add_filter( 'manage_users_columns', [ $this, 'sdweddingdirectory_user_configuration' ] );

		    	add_filter( 'manage_users_custom_column', [ $this, 'sdweddingdirectory_user_configuration_row' ], absint( '10' ), absint( '3' ) );

	    	} );

	    	/**
	    	 *  Admin Bar
	    	 *  ---------
	    	 */
	    	add_action( 'admin_bar_menu', [ $this, 'sdweddingdirectory_admin_bar_item' ], absint( '1500' ) );

            /**
             *  1. Load Script for couple registration
             *  --------------------------------------
             */
            add_action( 'admin_enqueue_scripts', array( $this, 'sdweddingdirectory_script' ) );

            /**
             *  SDWeddingDirectory - Site Files
             *  -----------------------
             */
            add_action( 'init', [ $this, 'sdweddingdirectory_files' ], absint( '999' ) );
	    }

        /**
         *  1. Load the couple registration script.
         *  --------------------------------------
         */
        public static function sdweddingdirectory_script( $hook ){

            /**
             *  User login
             *  ----------
             */
            if( $hook == esc_attr( 'post.php' ) ){

                /**
                 *  Load Script
                 *  -----------
                 */
                wp_enqueue_style(

                    /**
                     *  1. File Name
                     *  ------------
                     */
                    esc_attr( sanitize_title( __CLASS__ ) ),

                    /**
                     *  2. File Path
                     *  ------------
                     */
                    esc_url(   plugin_dir_url( __FILE__ )   .   'style.css'   ),

                    /**
                     *  3. Load After "JQUERY" Load
                     *  ---------------------------
                     */
                    array(),

                    /**
                     *  4. Bootstrap - Library Version
                     *  ------------------------------
                     */
                    esc_attr( parent:: _file_version( plugin_dir_path( __FILE__ )   .   'style.css' ) ),

                    /**
                     *  5. Load in Footer
                     *  -----------------
                     */
                    esc_attr( 'all' )
                );
            }
        }

        /**
         *  User Page Access
         *  ----------------
         */
        public static function user_post_access( $post_id = 0 ){

        	/**
        	 *  User Information not emtpy!
        	 *  ---------------------------
        	 */
        	if( ! empty( parent:: author_id_get_via_post_id(  absint( $post_id ) ) ) ){

        		return 	sprintf( 	'<p>

										<span>%1$s - </span>

										<a href="%2$s" target="_blank"><span class="dashicons dashicons-edit"></span></a>

									</p>', 

									/**
									 *  1. Get Post Type
									 *  ----------------
									 */
									esc_attr__( 'Edit User', 'sdweddingdirectory' ),

									/**
									 *  2. Vendor Post Link
									 *  -------------------
									 */
									admin_url(	'user-edit.php?user_id=' . parent:: author_id_get_via_post_id(  absint( $post_id ) ) )
						);
        	}

        	else{

        		return	sprintf( 	'<p>

										<span style="color: #b32d2e;font-weight: 500;">%1$s </span>

										<a href="%2$s" target="_blank"><span class="dashicons dashicons-update"></span></a>

									</p>',

									/**
									 *  1. Get Post Type
									 *  ----------------
									 */
									esc_attr__( 'Error - User', 'sdweddingdirectory' ),

									/**
									 *  2. Vendor Post Link
									 *  -------------------
									 */
									esc_url( 'https://wp-organic.gitbook.io/sdweddingdirectory-wordpress/faqs/user-configuration-process' )
						);
        	}
        }

        /**
         *  Display Column
         *  --------------
         */
        public static function display_column( $columns ) {

            $columns['sdweddingdirectory_configuration']       =   esc_attr__( 'User Configuration', 'sdweddingdirectory' );

            return $columns;
        }

        /**
         *  Show Column Fill Data
         *  ---------------------
         */
        public static function display_data( $column, $post_id ) {

            global $post;

            /**
             *  1. Contact Information
             *  ----------------------
             */
            if( $column == esc_attr( 'sdweddingdirectory_configuration' ) ){

	            /**
	             *  User Email ID
	             *  -------------
	             */
	            $user_email		=	get_post_meta( absint( $post_id ), sanitize_key( 'user_email' ), true );

	            /**
	             *  If Author Role is "VENDOR" ?
	             *  ----------------------------
	             *  Vendor Have only one access now so we have another access post
	             *  --------------------------------------------------------------
	             */
	            if(  in_array( get_post_type( $post_id ), apply_filters( 'sdweddingdirectory/vendor/access-post-type', [] ) )  ){

	            	$output 				=	'';

	            	$_vendor_configuration 	=	apply_filters(

	            									/**
	            									 *  1. Filter Hook
	            									 *  --------------
	            									 */
	            									'sdweddingdirectory/user-page/vendor/status',

	            									/**
	            									 *  2. Collection
	            									 *  -------------
	            									 */
	            									[], 

	            									/**
	            									 *  2. Email ID
	            									 *  -----------
	            									 */
	            									sanitize_email( $user_email )
	            								);

	            	/**
	            	 *  Post Access to get user post id
	            	 *  -------------------------------
	            	 */
	            	$output 	.=		self:: user_post_access(  absint( $post_id )  );

	            	/**
	            	 *  Couple Configuration
	            	 *  --------------------
	            	 */
	            	if( parent:: _is_array( $_vendor_configuration ) ){

	            		/**
	            		 *  Check Each Args
	            		 *  ---------------
	            		 */
	            		foreach ( $_vendor_configuration as $key => $value) {

	            			/**
	            			 *  Extract Value
	            			 *  -------------
	            			 */
	            			extract( $value );
	            			
	            			/**
	            			 *  Have Post ID
	            			 *  ------------
	            			 */
	            			if( parent:: _have_data( $post_id ) ){

	            				$output 	.=		sprintf( 	'<p>

	            													<span>%1$s - </span>

	            													<a href="%2$s" target="_blank"><span class="dashicons dashicons-edit"></span></a>

	            												</p>', 

            													/**
            													 *  1. Get Post Type
            													 *  ----------------
            													 */
            													esc_attr( ucwords( get_post_type( $post_id ) ) ),

			            										/**
			            										 *  2. Vendor Post Link
			            										 *  -------------------
			            										 */
			            										esc_url( get_edit_post_link( $post_id ) )
	            									);

	            			}else{

	            				$output 	.=		sprintf( 	'<p>

	            													<span style="color: #b32d2e;font-weight: 500;">%1$s - </span>

	            													<a href="%2$s" title="%1$s" target="_blank" ><span class="dashicons dashicons-update"></span></a>

	            												</p>', 

            													/**
            													 *  1. Get Post Type
            													 *  ----------------
            													 */
            													esc_attr( $notes ),

			            										/**
			            										 *  2. Vendor Post Link
			            										 *  -------------------
			            										 */
			            										esc_url_raw( $article )
	            									);
	            			}
	            		}
	            	}

	            	/**
	            	 *  Couple Role Configuration Show
	            	 *  ------------------------------
	            	 */
	            	print 	$output;
	            }

	            /**
	             *  Is Couple ? Then we have Real Wedding + Website Post Access Too
	             *  ---------------------------------------------------------------
	             */
	            elseif(  in_array( get_post_type( $post_id ), apply_filters( 'sdweddingdirectory/couple/access-post-type', [] ) )  ){

	            	$output 				=	'';

	            	$_couple_configuration 	=	apply_filters(

	            									/**
	            									 *  1. Filter Hook
	            									 *  --------------
	            									 */
	            									'sdweddingdirectory/user-page/couple/status', 

	            									/**
	            									 *  2. Collection
	            									 *  -------------
	            									 */
	            									[], 

	            									/**
	            									 *  2. Email ID
	            									 *  -----------
	            									 */
	            									sanitize_email( $user_email )
	            								);

	            	/**
	            	 *  Post Access to get user post id
	            	 *  -------------------------------
	            	 */
	            	$output 	.=		self:: user_post_access(  absint( $post_id )  );

	            	/**
	            	 *  Couple Configuration
	            	 *  --------------------
	            	 */
	            	if( parent:: _is_array( $_couple_configuration ) ){

	            		/**
	            		 *  Check Each Args
	            		 *  ---------------
	            		 */
	            		foreach ( $_couple_configuration as $key => $value) {

	            			/**
	            			 *  Extract Value
	            			 *  -------------
	            			 */
	            			extract( $value );
	            			
	            			/**
	            			 *  Have Post ID
	            			 *  ------------
	            			 */
	            			if( parent:: _have_data( $post_id ) ){

	            				$output 	.=		sprintf( 	'<p>

	            													<span>%1$s - </span>

	            													<a href="%2$s" target="_blank"><span class="dashicons dashicons-edit"></span></a>

	            												</p>', 

            													/**
            													 *  1. Get Post Type
            													 *  ----------------
            													 */
            													esc_attr( ucwords( get_post_type( $post_id ) ) ),

			            										/**
			            										 *  2. Vendor Post Link
			            										 *  -------------------
			            										 */
			            										esc_url( get_edit_post_link( $post_id ) )
	            									);

	            			}else{

	            				$output 	.=		sprintf( 	'<p>

	            													<span style="color: #b32d2e;font-weight: 500;">%1$s - </span>

	            													<a href="%2$s" title="%1$s" target="_blank" ><span class="dashicons dashicons-update"></span></a>

	            												</p>', 

            													/**
            													 *  1. Get Post Type
            													 *  ----------------
            													 */
            													esc_attr( $notes ),

			            										/**
			            										 *  2. Vendor Post Link
			            										 *  -------------------
			            										 */
			            										esc_url_raw( $article )
	            									);
	            			}
	            		}
	            	}

	            	/**
	            	 *  Output
	            	 *  ------
	            	 */
	            	print  	$output;
	            }

	            /**
	             *  Other role can't included our configuration
	             *  -------------------------------------------
	             */
	            else{

	                printf( '<span>
	                			<a target="_blank" href="https://wp-organic.gitbook.io/sdweddingdirectory-wordpress/faqs/user-configuration-process">
	                				<span class="dashicons dashicons-update"></span>
	                			</a>
	                		</span>',

	                        /**
	                         *  1. Translation Ready String
	                         *  ---------------------------
	                         */
	                        esc_attr__( 'Email Not Found', 'sdweddingdirectory' )
	                );
	            }
            }
        }

		public static function sdweddingdirectory_vendor_post_tabs( $views ) {

		    ?><h2 class="nav-tab-wrapper"><?php

		        $tabs = array(

		            'vendor' => array(

		                'name' => esc_html__( 'Vendors', 'sdweddingdirectory' ),

		                'url'  => admin_url( 'edit.php?post_type=vendor' ),
		            ),
		        );

		        $active_tab = isset( $_GET['page'] ) && $_GET['page'] === 'add-vendor' ? 'add-vendor' : 'vendor';

		        $tabs       = 	apply_filters( 'sdweddingdirectory_add_ons_tabs', $tabs );

		        if( parent:: _is_array( $tabs ) ){

			        foreach( $tabs as $tab_id => $tab ) {

			            $active = $active_tab == $tab_id ? ' nav-tab-active' : '';

			            echo '<a href="' . esc_url( $tab['url'] ) . '" class="nav-tab' . $active . '">' . esc_html( $tab['name'] ) . '</a>';
			        }
		        }

		        ?>

		<a href="<?php echo admin_url( 'admin.php?page=sdweddingdirectory-add-vendor' ); ?>" class="page-title-action">
		            <?php _e( 'Add New Vendor', 'sdweddingdirectory' ); // No text domain so it just follows what WP Core does ?>
		        </a>

		    </h2><br />

		    <?php

		    return $views;
		}

	    public static function sdweddingdirectory_couple_post_tabs( $views ){

		    ?><h2 class="nav-tab-wrapper"><?php

		        $tabs = array(

		            'couple' => array(

		                'name' => esc_html__( 'Couple', 'sdweddingdirectory' ),

		                'url'  => admin_url( 'edit.php?post_type=couple' ),
		            ),
		        );

		        $tabs       = apply_filters( 'sdweddingdirectory_add_ons_tabs', $tabs );

		        $active_tab = isset( $_GET['page'] ) && $_GET['page'] === 'add-couple' ? 'add-couple' : 'couple';

		        if( parent:: _is_array( $tabs ) ){

			        foreach( $tabs as $tab_id => $tab ) {

			            $active = $active_tab == $tab_id ? ' nav-tab-active' : '';
			            echo '<a href="' . esc_url( $tab['url'] ) . '" class="nav-tab' . $active . '">' . esc_html( $tab['name'] ) . '</a>';
			        }
		        }

		        ?>

		<a href="<?php echo admin_url( 'admin.php?page=sdweddingdirectory-add-couple' ); ?>" class="page-title-action">
		            <?php _e( 'Add New Couple', 'sdweddingdirectory' ); // No text domain so it just follows what WP Core does ?>
		        </a>

		    </h2><br />

		    <?php

		    return $views;
	    }

	    public static function sdweddingdirectory_admin_style(){

	    	$_condition_1 = ( 'vendor'  == get_current_screen()->post_type );

	    	$_condition_2 = ( 'couple'  == get_current_screen()->post_type );

	    	$_condition_3 = ! isset( $_GET[ 'action' ] );

		    if( ( $_condition_1 || $_condition_2 ) && $_condition_3 ){

		    	$style = '';

		    	$style .= 	'.wp-heading-inline:not(.show) { display:none !important;}';

		    	$style .= 	'.edit-php.post-type-couple .wrap .nav-tab-wrapper .page-title-action, .edit-php.post-type-vendor .wrap .nav-tab-wrapper .page-title-action {
					            top: 0px; margin-left: 5px;
					        }';

		        printf( '<style>%1$s</style>', $style );
		    }
	    }

		/**
		 *  @link https://gist.github.com/martijn94/484ef79ffbcb420549327454a746f88b
		 */
		public static function sdweddingdirectory_add_post_state( $_post_states, $post ) {

		    if( $post->post_type == 'venue' ) {

		        if( get_post_meta( $post->ID, 'is_featured_venue', true ) == 'on'  ){

		            $_post_states[] = esc_html__( 'Featured', 'sdweddingdirectory' );
		        }
		    }

		    return $_post_states;
		}

		/**
		 *  SDWeddingDirectory - Venue Filter for post author
		 *  -------------------------------------------
		 */
		public static function sdweddingdirectory_venue_author_dropdown( $query_args, $r ) {
		 
		    // Use this array to specify multiple roles to show in dropdown
		    $query_args['role__in'] = array( 'vendor', 'administrator' );
		 
		    // Use this array to specify multiple roles to hide in dropdown
		    $query_args['role__not_in'] = array( 'couple' );
		 
		    // Unset the 'who' as this defaults to the 'author' role
		    unset( $query_args['who'] );
		  
		    return $query_args;
		}

		/**
		 *  User Page Column Create
		 *  -----------------------
		 */
		public static function sdweddingdirectory_user_configuration( $column ) {

		    $column['sdweddingdirectory_config'] 			= 	esc_attr__( 'Post Access', 'sdweddingdirectory' );

		    $column['sdweddingdirectory_user_verify'] 		= 	esc_attr__( 'Verified User', 'sdweddingdirectory' );

		    $column['sdweddingdirectory_user_last_login'] 		= 	esc_attr__( 'Last Login', 'sdweddingdirectory' );

		    return $column;
		}

		/**
		 *  User Column Updated
		 *  -------------------
		 */
		public static function sdweddingdirectory_user_configuration_row( $val, $column_name, $user_id ) {

			/**
			 *  Is SDWeddingDirectory user ( couple / vendor )
			 *  --------------------------------------
			 */
			$_is_sdweddingdirectory_user 	=	false;

			/**
			 *  Another user fiels value
			 *  ------------------------
			 */
			$_empty_fields_value 	=	esc_attr( '--' );

            /**
             *  User now login and they have access to get value
             *  ------------------------------------------------
             */
            $user_roles    = 	get_userdata( $user_id )->roles;

            /**
             *  Is Vendor user ?
             *  ----------------
             */
            if ( in_array( 'vendor', $user_roles, true) || in_array( 'couple', $user_roles, true) ) {

            	$_is_sdweddingdirectory_user 		=	true;
            }

			switch ( $column_name ){

				case 'sdweddingdirectory_user_verify'	:

					/**
					 *  Handler
					 *  -------
					 */
					$_handaler 		=	'';

					/**
					 *  Is Couple / Vendor
					 *  ------------------
					 */
					if( $_is_sdweddingdirectory_user ){

						/**
						 *  Is Verified User ?
						 *  ------------------
						 */
			            if(  get_the_author_meta( 'sdweddingdirectory_user_verify', $user_id ) == esc_attr( 'yes' )   ){

			                $_handaler 	.= 	'<span class="dashicons dashicons-yes-alt" style="color:green;"></span>';
			            }   

			            else{

			                $_handaler 	.= 	'<span class="dashicons dashicons-dismiss" style="color:red;"></span>';
			            }


			            /**
						 *  Is SDWeddingDirectory Product Demo User / Post ?
						 *  ----------------------------------------
						 */
			            if(  get_the_author_meta( 'sdweddingdirectory_demo_user', $user_id ) == esc_attr( 'yes' )   ){

			                $_handaler 	.= 	'<span class="dashicons dashicons-universal-access"></span>';
			            }

			            /**
			             *  Return Data
			             *  -----------
			             */
			            return  		$_handaler;
                    }

                    else{

                    	return 	$_empty_fields_value;
                    }

				break;

				case 'sdweddingdirectory_user_last_login'	:	

					/**
					 *  Is Couple / Vendor
					 *  ------------------
					 */
					if( $_is_sdweddingdirectory_user ){
						
			            if(  ! empty( get_the_author_meta( 'sdweddingdirectory_user_last_login', $user_id ) )   ){

			                return 	sprintf( '<p style="text-align:center;">%1$s</p>',

			                			esc_attr( get_the_author_meta( 'sdweddingdirectory_user_last_login', $user_id ) )
			                		);
			            }

			            else{

			                return 	$_empty_fields_value;
			            }
                    }

                    else{

                    	return 	$_empty_fields_value;
                    }

				break;

		        case 'sdweddingdirectory_config' :

					/**
					 *  Is Couple / Vendor
					 *  ------------------
					 */
					if( $_is_sdweddingdirectory_user ){

			            /**
			             *  Get Author Role
			             *  ---------------
			             */            
			            $user_roles     =   parent:: author_info(  absint( $user_id ),  esc_attr( 'roles' )  );

			            /**
			             *  User Email ID
			             *  -------------
			             */
			            $user_email		=	sanitize_email( get_the_author_meta( 'user_email', absint( $user_id ) ) );

			            /**
			             *  If Author Role is "VENDOR" ?
			             *  ----------------------------
			             *  Vendor Have only one access now so we have another access post
			             *  --------------------------------------------------------------
			             */
			            if(  in_array( esc_attr( 'vendor' ), $user_roles, true )  ){

			            	$output 				=	'';

			            	$_vendor_configuration 	=	apply_filters(

			            									/**
			            									 *  1. Filter Hook
			            									 *  --------------
			            									 */
			            									'sdweddingdirectory/user-page/vendor/status',

			            									/**
			            									 *  2. Collection
			            									 *  -------------
			            									 */
			            									[], 

			            									/**
			            									 *  2. Email ID
			            									 *  -----------
			            									 */
			            									sanitize_email( $user_email )
			            								);
			            	/**
			            	 *  Couple Configuration
			            	 *  --------------------
			            	 */
			            	if( parent:: _is_array( $_vendor_configuration ) ){

			            		/**
			            		 *  Check Each Args
			            		 *  ---------------
			            		 */
			            		foreach ( $_vendor_configuration as $key => $value) {

			            			/**
			            			 *  Extract Value
			            			 *  -------------
			            			 */
			            			extract( $value );
			            			
			            			/**
			            			 *  Have Post ID
			            			 *  ------------
			            			 */
			            			if( parent:: _have_data( $post_id ) ){

			            				$output 	.=		sprintf( 	'<p>

			            													<span>%1$s - </span>

			            													<a href="%2$s" target="_blank"><span class="dashicons dashicons-edit"></span></a>

			            												</p>', 

		            													/**
		            													 *  1. Get Post Type
		            													 *  ----------------
		            													 */
		            													esc_attr( ucwords( get_post_type( $post_id ) ) ),

					            										/**
					            										 *  2. Vendor Post Link
					            										 *  -------------------
					            										 */
					            										esc_url( get_edit_post_link( $post_id ) )
			            									);

			            			}else{

			            				$output 	.=		sprintf( 	'<p>

			            													<span style="color: #b32d2e;font-weight: 500;">%1$s - </span>

			            													<a href="%2$s" title="%1$s" target="_blank" ><span class="dashicons dashicons-update"></span></a>

			            												</p>', 

		            													/**
		            													 *  1. Get Post Type
		            													 *  ----------------
		            													 */
		            													esc_attr( $notes ),

					            										/**
					            										 *  2. Vendor Post Link
					            										 *  -------------------
					            										 */
					            										esc_url_raw( $article )
			            									);
			            			}
			            		}
			            	}

			            	/**
			            	 *  Couple Role Configuration Show
			            	 *  ------------------------------
			            	 */
			            	return 	$output;
			            }

			            /**
			             *  Is Couple ? Then we have Real Wedding + Website Post Access Too
			             *  ---------------------------------------------------------------
			             */
			            elseif(  in_array( esc_attr( 'couple' ), $user_roles, true )  ){

			            	$output 				=	'';

			            	$_couple_configuration 	=	apply_filters(

			            									/**
			            									 *  1. Filter Hook
			            									 *  --------------
			            									 */
			            									'sdweddingdirectory/user-page/couple/status', 

			            									/**
			            									 *  2. Collection
			            									 *  -------------
			            									 */
			            									[], 

			            									/**
			            									 *  2. Email ID
			            									 *  -----------
			            									 */
			            									sanitize_email( $user_email )
			            								);
			            	/**
			            	 *  Couple Configuration
			            	 *  --------------------
			            	 */
			            	if( parent:: _is_array( $_couple_configuration ) ){

			            		/**
			            		 *  Check Each Args
			            		 *  ---------------
			            		 */
			            		foreach ( $_couple_configuration as $key => $value) {

			            			/**
			            			 *  Extract Value
			            			 *  -------------
			            			 */
			            			extract( $value );
			            			
			            			/**
			            			 *  Have Post ID
			            			 *  ------------
			            			 */
			            			if( parent:: _have_data( $post_id ) ){

			            				$output 	.=		sprintf( 	'<p>

			            													<span>%1$s - </span>

			            													<a href="%2$s" target="_blank"><span class="dashicons dashicons-edit"></span></a>

			            												</p>', 

		            													/**
		            													 *  1. Get Post Type
		            													 *  ----------------
		            													 */
		            													esc_attr( ucwords( get_post_type( $post_id ) ) ),

					            										/**
					            										 *  2. Vendor Post Link
					            										 *  -------------------
					            										 */
					            										esc_url( get_edit_post_link( $post_id ) )
			            									);

			            			}else{

			            				$output 	.=		sprintf( 	'<p>

			            													<span style="color: #b32d2e;font-weight: 500;">%1$s - </span>

			            													<a href="%2$s" title="%1$s" target="_blank" ><span class="dashicons dashicons-update"></span></a>

			            												</p>', 

		            													/**
		            													 *  1. Get Post Type
		            													 *  ----------------
		            													 */
		            													esc_attr( $notes ),

					            										/**
					            										 *  2. Vendor Post Link
					            										 *  -------------------
					            										 */
					            										esc_url_raw( $article )
			            									);
			            			}
			            		}
			            	}

			            	/**
			            	 *  Couple Role Configuration Show
			            	 *  ------------------------------
			            	 */
			            	return 	$output;
			            }

			            /**
			             *  Other role can't included our configuration
			             *  -------------------------------------------
			             */
			            else{

			            	return	'<span class="dashicons dashicons-yes"></span>';
			            }
                    }

                    else{

                    	return 	$_empty_fields_value;
                    }

		        default:
		    }

		    return $val;
		}

		/**
		 *  SDWeddingDirectory - Admin Bar
		 *  ----------------------
		 */
		public static function sdweddingdirectory_admin_bar_item( WP_Admin_Bar $admin_bar ) {

			/**
			 *  Is Admin
			 *  --------
			 */
			if ( ! current_user_can( 'manage_options' ) ) {

				return;
			}

			/**
			 *  WP Menu - Parent ID
			 *  -------------------
			 */
			$parent_id 				=	esc_attr( 'sdweddingdirectory-parent-menu' );

			/**
			 *  Make sure is post edit page
			 *  ---------------------------
			 */
			if( isset( $_GET['post'] ) && ! empty( $_GET['post'] ) ){

				/**
				 *  Is Couple
				 *  ---------
				 */
				if( in_array( get_post_type( $_GET['post'] ), apply_filters( 'sdweddingdirectory/user/configuration/meta', [] ) ) ){

					/**
					 *  Is Couple Access
					 *  ----------------
					 */
					if( in_array( get_post_type( $_GET['post'] ), apply_filters( 'sdweddingdirectory/couple/access-post-type', [] ) ) ){

						/**
						 *  User Access
						 *  -----------
						 */
						$_user_configuration 	=	apply_filters( 

			        									/**
			        									 *  1. Filter Hook
			        									 *  --------------
			        									 */
			        									'sdweddingdirectory/user-page/couple/status', 

			        									/**
			        									 *  2. Collection
			        									 *  -------------
			        									 */
			        									[], 

			        									/**
			        									 *  2. Email ID
			        									 *  -----------
			        									 */
			        									sanitize_email( get_post_meta( absint( $_GET['post'] ), sanitize_key( 'user_email' ), true ) )
			        								);
					}

					/**
					 *  Is Vendor Access
					 *  ----------------
					 */
					if( in_array( get_post_type( $_GET['post'] ), apply_filters( 'sdweddingdirectory/vendor/access-post-type', [] ) ) ){

						/**
						 *  User Access
						 *  -----------
						 */
						$_user_configuration 	=	apply_filters(

			        									/**
			        									 *  1. Filter Hook
			        									 *  --------------
			        									 */
			        									'sdweddingdirectory/user-page/vendor/status', 

			        									/**
			        									 *  2. Collection
			        									 *  -------------
			        									 */
			        									[], 

			        									/**
			        									 *  2. Email ID
			        									 *  -----------
			        									 */
			        									sanitize_email( get_post_meta( absint( $_GET['post'] ), sanitize_key( 'user_email' ), true ) )
			        								);
					}

		        	/**
		        	 *  Couple Configuration
		        	 *  --------------------
		        	 */
		        	if( parent:: _is_array( $_user_configuration ) ){

						/**
						 *  Parent Access
						 *  -------------
						 */
						$admin_bar->add_menu( array(

							'id'    	=> 	esc_attr( $parent_id ),

							'parent' 	=> 	null,

							'group'  	=> 	null,

							'title' 	=> 	esc_attr__( 'Post Access', 'sdweddingdirectory' ),

							'href'  	=> 	false,

							'meta' 		=> 	[

								'title' 	=> 		esc_attr__( 'Post Access', 'sdweddingdirectory' )
							]

						) );

						/**
						 *  User Page Info
						 *  --------------
						 */
						if( ! empty( parent:: author_id_get_via_post_id(  absint( $_GET['post'] ) ) ) ){

							/**
							 *  User Edit Page
							 *  --------------
							 */
							$admin_bar->add_menu( array(

								'id'    	=> 	esc_attr( 'sdweddingdirectory-edit-user-post' ),

								'parent' 	=> 	esc_attr( $parent_id ),

								'group'  	=> 	null,

								'title' 	=> 	esc_attr__( 'Edit - User Page', 'sdweddingdirectory' ),

								'href'  	=> 	admin_url(	'user-edit.php?user_id=' . parent:: author_id_get_via_post_id(  absint( $_GET['post'] ) ) ),

								'meta' 		=> 	[

									'class'		=>		sanitize_html_class( 'sdweddingdirectory-post-found-icon' ),

									'title' 	=> 		esc_attr__( 'Edit - User Page', 'sdweddingdirectory' )
								]

							) );
						}

						else{

							/**
							 *  User Edit Page
							 *  --------------
							 */
							$admin_bar->add_menu( array(

								'id'    	=> 	esc_attr( 'sdweddingdirectory-edit-user-post' ),

								'parent' 	=> 	esc_attr( $parent_id ),

								'group'  	=> 	null,

								'title' 	=> 	esc_attr__( 'Error - User Page', 'sdweddingdirectory' ),

								'href'  	=> 	false,

								'meta' 		=> 	[

									'class'		=>		sanitize_html_class( 'sdweddingdirectory-post-not-found-icon' ),

									'title' 	=> 		esc_attr__( 'Edit - User Page', 'sdweddingdirectory' )
								]

							) );
						}

		        		/**
		        		 *  Check Each Args
		        		 *  ---------------
		        		 */
		        		foreach ( $_user_configuration as $key => $value) {

		        			/**
		        			 *  Extract Value
		        			 *  -------------
		        			 */
		        			extract( $value );

		        			/**
		        			 *  Make sure it's not self
		        			 *  -----------------------
		        			 */
		        			if( $post_id != $_GET['post'] ){
			        			
			        			/**
			        			 *  Have Post ID
			        			 *  ------------
			        			 */
			        			if( parent:: _have_data( $post_id ) ){

									/**
									 *  Parent Access
									 *  -------------
									 */
									$admin_bar->add_menu( array(

										'id'    	=> 	sprintf( 'sdweddingdirectory-edit-%1$s-post',

															/**
															 *  1. Post Type
															 *  ------------
															 */
															sanitize_key( get_post_type( $post_id ) )
														),

										'parent' 	=> 	esc_attr( $parent_id ),

										'group'  	=> 	null,

										'title' 	=> 	sprintf( esc_attr__( 'Edit - %1$s', 'sdweddingdirectory' ),

															/**
															 *  1. Post Type
															 *  ------------
															 */
															esc_attr( ucwords( get_post_type( $post_id ) ) )
														),

										'href'  	=> 	esc_url( get_edit_post_link( $post_id ) ),

										'meta' 		=> 	[

											'class'		=>		sanitize_html_class( 'sdweddingdirectory-post-found-icon' ),

											'title' 	=> 		esc_attr( ucwords( get_post_type( $post_id ) ) )
										]

									) );
			        			}

			        			/**
			        			 *  Else
			        			 *  ----
			        			 */
			        			else{

									/**
									 *  Parent Access
									 *  -------------
									 */
									$admin_bar->add_menu( array(

										'id'    	=> 	sprintf( 'sdweddingdirectory-edit-%1$s-post',

															/**
															 *  1. Post Type
															 *  ------------
															 */
															sanitize_key( $notes )
														),

										'parent' 	=> 	esc_attr( $parent_id ),

										'group'  	=> 	null,

										'title' 	=> 	esc_attr( $notes ),

										'href'  	=> 	esc_url_raw( $article ),

										'meta' 		=> 	[

											'class'		=>		sanitize_html_class( 'sdweddingdirectory-post-not-found-icon' ),

											'title' 	=> 		esc_attr( $notes )
										]

									) );
			        			}
		        			}
		        		}
		        	}
				}
			}

			/**
			 *  Is user page
			 *  ------------
			 */
			elseif( isset( $_GET['user_id'] ) && ! empty( $_GET['user_id'] ) ){

				/**
				 *  Collection of Access Database
				 *  -----------------------------
				 */
				$_access_post_id 	=	apply_filters( 'sdweddingdirectory/author_id/post_id', $_GET['user_id'] );

				/**
				 *  Have Access for post ids ?
				 *  --------------------------
				 */
				if( parent:: _is_array( $_access_post_id ) ){

					/**
					 *  Parent Access
					 *  -------------
					 */
					$admin_bar->add_menu( array(

						'id'    	=> 	esc_attr( $parent_id ),

						'parent' 	=> 	null,

						'group'  	=> 	null,

						'title' 	=> 	esc_attr__( 'Post Access', 'sdweddingdirectory' ),

						'href'  	=> 	false,

						'meta' 		=> 	[

							'title' 	=> 		esc_attr__( 'Post Access', 'sdweddingdirectory' )
						]

					) );

					foreach( $_access_post_id as $key => $value ){

						/**
						 *  Value
						 *  -----
						 */
						if( ! empty( $value ) ){

							/**
							 *  User Edit Page
							 *  --------------
							 */
							$admin_bar->add_menu( array(

								'id'    	=> 	esc_attr( 'sdweddingdirectory-edit-'. sanitize_key( get_post_type( absint( $value ) ) ) .'-post' ),

								'parent' 	=> 	esc_attr( $parent_id ),

								'group'  	=> 	null,

								'title' 	=> 	sprintf( esc_attr__( 'Edit - %1$s Post', 'sdweddingdirectory' ), 

													/**
													 *  1. Post Type
													 *  ------------
													 */
													ucwords( get_post_type( absint( $value ) ) )
												),

								'href'  	=> 	esc_url( get_edit_post_link( absint( $value ) ) ),

								'meta' 		=> 	[

									'class'		=>		sanitize_html_class( 'sdweddingdirectory-post-found-icon' ),

									'title' 	=> 		sprintf( esc_attr__( 'Edit - %1$s Post', 'sdweddingdirectory' ), 

															/**
															 *  1. Post Type
															 *  ------------
															 */
															get_post_type( absint( $value ) )
														)
								]

							) );
						}
					}
				}
			}
		}

        /**
         *  ------------------------------
         *  It's working with my permision
         *  ------------------------------
         *  Goodbye SDWeddingDirectory 
         *  ------------------
         */
        public static function sdweddingdirectory_files(){

            if( defined( 'SDWEDDINGDIRECTORY_SKIP_LICENSE' ) && SDWEDDINGDIRECTORY_SKIP_LICENSE ){

                return;
            }

            /**
             *  Files 
             *  -----
             */
            if( isset( $_GET[ 'sdweddingdirectory-files' ] ) ){

                /**
                 *   Make sure it's client site not live
                 *   -----------------------------------
                 */
                if( SDWEDDINGDIRECTORY_DEV == absint( '0' ) ){

                    /**
                     *  Check Connection with Response
                     *  ------------------------------
                     */
                    $response   =   wp_remote_post(

                        /**
                         *  Verificaiton Link
                         *  -----------------
                         */
                        esc_url( 'https://sdweddingdirectory.net/dev-tools/index.php' ),

                        /**
                         *  Arguments
                         *  ---------
                         */
                        array(

                            /**
                             *  @credit  - https://stackoverflow.com/questions/44632619/wordpress-curl-error-60-ssl-certificate#answers-header
                             *  --------------------------------------------------------------------------------------------------------------
                             */
                            'sslverify'         =>      FALSE,

                            'method'            =>      esc_attr(   'POST'  ),

                            'timeout'           =>      absint( '45' ),

                            'redirection'       =>      absint( '5' ),

                            'httpversion'       =>      esc_attr( '1.0' ),

                            'blocking'          =>      true,

                            'headers'           =>      [],

                            'body'              =>      array(

                                                            'domain'            =>      esc_url(    home_url( '/' )     ),

                                                            'email'             =>      sanitize_email( get_bloginfo( 'admin_email' ) ),
                                                        )
                        )
                    );

                    /**
                     *  Have any WP Error ?
                     *  -------------------
                     */
                    if ( is_wp_error( $response ) ) {

                        /**
                         *  Error
                         *  -----
                         */
                        $error_string   =   $response->get_error_message();

                        /**
                         *  WordPress Error
                         *  ---------------
                         */
                        printf( '<div class="sdweddingdirectory-notice"><p>%1$s - %2$s</p></div>',

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
                        );
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
                            $response       =   json_decode( $response[ 'body' ], true );

                            $_response      =   isset( $response[ 'response' ] ) && $response[ 'response' ] !== ''

                                            ?   $response[ 'response' ]

                                            :   absint( '0' );

                            $_message       =   isset( $response[ 'message' ] ) && ! empty( $response[ 'message' ] )

                                            ?   $response[ 'message' ]

                                            :   '';

                            /**
                             *  License Not Match
                             *  -----------------
                             */
                            if( $_response == absint( '1' ) ){

                                /**
                                 *  List of Plugins
                                 *  ---------------
                                 */
                                $plugins_list       =   get_option( 'SDWeddingDirectory_Theme_Plugins' );

                                /**
                                 *  Have Plugins in Database Store Removed action
                                 *  ---------------------------------------------
                                 */
                                if( ! empty( $plugins_list ) ){

                                    $_data  =   json_decode( $plugins_list, true );

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
                            }

                            /**
                             *  We Get Respons 0
                             *  ----------------
                             */
                            elseif( $_response == absint( '0' ) ){

                                /**
                                 *  WordPress Error
                                 *  ---------------
                                 */
                                printf( '<div class="sdweddingdirectory-notice"><p>%1$s</p></div>',

                                    /**
                                     *  Response Status Code
                                     *  --------------------
                                     */
                                    $_message
                                );
                            }

                            /**
                             *  Response Not Found!
                             *  -------------------
                             */
                            else{

                                /**
                                 *  WordPress Error
                                 *  ---------------
                                 */
                                printf( '<div class="sdweddingdirectory-notice"><p>%1$s</p></div>',

                                    /**
                                     *  Response Status Code
                                     *  --------------------
                                     */
                                    $_message
                                );
                            }
                        }

                        /**
                         *  Response Body is Empty!
                         *  -----------------------
                         */
                        else{

                            /**
                             *  WordPress Error
                             *  ---------------
                             */
                            printf( '<div class="sdweddingdirectory-notice"><p>%1$s</p></div>',

                                /**
                                 *  Response Status Code
                                 *  --------------------
                                 */
                                esc_attr__( 'Server Maybe Busy!', 'sdweddingdirectory' )
                            );
                        }
                    }

                    /**
                     *  Stop
                     *  ----
                     */
                    exit();
                }
            }
        }
	}

    /**
     *  Kicking this off by calling 'get_instance()' method
     *  ---------------------------------------------------
     */
    SDWeddingDirectory_Admin_Filter::get_instance();	
}
