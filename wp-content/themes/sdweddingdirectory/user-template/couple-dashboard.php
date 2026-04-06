<?php
/**
 *   Template name: Couple Dashbaord
 *   -------------------------------
 */
global $wp_query, $post;

/**
 *  Is User Login ? + Is Couple too
 *  -------------------------------
 */
if( is_user_logged_in() && class_exists( 'SDWeddingDirectory_Config' ) ){

	/**
	 *  Is Couple ?
	 *  -----------
	 */
	if( SDWeddingDirectory_Config:: is_couple() ){

		/**
		 *  1. Load SDWeddingDirectory - Header
		 *  ---------------------------
		 */
        get_header();

	        /**
	         *   Dashboard action
	         *   ----------------
	         */
	        do_action( 'sdweddingdirectory/dashboard' );

	    /**
	     *  2. Load SDWeddingDirectory - Footer
	     *  ---------------------------
	     */
       	get_footer();
  	}

}else{

	/**
	 *  Redirection on Home Page
	 *  ------------------------
	 */
    die( wp_redirect( home_url() ) );
}