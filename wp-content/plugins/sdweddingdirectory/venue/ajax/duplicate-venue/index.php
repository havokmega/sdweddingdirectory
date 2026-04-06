<?php
/**
 *  SDWeddingDirectory - Duplicate Venue
 *  ------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Vendor_Duplicate_Venue_AJAX' ) && class_exists( 'SDWeddingDirectory_Vendor_Venue_AJAX' ) ){

    /**
     *  SDWeddingDirectory - Duplicate Venue
     *  ------------------------------
     */
    class SDWeddingDirectory_Vendor_Duplicate_Venue_AJAX extends SDWeddingDirectory_Vendor_Venue_AJAX{

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
        public function __construct() {

            /**
             *  1. Have AJAX action ?
             *  ---------------------
             */
            if(  wp_doing_ajax()  ){

                /**
                 *  Have AJAX action ?
                 *  ------------------
                 */
                if( isset( $_POST['action'] ) && $_POST['action'] !== '' ){

                    /**
                     *  Have AJAX action ?
                     *  ------------------
                     */
                    $action     =   esc_attr( trim( $_POST['action'] ) );

                    /**
                     *  1.  Bit of security
                     *  -------------------
                     */
                    $allowed_actions = array(

                        /**
                         *  Update Venue Data
                         *  -------------------
                         */
                        esc_attr( 'sdweddingdirectory_duplicate_venue_action' ),
                    );

                    /**
                     *  If have action then load this object members
                     *  --------------------------------------------
                     */
                    if( in_array( $action, $allowed_actions, true ) ) {

                        /**
                         *  Duplicate venue endpoint is authenticated-only
                         *  -----------------------------------------------
                         */
                        add_action( 'wp_ajax_' . $action, [ $this, $action ] );
                    }
                }
            }
        }

        /**
         *  Duplicate Post
         *  --------------
         */
        public static function sdweddingdirectory_duplicate_venue_action(){

            if( ! is_user_logged_in() ){

                parent:: security_issue_found();
            }

            /**
             *  Found Post
             *  ----------
             */
            $found_total_post   =   apply_filters( 'sdweddingdirectory/vendor/venue/found', [

                                        'post_status'       =>      [ 'publish', 'pending', 'trash', 'draft' ],

                                    ] );

            $vendor_plan_id     =   get_post_meta( parent:: post_id(), sanitize_key( 'pricing_plan_id' ), true );

            $plan_capacity      =   get_post_meta( $vendor_plan_id, sanitize_key( 'create_venue_capacity' ), true );

            /**
             *  Have Capacity ?
             *  ---------------
             */
            $enforce_capacity = apply_filters( 'sdsdweddingdirectoryectory/enable_venue_capacity_check', false );

            if( $enforce_capacity && $found_total_post >= $plan_capacity ){

                /**
                 *  Alert with Stop
                 *  ---------------
                 */
                die( json_encode( array(

                    'notice'    =>  absint( '0' ),

                    'message'   =>  esc_attr__( 'You have reached the maximum number of venues allowed. To add more venues, please consider upgrading your plan.', 'sdweddingdirectory-venue' )

                ) ) );
            }

            /**
             *  Global Variable
             *  ---------------
             */
            global  $current_user, $post, $wp_query, $wpdb;

            /**
             *  Check Security
             *  --------------
             */
            $_condition_1   = isset( $_POST['venue_id'] ) && $_POST['venue_id'] !== '';

            $_condition_2   = $_condition_1 ? self:: is_venue_owner( $_POST['venue_id'] ) : false;

            $_condition_3   = isset( $_POST[ 'security' ] ) && $_POST[ 'security' ] !== '';

            $_condition_4   = wp_verify_nonce( $_POST[ 'security' ], esc_attr( "duplicate_post-{$_REQUEST['venue_id']}" ) );

            /**
             *  Is Owner of Venue ?
             *  ---------------------
             */
            if( $_condition_1 && $_condition_2 && $_condition_3 && $_condition_4 ){

                /*
                 * get the original post id
                 */
                $post_id = absint( $_POST['venue_id'] );

                if( ! current_user_can( 'edit_post', $post_id ) ){

                    parent:: security_issue_found();
                }
                /*
                 * and all the original post data then
                 */
                $post = get_post( $post_id );
             
                /*
                 * if you don't want current user to be the new post author,
                 * then change next couple of lines to this: $new_post_author = $post->post_author;
                 */
                $current_user = wp_get_current_user();
                $new_post_author = $current_user->ID;
             
                /*
                 * if post data exists, create the post duplicate
                 */
                if (isset( $post ) && $post != null) {
             
                    /*
                     * new post data array
                     */
                    $args = array(
                        'comment_status' => $post->comment_status,
                        'ping_status'    => $post->ping_status,
                        'post_author'    => $new_post_author,
                        'post_content'   => $post->post_content,
                        'post_excerpt'   => $post->post_excerpt,
                        'post_name'      => $post->post_name,
                        'post_parent'    => $post->post_parent,
                        'post_password'  => $post->post_password,
                        'post_status'    => esc_attr( 'pending' ),
                        'post_title'     => sprintf( esc_attr__( '%1$s (copy)', 'sdweddingdirectory-venue' ), $post->post_title ),
                        'post_type'      => $post->post_type,
                        'to_ping'        => $post->to_ping,
                        'menu_order'     => $post->menu_order
                    );
             
                    /*
                     * insert the post by wp_insert_post() function
                     */
                    $new_post_id = wp_insert_post( $args );
             
                    /*
                     * get all current post terms ad set them to the new post draft
                     */
                    $taxonomies = get_object_taxonomies($post->post_type); // returns array of taxonomy names for post type, ex array("category", "post_tag");
                    foreach ($taxonomies as $taxonomy) {
                        $post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
                        wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
                    }
             
                    /*
                     * duplicate all post meta just in two SQL queries
                     */
                    $post_meta_infos = $wpdb->get_results(
                        $wpdb->prepare(
                            "SELECT meta_key, meta_value FROM {$wpdb->postmeta} WHERE post_id = %d",
                            $post_id
                        )
                    );

                    if ( count( $post_meta_infos ) !== 0 ) {
                        foreach ( $post_meta_infos as $meta_info ) {
                            $meta_key = $meta_info->meta_key;
                            if( $meta_key === '_wp_old_slug' ) {
                                continue;
                            }

                            $wpdb->insert(
                                $wpdb->postmeta,
                                [
                                    'post_id'    => absint( $new_post_id ),
                                    'meta_key'   => $meta_key,
                                    'meta_value' => $meta_info->meta_value,
                                ],
                                [ '%d', '%s', '%s' ]
                            );
                        }
                    }

                    /**
                     *  Removed Post Meta Is Exists
                     *  ---------------------------
                     */
                    update_post_meta( $new_post_id, sanitize_key( 'venue_badge' ), '' );

                    /**
                     *  Done
                     *  ----
                     */
                    die( json_encode( array(

                        'notice'    =>  absint( '1' ),

                        'message'   =>  esc_attr__('Duplicate Venue Post Successfully!','sdweddingdirectory-venue')

                    ) ) );
                }

            }else{

                /**
                 *  Have Error ?
                 *  ------------
                 */
                parent:: security_issue_found();
            }
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Vendor_Duplicate_Venue_AJAX:: get_instance();
}
