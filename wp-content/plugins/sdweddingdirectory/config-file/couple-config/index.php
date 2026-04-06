<?php
/**
 *  SDWeddingDirectory - Confiugration
 *  --------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Couple_Configuration' ) && class_exists( 'SDWeddingDirectory_Config' ) ){

    /**
     *  SDWeddingDirectory - Confiugration
     *  --------------------------
     */
    class SDWeddingDirectory_Couple_Configuration extends SDWeddingDirectory_Config {

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
             *  SDWeddingDirectory - User Configuration Meta
             *  ------------------------------------
             */
            add_filter( 'sdweddingdirectory/user/configuration/meta', [$this, 'user_configuration'], absint( '20' ), absint( '1' ) );

            /**
             *  Which User ( Role Wise ) have access post type
             *  ----------------------------------------------
             */
            add_filter( 'sdweddingdirectory/couple/access-post-type', [$this, 'user_configuration'], absint( '20' ), absint( '1' ) );

            /**
             *  User Configuration Wizard Information
             *  -------------------------------------
             */
            add_filter( 'sdweddingdirectory/user-page/couple/status', [ $this, 'configuration_status' ], absint( '20' ), absint( '2' ) );

            /**
             *  Create Post
             *  -----------
             */
            add_action( 'sdweddingdirectory/register/couple/configuration', [ $this, 'create_post' ], absint( '20' ), absint( '1' ) );

            /**
             *  Post ID - Finder Filter
             *  -----------------------
             */
            add_filter( 'sdweddingdirectory/couple/post-id', [ $this, 'get_post_id' ], absint( '20' ), absint( '1' ) );

            /**
             *  Repair missing couple configuration posts for logged-in couple users.
             */
            add_action( 'init', [ $this, 'maybe_repair_logged_in_couple_configuration' ], absint( '200' ) );
        }

        /**
         *  Core configuration meta relationship
         *  ------------------------------------
         */
        public static function user_configuration( $args = [] ){

            /**
             *  Return the post type
             *  --------------------
             */
            return      array_merge( $args, array( 'couple' ) );
        }

        /**
         *  User Configuration Wizard Information
         *  -------------------------------------
         */
        public static function configuration_status( $args = [], $user_email = '' ){

            /**
             *  Merge Configuration
             *  -------------------
             */
            return  array_merge( 

                        /**
                         *  1. Have Data ?
                         *  --------------
                         */
                        $args, 

                        /**
                         *  2. Have Real Wedding Configuration ?
                         *  ------------------------------------
                         */
                        array(

                            array(

                                'post_id'   =>  self:: get_post_id( sanitize_email( $user_email ) ),

                                'notes'     =>  esc_attr__( 'Couple', 'sdweddingdirectory' ),

                                'article'   =>  esc_url_raw( 'https://wp-organic.gitbook.io/sdweddingdirectory-wordpress/faqs/user-configuration-process/couple-user#couple-post-configuration' )
                            )
                        )
                    );
        }

        /**
         *  Create Post
         *  -----------
         */
        public static function create_post( $args = [] ){

            /**
             *  Make sure have data ?
             *  ---------------------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract Args
                 *  ------------
                 */
                extract( wp_parse_args( $args, [

                    'post_type'     =>      esc_attr( 'couple' )

                ] ) );

                /**
                 *  List
                 *  ----
                 */
                $found_post         =       apply_filters( 'sdweddingdirectory/post/data', [

                                                'post_type'     =>      esc_attr( $post_type ),

                                                'meta_query'    =>      array(

                                                    'key'           =>      esc_attr( 'user_email' ),

                                                    'compare'       =>      esc_attr( '=' ),

                                                    'value'         =>      sanitize_email( $user_email )
                                                )

                                            ] );

                /**
                 *  Make sure post exists ?
                 *  -----------------------
                 */
                if( ! parent:: _is_array( $found_post ) ){

                    /**
                     *  Couple Created New Post ID
                     *  --------------------------
                     */
                    $post_id    =   wp_insert_post( [

                                            'post_author'       =>  absint( $user_id ),

                                            'post_name'         =>  esc_attr( $username ),

                                            'post_title'        =>  esc_attr( $username ),

                                            'post_status'       =>  esc_html( 'publish' ),

                                            'post_type'         =>  esc_html( $post_type ),

                                            'post_content'      =>  sprintf( esc_attr__( 'Welcome %1$s', 'sdweddingdirectory' ),
                                                                        
                                                                       /**
                                                                        *  Username
                                                                        *  --------
                                                                        */
                                                                        esc_attr( $username )
                                                                    ),
                                        ] );

                    /**
                     *  Couple Information updated in Post ID
                     *  -------------------------------------
                     */
                    $post_meta  =   [

                        'user_id'                   =>  absint( $user_id ),

                        'first_name'                =>  esc_attr( $first_name ),

                        'last_name'                 =>  esc_attr( $last_name ),

                        'user_email'                =>  sanitize_email( $user_email ),

                        'wedding_date'              =>  esc_attr( $wedding_date ),

                        'register_user_is'          =>  esc_attr( $register_user_is ),
                    ];

                    /**
                     *  Update Meta Key
                     *  ---------------
                     */
                    if( parent:: _is_array( $post_meta ) ){

                        foreach( $post_meta as $key => $value ){

                            update_post_meta( $post_id,  sanitize_key( $key ), $value );
                        }
                    }

                    /**
                     *  New Couple Register As SDWeddingDirectory Configuration
                     *  -----------------------------------------------
                     */
                    do_action( 'sdweddingdirectory/user-register/couple',  array_merge( $args, [ 'post_id' =>  $post_id ] )  );
                }
            }
        }

        /**
         *  If any required couple configuration post is missing, rebuild it using the existing create flow.
         */
        public static function maybe_repair_logged_in_couple_configuration(){

            if( is_admin() || ! is_user_logged_in() || ! parent:: is_couple() ){
                return;
            }

            $user = wp_get_current_user();

            if( empty( $user ) || empty( $user->ID ) || empty( $user->user_email ) ){
                return;
            }

            $email = sanitize_email( $user->user_email );

            if( empty( $email ) ){
                return;
            }

            $_required_post_types = [ 'couple', 'website', 'real-wedding' ];

            foreach( $_required_post_types as $post_type ){

                if( self:: configuration_post_count( $post_type, $email ) == absint( '0' ) ){

                    self:: repair_couple_configuration( $user );

                    break;
                }
            }
        }

        /**
         *  Return post count for a post type + couple email relationship.
         */
        public static function configuration_post_count( $post_type = '', $email = '' ){

            if( empty( $post_type ) || empty( $email ) ){
                return absint( '0' );
            }

            $query = new WP_Query([

                'post_type'              =>  esc_attr( $post_type ),

                'post_status'            =>  [ 'publish', 'draft', 'pending', 'private' ],

                'posts_per_page'         =>  absint( '2' ),

                'fields'                 =>  'ids',

                'no_found_rows'          =>  true,

                'update_post_meta_cache' =>  false,

                'update_post_term_cache' =>  false,

                'meta_query'             =>  [

                    [
                        'key'       =>  esc_attr( 'user_email' ),

                        'compare'   =>  esc_attr( '=' ),

                        'value'     =>  sanitize_email( $email )
                    ]
                ]

            ]);

            $_count = absint( $query->post_count );

            if( isset( $query ) ){

                wp_reset_postdata();
            }

            return $_count;
        }

        /**
         *  Re-run couple configuration creation for an existing couple user.
         */
        public static function repair_couple_configuration( $user = '' ){

            if( empty( $user ) || empty( $user->ID ) || empty( $user->user_email ) ){
                return;
            }

            $user_id = absint( $user->ID );

            $user_login = ! empty( $user->user_login )

                        ? sanitize_user( $user->user_login )

                        : sanitize_user( current( explode( '@', sanitize_email( $user->user_email ) ) ) );

            $wedding_date = esc_attr( get_user_meta( $user_id, sanitize_key( 'wedding_date' ), true ) );

            /**
             *  If user meta does not have wedding date, try existing couple post meta.
             */
            if( empty( $wedding_date ) ){

                $couple_post = get_posts([

                    'post_type'              =>  esc_attr( 'couple' ),

                    'post_status'            =>  [ 'publish', 'draft', 'pending', 'private' ],

                    'posts_per_page'         =>  absint( '1' ),

                    'fields'                 =>  'ids',

                    'no_found_rows'          =>  true,

                    'update_post_meta_cache' =>  false,

                    'update_post_term_cache' =>  false,

                    'meta_query'             =>  [
                        [
                            'key'       =>  esc_attr( 'user_email' ),

                            'compare'   =>  esc_attr( '=' ),

                            'value'     =>  sanitize_email( $user->user_email )
                        ]
                    ]
                ]);

                if( parent:: _is_array( $couple_post ) ){

                    $wedding_date = esc_attr(
                                        get_post_meta(
                                            absint( $couple_post[0] ),
                                            sanitize_key( 'wedding_date' ),
                                            true
                                        )
                                    );
                }
            }

            do_action( 'sdweddingdirectory/register/couple/configuration', [

                'user_id'           =>  $user_id,

                'username'          =>  esc_attr( $user_login ),

                'first_name'        =>  esc_attr( get_user_meta( $user_id, sanitize_key( 'first_name' ), true ) ),

                'last_name'         =>  esc_attr( get_user_meta( $user_id, sanitize_key( 'last_name' ), true ) ),

                'user_email'        =>  sanitize_email( $user->user_email ),

                'wedding_date'      =>  esc_attr( $wedding_date ),

                'register_user_is'  =>  esc_attr( 'i_am_couple' )
            ] );
        }

        /**
         *  [ Couple ] Post ID - Finder
         *  ---------------------------
         */
        public static function get_post_id( $email = '' ){

            /**
             *  Have Email ?
             *  ------------
             */
            if( ! empty( $email ) ){

                /**
                 *  Return : Post ID with find meta value
                 *  -------------------------------------
                 */
                return      parent:: find_post_id( esc_attr( 'couple' ), sanitize_email( $email ) );
            }
        }
    }

    /**
     *  SDWeddingDirectory - Confiugration
     *  --------------------------
     */
    SDWeddingDirectory_Couple_Configuration:: get_instance();
}
