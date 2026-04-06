<?php
/**
 *  SDWeddingDirectory - Admin Dashboard Widget
 *  -----------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Dashboard_Widget' ) && class_exists( 'SDWeddingDirectory_Admin_Settings' ) && FALSE ) {

	/**
	 *  SDWeddingDirectory - Admin Dashboard Widget
	 *  -----------------------------------
	 */
	class SDWeddingDirectory_Dashboard_Widget extends SDWeddingDirectory_Admin_Settings {

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
	    	 *  1. Register : Couple Post
	    	 *  -------------------------
	    	 */
	    	add_action( 'wp_dashboard_setup', function(){

	    		/**
	    		 *  Load Widget
	    		 *  -----------
	    		 */
			    wp_add_dashboard_widget(

			    	/**
			    	 *  Widget slug
			    	 *  -----------
			    	 */
			        esc_attr( 'sdweddingdirectory_overview_widget' ),

			        /**
			         *  Widget Title
			         *  ------------
			         */
			        esc_html__( 'SDWeddingDirectory Overview', 'sdweddingdirectory' ),

			        /**
			         *  Call back function
			         *  ------------------
			         */
			        [ $this, 'sdweddingdirectory_dashboard_widget' ]
			    );

	    	} );
	    }

	    /**
	     *  Widget Content
	     *  --------------
	     */
	    public static function sdweddingdirectory_dashboard_widget(){

	    	printf( '<div class="documentation"><a target="_blank" href="%1$s">%2$s</a></div>', 

	    		/**
	    		 *  1. Documentation
	    		 *  ----------------
	    		 */
	    		esc_url( 'https://wp-organic.gitbook.io/sdweddingdirectory-wordpress/' ),

	    		/**
	    		 *  2. Translation Ready String
	    		 *  ---------------------------
	    		 */
	    		esc_attr__( 'Documentation', 'sdweddingdirectory' )
	    	);

	    	printf( '<div class="youtube-video"><a target="_blank" href="%1$s">%2$s</a></div>', 

	    		/**
	    		 *  1. Documentation
	    		 *  ----------------
	    		 */
	    		esc_url( 'https://www.youtube.com/watch?v=KZPwRJ-bV5I&t=6s&ab_channel=SDWeddingDirectoryPro' ),

	    		/**
	    		 *  2. Translation Ready String
	    		 *  ---------------------------
	    		 */
	    		esc_attr__( 'Installation Process', 'sdweddingdirectory' )
	    	);
	    }

	} // class end

	/**
	 *  SDWeddingDirectory - Admin Dashboard Widget
	 *  -----------------------------------
	 */
    SDWeddingDirectory_Dashboard_Widget::get_instance();	
}