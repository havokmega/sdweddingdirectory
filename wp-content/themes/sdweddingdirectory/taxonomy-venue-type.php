<?php
/**
 *  Venue Category Taxonomy (Venue Types)
 *  ----------------------------------------
 *  Renders using the same venues search page layout
 *  with sidebar filters, search banner, and venue cards.
 */

$queried_term = get_queried_object();

if ( $queried_term instanceof WP_Term ) {
    $_GET['cat_id'] = absint( $queried_term->term_id );
}

/**
 *  Render via the venues search pipeline
 *  (calls get_header/get_footer internally)
 */
do_action( 'sdweddingdirectory/find-venue' );
