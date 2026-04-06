<?php
/**
 *   Template name: Wedding Website
 *   ------------------------------
 */
global $wp_query, $post;

/**
 *  Is User Login ? + Is Couple too
 *  -------------------------------
 */
if( is_user_logged_in() && class_exists( 'SDWeddingDirectory_Config' ) ){

    /**
     *  1. Load SDWeddingDirectory - Header
     *  ---------------------------
     */
    get_header();

    esc_attr_e( 'couple wedding website', 'sdweddingdirectory' );

    /**
     *  2. Load SDWeddingDirectory - Footer
     *  ---------------------------
     */
    get_footer();

}else{

    /**
     *  Redirection on Home Page
     *  ------------------------
     */
    die( wp_redirect( home_url() ) );
}