<?php
/**
 *  SDWeddingDirectory - Import Dummy Content
 *  ---------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Import_Dummy_Content' ) && class_exists( 'SDWeddingDirectory_Admin_Settings' ) ) {

	/**
	 *  SDWeddingDirectory - Import Dummy Content
	 *  ---------------------------------
	 */
	class SDWeddingDirectory_Import_Dummy_Content extends SDWeddingDirectory_Admin_Settings {

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

	    	/**
	    	 *  Import Start
	    	 *  ------------
	    	 */
			add_action( 'import_start', [ $this, 'import_start_setup' ], absint( '10' ) );

	    	/**
	    	 *  Import End
	    	 *  ----------
	    	 */
			add_action( 'import_end', [ $this, 'import_end_setup' ], absint( '10' ) );
	    }

	    /**
	     *  When Import Start
	     *  -----------------
	     */
		public static function import_start_setup(){

		}

	    /**
	     *  When Import End
	     *  ---------------
	     */
		public static function import_end_setup(){

			/**
		     *  1. Update Blog and Post Pages
		     *  -----------------------------
		     */
		    update_option( esc_attr( 'show_on_front' ), esc_attr( 'page' ) );

		    /**
		     *  2. Front Page ID
		     *  ----------------
		     */
		    update_option( esc_attr( 'page_on_front' ), 

		    	/**
		    	 *  Get Post ID By Title
		    	 *  --------------------
		    	 */
		    	apply_filters( 'sdweddingdirectory/get-page-id', [ 	'post_name'	=>	esc_attr( 'Home' )   ] )
		    );

		    /**
		     *  3. Blog Post ID
		     *  ---------------
		     */
		    update_option( esc_attr( 'page_for_posts' ), 

		    	/**
		    	 *  Get Post ID By Title
		    	 *  --------------------
		    	 */
		    	apply_filters( 'sdweddingdirectory/get-page-id', [ 	'post_name'	=>	esc_attr( 'Inspiration' )   ] )
		    );

		    /**
		     *  4. Update Default Role
		     *  ----------------------
		     */
		    update_option( esc_attr( 'default_role' ), esc_attr( 'couple' ) );

		    /**
		     *  SDWeddingDirectory - Menu
		     *  -----------------
		     */
		    $_sdweddingdirectory_menus      =   apply_filters( 'sdweddingdirectory/nav-menus', [] );

		    /**
		     *  Have Menu ?
		     *  -----------
		     */
		    if( parent:: _is_array( $_sdweddingdirectory_menus ) ){

		        /**
		         *  Menu Collection
		         *  ---------------
		         */
		        $_menu_collection   =   [];

		        /**
		         *  All Menu
		         *  --------
		         */
		        foreach( $_sdweddingdirectory_menus as $key => $value ){

		        	/**
		        	 *  Object
		        	 *  ------
		        	 */
		        	$obj 	=	get_term_by( 'name', $value, 'nav_menu' );

		        	/**
		             *  Get Menu List
		             *  -------------
		             */
		        	if( ! empty( $obj ) ){

		        		$_menu_collection[ $key ]   =   $obj->term_id;
		        	}
		        }

		        /**
		         *  Have Menu Collection ?
		         *  ----------------------
		         */
		        if( parent:: _is_array( $_menu_collection ) ){

		            /**
		             *  Update Menu
		             *  -----------
		             */
		            set_theme_mod( 'nav_menu_locations', $_menu_collection );
		        }
		    }

		    /**
		     *  Update Permalink Structure as Post Name
		     *  ---------------------------------------
		     */
		    global $wp_rewrite;
		    
		    $wp_rewrite->set_permalink_structure( '/inspiration/%postname%/' );
		    update_option( esc_attr( 'category_base' ), esc_attr( 'inspiration' ) );

		    $wp_rewrite->flush_rules();
		}

	} // class end

	/**
	 *  SDWeddingDirectory - Import Dummy Content
	 *  ---------------------------------
	 */
    SDWeddingDirectory_Import_Dummy_Content::get_instance();	
}
