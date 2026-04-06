<?php
/**
 *    Template name: Compatibility Checker
 *    ------------------------------------
 */

global $wp_query, $post;

/**
 *  1. Load SDWeddingDirectory - Header
 *  ---------------------------
 */
get_header();

    /**
     *   Dashboard action
     *   ----------------
     */
    do_action( 'sdweddingdirectory/compatibility-checker' );

/**
 *  2. Load SDWeddingDirectory - Footer
 *  ---------------------------
 */
get_footer();