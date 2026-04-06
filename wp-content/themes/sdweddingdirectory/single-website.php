<?php
/**
 *  Website Singular Page
 *  ---------------------
 */

/**
 *  1. Load Theme Header
 *  --------------------
 */
get_header();

    /**
     *  2. Load Vendor Singular Page
     *  ----------------------------
     */
    do_action( 'sdweddingdirectory/website/detail-page' );

/**
 *  3. Load Theme Footer
 *  --------------------
 */
get_footer();