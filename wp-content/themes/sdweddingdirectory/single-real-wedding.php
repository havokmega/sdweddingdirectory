<?php
/**
 *  1. [ Real Wedding ] - Load Theme Header
 *  ---------------------------------------
 */
get_header();

    /**
     *  2. Load Vendor Singular Page
     *  ----------------------------
     */
    do_action( 'sdweddingdirectory/real-wedding/detail-page' );

/**
 *  3. Load Theme Footer
 *  --------------------
 */
get_footer();