<?php
/**
 *  SDWeddingDirectory - Removed User
 *  -------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Remove_Author' ) && class_exists( 'SDWeddingDirectory_Config' ) ){

    /**
     *  SDWeddingDirectory - Removed User
     *  -------------------------
     */
    class SDWeddingDirectory_Remove_Author extends SDWeddingDirectory_Config{

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

        /**
         *  Construct
         *  ---------
         */
        public function __construct(){

            /**
             *  1. When Process is Start for Delete User After Removed Related Post IDs
             *  -----------------------------------------------------------------------
             *  @credit - https://developer.wordpress.org/reference/hooks/delete_user/
             *  ----------------------------------------------------------------------
             */
            add_action( 'delete_user', [ $this, 'sdweddingdirectory_remove_user_access' ], absint( '100' ), absint( '3' ) );
        }

        /**
         *  1. When Process is Start for Delete User After Removed Related Post IDs
         *  -----------------------------------------------------------------------
         */
        public static function sdweddingdirectory_remove_user_access( $id, $reassign, $user ){

            global $wpdb, $post, $wp_query;

            $_access_post_ids   =   apply_filters( 'sdweddingdirectory/author_id/post_id', absint( $id ) );

            if( parent:: _is_array( $_access_post_ids ) ){

                foreach( $_access_post_ids as $key => $value ){

                    /**
                     *  Removed Post ID
                     *  ---------------
                     */
                    wp_delete_post( absint( $value ), true );
                }
            }

            /**
             *  Sending Email
             *  -------------
             *  @credit - https://developer.wordpress.org/reference/hooks/delete_user/#user-contributed-notes
             *  ---------------------------------------------------------------------------------------------
             */
            $user_data  =   get_userdata( $id );

            $headers    =   'From: ' . get_bloginfo( 'name' ) . ' ' . "\r\n";

            /**
             *  Email For User for Deleted Account
             *  ----------------------------------
             */
            wp_mail(

                /**
                 *  1. User Email
                 *  -------------
                 */
                sanitize_email( $user_data->user_email ),

                /**
                 *  2. Translation Ready String
                 *  ---------------------------
                 */
                esc_attr__( 'We are deleting your account', 'sdweddingdirectory' ),

                /**
                 *  3. Translation Ready String
                 *  ---------------------------
                 */
                sprintf( esc_attr__( 'Your account at %1$s will be deleted.', 'sdweddingdirectory' ),

                    /**
                     *  1. Blog Info
                     *  ------------
                     */
                    esc_attr( get_bloginfo( 'name' ) )
                ),

                /**
                 *  4. Email Header
                 *  ---------------
                 */
                $headers
            );
        }
       
    } // end class

    /**
     *  SDWeddingDirectory - Removed User
     *  -------------------------
     */
    SDWeddingDirectory_Remove_Author::get_instance();
}