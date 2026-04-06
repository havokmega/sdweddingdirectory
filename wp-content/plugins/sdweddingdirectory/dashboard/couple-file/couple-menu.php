<?php
/**
 *  SDWeddingDirectory - Couple Menu
 *  ------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Couple_Menu' ) && class_exists( 'SDWeddingDirectory_Config' ) ) {

	/**
	 *  SDWeddingDirectory - Couple Menu
	 *  ------------------------
	 */
	class SDWeddingDirectory_Couple_Menu extends SDWeddingDirectory_Config{

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
        	 *  Couple Menu Link Managment Filter
        	 *  ---------------------------------
        	 */
        	add_filter( 'sdweddingdirectory/couple-menu/page-link', [ $this, 'couple_menu_permalink' ], absint( '10' ), absint( '1' ) );

            /**
             *  Display Logout Tab
             *  ------------------
             */
            add_filter( 'sdweddingdirectory/couple/dashboard/menu', [ $this, 'couple_menu_logout' ], absint( '200' ), absint( '1' ) );

            /**
             *  Welcome String
             *  --------------
             */
            add_filter( 'sdweddingdirectory/couple/sidebar-menu/username', [ $this, 'welcome_username' ], absint( '10' ), absint( '1' ) );
        }

        /**
         *  Display Couple Tools [ Logout ] Tab
         *  -----------------------------------
         */
        public static function couple_menu_permalink( $page_name = '' ){

    		/**
    		 *  Make sure filter have page name ?
    		 *  ---------------------------------
    		 */
    		if( ! empty( $page_name ) ){

    			/**
    			 *  Dashboard Page Name
    			 *  -------------------
    			 */
    			return 		esc_url_raw(

							    add_query_arg(

							    	/**
							    	 *  Query Args
							    	 *  ----------
							    	 */
							        [
							        	'dashboard' => esc_attr( $page_name )
							        ],

							        /**
							         *  Permalink
							         *  ---------
							         */
							        SDWeddingDirectory_Template:: couple_dashboard_template() 
							    )
							);
    		}
        }

        /**
         *  Display Couple Tools [ Logout ] Tab
         *  -----------------------------------
         */
        public static function couple_menu_logout( $args = [] ){

    		/**
    		 *  Couple Menu [ Logout ]
    		 *  ----------------------
    		 */
            return 		array_merge( $args, array(

	                        'logout'    		=> 	array(

						      	'menu_show'   	=> 	true,

						      	'menu_name' 	=> 	esc_html__( 'Logout', 'sdweddingdirectory' ),

						      	'menu_icon' 	=> 	esc_attr( 'sdweddingdirectory-logout' ),

						      	'menu_active' 	=> 	null,

						      	'menu_link' 	=> 	esc_url( wp_logout_url( home_url( '/' ) ) ),
	                        ),
	                )  );
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
         *  Couple Menu
         *  -----------
         */
		public static function couple_dashboard_menu( $args = '' ){

			$menu 			= 	'';

			$menu_args 		= 	apply_filters( 'sdweddingdirectory/couple/dashboard/menu', [] );

			/**
			 *  Side Menu ?
			 *  -----------
			 */
			if( $args == esc_attr( 'side' ) && parent:: _is_array( $menu_args ) ){

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

					    $menu .= 

					    sprintf( '<li %6$s class="%4$s %5$s"><a href="%1$s"><i class="%2$s"></i> %3$s</a></li>',

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
						    ! empty( $menu_id )	  ?	 sprintf( 'id="%1$s"', esc_attr( $menu_id ) )  :  ''
						);
					}
				}

				return 	

				sprintf(   '<aside class="offcanvas-collapse">

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

			                    'default_img'       	=>      esc_url( parent:: placeholder( 'my-profile' ) ),

			                    'extra_link_update'		=>		[ 'img.couple-dropdown-image'  =>	'src' ]

			                ] ),

							/**
							 *  2. couple username
							 *  ------------------
							 */
							apply_filters( 'sdweddingdirectory/couple/sidebar-menu/username', [ 	

								'post_id'		=>		absint( parent:: post_id()  )

							] ),

							/**
							 *  3. Couple Sidebar Menu
							 *  ----------------------
							 */
							$menu,

							/**
							 *  4. Image ALT
							 *  ------------
							 */
							esc_attr( get_bloginfo( 'name' ) )
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

					<a class="nav-link" href="#" id="couple-dropdown-menu" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">

						<img src="%1$s" class="couple-dropdown-image" alt="couple dashboard menu %3$s" />

					    <i class="fa fa-angle-down"></i>

					</a>

					<ul class="dropdown-menu" aria-labelledby="couple-dropdown-menu">%2$s</ul>',

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
					get_option( 'blogname' )

				);

			} // else
		}
	}

	/**
	 *  Couple Dashboard Menu
	 *  ---------------------
	 */
	SDWeddingDirectory_Couple_Menu::get_instance();
}