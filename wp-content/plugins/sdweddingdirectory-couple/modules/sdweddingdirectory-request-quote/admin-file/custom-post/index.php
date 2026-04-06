<?php
/**
 *  SDWeddingDirectory - Register Post
 *  --------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Register_Request_Quote_Post' ) && class_exists( 'SDWeddingDirectory_Register_Posts' ) ) {

    /**
     *  SDWeddingDirectory - Register Post
     *  --------------------------
     */
	class SDWeddingDirectory_Register_Request_Quote_Post extends SDWeddingDirectory_Register_Posts{

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
         *  Post Type
         *  ---------
         */
        public static function post_type(){

            /**
             *  Slug
             *  ----
             */
            return      esc_attr( 'venue-request' );
        }

        /**
         *  Menu Position
         *  -------------
         */
        public static function menu_position(){

            return      absint( '25' );
        }

        /**
         *  Object Load
         *  -----------
         */
	    public function __construct(){

            /**
             *  Register Post
             *  -------------
             */
            add_action( 'init', [ $this, 'create_post_type' ], absint( '10' ) );

            /**
             *  When admin approved ( Publish ) 
             *  -------------------------------
             */
            add_action( 'post_updated', [ $this, 'post_update_action' ], absint( '10' ), absint( '3' ) );
	    }

        /**
         *  Create Post
         *  -----------
         */
		public function create_post_type(){

            /**
             *  Label
             *  -----
             */
			$labels                         =        [

				'name'                      =>      _x( 'Request Quote', 'Post Type General Name', 'sdweddingdirectory-request-quote' ),

				'singular_name'             =>      _x( 'Request Quote', 'Post Type Singular Name', 'sdweddingdirectory-request-quote' ),

				'menu_name'                 =>      __( 'Request Quote', 'sdweddingdirectory-request-quote' ),

				'name_admin_bar'            =>      __( 'Request Quote', 'sdweddingdirectory-request-quote' ),

				'archives'                  =>      __( 'Request Archives', 'sdweddingdirectory-request-quote' ),

				'attributes'                =>      __( 'Request Attributes', 'sdweddingdirectory-request-quote' ),

				'parent_item_colon'         =>      __( 'Request Parent Item:', 'sdweddingdirectory-request-quote' ),

				'all_items'                 =>      __( 'All Request', 'sdweddingdirectory-request-quote' ),

				'add_new_item'              =>      __( 'Add New Request', 'sdweddingdirectory-request-quote' ),

				'add_new'                   =>      __( 'Add New Request', 'sdweddingdirectory-request-quote' ),

				'new_item'                  =>      __( 'New Item Request', 'sdweddingdirectory-request-quote' ),

				'edit_item'                 =>      __( 'Edit Request', 'sdweddingdirectory-request-quote' ),

				'update_item'               =>      __( 'Update Request', 'sdweddingdirectory-request-quote' ),

				'view_item'                 =>      __( 'View Request', 'sdweddingdirectory-request-quote' ),

				'view_items'                =>      __( 'View Request', 'sdweddingdirectory-request-quote' ),

				'search_items'              =>      __( 'Search Request', 'sdweddingdirectory-request-quote' ),

				'not_found'                 =>      __( 'Not found Request', 'sdweddingdirectory-request-quote' ),

				'not_found_in_trash'        =>      __( 'Not found in Trash Request', 'sdweddingdirectory-request-quote' ),

				'featured_image'            =>      __( 'Featured Image For Request', 'sdweddingdirectory-request-quote' ),

				'set_featured_image'        =>      __( 'Set featured image For Request', 'sdweddingdirectory-request-quote' ),

				'remove_featured_image'     =>      __( 'Remove featured image For Request', 'sdweddingdirectory-request-quote' ),

				'use_featured_image'        =>      __( 'Use as featured image In Request', 'sdweddingdirectory-request-quote' ),

				'insert_into_item'          =>      __( 'Insert into Request', 'sdweddingdirectory-request-quote' ),

				'uploaded_to_this_item'     =>      __( 'Uploaded to this Request', 'sdweddingdirectory-request-quote' ),

				'items_list'                =>      __( 'Request Quote', 'sdweddingdirectory-request-quote' ),

				'items_list_navigation'     =>      __( 'Request list navigation', 'sdweddingdirectory-request-quote' ),

				'filter_items_list'         =>      __( 'Filter Request', 'sdweddingdirectory-request-quote' ),
			];

            $slug                       =   self:: post_type();

            $slug_plural                =   $slug . 's';

            $rewrite                    =   [

                'slug'                  =>  $slug,

                'with_front'            =>  true,
            ];

            /**
             *  Args
             *  ----
             */
			$args = [

				'label'                     =>      __( 'Request Quote', 'sdweddingdirectory-request-quote' ),

				'description'               =>      __( 'User requests for venue information.', 'sdweddingdirectory-request-quote' ),

				'labels'                    =>      $labels,

				'supports'                  =>      array( 'title' ),

				'taxonomies'                =>      [],

				'hierarchical'              =>      false,

				'public'                    =>      true,

				'show_ui'                   =>      true,

				'show_in_menu'              =>      true,

				'menu_position'             =>      self:: menu_position(),

				'menu_icon'                 =>      esc_attr( 'dashicons-edit' ),

				'show_in_admin_bar'         =>      true,

				'show_in_nav_menus'         =>      true,

				'can_export'                =>      true,

				'has_archive'               =>      true,

				'exclude_from_search'       =>      true,

				'publicly_queryable'        =>      true,

				'capability_type'           =>      esc_attr( 'post' ),

				'map_meta_cap' 			    =>      true,

				'query_var' 			    =>      true,

				'rewrite' 				    =>      $rewrite
			];

            /**
             *  Register Post Type
             *  ------------------
             */
            register_post_type( self:: post_type(), array_merge( 

                /**
                 *  Default Args
                 *  ------------
                 */
                $args,

                /**
                 *  Enable Rest API
                 *  ---------------
                 */
                apply_filters( 'sdweddingdirectory/rest-api/' . self:: post_type(),  false  )

                ?       parent:: rest_api_enable(  self:: post_type()  )

                :       [],

                /**
                 *  Create Post Capacity ?
                 *  ----------------------
                 */
                apply_filters( 'sdweddingdirectory/post-cap/' . self:: post_type(), true  )

                ?   [   'capabilities'      =>     array( 'create_posts' => 'do_not_allow' )  ]

                :   []


            ) );
		}

        /**
         *  When admin approved ( Publish ) this venue after claim venue assing with vendor and email sending too
         *  ---------------------------------------------------------------------------------------------------------
         */
        public static function post_update_action( $post_id, $post_after, $post_before ){

            /**
             *  Is Post Correct ?
             *  -----------------
             */
            $_condition_1       =   $post_after->post_type == esc_attr( self:: post_type() );

            $_condition_2       =   ! empty( $post_id );

            $_approved_status   =   $_condition_1 && $_condition_2 && 

                                    $post_after->post_status == esc_attr( 'publish' ) &&  

                                    $post_before->post_status !== esc_attr( 'publish' );

            $_declain_status    =   $_condition_1 && $_condition_2 && 

                                    $post_after->post_status == esc_attr( 'trash' ) &&  

                                    $post_before->post_status !== esc_attr( 'pending' );

            /**
             *  Make sure this WP_Action is for claim post
             *  ------------------------------------------
             */
            if( $_condition_1 && $_condition_2 ){

                /**
                 *  @link  https://wordpress.stackexchange.com/questions/152422/get-list-of-all-the-authors#answer-152430
                 *  -----------------------------------------------------------------------------------------------------
                 */
                $_venue_id       	=  get_post_meta( $post_id, sanitize_key( "venue_id" ), true );

                $_vendor_id         =  get_post_meta( $post_id, sanitize_key( "vendor_id" ), true );

                $_couple_id         =  get_post_meta( $post_id, sanitize_key( "couple_id" ), true );

                /**
                 *  User Email IDs
                 *  --------------
                 */
                $_vendor_email 		=	get_post_meta( $_vendor_id, sanitize_key( 'user_email' ), true );

                $_couple_email 		=	get_post_meta( $_couple_id, sanitize_key( 'user_email' ), true );

                /**
                 *  Request Couple Information
                 *  --------------------------
                 */
                $_request_name      =  get_post_meta( $post_id, sanitize_key( "request_quote_name" ), true );

                $_request_guest     =  get_post_meta( $post_id, sanitize_key( "request_quote_guest" ), true );

                $_request_badget 	=  get_post_meta( $post_id, sanitize_key( "request_quote_budget" ), true );

                $_request_contact 	=  get_post_meta( $post_id, sanitize_key( "request_quote_contact" ), true );

                $_request_date 		=  get_post_meta( $post_id, sanitize_key( "request_quote_wedding_date" ), true );

                $_request_message 	=  get_post_meta( $post_id, sanitize_key( "request_quote_message" ), true );
                
                $_contact_via 		=  get_post_meta( $post_id, sanitize_key( "request_quote_contact_option" ), true );

                $_contact_me_via 	=	'';

                if( $_contact_via == absint( '1' ) ){

                	$_contact_me_via	=	esc_attr__( 'Call', 'sdweddingdirectory-request-quote' );

                }elseif( $_contact_via == absint( '2' ) ){

                	$_contact_me_via	=	esc_attr__( 'Email', 'sdweddingdirectory-request-quote' );

                }elseif( $_contact_via == absint( '3' ) ){

                	$_contact_me_via	=	esc_attr__( 'Video Call', 'sdweddingdirectory-request-quote' );
                }
                
                /**
                 *  Admin Approved Claim
                 *  --------------------
                 */
                if( $_approved_status ){

                    wp_update_post( array(

                        'ID'            =>  absint( $post_id ),

                        'post_author'   =>  absint( '1' ),

                    ), true );

                    /**
                     *  Email Process
                     *  -------------
                     */
                    if( class_exists( 'SDWeddingDirectory_Email' ) ){

                        /**
                         *  Sending Email
                         *  -------------
                         */
                        SDWeddingDirectory_Email:: sending_email( array(

                            /**
                             *  1. Setting ID : Email PREFIX_
                             *  -----------------------------
                             */
                            'setting_id'        =>      esc_attr( 'vendor-venue-request' ),

                            /**
                             *  2. Sending Email ID
                             *  -------------------
                             */
                            'sender_email'      =>      sanitize_email( $_vendor_email ),

                            /**
                             *  3. Email Data Key and Value as Setting Body Have {{...}} all
                             *  ------------------------------------------------------------
                             */
                            'email_data'        =>      array(

                                                            'venue_name'      			=>  esc_attr( get_the_title( absint( $_venue_id ) ) ),

                                                            'vendor_username'				=>	esc_attr( get_the_title( absint( $_vendor_id ) ) ),

                                                            'request_quote_name'			=>	esc_attr( $_request_name ),

                                                            'request_quote_guest'			=>	absint( $_request_guest ),

                                                            'request_quote_budget'			=>	esc_attr( $_request_badget ),

                                                            'request_quote_email'			=>	sanitize_email( $_couple_email ),

                                                            'request_quote_contact'			=>	esc_attr( $_request_contact ),

                                                            'request_quote_wedding_date'	=>	esc_attr( $_request_date ),

                                                            'request_quote_message'			=>	esc_textarea( $_request_message ),

                                                            'request_quote_contact_option'	=>	esc_attr( $_contact_me_via )
                                                        )
                        ) );
                    }
                }

                /**
                 *  Admin Declain Claim
                 *  -------------------
                 */
                elseif( $_declain_status ){

                	// If in future work.....
                }
            }
        }
	}

    /**
     *  SDWeddingDirectory - Register Post
     *  --------------------------
     */
    SDWeddingDirectory_Register_Request_Quote_Post::get_instance();	
}