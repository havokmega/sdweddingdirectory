<?php
/**
 *    Template name: Request Missing Info
 *    -----------------------------------
 */

global $wp_query, $post;

/**
 *  1. Load SDWeddingDirectory - Header
 *  ---------------------------
 */
get_header();

    /**
     *  Is Couple User
     *  --------------
     */
    $_user          =   isset( $_GET['user'] ) && ! empty( $_GET['user'] );

    /**
     *  Is  User
     *  --------------
     */
    $_guest         =   isset( $_GET['guest'] ) && ! empty( $_GET['guest'] );

    /**
     *  Have Token ?
     *  ------------
     */
    if( $_user && $_guest ){

        /**
         *  Create Action
         *  -------------
         */
        do_action( 'sdweddingdirectory/guest-list/request-missing-info', [

            'couple_id'     =>      absint( $_GET['user'] ),

            'guest_id'      =>      absint( $_GET['guest'] )

        ] );
    }

    /**
     *  Error Create
     *  ------------
     */
    else{

        /**
         *  Create Action
         *  -------------
         */
        do_action( 'sdweddingdirectory/guest-list/request-missing-info/error' );
    }

/**
 *  2. Load SDWeddingDirectory - Footer
 *  ---------------------------
 */
get_footer();