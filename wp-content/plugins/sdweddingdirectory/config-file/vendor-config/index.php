<?php
/**
 *  SDWeddingDirectory - Confiugration
 *  --------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Vendor_Configuration' ) && class_exists( 'SDWeddingDirectory_Config' ) ){

    /**
     *  SDWeddingDirectory - Confiugration
     *  --------------------------
     */
    class SDWeddingDirectory_Vendor_Configuration extends SDWeddingDirectory_Config {

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
            add_filter( 'sdweddingdirectory/user/configuration/meta', [ $this, 'user_configuration' ], absint( '10' ), absint( '1' ) );

            /**
             *  Which User ( Role Wise ) have access post type
             *  ----------------------------------------------
             */
            add_filter( 'sdweddingdirectory/vendor/access-post-type', [$this, 'user_configuration'], absint( '10' ), absint( '1' ) );

            /**
             *  User Configuration Wizard Info
             *  ------------------------------
             */
            add_filter( 'sdweddingdirectory/user-page/vendor/status', [ $this, 'configuration_status' ], absint( '10' ), absint( '2' ) );

            /**
             *  Create Post
             *  -----------
             */
            add_action( 'sdweddingdirectory/register/vendor/configuration', [ $this, 'create_vendor_post' ], absint( '10' ), absint( '1' ) );

            /**
             *  Post ID - Finder Filter
             *  -----------------------
             */
            add_filter( 'sdweddingdirectory/vendor/post-id', [ $this, 'get_post_id' ], absint( '10' ), absint( '1' ) );

            /**
             *  Repair missing vendor post for logged-in vendor users.
             */
            add_action( 'init', [ $this, 'maybe_repair_logged_in_vendor_configuration' ], absint( '200' ) );
        }

        /**
         *  Core configuration meta relationship
         *  ------------------------------------
         */
        public static function user_configuration( $args = [] ){

            /**
             *  Return the couple post type
             *  ---------------------------
             */
            return      array_merge( $args, array( 'vendor' ) );
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

                                'notes'     =>  esc_attr__( 'Vendor', 'sdweddingdirectory' ),

                                'article'   =>  esc_url_raw( 'https://wp-organic.gitbook.io/sdweddingdirectory-wordpress/faqs/user-configuration-process/vendor-user' )
                            )
                        )
                    );
        }

        /**
         *  6. Vendor Register After - Vendor Create Post
         *  ---------------------------------------------
         */
        public static function create_vendor_post( $args = [] ){

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

                    'post_type'                 =>      esc_attr( 'vendor' ),
                    'user_id'                   =>      absint( '0' ),
                    'first_name'                =>      '',
                    'last_name'                 =>      '',
                    'user_email'                =>      '',
                    'user_name'                 =>      '',
                    'company_name'              =>      '',
                    'company_contact'           =>      '',
                    'company_address'           =>      '',
                    'company_website'           =>      '',
                    'company_location_pincode'  =>      '',
                    'vendor_category'           =>      absint( '0' ),
                    'account_type'              =>      esc_attr( 'vendor' ),

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

                    $slug_validation = parent:: validate_business_slug(

                                            $company_name,

                                            0,

                                            [
                                                'user_id'       => absint( $user_id ),
                                                'post_types'    => [ 'vendor', 'venue' ],
                                            ]
                                        );

                    if( $slug_validation['status'] === 'taken' ){

                        update_user_meta( absint( $user_id ), sanitize_key( 'sdwd_pending_claim_target_post_id' ), absint( $slug_validation['target_post_id'] ) );
                        update_user_meta( absint( $user_id ), sanitize_key( 'sdwd_pending_claim_target_post_type' ), sanitize_key( $slug_validation['target_post_type'] ) );
                        update_user_meta( absint( $user_id ), sanitize_key( 'sdwd_pending_claim_target_slug' ), sanitize_title( $slug_validation['target_slug'] ) );

                        return;
                    }

                    if( ! in_array( $slug_validation['status'], [ 'available', 'owned' ], true ) ){

                        return;
                    }

                    delete_user_meta( absint( $user_id ), sanitize_key( 'sdwd_pending_claim_target_post_id' ) );
                    delete_user_meta( absint( $user_id ), sanitize_key( 'sdwd_pending_claim_target_post_type' ) );
                    delete_user_meta( absint( $user_id ), sanitize_key( 'sdwd_pending_claim_target_slug' ) );

                    /**
                     *  Vendor Created New Post ID
                     *  --------------------------
                     */
                                    $post_id    =   wp_insert_post( [

                                        'post_author'       =>  absint( $user_id ) > 0 ? absint( $user_id ) : absint( '1' ),

                                        'post_name'         =>  sanitize_title( $slug_validation['slug'] ),

                                        'post_title'        =>  esc_attr( $company_name ),

                                        'post_status'       =>  esc_attr( 'publish' ),

                                        'post_type'         =>  esc_attr( $post_type ),

                                        'post_content'      =>  sprintf( esc_attr__( 'Welcome %1$s', 'sdweddingdirectory' ),
                                                                      
                                                                    /**
                                                                     *  Username
                                                                     *  --------
                                                                     */
                                                                    esc_attr( $company_name )
                                                                ),
                                    ] );

                    /**
                     *  Vendor Information updated in Post ID
                     *  -------------------------------------
                     */
                    $post_meta                          =       array(

                        'user_id'                       =>      absint( $user_id ),

                        'first_name'                    =>      esc_attr( $first_name ),

                        'last_name'                     =>      esc_attr( $last_name ),

                        'user_email'                    =>      sanitize_email( $user_email ),

                        /**
                         *  Company Information
                         *  -------------------
                         */
                        'company_name'                  =>      esc_attr( $company_name ),

                        'company_contact'               =>      esc_attr( $company_contact ),

                        'company_address'               =>      esc_attr( $company_address ),

                        'company_website'               =>      esc_url( $company_website ),

                        'company_location_pincode'      =>      esc_attr( $company_location_pincode ),

                        'account_type'                  =>      in_array( sanitize_key( $account_type ), [ 'vendor', 'venue' ], true )

                                                                ? sanitize_key( $account_type )

                                                                : esc_attr( 'vendor' ),
                    );

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
                     *  Set Vendor Category Taxonomy
                     *  ----------------------------
                     */
                    wp_set_post_terms(  absint( $post_id ), [ absint( $vendor_category ) ], esc_attr( 'vendor-category' )  );

                    /**
                     *  New Vendor Register As SDWeddingDirectory Configuration
                     *  -----------------------------------------------
                     */
                    do_action( 'sdweddingdirectory/user-register/vendor',  array_merge( $args, [ 'post_id'  =>  $post_id ] )  );
                }
            }
        }

        /**
         *  [ Vendor ] Post ID - Finder
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
                return      parent:: find_post_id( esc_attr( 'vendor' ), sanitize_email( $email ) );
            }
        }

        /**
         *  If vendor post is missing for a logged-in vendor user, rebuild it from user meta.
         */
        public static function maybe_repair_logged_in_vendor_configuration(){

            if( is_admin() || ! is_user_logged_in() || ! parent:: is_vendor() ){
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

            $vendor_post_id = parent:: find_post_id( esc_attr( 'vendor' ), $email );

            if( ! empty( $vendor_post_id ) ){
                return;
            }

            $company_name = sanitize_text_field( get_user_meta( $user->ID, sanitize_key( 'sdwd_vendor_company_name' ), true ) );

            if( empty( $company_name ) ){
                return;
            }

            do_action( 'sdweddingdirectory/register/vendor/configuration', [

                'user_id'           =>  absint( $user->ID ),

                'user_name'         =>  sanitize_user( $user->user_login ),

                'first_name'        =>  esc_attr( get_user_meta( $user->ID, sanitize_key( 'first_name' ), true ) ),

                'last_name'         =>  esc_attr( get_user_meta( $user->ID, sanitize_key( 'last_name' ), true ) ),

                'user_email'        =>  $email,

                'company_name'      =>  esc_attr( $company_name ),

                'company_contact'   =>  esc_attr( get_user_meta( $user->ID, sanitize_key( 'sdwd_vendor_company_contact' ), true ) ),

                'company_website'   =>  esc_url( get_user_meta( $user->ID, sanitize_key( 'sdwd_vendor_company_website' ), true ) ),

                'vendor_category'   =>  absint( get_user_meta( $user->ID, sanitize_key( 'sdwd_vendor_category' ), true ) ),

                'account_type'      =>  sanitize_key( get_user_meta( $user->ID, sanitize_key( 'sdwd_vendor_account_type' ), true ) ),
            ] );
        }
    }

    /**
     *  SDWeddingDirectory - Confiugration
     *  --------------------------
     */
    SDWeddingDirectory_Vendor_Configuration:: get_instance();
}
