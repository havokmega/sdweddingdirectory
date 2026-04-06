<?php
/**
 *   Follow Vendor Object
 *   --------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Follow_Vendor' ) && class_exists( 'SDWeddingDirectory_Follow_Vendor_Database' ) ){

    /**
     *   Follow Vendor Object
     *   --------------------
     */
    class SDWeddingDirectory_Follow_Vendor extends SDWeddingDirectory_Follow_Vendor_Database{

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
             *  1. Load Script
             *  --------------
             */
            add_action( 'wp_enqueue_scripts', [$this, 'sdweddingdirectory_script'] );

            /**
             *  2. Vendor's Following
             *  ---------------------
             */
            add_filter( 'sdweddingdirectory/vendor/following', [ $this, 'vendor_post_follow_button' ], absint( '10' ), absint( '2' ) );
        }

        /**
         *  1. Load Script
         *  --------------
         */
        public static function sdweddingdirectory_script(){

            /**
             *  Load script when get this condition is true
             *  -------------------------------------------
             */
            if( parent:: is_couple() && ! parent:: is_dashboard() ){

                /**
                 *  Load Script
                 *  -----------
                 */
                wp_enqueue_script(

                    /**
                     *  1. File Name
                     *  ------------
                     */
                    esc_attr( sanitize_title( __CLASS__ ) ),

                    /**
                     *  2. File Path
                     *  ------------
                     */
                    esc_url(   plugin_dir_url( __FILE__ )   .   'script.js'   ),

                    /**
                     *  3. Load After "JQUERY" Load
                     *  ---------------------------
                     */
                    array( 'jquery', 'toastr' ),

                    /**
                     *  4. Bootstrap - Library Version
                     *  ------------------------------
                     */
                    esc_attr( parent:: _file_version( plugin_dir_path( __FILE__ )   .   'script.js' ) ),

                    /**
                     *  5. Load in Footer
                     *  -----------------
                     */
                    true
                );
            }
        }

        /**
         *  2. Follow Button
         *  ----------------
         */
        public static function vendor_post_follow_button( $handler = '', $args = [] ){

            /**
             *  Have Args ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract Args
                 *  ------------
                 */
                extract( wp_parse_args( $args, [

                    'post_id'       =>      absint( '0' ),

                    'is_admin'      =>      is_user_logged_in() && current_user_can('administrator'),

                    'is_vendor'     =>      is_user_logged_in() && parent:: is_vendor(),

                    'is_couple'     =>      is_user_logged_in() && parent:: is_couple(),

                ] ) );

                /**
                 *  Have post id
                 *  ------------
                 */
                if( empty( $post_id ) ){

                    return      $handler;
                }

                /**
                 *  If User Not Login then showing login popup
                 *  ------------------------------------------
                 */
                if( ! is_user_logged_in() ){

                    $handler    .=

                    sprintf(   '<a class="%1$s" %2$s>%3$s</a>',

                                /**
                                 *  1. Button Style
                                 *  ---------------
                                 */
                                esc_attr( 'btn btn-light btn-sm' ),

                                /**
                                 *  2. Couple Login Popup
                                 *  ---------------------
                                 */
                                apply_filters( 'sdweddingdirectory/couple-login/attr', '' ),

                                /**
                                 *  3. Translation Ready String
                                 *  ---------------------------
                                 */
                                esc_attr__( 'Follow Us', 'sdweddingdirectory' )
                    );
                }

                /**
                 *  Is Couple
                 *  ---------
                 */
                elseif( $is_couple && ! ( $is_vendor || $is_admin ) ){

                    /**
                     *  Couple Post ID
                     *  --------------
                     */
                    $couple_id      =     absint( parent:: post_id() );

                    /**
                     *  Extract Args
                     *  ------------
                     */
                    extract( [

                        'followers'         =>      get_post_meta( $couple_id, sanitize_key( 'sdweddingdirectory_follow_vendors' ), true ),

                        'already_follow'    =>      false,

                    ] );

                    /**
                     *  Have Backend Data ?
                     *  -------------------
                     */
                    if( parent:: _is_array( $followers ) ){

                        /**
                         *  Check Couple Already Follow This Vendor ?
                         *  -----------------------------------------
                         */
                        foreach( $followers as $key => $value ){
                            
                            /**
                             *  Already Follow This Vendor ?
                             *  ----------------------------
                             */
                            if( $value[ 'vendor_id' ] == absint( $post_id ) ){

                                $already_follow    =   true;
                            }
                        }
                    }

                    /**
                     *  Follow The Vendor
                     *  -----------------
                     */
                    if( $already_follow ){

                        /**
                         *  Follow Vendor
                         *  -------------
                         */
                        $handler    .=

                        sprintf(    '<a class="btn btn-default btn-sm sdweddingdirectory-unfollow-vendor" 

                                        href="javascript:" id="%4$s" data-alert-follow="%5$s" data-alert-unfollow="%6$s" 

                                        data-vendor-id="%3$s" data-security="%2$s">%1$s</a>',

                                    /**
                                     *  1. Translation Ready String
                                     *  ---------------------------
                                     */
                                    esc_attr__( 'Following', 'sdweddingdirectory' ),

                                    /**
                                     *  2. Follow Button Click Security
                                     *  -------------------------------
                                     */
                                    wp_create_nonce( 'sdweddingdirectory_follow_vendor_security-' . $couple_id  ),

                                    /**
                                     *  3. Vendor Post ID
                                     *  -----------------
                                     */
                                    absint( $post_id ),

                                    /**
                                     *  4. Random ID
                                     *  ------------
                                     */
                                    esc_attr( parent:: _rand() ),

                                    /**
                                     *  5. Follow Vendor Alert
                                     *  ----------------------
                                     */
                                    esc_attr__( 'Follow Us', 'sdweddingdirectory' ),

                                    /**
                                     *  6. UnFollow Vendor Alert
                                     *  ------------------------
                                     */
                                    esc_attr__( 'Following', 'sdweddingdirectory' )
                                );
                    }

                    /**
                     *  Else
                     *  ----
                     */
                    else{

                        /**
                         *  UnFollow the Vendor
                         *  -------------------
                         */
                        $handler    .=

                        sprintf(    '<a class="btn btn-light btn-sm sdweddingdirectory-follow-vendor" href="javascript:" 

                                        id="%4$s" data-alert-follow="%5$s" data-alert-unfollow="%6$s" data-vendor-id="%3$s" 

                                        data-security="%2$s">%1$s</a>',

                                    /**
                                     *  1. Translation Ready String
                                     *  ---------------------------
                                     */
                                    esc_attr__( 'Follow Us', 'sdweddingdirectory' ),

                                    /**
                                     *  2. Follow Button Click Security
                                     *  -------------------------------
                                     */
                                    wp_create_nonce( 'sdweddingdirectory_follow_vendor_security-' . $couple_id  ),

                                    /**
                                     *  3. Vendor Post ID
                                     *  -----------------
                                     */
                                    absint( $post_id ),

                                    /**
                                     *  4. Random ID
                                     *  ------------
                                     */
                                    esc_attr( parent:: _rand() ),

                                    /**
                                     *  5. Follow Vendor Alert
                                     *  ----------------------
                                     */
                                    esc_attr__( 'Follow Us', 'sdweddingdirectory' ),

                                    /**
                                     *  6. UnFollow Vendor Alert
                                     *  ------------------------
                                     */
                                    esc_attr__( 'Following', 'sdweddingdirectory' )
                                );

                    }
                }

                /**
                 *  Is Vendor / Admin
                 *  -----------------
                 */
                elseif( $is_vendor || $is_admin ){

                    /**
                     *  Return Simple Button with Disable Possition
                     *  -------------------------------------------
                     */
                    $handler    .=  

                    sprintf(   '<button type="button" class="%1$s" disabled="">%2$s</button>',

                                /**
                                 *  1. Button Style
                                 *  ---------------
                                 */
                                esc_attr( 'btn btn-light btn-sm' ),

                                /**
                                 *  2. Translation Ready String
                                 *  ---------------------------
                                 */
                                esc_attr__( 'Follow Us', 'sdweddingdirectory' )
                    );
                }

                /**
                 *  Handler
                 *  -------
                 */
                return          $handler;
            }
        }
    }

    /**
     *  Follow Vendor Object
     *  --------------------
     */
    SDWeddingDirectory_Follow_Vendor::get_instance();
}