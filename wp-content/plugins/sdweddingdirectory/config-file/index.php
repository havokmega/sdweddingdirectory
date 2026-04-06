<?php 
/**
 *  WedingDir! - Core Configuration Setting
 *  ---------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Config' ) && class_exists( 'SDWeddingDirectory_Loader' )  ) {

	/**
	 *  WedingDir! - Core Configuration Setting
	 *  ---------------------------------------
	 */
	class SDWeddingDirectory_Config extends SDWeddingDirectory_Loader{

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
         *  Object Constructor
         *  ------------------
         */
	    public function __construct(){

            /**
             *  Load one by one shortcode file
             *  ------------------------------
             */
            foreach ( glob( plugin_dir_path( __FILE__ ) . '/*/index.php' ) as $file ) {
           
                require_once $file;
            }

	        /**
	         *  Number to Views
	         *  ---------------
	         */
	        add_filter( 'sdweddingdirectory/number-to-views', [ $this, 'number_to_views' ], absint( '10' ), absint( '1' ) );

            /**
             *  Post Type : Dropdown
             *  --------------------
             */
            add_filter( 'sdweddingdirectory/post/data', [ $this, 'post_dropdown' ], absint( '10' ), absint( '1' ) );

            /**
             *  Post Type : Dropdown
             *  --------------------
             */
            add_filter( 'sdweddingdirectory_author_dropdown', [ $this, 'sdweddingdirectory_author_dropdown' ], absint( '10' ), absint( '2' ) );

	    	/**
	    	 *  Author ID to get Access Post ID Filter
	    	 *  --------------------------------------
	    	 */
	    	add_filter( 'sdweddingdirectory/author_id/post_id', [ $this, 'sdweddingdirectory_user_post_id' ], absint( '10' ), absint( '1' ) );

	    	/**
	    	 *  SDWeddingDirectory - User [ Couple And Vendor ] - Profile image
	    	 *  -------------------------------------------------------
	    	 */
	    	add_filter( 'sdweddingdirectory/user-profile-image', [ $this, 'profile_picture' ], absint( '10' ), absint( '1' ) );

            /**
             *  Vendor - Business Image
             *  -----------------------
             */
            add_filter( 'sdweddingdirectory/vendor-business-image', [ $this, 'vendor_business_image' ], absint( '10' ), absint( '1' ) );

            /**
             *  User - Full Name
             *  ----------------
             */
            add_filter( 'sdweddingdirectory/user/full-name', [ $this, 'user_full_name' ], absint( '10' ), absint( '1' ) );
	    }

        /**
         *  Post Type : Dropdown
         *  --------------------
         */
        public static function post_dropdown( $args = [] ){

            /**
             *  Global Var
             *  ----------
             */
            global $wp_query, $post;

            /**
             *  Extract
             *  -------
             */
            extract( wp_parse_args( $args, [

                'post_type'         =>      esc_attr( 'post' ),

                'tax_query'         =>      [],

                'meta_query'        =>      [],

                'collection'        =>      [],

                'post_status'       =>      [ 'publish' ],

                'author_id'         =>      '',

                'post__not_in'		=>		[],

                'posts_per_page'	=>		-1

            ] ) );

            /**
             *  WP_Query
             *  --------
             */
            $query  =   new WP_Query( array_merge(

							/**
							 *  Default Args
							 *  ------------
							 */
    						array(

                                'post_type'         =>  esc_attr( $post_type ),

                                'posts_per_page'    =>  $posts_per_page,

                                'post_status'       =>  $post_status,
                            ),

                            /**
                             *  2. If Have Meta Query ?
                             *  -----------------------
                             */
                            parent:: _is_array( $meta_query )

                            ?   [
                                    'meta_query'    =>  [

                                        'relation'  =>  'AND',

                                        $meta_query,
                                    ]
                                ]

                            :   [],

                            /**
                             *  3. If Have Taxonomy Query ?
                             *  ---------------------------
                             */
                            parent:: _is_array( $tax_query ) 

                            ?   [   'tax_query'     =>  [

                                        'relation'  =>  'AND',

                                        $tax_query,
                                    ]
                                ]

                            :   [],

                            /**
                             *  Have Vendor Name in Query String ?
                             *  ----------------------------------
                             */
                            !   empty( $author_id )

                            ?   [  'author'  =>  absint( $author_id )  ]

                            :   [],

                            /**
                             *   ID not included
                             *   ---------------
                             */
                            parent:: _is_array( $post__not_in )

                            ?	[  'post__not_in'  	=> 	$post__not_in   ]

                            :  	[]

    					) );

            /**
             *  Have Post ?
             *  -----------
             */
            if( $query->have_posts() ){

                /**
                 *  One by one pass
                 *  ---------------
                 */
                while ( $query->have_posts() ){   $query->the_post();

                    /**
                     *  Post ID
                     *  -------
                     */
                    $post_id    =   absint( get_the_ID() );

                    /**
                     *  Have Title ?
                     *  ------------
                     */
                    if( get_the_title() != '' ) {

                        $collection[ $post_id ]     =  esc_attr( get_the_title( $post_id ) );
                    }
                }

                /**
                 *  Reset Query
                 *  -----------
                 */
                if( isset( $query ) ){

                    wp_reset_postdata();
                }
            }
            
            /**
             *  Return : Post ID = Post Title
             *  -----------------------------
             */
            return     $collection;
        }

        /**
         *  Author Dropdown Role Wise
         *  -------------------------
         */
        public static function sdweddingdirectory_author_dropdown( $args, $author_role = 'vendor' ){

            global $wp_query, $post;

            $_collection        =   [];

			$users 				= 	get_users( array(

										'role__in' 	=> 	[ $author_role ]

									) );

			if( parent:: _is_array( $users ) ){

				foreach ($users as $user) {

					$_collection[ $user->ID ]  = esc_attr( $user->display_name );
				}
			}
            
            /**
             *  Return : Post ID = Post Title
             *  -----------------------------
             */
            return     $_collection;
        }

	    /**
	     *  Get Access Post ID Filter
	     *  -------------------------
	     */
	    public static function sdweddingdirectory_user_post_id( $author_id = '0' ){

	    	global $post, $page, $wp_query, $wpdb;

	    	$data 	=	[];

	    	if( empty( $author_id ) ){

	    		return false;
	    	}

	    	/**
	    	 *  User Email ID
	    	 *  -------------
	    	 */
	    	$_user_object 	=	get_user_by(

									/**
									 *  1. ID
									 *  -----
									 */
									esc_attr( 'id' ),

									/**
									 *  2. User ID
									 *  ----------
									 */
									$author_id
								);

	    	/**
	    	 *  Get Access Post ID
	    	 *  ------------------
	    	 */
	    	$post_id 	=	self:: user_access_post_id( $_user_object );

	    	/**
	    	 *  Main Post Id for ( Couple / Vendor )
	    	 *  ------------------------------------
	    	 */
	    	if( parent:: _have_data( $post_id ) ){

	    		$data[ 'post_id' ] 	=	absint( $post_id );
	    	}

            /**
             *  Get Author Role
             *  ---------------
             */            
            $user_roles     =   self:: author_info(  absint( $author_id ),  esc_attr( 'roles' )  );

            /**
             *  If Author Role is "VENDOR" ?
             *  ----------------------------
             *  Vendor Have only one access now so we have another access post
             *  --------------------------------------------------------------
             */
            if(  in_array( esc_attr( 'vendor' ), $user_roles, true )  ){

            	/**
            	 *  Right now vendor have only one post access ( Vendor Post Type )
            	 *  ---------------------------------------------------------------
            	 */
            }

            /**
             *  Is Couple ? Then we have Real Wedding + Website Post Access Too
             *  ---------------------------------------------------------------
             */
            elseif(  in_array( esc_attr( 'couple' ), $user_roles, true )  ){

            	/**
            	 *  Couple Have Real Wedding Post Access Too
            	 *  ----------------------------------------
            	 */
            	$_have_real_wedding 	=		self:: find_post_id(

													/**
													 *  1. Post Type
													 *  ------------
													 */
													esc_attr( 'real-wedding' ),

													/**
													 *  2. Email
													 *  --------
													 */
													sanitize_email( $_user_object->data->user_email ) 
												);

            	/**
            	 *  Make sure real wedding not emtpy!
            	 *  ---------------------------------
            	 */
            	if( ! empty( $_have_real_wedding ) ){

            		$data[ 'realwedding_post' ] 	=		absint( $_have_real_wedding );
            	}
            	
            	/**
            	 *  Couple Have Real Wedding Post Access Too
            	 *  ----------------------------------------
            	 */
            	$_have_website_access 	=		self:: find_post_id(

													/**
													 *  1. Post Type
													 *  ------------
													 */
													esc_attr( 'website' ),

													/**
													 *  2. Email
													 *  --------
													 */
													sanitize_email( $_user_object->data->user_email ) 
												);

            	/**
            	 *  Make sure real wedding not emtpy!
            	 *  ---------------------------------
            	 */
            	if( ! empty( $_have_website_access ) ){

            		$data[ 'wedding_website_post' ] 	=		absint( $_have_website_access );
            	}
            }

			/**
			 *  Return : Related Post IDs
			 *  -------------------------
			 */
			return 	$data;
	    }

		/**
		 *  Current Login User get Own Post Data ID
		 *  ---------------------------------------
		 */
	    public static function post_id(){

		    global $post, $wp_query, $current_user, $wpdb;

		    /**
		     *  Make sure user is login ?
		     *  -------------------------
		     */
	    	if( is_user_logged_in() ){

	    		return  self:: user_access_post_id( wp_get_current_user() );
	    	}
	    }

	    /**
	     *  User Object to Return Access Data Post ID
	     *  -----------------------------------------
	     */
	    public static function user_access_post_id( $user ){

	    	if( parent:: _is_array( (array) $user ) ){

				/**
				 *  If login user have couple role
				 *  ------------------------------
				 */
				if ( in_array( 'couple', (array) $user->roles ) ) {

					return 	self:: find_post_id(

								/**
								 *  1. Post Type
								 *  ------------
								 */
								esc_attr( 'couple' ),

								/**
								 *  2. Email
								 *  --------
								 */
								sanitize_email( $user->data->user_email ) 
							);
				}

				/**
				 *  If login user have vendor role
				 *  ------------------------------
				 */
				elseif ( in_array( 'vendor', (array) $user->roles ) ) {

					return 	self:: find_post_id(

								/**
								 *  1. Post Type
								 *  ------------
								 */
								esc_attr( 'vendor' ),

								/**
								 *  2. Email
								 *  --------
								 */
								sanitize_email( $user->data->user_email ) 
							);
				}
	    	}
	    }

	    /**
	     *  Found Custom Post Type Meta Key to return Post ID
	     *  -------------------------------------------------
	     */
	    public static function find_post_id( $post_type = '', $email_id = '' ){

	    	/**
	    	 *  Global Filters
	    	 *  --------------
	    	 */
	    	global 	$post, $wp_query, $current_user, $wpdb;

	    	/**
	    	 *  Query
	    	 *  -----
	    	 */
            $query 	= 	new WP_Query( [

			                'post_type'         => 		esc_attr( $post_type ),

			                'post_status'       => 		array( 'publish', 'pending', 'draft' ),

			                'meta_query'        => 		array(

			                    'relation'  	=> 		esc_attr( 'AND' ),

			                    array(

				                    'key'       =>  	esc_attr( 'user_email' ),

				                    'compare'   =>  	esc_attr( '=' ),

				                    'value'     =>  	sanitize_email( $email_id )
				                )
			                )

			            ] );

            /**
             *  Have Data
             *  ---------
             */
		    	if( $query->found_posts == absint( '1' ) ){

				/**
				 *  Post IDs
				 *  --------
				 */
				$_post_id 	=	absint( wp_list_pluck( $query->posts, 'ID' )[ 0 ] );

				/**
				 *  Reset Post Query
				 *  ----------------
				 */
				if( isset( $query ) ){

					wp_reset_postdata();
				}

				/**
				 *  Get Post ID
				 *  -----------
				 */
					return 		absint( $_post_id );
				}

		    	/**
		    	 *  Vendor claim recovery:
		    	 *  if meta is stale after ownership transfer, fall back to the
		    	 *  vendor post currently owned by the matching vendor user.
		    	 */
		    	if( $post_type === esc_attr( 'vendor' ) && ! empty( $email_id ) ){

	                $user = get_user_by( esc_attr( 'email' ), sanitize_email( $email_id ) );

	                if( ! empty( $user ) && in_array( esc_attr( 'vendor' ), (array) $user->roles, true ) ){

	                    $author_query = new WP_Query( [

	                        'post_type'              => esc_attr( 'vendor' ),

	                        'post_status'            => array( 'publish', 'pending', 'draft' ),

	                        'author'                 => absint( $user->ID ),

	                        'posts_per_page'         => 1,

	                        'orderby'                => esc_attr( 'ID' ),

	                        'order'                  => esc_attr( 'ASC' ),

	                        'no_found_rows'          => true,

	                        'ignore_sticky_posts'    => true,

	                        'update_post_term_cache' => false,

	                        'update_post_meta_cache' => false,
	                    ] );

	                    if( ! empty( $author_query->posts ) ){

	                        $_post_id = absint( wp_list_pluck( $author_query->posts, 'ID' )[0] );

	                        if( $_post_id > 0 ){

	                            $stored_email = sanitize_email( get_post_meta( $_post_id, sanitize_key( 'user_email' ), true ) );

	                            if( $stored_email !== sanitize_email( $email_id ) ){

	                                update_post_meta( $_post_id, sanitize_key( 'user_email' ), sanitize_email( $email_id ) );
	                            }

	                            $stored_user_id = absint( get_post_meta( $_post_id, sanitize_key( 'user_id' ), true ) );

	                            if( $stored_user_id !== absint( $user->ID ) ){

	                                update_post_meta( $_post_id, sanitize_key( 'user_id' ), absint( $user->ID ) );
	                            }

	                            if( isset( $author_query ) ){

	                                wp_reset_postdata();
	                            }

	                            return absint( $_post_id );
	                        }
	                    }

	                    if( isset( $author_query ) ){

	                        wp_reset_postdata();
	                    }
	                }
	            }

	            /**
	             *  Never hard-exit on front-end lookup failures.
	             *  Callers must handle missing post IDs gracefully.
	             */
	            return false;
		    }

	    /**
	     *  SDWeddingDirectory - Have Map ?
	     *  -----------------------
	     */
	    public static function sdweddingdirectory_have_map(){

            /**
             *  Have Map ?
             *  ----------
             */
	    	return      parent:: _is_array( apply_filters( 'sdweddingdirectory/map/provider', [] ) ) && 

                        !   empty( sdweddingdirectory_option( 'sdweddingdirectory_map_provider' ) )

                        ?       true        

                        :       false;
	    }

	    /** 
	     *  Is Find Venue Page ?
	     *  ----------------------
	     */
	    public static function is_search_venue_page(){

	    	/**
	    	 *  Map Marker Cluster load Condition
	    	 *  ---------------------------------
	    	 */
			return 	     parent:: _load_script( 'sdweddingdirectory/enable-script/map-marker' );
	    }

	    public static function have_map(){

	    	/**
	    	 *  Map Condition
	    	 *  -------------
	    	 */
	    	return 	      parent:: _load_script( 'sdweddingdirectory/enable-script/map' );
	    }

	    /**
	     *  Is Venue Post Type ?
	     *  ----------------------
	     */
	    public static function is_venue_post( $post_id = '' ){

	    	global $post, $wp_query;

	    	/**
	    	 *  Post is not Empty!
	    	 *  ------------------
	    	 */
	    	if( empty( $post_id ) ){

	    		return false;
	    	}

            /**
             *   Post type is venue ?
             *   ----------------------
             */
            if(  get_post_type( absint( $post_id ) ) == esc_attr( 'venue' ) ){

            	return 	true;

            }else{

            	return 	false;
            }
	    }

	    /**
	     *  Venue ID to get Vendor Author ID
	     *  ----------------------------------
	     */
	    public static function venue_author_id( $post_id = '' ){

	    	if( empty( $post_id ) ){

	    		return absint( '0' );
	    	}

	    	/**
	    	 *  Get Post Author ID is venue owner
	    	 *  -----------------------------------
	    	 */
	    	return 	absint( get_post( $post_id )->post_author );
	    }

	    /**
	     *   Venue ID to get Vendor Post ID
	     *   --------------------------------
	     */
	    public static function venue_author_post_id( $post_id = '' ){

	    	if( empty( $post_id ) ){

	    		return absint( '0' );
	    	}

			/**
			 *  Author Meta to get Access Post ID Value
			 *  ---------------------------------------
			 */
			return 	self:: user_access_post_id( get_user_by(

						/**
						 *  1. ID
						 *  -----
						 */
						esc_attr( 'id' ),

						/**
						 *  2. User ID
						 *  ----------
						 */
						self:: venue_author_id(

							/**
							 *  Venue Post ID
							 *  ---------------
							 */
							absint( $post_id )
						)

					) );
	    }

	    /**
	     *  Venue Post Author Role is "Vendor" ?
	     *  --------------------------------------
	     */
	    public static function venue_author_is_vendor( $post_id = '' ){

	    	/**
	    	 *  If empty post to return
	    	 *  -----------------------
	    	 */
	    	if( empty( $post_id ) ){

	    		return false;
	    	}

            /**
             *  Get Author Role ( Is Vendor ) ?
             *  -------------------------------
             */            
            $_author_id 	=	self:: venue_author_id(  absint( $post_id )  );

            $user_roles     =   self:: author_info(  absint( $_author_id ),  esc_attr( 'roles' )  );

			if ( ! is_array( $user_roles ) ) {
				$user_roles = [];
			}

            /**
             *  If Author Role is "VENDOR" ?
             *  ----------------------------
             */
            if(  in_array( esc_attr( 'vendor' ), $user_roles, true )  ){

            	return 	true;

            }else{

            	return 	false;
            }
	    }

	    /**
	     *  Real Wedding Post ID
	     *  --------------------
	     */
	    public static function realwedding_post_id(){

	    	global $post, $wp_query;

	    	if( self:: is_couple() ){

	    		$query =	self:: find_post_id(

								/**
								 *  1. Post Type
								 *  ------------
								 */
								esc_attr( 'real-wedding' ),

								/**
								 *  2. Email
								 *  --------
								 */
								sanitize_email( get_post_meta( 

									absint( self:: post_id() ),

									sanitize_key( 'user_email' ), 

									true
								) ) 
							);

	    		/**
	    		 *  Have Real Wedding Post ID ?
	    		 *  ---------------------------
	    		 */
	    		if( ! empty( $query ) ){

	    			return absint( $query );
	    		}
	    	}
	    }

	    /**
	     *  Couple - Wedding Website Post ID
	     *  --------------------------------
	     */
	    public static function website_post_id(){

	    	global $post, $wp_query;

	    	if( self:: is_couple() ){

	    		$query =	self:: find_post_id(

								/**
								 *  1. Post Type
								 *  ------------
								 */
								esc_attr( 'website' ),

								/**
								 *  2. Email
								 *  --------
								 */
								sanitize_email( get_post_meta( 

									absint( self:: post_id() ),

									sanitize_key( 'user_email' ), 

									true
								) ) 
							);

	    		/**
	    		 *  Have Real Wedding Post ID ?
	    		 *  ---------------------------
	    		 */
	    		if( ! empty( $query ) ){

	    			return absint( $query );
	    		}
	    	}
	    }

	    /**
	     *  Login User ID
	     *  -------------
	     */
	    public static function author_id(){

		    global $current_user;
		    
		    return absint( wp_get_current_user()->ID );
	    }

	    /**
	     *  Real Wedding Post Data
	     *  ----------------------
	     */
	    public static function get_realwedding_data( $args ){

	    	global $post, $wp_query;

	    	if( empty( $args ) ){
	    		return false;
	    	}

	    	return get_post_meta( absint( self:: realwedding_post_id() ), sanitize_key( $args ), true );
	    }

	    /**
	     *  Get Post Data
	     *  -------------
	     */
	    public static function get_data( $args ){

	    	global $post, $wp_query;

	    	if( empty( $args ) ){

	    		return false;
	    	}

	    	return 	get_post_meta( self:: post_id(), sanitize_key( $args ), true );
	    }

	    /**
	     *  Get Website Post Data
	     *  ---------------------
	     */
		public static function get_website_data( $args ){

	    	global $post, $wp_query;

	    	if( empty( $args ) ){

	    		return false;
	    	}

	    	return 	get_post_meta( absint( self:: website_post_id() ), sanitize_key( $args ), true );
		}

	    /**
	     *  Is Login User is ( Vendor ) ?
	     *  -----------------------------
	     */
	    public static function is_vendor(){

	    	global $post, $wp_query, $current_user, $wpdb;

	    	if( is_user_logged_in() ){

				$user 	= 	wp_get_current_user();

				if ( in_array( 'vendor', (array) $user->roles ) ) {

				    return true;

				}else{

					return false;
				}

	    	}else{

	    		return false;
	    	}
	    }

	    /**
	     *  Is Login User is ( Couple ) ?
	     *  -----------------------------
	     */
	    public static function is_couple(){

            /**
             *  Handler
             *  -------
             */
            $handler    =   false;

            /**
             *  Is login user ?
             *  ---------------
             */
	    	if( is_user_logged_in() ){

                /**
                 *  Get Current ( login user )
                 *  --------------------------
                 */
				$user 	= 	wp_get_current_user();

                /**
                 *  Is couple role ?
                 *  ----------------
                 */
				if ( in_array( 'couple', (array) $user->roles ) ) {

				    $handler  =    true;
				}
	    	}

            /**
             *  Return Args
             *  -----------
             */
            return      $handler;
	    }

        /**
         *  Is Demo User ?
         *  --------------
         */
        public static function is_demo_user(){

            /**
             *  Handler
             *  -------
             */
            $handler    =   false;

            /**
             *  Is login user ?
             *  ---------------
             */
            if( is_user_logged_in() ){

                /**
                 *  Get Current ( login user )
                 *  --------------------------
                 */
                $user   =   wp_get_current_user();

                /**
                 *  Is Demo user ?
                 *  --------------
                 */
                if( get_the_author_meta( 'sdweddingdirectory_demo_user', absint( $user->ID ) ) == esc_attr( 'yes' ) ){

                    $handler  =    true;
                }
            }

            /**
             *  Return Args
             *  -----------
             */
            return      $handler;
        }

	    /**
	     *  Vendor Has Venue Posts ?
	     *  -----------------------
	     */
	    public static function vendor_has_venue_posts( $user_id = 0 ){

	    	$user_id = absint( $user_id );

	    	if( $user_id === 0 ){

	    		return false;
	    	}

	    	$venue_posts = get_posts( [

	    		'post_type'         =>      esc_attr( 'venue' ),

	    		'post_status'       =>      [ 'publish', 'pending', 'draft', 'private', 'future' ],

	    		'author'            =>      absint( $user_id ),

	    		'numberposts'       =>      1,

	    		'fields'            =>      'ids',

	    		'no_found_rows'     =>      true,
	    	] );

	    	return ! empty( $venue_posts );
	    }

	    /**
	     *  Resolve Vendor Dashboard Entry Page
	     *  -----------------------------------
	     */
	    public static function vendor_dashboard_entry_page( $user = null, $requested_page = '' ){

	    	$requested_page = sanitize_key( $requested_page );

	    	if( $requested_page !== '' && $requested_page !== esc_attr( 'vendor-dashboard' ) ){

	    		return $requested_page;
	    	}

	    	if( empty( $user ) ){

	    		$user = wp_get_current_user();
	    	}

	    	if( empty( $user ) || empty( $user->ID ) || ! in_array( esc_attr( 'vendor' ), (array) $user->roles, true ) ){

	    		return esc_attr( 'vendor-dashboard' );
	    	}

	    	$account_type    = sanitize_key( get_user_meta( absint( $user->ID ), sanitize_key( 'sdwd_vendor_account_type' ), true ) );
	    	$vendor_post_id  = self:: find_post_id( esc_attr( 'vendor' ), sanitize_email( $user->user_email ) );

	    	if( empty( $vendor_post_id ) && ( $account_type === esc_attr( 'venue' ) || self:: vendor_has_venue_posts( $user->ID ) ) ){

	    		return esc_attr( 'vendor-venue' );
	    	}

	    	return esc_attr( 'vendor-dashboard' );
	    }

	    /**
	     *  Check Dashboard Page Condition
	     *  ------------------------------
	     */
	    public static function dashboard_page_set( $page_name = '' ){

            /**
             *  Make sure page name not empty!
             *  ------------------------------
             */
	    	if( empty( $page_name ) ){

	    		return       false;
	    	}

            /**
             *  Make Sure Page Name not Empty!
             *  ------------------------------
             */
            else{

	    		$_dashboard_page = isset( $_GET[ 'dashboard' ] ) ? sanitize_key( $_GET[ 'dashboard' ] ) : '';

	    		if( self:: is_vendor() && ( $_dashboard_page === '' || $_dashboard_page === esc_attr( 'vendor-dashboard' ) ) ){

	    			$_dashboard_page = self:: vendor_dashboard_entry_page( wp_get_current_user(), $_dashboard_page );
	    		}

	    		$_condition_1 	=	$_dashboard_page !== '';

	    		$_condition_2 	=	isset( $page_name ) && ! empty( $page_name );

	    		/**
	    		 *  Page have this attributes as *GET* method ?
	    		 *  -------------------------------------------
	    		 */
	    		return       $_condition_1 && $_condition_2 && $_dashboard_page == esc_attr( $page_name );
	    	}
	    }

	    /**
	     *  Return Author Information
	     *  -------------------------
	     */
	    public static function author_info( $author_id, $get_value ){

	    	global $post, $wp_query, $current_user;

	    	if( empty( $author_id ) && empty( $get_value ) ){

	    		return;
	    	}

			$user = get_user_by( 'id', $author_id );

			if ( ! $user || ! is_object( $user ) || ! isset( $user->$get_value ) ) {
				return ( $get_value === 'roles' ) ? [] : '';
			}

			$value = $user->$get_value;

			if ( $get_value === 'roles' && ! is_array( $value ) ) {
				return [];
			}

			return $value;
	    }

	    /**
	     *  Return Profile Image
	     *  --------------------
	     */
	    public static function user_full_name( $args = [] ){

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

	    			'post_id'		=>		absint( '0' ),

	    			'handler'		=>		[]

	    		] ) );

	    		/**
	    		 *  Make sure it's not empty!
	    		 *  -------------------------
	    		 */
	    		if( empty( $post_id ) ){

	    			return;
	    		}

	    		/**
	    		 *  Make sure two user 
	    		 *  ------------------
	    		 */
	    		if( in_array( get_post_type( $post_id ), [ 'couple', 'vendor' ] ) ){

	    			$first_name 		=		get_post_meta( absint( $post_id ), sanitize_key( 'first_name' ), true );

	    			$last_name 			=		get_post_meta( absint( $post_id ), sanitize_key( 'last_name' ), true );

		    		/**
		    		 *  Full Name - First Name and Last Name
		    		 *  ------------------------------------
		    		 */
	    			return   	esc_attr(  $first_name . ' ' .  $last_name  );
	    		}
	    	}
	    }

	    /**
	     *  Return Profile Image
	     *  --------------------
	     */
	    public static function profile_picture( $args = [] ){

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

	    			'post_id'				=>		absint( '0' ),

	    			'default_image'			=>		esc_url( self:: placeholder( 'my-profile' ) )

	    		] ) );

	    		/**
	    		 *  Make sure it's not empty!
	    		 *  -------------------------
	    		 */
	    		if( empty( $post_id ) ){

	    			return   	esc_url(  $default_image  );
	    		}

	    		/**
	    		 *  Make sure two user 
	    		 *  ------------------
	    		 */
	    		if( in_array( get_post_type( $post_id ), [ 'couple', 'vendor' ] ) ){

		    		/**
		    		 *  Is Couple Post ?
		    		 *  ----------------
		    		 */
		    		if( get_post_type( $post_id ) == esc_attr( 'couple' ) ){

		    			$placeholder 		=		esc_attr( 'my-profile' );
		    		}

		    		/**
		    		 *  Is Vendor Post ?
		    		 *  ----------------
		    		 */
		    		elseif( get_post_type( $post_id ) == esc_attr( 'vendor' ) ){

		    			$placeholder 		=		esc_attr( 'vendor-profile' );
		    		}

	    			/*
	    			 *  Have Image ?
	    			 *  ------------
	    			 */
	    			$media_id 				=		get_post_meta( $post_id, sanitize_key( 'user_image' ), true );

	    			/**
	    			 *  Have Image ?
	    			 *  ------------
	    			 */
	    			if(  parent:: _have_media( $media_id ) &&  ! empty( $media_id ) ){

						return  	esc_url(  apply_filters( 'sdweddingdirectory/media-data', [

					                    'media_id'         =>  absint( $media_id ),

					                    'image_size'       =>  esc_attr( 'thumbnail' ),

						             ] ) );
	    			}

	    			/**
	    			 *  Return Default - Setting Option Added Placeholder
	    			 *  -------------------------------------------------
	    			 */
	    			else{

	    				return   	esc_url( self:: placeholder( $placeholder ) );
	    			}
	    		}
	    	}
	    }

        /**
         *  Get Vendor Company IMG
         *  ----------------------
         */
        public static function vendor_business_image( $args = [] ){

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

                    'post_id'           =>      absint( '0' ),

                    'default_image'     =>      esc_url( parent:: placeholder( 'vendor-brand-image' ) ),

                    'image_size'		=>		esc_attr( 'thumbnail' )

                ] ) );

                /**
                 *  Make sure post id not empty!
                 *  ----------------------------
                 */
                if( empty( $post_id ) ){

                    return          $default_image;
                }

                /**
                 *  Media ID
                 *  --------
                 */
                $media_id       =       get_post_meta( $post_id, sanitize_key( 'business_icon' ), true );

                /**
                 *  Return Media
                 *  ------------
                 */
                if(  parent:: _have_media( $media_id )  ){

                    /**
                     *  Return - Media Url
                     *  ------------------
                     */
                    return      apply_filters( 'sdweddingdirectory/media-data', [

                                    'media_id'         =>  absint( $media_id ),

                                    'image_size'       =>  esc_attr( $image_size ),

                                ] );
                }

                /**
                 *  Default Image
                 *  -------------
                 */
                else{

                    return          $default_image;
                }
            }
        }

	    /**
	     *  Get User Name
	     *  -------------
	     */
        public static function user_login(){

            global $current_user;
            
            return esc_html( wp_get_current_user()->user_login );
        }

        /**
         *  If Found security issue
         *  -----------------------
         */
        public static function security_issue_found(){

            die( json_encode( array(

                'message'   => esc_attr__( 'Security Issue Found!', 'sdweddingdirectory' ),

                'notice'    => absint( '2' )

            ) ) );
        }

        /**
         *  SDWeddingDirectory - Popup IDs
         *  ----------------------
         */
        public static function popup_id( $args = '' ){

        	/**
        	 *  Get List Of Model
        	 *  -----------------
        	 */
        	$_list_of_model 	=	apply_filters( 'sdweddingdirectory/model-popup', [] );

        	/**
        	 *  Have Data ?
        	 *  -----------
        	 */
        	if( parent:: _is_array( $_list_of_model ) ){

                $_list_of_model     =   array_column( $_list_of_model, esc_attr( 'modal_id' ), esc_attr( 'slug' ) );

        		if( isset( $_list_of_model[ $args ] ) && ! empty( $_list_of_model[ $args ] ) ){

        			return 	esc_attr( $_list_of_model[ $args ] );
        		}
        	}
        }

        /**
         *  Get Vendor / Couple Post ID to Get User Register ID
         *  ---------------------------------------------------
         */
        public static function author_id_get_via_post_id( $post_id = 0 ){

        	/**
        	 *  Make sure post id is not empty!
        	 *  -------------------------------
        	 */
        	if( parent:: _have_data( $post_id ) ){

    			$email 	=	sanitize_email( get_post_meta(

			                    /**
			                     *  1. Vendor Post ID
			                     *  -----------------
			                     */
			                    absint( $post_id ),

			                    /**
			                     *  2. Meta Key ( Get Author ID )
			                     *  -----------------------------
			                     */
			                    sanitize_key( 'user_email' ),

			                    /**
			                     *  3. TRUE
			                     *  -------
			                     */
			                    true 
			                ) );

    			/**
    			 *  Return : Register User ID
    			 *  -------------------------
    			 */
    			return 	self:: get_user_id_via_email( $email );

        	}else{

        		return 	false;
        	}
        }

        /** 
         *  User Email ID to Get User ID
         *  ----------------------------
         *  @credit - https://rudrastyh.com/wordpress/get-user-id.html#user_id_by_email
         *  ---------------------------------------------------------------------------
         */
        public static function get_user_id_via_email( $email = '' ){

        	/**
        	 *  Make sure email not empty!
        	 *  --------------------------
        	 */
        	if( parent:: _have_data( $email ) ){

				$the_user 	= 	get_user_by('email', sanitize_email( $email ) );

				/**
				 *  Make sure user found
				 *  --------------------
				 */
				if( ! empty( $the_user ) ){

					return 	absint( $the_user->ID );
				}

				else{

					return 	absint( '0' );
				}

        	}else{

        		return 	absint( '0' );
        	}
        }

        /**
         *  Check the enable library / script / style request filter available ?
         *  --------------------------------------------------------------------
         */
        public static function _load_script( $hook = '' ){

        	/**
        	 *  Make sure hook not empty
        	 *  ------------------------
        	 */
        	if( empty( $hook ) ){

        		return false;
        	}

            /**
             *  Have Collection ?
             *  -----------------
             */
            $_request_collection    =   apply_filters( $hook, [] );

            /**
             *  Have Request ?
             *  --------------
             */
            if( parent:: _is_array( $_request_collection ) && in_array( 'true', $_request_collection ) ){

            	return 	true;

            }else{

            	return false;
            }
        }

        /**
         *  Find Slug Target Post
         *  ---------------------
         */
        public static function slug_target_post( $slug = '', $exclude_post_id = 0, $post_types = [ 'vendor', 'venue' ] ){

            $slug = sanitize_title( $slug );

            if( $slug === '' ){

                return null;
            }

            $query = new WP_Query( [

                'post_type'              => $post_types,

                'post_status'            => [ 'publish', 'pending', 'draft', 'private', 'future' ],

                'name'                   => $slug,

                'posts_per_page'         => 1,

                'fields'                 => 'ids',

                'ignore_sticky_posts'    => true,

                'no_found_rows'          => true,

                'post__not_in'           => absint( $exclude_post_id ) > 0 ? [ absint( $exclude_post_id ) ] : [],
            ] );

            if( ! empty( $query->posts ) ){

                $post_id = absint( $query->posts[0] );

                return get_post( $post_id );
            }

            return null;
        }

        /**
         *  Validate Business Slug
         *  ----------------------
         */
        public static function validate_business_slug( $slug_source = '', $exclude_post_id = 0, $args = [] ){

            extract( wp_parse_args( $args, [

                'user_id'       => 0,

                'post_types'    => [ 'vendor', 'venue' ],
            ] ) );

            $slug = sanitize_title( $slug_source );

            if( $slug === '' ){

                return [

                    'status'            => 'invalid',

                    'slug'              => '',

                    'message'           => esc_attr__( 'Please enter a valid business name.', 'sdweddingdirectory' ),

                    'target_post_id'    => 0,

                    'target_post_type'  => '',

                    'target_slug'       => '',
                ];
            }

            $target_post = self:: slug_target_post( $slug, absint( $exclude_post_id ), $post_types );

            if( empty( $target_post ) ){

                return [

                    'status'            => 'available',

                    'slug'              => $slug,

                    'message'           => '',

                    'target_post_id'    => 0,

                    'target_post_type'  => '',

                    'target_slug'       => '',
                ];
            }

            $target_id = absint( $target_post->ID );

            $is_owner  = absint( $exclude_post_id ) > 0 && absint( $exclude_post_id ) === $target_id;

            if( ! $is_owner && absint( $user_id ) > 0 ){

                $is_owner = absint( $target_post->post_author ) === absint( $user_id );
            }

            return [

                'status'            => $is_owner ? 'owned' : 'taken',

                'slug'              => $slug,

                'message'           => $is_owner ? '' : esc_attr__( 'This profile already exists.', 'sdweddingdirectory' ),

                'target_post_id'    => $target_id,

                'target_post_type'  => sanitize_key( $target_post->post_type ),

                'target_slug'       => sanitize_title( $target_post->post_name ),
            ];
        }

        /**
         *  Update Post Slug via Validator
         *  ------------------------------
         */
        public static function apply_validated_slug( $post_id = 0, $slug_source = '', $args = [] ){

            $post_id = absint( $post_id );

            if( $post_id === 0 ){

                return [

                    'status'            => 'invalid',

                    'slug'              => '',

                    'updated'           => false,

                    'message'           => esc_attr__( 'Invalid profile.', 'sdweddingdirectory' ),

                    'target_post_id'    => 0,

                    'target_post_type'  => '',

                    'target_slug'       => '',
                ];
            }

            $validation = self:: validate_business_slug( $slug_source, $post_id, $args );

            if( ! in_array( $validation['status'], [ 'available', 'owned' ], true ) ){

                return array_merge( $validation, [ 'updated' => false ] );
            }

            $slug = sanitize_title( $validation['slug'] );

            if( $slug === '' ){

                return array_merge( $validation, [

                    'status'    => 'invalid',

                    'updated'   => false,
                ] );
            }

            $current_slug = get_post_field( 'post_name', $post_id );

            if( $current_slug === $slug ){

                return array_merge( $validation, [ 'updated' => true ] );
            }

            $updated = wp_update_post( [

                'ID'        => $post_id,

                'post_name' => $slug,

            ], true );

            if( is_wp_error( $updated ) ){

                return array_merge( $validation, [

                    'status'    => 'error',

                    'updated'   => false,

                    'message'   => $updated->get_error_message(),
                ] );
            }

            return array_merge( $validation, [ 'updated' => true ] );
        }

        /**
         *  Is Dashboard Page Template ?
         *  ----------------------------
         */
        public static function is_dashboard(){

            /**
             *  Is Couple Dashboard - Page Template
             *  -----------------------------------
             */
            $_condition_1   =   is_page_template( 'user-template/couple-dashboard.php' );

            /**
             *  Is Vendor Dashboard - Page Template
             *  -----------------------------------
             */
            $_condition_2   =   is_page_template( 'user-template/vendor-dashboard.php' );

            /**
             *  Is Dashboard Page Template ?
             *  ----------------------------
             */
            return   (  $_condition_1 ||  $_condition_2  );
        }

        /**
         *  Counter 
         *  -------
         */
        public static function number_to_views( $num ){

        	/**
        	 *  Number is Max 1K
        	 *  ----------------
        	 */
          	if( $num > 1000 ){

                $x 					= 	round( $num );

                $x_number_format 	= 	number_format($x);

                $x_array 			= 	explode(',', $x_number_format);

                $x_parts 			= 	array( 'k', 'm', 'b', 't' );

                $x_count_parts 		= 	count($x_array) - 1;

                $x_display 			= 	$x;

                $x_display 			= 	$x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');

                $x_display 		   .= 	$x_parts[$x_count_parts - 1];

                /**
                 *  Return
                 *  ------
                 */
                return 					$x_display;
          	}

          	return $num;
        }
	}

	/**
	 *  SDWeddingDirectory - Configuration OBJ calling
	 *  --------------------------------------
	 */
	SDWeddingDirectory_Config:: get_instance();
}
