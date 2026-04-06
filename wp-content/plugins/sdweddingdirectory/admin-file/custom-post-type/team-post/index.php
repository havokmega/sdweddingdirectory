<?php
/**
 *  SDWeddingDirectory - Register Post
 *  --------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Register_Team_Posts' ) && class_exists( 'SDWeddingDirectory_Register_Posts' ) ) {

	/**
	 *  SDWeddingDirectory - Register Post
	 *  --------------------------
	 */
	class SDWeddingDirectory_Register_Team_Posts extends SDWeddingDirectory_Register_Posts {

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

        	return 		esc_attr( 'team' );
        }

        /**
         *  Menu Position
         *  -------------
         */
        public static function menu_position(){

        	return  	absint( '25' );
        }

        /**
         *  Load Object
         *  -----------
         */
	    public function __construct(){

	    	/**
	    	 *  Register : Post
	    	 *  ---------------
	    	 */
	    	add_action( 'init', [ $this, 'create_post_type' ], absint( '5' ) );

            /**
             *  Team Post Type - Metabox [ Designation ]
             *  ----------------------------------------
             */
            add_filter( 'sdweddingdirectory/meta', [ $this, 'team_post_type_designation' ] );

            /**
             *  Post Column
             *  -----------
             */
            add_filter( 'manage_edit-'. self:: post_type() .'_columns', [ $this, 'display_column' ] );

            add_action( 'manage_'. self:: post_type() .'_posts_custom_column', [ $this, 'display_data' ], absint( '10' ), absint( '2' ) );
	    }

    	/**
    	 *  1. Register : Team Post
    	 *  -----------------------
    	 */
		public static function create_post_type() {

			/**
			 *  Labels
			 *  ------
			 */
			$labels 		= 	[

				'name'                  	=> 		_x( 'Team', 'Post Type General Name', 'sdweddingdirectory' ),

				'singular_name'         	=> 		_x( 'Team', 'Post Type Singular Name', 'sdweddingdirectory' ),

				'menu_name'             	=> 		__( 'Team', 'sdweddingdirectory' ),

				'name_admin_bar'        	=> 		__( 'Team', 'sdweddingdirectory' ),

				'archives'              	=> 		__( 'Team Archives', 'sdweddingdirectory' ),

				'attributes'            	=> 		__( 'Team Attributes', 'sdweddingdirectory' ),

				'parent_item_colon'     	=> 		__( 'Team Parent Item:', 'sdweddingdirectory' ),

				'all_items'             	=> 		__( 'All Team', 'sdweddingdirectory' ),

				'add_new_item'          	=> 		__( 'Add New Team', 'sdweddingdirectory' ),

				'add_new'               	=> 		__( 'Add New Team', 'sdweddingdirectory' ),

				'new_item'              	=> 		__( 'New Item Team', 'sdweddingdirectory' ),

				'edit_item'             	=> 		__( 'Edit Team', 'sdweddingdirectory' ),

				'update_item'           	=> 		__( 'Update Team', 'sdweddingdirectory' ),

				'view_item'             	=> 		__( 'View Team', 'sdweddingdirectory' ),

				'view_items'            	=> 		__( 'View Team', 'sdweddingdirectory' ),

				'search_items'          	=> 		__( 'Search Team', 'sdweddingdirectory' ),

				'not_found'             	=> 		__( 'Not found Team', 'sdweddingdirectory' ),

				'not_found_in_trash'    	=> 		__( 'Not found in Trash Team', 'sdweddingdirectory' ),

				'featured_image'        	=> 		__( 'Featured Image For Team', 'sdweddingdirectory' ),

				'set_featured_image'    	=> 		__( 'Set featured image For Team', 'sdweddingdirectory' ),

				'remove_featured_image' 	=> 		__( 'Remove featured image For Team', 'sdweddingdirectory' ),

				'use_featured_image'    	=> 		__( 'Use as featured image In Team', 'sdweddingdirectory' ),

				'insert_into_item'      	=> 		__( 'Insert into Team', 'sdweddingdirectory' ),

				'uploaded_to_this_item' 	=> 		__( 'Uploaded to this Team', 'sdweddingdirectory' ),

				'items_list'            	=> 		__( 'Team', 'sdweddingdirectory' ),

				'items_list_navigation' 	=> 		__( 'Team list navigation', 'sdweddingdirectory' ),

				'filter_items_list'     	=> 		__( 'Filter Team', 'sdweddingdirectory' ),
			];

			$slug 						= 	self:: post_type();

			$slug_plural 				= 	$slug . 's';

			$rewrite 					= 	[

				'slug'                  => 	$slug,

				'with_front'            => 	true,
			];

			/**
			 *  Have Args ?
			 *  -----------
			 */
			$args 	=	 [

				'label'                 	=>	 	__( 'Team', 'sdweddingdirectory' ),

				'description'           	=>	 	__( 'Register Team User Database Management Here', 'sdweddingdirectory' ),

				'labels'                	=>	 	$labels,

				'supports'              	=>	 	array( 'title', 'thumbnail' ),

				'taxonomies'            	=>		[],

				'hierarchical'          	=> 		false,

				'public'                	=> 		true,

				'show_ui'               	=> 		true,

				'show_in_menu'          	=> 		true,

				'menu_position'         	=> 		self:: menu_position(),

				'menu_icon'             	=> 		esc_attr( 'dashicons-businesswoman' ),

				'show_in_admin_bar'     	=> 		true,

				'show_in_nav_menus'     	=> 		true,

				'can_export'            	=> 		true,

				'has_archive'           	=> 		true,

				'exclude_from_search'   	=> 		true,

				'publicly_queryable'    	=> 		true,

				'capability_type'       	=> 		esc_attr( 'post' ),

				'map_meta_cap' 				=> 		true,

				'query_var' 				=> 		true,

				'rewrite' 					=> 		$rewrite
			];

            /**
             *  Register Post Type
             *  ------------------
             */
            register_post_type( self:: post_type(), array_merge( $args,

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
                apply_filters( 'sdweddingdirectory/post-cap/' . self:: post_type(), false  )

                ?   [   'capabilities'      =>     array( 'create_posts' => 'do_not_allow' )  ]

                :   []

            ) );
		}

        /**
         *  Team Post Type - Metabox [ Designation ]
         *  ----------------------------------------
         */
        public static function team_post_type_designation( $args = [] ){

            $_social_data       =   [];

            $_social_profile    =   apply_filters( 'sdweddingdirectory/social-media', [] );

            /**
             *  Is Array ?
             *  ----------
             */
            if( parent:: _is_array( $_social_profile ) ){

                foreach( $_social_profile as $key => $value ){

                    /**
                     *  Value
                     *  -----
                     */
                    extract( $value );

                    $_social_data[]  =  array(

                                            'value'     =>  esc_attr( $icon ),

                                            'label'     =>  esc_attr( $name ),

                                            'src'       =>  '',
                                        );
                }
            }

            /**
             *  New Args
             *  --------
             */
            $new_args       =   array(

                'id'        =>  esc_attr( 'team-post-type-designation-meta-box' ),

                'title'     =>  esc_attr__( 'Team Information', 'sdweddingdirectory' ),

                'pages'     =>  array( self:: post_type() ),

                'context'   =>  esc_attr( 'normal' ),

                'priority'  =>  esc_attr( 'high' ),

                'fields'    =>  array(

                    /**
                     *  Designation Tab
                     *  ---------------
                     */
                    array(

                        'label'     =>  esc_attr__( 'Designation', 'sdweddingdirectory' ),

                        'id'        =>  esc_attr( 'sdweddingdirectory_team_designation_meta_setting' ),

                        'type'      =>  esc_attr( 'tab' ),
                    ),

                    array(

                        'id'    => esc_attr( 'designation' ),

                        'label' => esc_attr__( 'Designation', 'sdweddingdirectory' ),

                        'type'  => esc_attr( 'text' ),
                    ),

                    /**
                     *  Social Media Tab
                     *  ----------------
                     */
                    array(

                        'label'     =>  esc_attr__( 'Social Media', 'sdweddingdirectory' ),

                        'id'        =>  esc_attr( 'sdweddingdirectory_team_social_media_meta_setting' ),

                        'type'      =>  esc_attr( 'tab' ),
                    ),

                    array(

                        'id'        =>  sanitize_key( 'social_profile' ),

                        'type'      =>  esc_attr( 'list-item' ),

                        'operator'  =>  esc_attr( 'or' ),

                        'choices'   =>  [],

                        'std'       =>  array(

                            /**
                             *  Social Media
                             *  ------------
                             */
                            array(

                                'title'             =>      esc_attr( 'Facebook' ),

                                'platform'          =>      esc_attr( 'fa-facebook' ),

                                'link'              =>      esc_url( 'https://facebook.com/' ),
                            ),

                            /**
                             *  Social Media
                             *  ------------
                             */
                            array(

                                'title'             =>      esc_attr( 'Twitter' ),

                                'platform'          =>      esc_attr( 'fa-twitter' ),

                                'link'              =>      esc_url( 'https://twitter.com/' ),
                            ),

                            /**
                             *  Social Media
                             *  ------------
                             */
                            array(

                                'title'             =>      esc_attr( 'Instagram' ),

                                'platform'          =>      esc_attr( 'fa-instagram' ),

                                'link'              =>      esc_url( 'https://instagram.com/' ),
                            ),

                            /**
                             *  Social Media
                             *  ------------
                             */
                            array(

                                'title'             =>      esc_attr( 'LinkedIn' ),

                                'platform'          =>      esc_attr( 'fa-linkedin' ),

                                'link'              =>      esc_url( 'https://linkedin.com/' ),
                            ),
                        ),

                        'settings'  =>  array(

                            array(

                                'id'        =>  esc_attr( 'platform' ),

                                'label'     =>  esc_attr__( 'Social Media Platform', 'wporganic'),

                                'std'       =>  absint( '0' ),

                                'type'      =>  esc_attr( 'select' ),

                                'choices'   =>  parent:: _is_array( $_social_data ) ? $_social_data : []
                            ),

                            array(

                                'id'        =>  sanitize_key( 'link' ),

                                'label'     =>  esc_attr__( 'Social Media Link', 'wporganic' ),

                                'type'      =>  esc_attr( 'text' ),
                            ),
                        ),
                    )

                ),
            );

            /**
             *  Merge Meta
             *  ----------
             */
            return      array_merge( $args, array( $new_args ) );
        }

        /**
         *  Display Column
         *  --------------
         */
        public static function display_column( $columns ) {

            /**
             *  Column
             *  ------
             */
            $columns[ 'designation' ]       =       esc_attr__( 'Designation', 'sdweddingdirectory' );

            /**
             *  Return Column
             *  -------------
             */
            return      $columns;
        }

        /**
         *  Show Column Fill Data
         *  ---------------------
         */
        public static function display_data( $column, $post_id ) {

            /**
             *  Column Data
             *  -----------
             */
            if( $column == esc_attr( 'designation' ) ){

                /**
                 *  Designation
                 *  -----------
                 */
                printf( '<span><strong>%1$s</strong></span>',

                        /**
                         *  1. Designation
                         *  --------------
                         */
                        get_post_meta( absint( $post_id ), sanitize_key( "designation" ), true )
                );
            }
        }
	}

	/**
	 *  SDWeddingDirectory - Register Post
	 *  --------------------------
	 */
    SDWeddingDirectory_Register_Team_Posts::get_instance();	
}