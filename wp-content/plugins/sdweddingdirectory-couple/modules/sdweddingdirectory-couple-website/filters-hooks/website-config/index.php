<?php
/**
 *  SDWeddingDirectory - Confiugration
 *  --------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Website_Configuration' ) && class_exists( 'SDWeddingDirectory_Couple_Website_Filters' ) ){

    /**
     *  SDWeddingDirectory - Confiugration
     *  --------------------------
     */
    class SDWeddingDirectory_Website_Configuration extends SDWeddingDirectory_Couple_Website_Filters {

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
            add_filter( 'sdweddingdirectory/user/configuration/meta', [$this, 'user_configuration'], absint( '40' ), absint( '1' ) );

            /**
             *  Which User ( Role Wise ) have access post type
             *  ----------------------------------------------
             */
            add_filter( 'sdweddingdirectory/couple/access-post-type', [$this, 'user_configuration'], absint( '40' ), absint( '1' ) );

            /**
             *  User Configuration Wizard Information
             *  -------------------------------------
             */
            add_filter( 'sdweddingdirectory/user-page/couple/status', [ $this, 'configuration_status' ], absint( '40' ), absint( '2' ) );

            /**
             *  Create Post
             *  -----------
             */
            add_action( 'sdweddingdirectory/register/couple/configuration', [ $this, 'create_post' ], absint( '40' ), absint( '1' ) );

            /**
             *  Post ID - Finder Filter
             *  -----------------------
             */
            add_filter( 'sdweddingdirectory/website/post-id', [ $this, 'get_post_id' ], absint( '40' ), absint( '1' ) );
        }

        /**
         *  SDWeddingDirectory - User Configuration Meta
         *  ------------------------------------
         */
        public static function user_configuration( $args = [] ){

            /**
             *  Return the couple post type
             *  ---------------------------
             */
            return      array_merge( $args, array( 'website' ) );
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

                                'post_id'   =>  self:: get_post_id( sanitize_email( $user_email )  ),

                                'notes'     =>  esc_attr__( 'Website', 'sdweddingdirectory-couple-website' ),

                                'article'   =>  esc_url_raw( 'https://wp-organic.gitbook.io/sdweddingdirectory-wordpress/faqs/user-configuration-process/couple-user#wedding-website-post-configuration' )
                            )
                        )
                    );
        }

        /**
         *  3. Couple Register After - Wedding Website Create Post
         *  ------------------------------------------------------
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

                    'post_type'     =>      esc_attr( 'website' )

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
                     *  Created New Post ID
                     *  -------------------
                     */
                    $post_id    =   wp_insert_post( [

                                        'post_author'       =>  absint( $user_id ),

                                        'post_name'         =>  esc_attr( $username ),

                                        'post_title'        =>  esc_attr( $username ),

                                        'post_status'       =>  esc_attr( 'publish' ),

                                        'post_type'         =>  esc_attr( $post_type ),

                                        'post_content'      =>  sprintf( esc_attr__( 'Welcome %1$s', 'sdweddingdirectory-couple-website' ),
                                                                    
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

                        'website_template_layout'   =>      esc_attr( 'website_template_layout_1' ),

                        'user_email'                =>      sanitize_email( $user_email ),

                        'wedding_date'              =>      esc_attr( $wedding_date )
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
                    do_action( 'sdweddingdirectory/user-register/website',  array_merge( $args, [ 'post_id' =>  $post_id ] )  );
                }
            }
        }

        /**
         *  [ Website ] Post ID - Finder
         *  ----------------------------
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
                return      parent:: find_post_id( esc_attr( 'website' ), sanitize_email( $email ) );
            }
        }
    }

    /**
     *  SDWeddingDirectory - Confiugration
     *  --------------------------
     */
    SDWeddingDirectory_Website_Configuration:: get_instance();
}