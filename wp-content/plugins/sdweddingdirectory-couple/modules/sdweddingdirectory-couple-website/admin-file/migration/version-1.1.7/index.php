<?php
/**
 *  SDWeddingDirectory - Migration Filter 
 *  -----------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Couple_Website_Migration_Wedding_Website_When_And_Where' ) && class_exists( 'SDWeddingDirectory_Couple_Website_Migration' ) && false ){

    /**
     *  SDWeddingDirectory - Migration Filter 
     *  -----------------------------
     */
    class SDWeddingDirectory_Couple_Website_Migration_Wedding_Website_When_And_Where extends SDWeddingDirectory_Couple_Website_Migration{

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
             *  Add New Changes
             *  ---------------
             */
            add_filter( 'sdweddingdirectory/migration', function( $args = [] ){

                return      array_merge( $args, [

                                'sdweddingdirectory_migration_couple_website_event_where_and_when'        =>  [

                                    'version'         =>    esc_attr( '1.1.7' ),

                                    'log'             =>    esc_attr__( 'Migration with Update', 'sdweddingdirectory-couple-website' ),

                                    'message'         =>    esc_attr__( 'Couple Tools Wedding Website When and Where Meta', 'sdweddingdirectory-couple-website' ),

                                    'name'            =>    esc_attr__( 'Wedding Website - When and Where Meta Migration', 'sdweddingdirectory-couple-website' ),
                                ],

                            ] );

            }, absint( '20' ) );


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
                    $allowed_actions    =   array(

                        /**
                         *  Migration
                         *  ---------
                         */
                        esc_attr( 'sdweddingdirectory_migration_couple_website_event_where_and_when' ),
                    );

                    /**
                     *  If have action then load this object members
                     *  --------------------------------------------
                     */
                    if( in_array( $action, $allowed_actions) ) {

                        /**
                         *  Check the AJAX action with login user
                         *  -------------------------------------
                         */
                        if( is_user_logged_in() ){

                            /**
                             *  1. If user already login then AJAX action
                             *  -----------------------------------------
                             */
                            add_action( 'wp_ajax_' . $action, [ $this, $action ] );

                        }else{

                            /**
                             *  2. If user not login that mean is front end AJAX action
                             *  -------------------------------------------------------
                             */
                            add_action( 'wp_ajax_nopriv_' . $action, [ $this, $action ] );
                        }
                    }
                }
            }
        }

        /**
         *  SDWeddingDirectory - Plugin
         *  -------------------
         */
        public static function sdweddingdirectory_migration_couple_website_event_where_and_when(){

            /**
             *  Option Name
             *  -----------
             */
            $_option_name   =   esc_attr( $_POST[ 'option' ] );

            /**
             *  Make sure this wizard first time
             *  --------------------------------
             */
            if( get_option( $_option_name ) == absint( '0' ) ){

                global $post, $wp_query;

                /**
                 *  Import : Demo Hire Vendor in Real Wedding
                 *  -----------------------------------------
                 */
                $query    = new WP_Query( array(

                    'post_type'         =>  [ 'website' ],

                    'post_status'       =>  esc_attr( 'publish' ),

                    'posts_per_page'    =>  -1,

                ) );

                /**
                 *  Have Post ?
                 *  -----------
                 */
                if ( $query->have_posts() ){

                    while ( $query->have_posts() ){  $query->the_post(); 

                        /**
                         *  Post ID
                         *  -------
                         */
                        $post_id            =   absint( get_the_ID() );

                        update_post_meta( $post_id, 'when_and_where_heading', get_post_meta( $post_id, 'couple_event_heading', true ) );

                        update_post_meta( $post_id, 'when_and_where_description', get_post_meta( $post_id, 'couple_event_description', true ) );

                        update_post_meta( $post_id, 'when_and_where', get_post_meta( $post_id, 'couple_event', true ) );
                    }
                }

                /**
                 *  Reset Query
                 *  -----------
                 */
                if ( isset( $query ) ) {

                    wp_reset_postdata();
                }

                /**
                 *  Social Migratrion Task DONE
                 *  ---------------------------
                 */
                update_option( $_option_name, absint( '1' ) );

                /**
                 *  Status
                 *  ------
                 */
                die( json_encode( [

                    'message'   =>  esc_attr__( 'Migration Done!', 'sdweddingdirectory-couple-website' ),

                ] ) );

            }else{

                /**
                 *  Status
                 *  ------
                 */
                die( json_encode( [

                    'message'   =>  esc_attr__( 'Error!', 'sdweddingdirectory-couple-website' )

                ] ) );
            }
        }
    }

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Couple_Website_Migration_Wedding_Website_When_And_Where:: get_instance();
}