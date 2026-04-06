<?php
/**
 *  SDWeddingDirectory - Vendor Dashboard Menu
 *  ----------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Vendor_Menu' ) && class_exists( 'SDWeddingDirectory_Config' ) ) {

	/**
	 *  SDWeddingDirectory - Vendor Dashboard Menu
	 *  ----------------------------------
	 */
	class SDWeddingDirectory_Vendor_Menu extends SDWeddingDirectory_Config{

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
        	 *  Vendor Menu Link Managment Filter
        	 *  ---------------------------------
        	 */
        	add_filter( 'sdweddingdirectory/vendor-menu/page-link', [ $this, 'vendor_menu_link' ], absint( '10' ), absint( '1' ) );

        	/**
        	 *  Logout Menu
        	 *  -----------
        	 */
        	add_filter( 'sdweddingdirectory/vendor/dashboard/menu', [ $this, 'menu' ], absint( '200' ), absint( '1' ) );

            /**
             *  Welcome String
             *  --------------
             */
            add_filter( 'sdweddingdirectory/vendor/sidebar-menu/username', [ $this, 'welcome_username' ], absint( '10' ), absint( '1' ) );
        }

        /**
         *  Vendor Menu link
         *  ----------------
         */
        public static function vendor_menu_link( $page_name = '' ){

    		/**
    		 *  Make sure filter have page name ?
    		 *  ---------------------------------
    		 */
    		if( ! empty( $page_name ) ){

    			/**
    			 *  Dashboard Page Name
    			 *  -------------------
    			 */
    			return 	esc_url_raw(

						    add_query_arg( 

						        array(

						            'dashboard' => esc_attr( $page_name )
						        ), 

						        SDWeddingDirectory_Template:: vendor_dashboard_template() 
						    )
						);
    		}
        }

        /**
         *  Welcome Username Filter
         *  -----------------------
         */
        public static function welcome_username( $args = [] ){

            /**
             *  Extract Args
             *  ------------
             */
            extract( wp_parse_args( $args, [

                'post_id'       =>  	absint( '0' ),

                'welcome'		=>		esc_attr__( 'Welcome', 'sdweddingdirectory' )

            ] ) );

            /**
             *  Make sure post id not empty!
             *  ----------------------------
             */
            if( empty( $post_id ) ){

                /**
                 *  Translation Ready String
                 *  ------------------------
                 */
                return      $welcome;
            }

            /**
             *  Translation Ready String
             *  ------------------------
             */
            return      $welcome . ' ' . esc_attr( ucfirst( get_the_title( $post_id ) ) );
        }

        /**
         *  Vendor Logout Menu
         *  ------------------
         */
		public static function menu( $args = [] ){

            /**
             *  Display Logout Menu
             *  -------------------
             */
            return 	array_merge( $args, [

	                    'logout'     		=> 	array(

					      	'menu_show'   	=> 	apply_filters( 'sdweddingdirectory/vendor-menu/logout/show', true ),

					      	'menu_name' 	=> 	apply_filters( 'sdweddingdirectory/vendor-menu/logout/name', esc_attr__( 'Logout', 'sdweddingdirectory' ) ),

					      	'menu_icon' 	=> 	apply_filters( 'sdweddingdirectory/vendor-menu/logout/icon', esc_attr( 'sdweddingdirectory-logout' ) ),

					      	'menu_active' 	=> 	null,

					      	'menu_link' 	=> 	esc_url( wp_logout_url( home_url( '/' ) ) ),
	                    )

            		] );
		}

		/**
		 *  Vendor Menu
		 *  -----------
		 */
		public static function vendor_dashboard_menu( $args ){

			$menu 			=  '';

			$menu_args 		=  apply_filters( 'sdweddingdirectory/vendor/dashboard/menu', [] );

			if( $args == 'side' && parent:: _is_array( $menu_args ) ){

				foreach( $menu_args as $key => $value){

					/**
					 *  Extract
					 *  -------
					 */
					extract( $value );

					/**
					 *  Enable Menu ?
					 *  -------------
					 */
					if( $menu_show == true ){

					    /**
				     *  Badge Support
				     *  -------------
				     */
				    $badge_html = isset( $value['menu_badge'] ) && absint( $value['menu_badge'] ) > 0
				        ? sprintf( ' <span class="sdwd-menu-badge">%d</span>', absint( $value['menu_badge'] ) )
				        : '';

				    $menu .=

					    sprintf( '<li %6$s class="%4$s %5$s"><a href="%1$s"><i class="%2$s"></i> %3$s%7$s</a></li>',

					    	/**
					    	 *  1. Menu Link
					    	 *  ------------
					    	 */
							esc_url( $menu_link ),

							/**
							 *  2. Menu Icon
							 *  ------------
							 */
						   	$menu_icon,

						   	/**
						   	 *  3. Menu Name
						   	 *  ------------
						   	 */
						   	esc_attr( $menu_name ),

						   	/**
						   	 *  4. Is Active Menu
						   	 *  -----------------
						   	 */
						    esc_attr( $value['menu_active'] ),

						    /**
						     *  5. Have Class ?
						     *  ---------------
						     */
						    esc_attr( $menu_class ),

						    /**
						     *  6. Have ID ?
						     *  ------------
						     */
						    ! empty( $menu_id )	  ?	 sprintf( 'id="%1$s"', esc_attr( $menu_id ) )  :  '',

						    /**
						     *  7. Badge
						     *  --------
						     */
						    $badge_html
						);
					}
				}

				return 	sprintf('

		            <aside class="offcanvas-collapse">

		            	<div class="row text-center">

		            		<div class="mx-auto my-3 col-6">%1$s</div>

		            		<div class="col-12"><h3 class="text-default fw-bold py-2">%2$s</h3></div>

		            	</div>

		                <div class="sidebar-nav">

		                    <ul class="list-unstyled">%3$s</ul>

		                </div>

		            </aside>',

					/**
					 *  1. load couple profile picture
					 *  ------------------------------
					 */
					apply_filters( 'sdweddingdirectory/field/single-media', [

	                    'database_key'      	=>      esc_attr( 'user_image' ),

	                    'image_size'        	=>      esc_attr( 'thumbnail' ),

	                    'is_ajax'           	=>      true,

	                    'image_class'			=>		'border rounded',

	                    'icon_layout'			=>		absint( '3' ),

	                    'default_img'       	=>      esc_url( parent:: placeholder( 'vendor-profile' ) ),

	                    'extra_link_update'		=>		[ 'img.vendor-dropdown-image'  =>	'src' ],
	                ] ),

					/**
					 *  2. Vendor username
					 *  ------------------
					 */
					apply_filters( 'sdweddingdirectory/vendor/sidebar-menu/username', [

						'post_id'		=>	parent:: post_id()

					] ),

					/**
					 *  3. Couple Sidebar Menu
					 *  ----------------------
					 */
					$menu
		        );

			}else{

				if( parent:: _is_array( $menu_args ) ){

					foreach( $menu_args as $key => $value){

						if( $value['menu_show'] === true ){

						    $menu .=

						    sprintf( '<li><a class="dropdown-item" href="%1$s">%2$s</a></li>',

						    	// 1
						       	esc_url( $value['menu_link'] ),

						       	// 2
						        esc_html( $value['menu_name'] )
						    );
						}
					}
				}

				return 	sprintf( '

					<a class="nav-link" href="#" id="vendor-dropdown-menu" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
						
						<img src="%1$s" class="vendor-dropdown-image" alt="vendor dashboard menu %3$s" />

					    <i class="fa fa-angle-down"></i>
					</a>

					<ul class="dropdown-menu" aria-labelledby="vendor-dropdown-menu">%2$s</ul>',

					/**
					 *  1. Couple Profile Pic
					 *  ---------------------
					 */
					parent:: profile_picture( [

						'post_id'		=>		absint( parent:: post_id()  )

					] ),

					/**
					 *  2. Couple Menu list
					 *  -------------------
					 */
					$menu,

					/**
					 *  3. Image alt text Site Name
					 *  ---------------------------
					 */
					esc_attr( get_option( 'blogname' ) )
				);
			}
		}
	}

	/**
	 *  SDWeddingDirectory - Vendor Dashboard Menu
	 *  ----------------------------------
	 */
	SDWeddingDirectory_Vendor_Menu::get_instance();
}