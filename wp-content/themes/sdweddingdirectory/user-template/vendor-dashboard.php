<?php
/**
 *    Template name: Vendor Dashbaord
 *    -------------------------------
 */

global $wp_query, $post;

/**
 *  Is User Login ?  + Is Vendor Too
 *  --------------------------------
 */
if( is_user_logged_in() && class_exists( 'SDWeddingDirectory_Config' ) ){

	/**
	 *  Is Vendor ?
	 *  -----------
	 */
	if( SDWeddingDirectory_Config:: is_vendor() ){
  
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