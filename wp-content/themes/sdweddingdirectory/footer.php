<?php
/**
 *  ----------------------------------
 *  SDWeddingDirectory - Theme Footer Template
 *  ----------------------------------
 *  @link - https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *  ---------------------------------------------------------------------------------------
 *  @package sdweddingdirectory
 *  -------------------
 */
?></main><!-- #content area end --><?php

    /**
     *  Load Footer ?
     *  -------------
     */
    if( ! is_singular( 'website' ) ){

        /**
         *  SDWeddingDirectory - Footer Markup
         *  --------------------------
         */
        do_action( 'sdweddingdirectory/footer' );
    }

?></div><!-- #page --><?php 

    /**
     *  -----------
     *  Load Footer
     *  -----------
     *  @link - https://developer.wordpress.org/reference/functions/wp_footer/
     *  ----------------------------------------------------------------------
     */

    wp_footer(); 

?></body></html>