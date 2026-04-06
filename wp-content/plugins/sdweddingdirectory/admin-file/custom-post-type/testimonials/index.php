<?php
/**
 *  SDWeddingDirectory - Register Post
 *  --------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Register_Testimonials_Posts' ) && class_exists( 'SDWeddingDirectory_Register_Posts' ) ) {

	/**
	 *  SDWeddingDirectory - Register Post
	 *  --------------------------
	 */
	class SDWeddingDirectory_Register_Testimonials_Posts extends SDWeddingDirectory_Register_Posts {

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

        	return 		esc_attr( 'testimonial' );
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
             *  Testimonial Post Type - Metabox [ Designation ]
             *  -----------------------------------------------
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
    	 *  1. Register : Testimonial Post
    	 *  ------------------------------
    	 */
		public static function create_post_type() {

			/**
			 *  Labels
			 *  ------
			 */
			$labels 		= 	[

				'name'                  	=> 		_x( 'Testimonial', 'Post Type General Name', 'sdweddingdirectory' ),

				'singular_name'         	=> 		_x( 'Testimonial', 'Post Type Singular Name', 'sdweddingdirectory' ),

				'menu_name'             	=> 		__( 'Testimonial', 'sdweddingdirectory' ),

				'name_admin_bar'        	=> 		__( 'Testimonial', 'sdweddingdirectory' ),

				'archives'              	=> 		__( 'Testimonial Archives', 'sdweddingdirectory' ),

				'attributes'            	=> 		__( 'Testimonial Attributes', 'sdweddingdirectory' ),

				'parent_item_colon'     	=> 		__( 'Testimonial Parent Item:', 'sdweddingdirectory' ),

				'all_items'             	=> 		__( 'All Testimonial', 'sdweddingdirectory' ),

				'add_new_item'          	=> 		__( 'Add New Testimonial', 'sdweddingdirectory' ),

				'add_new'               	=> 		__( 'Add New Testimonial', 'sdweddingdirectory' ),

				'new_item'              	=> 		__( 'New Item Testimonial', 'sdweddingdirectory' ),

				'edit_item'             	=> 		__( 'Edit Testimonial', 'sdweddingdirectory' ),

				'update_item'           	=> 		__( 'Update Testimonial', 'sdweddingdirectory' ),

				'view_item'             	=> 		__( 'View Testimonial', 'sdweddingdirectory' ),

				'view_items'            	=> 		__( 'View Testimonial', 'sdweddingdirectory' ),

				'search_items'          	=> 		__( 'Search Testimonial', 'sdweddingdirectory' ),

				'not_found'             	=> 		__( 'Not found Testimonial', 'sdweddingdirectory' ),

				'not_found_in_trash'    	=> 		__( 'Not found in Trash Testimonial', 'sdweddingdirectory' ),

				'featured_image'        	=> 		__( 'Featured Image For Testimonial', 'sdweddingdirectory' ),

				'set_featured_image'    	=> 		__( 'Set featured image For Testimonial', 'sdweddingdirectory' ),

				'remove_featured_image' 	=> 		__( 'Remove featured image For Testimonial', 'sdweddingdirectory' ),

				'use_featured_image'    	=> 		__( 'Use as featured image In Testimonial', 'sdweddingdirectory' ),

				'insert_into_item'      	=> 		__( 'Insert into Testimonial', 'sdweddingdirectory' ),

				'uploaded_to_this_item' 	=> 		__( 'Uploaded to this Testimonial', 'sdweddingdirectory' ),

				'items_list'            	=> 		__( 'Testimonial', 'sdweddingdirectory' ),

				'items_list_navigation' 	=> 		__( 'Testimonial list navigation', 'sdweddingdirectory' ),

				'filter_items_list'     	=> 		__( 'Filter Testimonial', 'sdweddingdirectory' ),
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

				'label'                 	=>	 	__( 'Testimonial', 'sdweddingdirectory' ),

				'description'           	=>	 	__( 'Register Testimonial for your website', 'sdweddingdirectory' ),

				'labels'                	=>	 	$labels,

				'supports'              	=>	 	array( 'title', 'thumbnail' ),

				'taxonomies'            	=>		[],

				'hierarchical'          	=> 		false,

				'public'                	=> 		true,

				'show_ui'               	=> 		true,

				'show_in_menu'          	=> 		true,

				'menu_position'         	=> 		self:: menu_position(),

				'menu_icon'             	=> 		esc_attr( 'dashicons-testimonial' ),

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
         *  Testimonial Post Type - Metabox [ Designation ]
         *  ----------------------------------------
         */
        public static function team_post_type_designation( $args = [] ){

            /**
             *  Add New Args
             *  ------------
             */
            $new_args               =       array(

                'id'                =>      esc_attr( 'testimonial-post-type-meta-box' ),

                'title'             =>      esc_attr__( 'Testimonial Information', 'sdweddingdirectory' ),

                'pages'             =>      array( self:: post_type() ),

                'context'           =>      esc_attr( 'normal' ),

                'priority'          =>      esc_attr( 'high' ),

                'fields'            =>      array(

                    array(

                        'id'            =>      esc_attr( 'location' ),

                        'label'         =>      esc_attr__( 'Location', 'sdweddingdirectory' ),

                        'type'          =>      esc_attr( 'text' ),

                        'std'           =>      esc_attr( 'New York, USA' )
                    ),

                    array(

                        'id'            =>      esc_attr( 'rating' ),

                        'label'         =>      esc_attr__( 'Rating', 'sdweddingdirectory' ),

                        'type'          =>      esc_attr( 'select' ),

                        'std'           =>      esc_attr( '5' ),

                        'choices'       =>      array(

                                                    array(

                                                        'value'       =>    '1',

                                                        'label'       =>    esc_attr__( '1 Star', 'sdweddingdirectory' ),

                                                        'src'         =>    ''
                                                    ),

                                                    array(

                                                        'value'       =>    '2',

                                                        'label'       =>    esc_attr__( '2 Star', 'sdweddingdirectory' ),

                                                        'src'         =>    ''
                                                    ),

                                                    array(

                                                        'value'       =>    '3',

                                                        'label'       =>    esc_attr__( '3 Star', 'sdweddingdirectory' ),

                                                        'src'         =>    ''
                                                    ),

                                                    array(

                                                        'value'       =>    '4',

                                                        'label'       =>    esc_attr__( '4 Star', 'sdweddingdirectory' ),

                                                        'src'         =>    ''
                                                    ),

                                                    array(

                                                        'value'       =>    '5',

                                                        'label'       =>    esc_attr__( '5 Star', 'sdweddingdirectory' ),

                                                        'src'         =>    ''
                                                    )
                                                )
                    ),

                    array(

                        'id'            =>      esc_attr( 'content' ),

                        'label'         =>      esc_attr__( 'Content', 'sdweddingdirectory' ),

                        'type'          =>      esc_attr( 'textarea-simple' ),

                        'rows'          =>      absint( '3' ),

                        'std'           =>      esc_attr( 'At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores qui blanditiis praesentium.' )
                    ),
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
            $columns[ 'location' ]      =       esc_attr__( 'Location', 'sdweddingdirectory' );

            $columns[ 'content' ]       =       esc_attr__( 'Content', 'sdweddingdirectory' );

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
            if( $column == esc_attr( 'location' ) ){

                /**
                 *  Designation
                 *  -----------
                 */
                printf( '<span><strong>%1$s</strong></span>',

                        /**
                         *  1. Designation
                         *  --------------
                         */
                        get_post_meta( absint( $post_id ), sanitize_key( 'location' ), true )
                );
            }

            /**
             *  Column Data
             *  -----------
             */
            if( $column == esc_attr( 'content' ) ){

                /**
                 *  Designation
                 *  -----------
                 */
                printf( '<p>%1$s</p>',

                    /**
                     *  1. Designation
                     *  --------------
                     */
                    get_post_meta( absint( $post_id ), sanitize_key( 'content' ), true )
                );
            }
        }
	}

	/**
	 *  SDWeddingDirectory - Register Post
	 *  --------------------------
	 */
    SDWeddingDirectory_Register_Testimonials_Posts::get_instance();	
}