<?php
/**
 *  SDWeddingDirectory - Email Helper ShortCode Filters
 *  -------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Email_Helper_ShortCodes' ) && class_exists( 'SDWeddingDirectory_Core_Filters' ) ){

    /**
     *  SDWeddingDirectory - Email Helper ShortCode Filters
     *  -------------------------------------------
     */
    class SDWeddingDirectory_Email_Helper_ShortCodes extends SDWeddingDirectory_Core_Filters{

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
             *  Couple Dashboard Pages
             *  ----------------------
             */
            add_filter( 'sdweddingdirectory/couple-dashboard/pages', [ $this, 'couple_tools_pages' ], absint( '10' ), absint( '1' ) );

            /**
             *  Vendor Dashboard Pages
             *  ----------------------
             */
            add_filter( 'sdweddingdirectory/vendor-dashboard/pages', [ $this, 'vendor_tools_pages' ], absint( '10' ), absint( '1' ) );

            /**
             *  Vendor Login - Redirection
             *  --------------------------
             */
            add_filter( 'sdweddingdirectory/vendor-login/redirection', [ $this, 'vendor_login_redirection' ], absint( '10' ), absint( '1' ) );

            /**
             *  Couple Login - Redirection
             *  --------------------------
             */
            add_filter( 'sdweddingdirectory/couple-login/redirection', [ $this, 'couple_login_redirection' ], absint( '10' ), absint( '1' ) );

            /**
             *  SDWeddingDirectory - Email - ShortCode Helper
             *  -------------------------------------
             */
            add_filter( 'sdweddingdirectory/helper', [ $this, 'user_login_after_redirection_helper' ], absint( '10' ), absint( '1' ) );

            /**
             *  SDWeddingDirectory - Email - ShortCode Helper
             *  -------------------------------------
             */
            add_filter( 'sdweddingdirectory/helper', [ $this, 'email_helper_shortcode' ], absint( '20' ), absint( '1' ) );

            /**
             *  SDWeddingDirectory - Email Button Wise Redirection on Page with Condition
             *  -----------------------------------------------------------------
             */
            add_action( 'init', [ $this, 'redirection_page' ], absint( '10' ) );
        }

        /**
         *  Get Couple Tools Menu helper
         *  ----------------------------
         */
        public static function couple_tools_pages( $args = [] ){

            /**
             *  Extract
             *  -------
             */
            extract( wp_parse_args( $args, [

                'menu'          =>      apply_filters( 'sdweddingdirectory/couple/dashboard/menu', [] ),

                'handler'       =>      [],

                'prefix'        =>      esc_attr( 'couple_' )

            ] ) );

            /**
             *  Couple Dashboard Page links
             *  ---------------------------
             */
            if( parent:: _is_array( $menu ) ){

                unset( $menu[ 'logout' ] );

                foreach( $menu as $key => $value ){

                    $handler[ esc_attr( $prefix . $key ) ]    =   esc_url( $value[ 'menu_link' ] );
                }
            }

            /**
             *  Update new args
             *  ---------------
             */
            return      $handler;
        }

        /**
         *  Get Vendor Tools Menu helper
         *  ----------------------------
         */
        public static function vendor_tools_pages( $args = [] ){

            /**
             *  Extract
             *  -------
             */
            extract( wp_parse_args( $args, [

                'menu'          =>      apply_filters( 'sdweddingdirectory/vendor/dashboard/menu', [] ),

                'handler'       =>      [],

                'prefix'        =>      esc_attr( 'vendor_' )

            ] ) );

            /**
             *  Vendor Dashboard Page links
             *  ---------------------------
             */
            if( parent:: _is_array( $menu ) ){

                unset( $menu[ 'logout' ] );

                foreach( $menu as $key => $value ){

                    $handler[ esc_attr( $prefix . $key ) ]    =   esc_url( $value[ 'menu_link' ] );
                }
            }

            /**
             *  Update new args
             *  ---------------
             */
            return      $handler;
        }

        /**
         *  Get Vendor Login Redirection
         *  ----------------------------
         */
        public static function vendor_login_redirection( $args = [] ){

            /**
             *  Extract
             *  -------
             */
            extract( wp_parse_args( $args, [

                'menu'          =>      apply_filters( 'sdweddingdirectory/vendor/dashboard/menu', [] ),

                'handler'       =>      [],

                'prefix'        =>      esc_attr( 'vendor_login' )

            ] ) );

            /**
             *  Vendor Dashboard Page links
             *  ---------------------------
             */
            if( parent:: _is_array( $menu ) ){

                unset( $menu[ 'logout' ] );

                foreach( $menu as $key => $value ){

                    $handler[ esc_attr( $prefix . '_redirect_link_' . $key ) ]    =

                    add_query_arg(

                        /**
                         *  1. Parameters
                         *  -------------
                         */
                        array(

                            $prefix                         =>      esc_attr( parent:: popup_id( $prefix ) ),

                            $prefix . '_redirect_link'      =>      esc_url( $value[ 'menu_link' ] )
                        ),

                        /**
                         *  2. Home link
                         *  ------------
                         */
                        home_url( '/' )
                    );
                }
            }

            /**
             *  Update new args
             *  ---------------
             */
            return      $handler;
        }

        /**
         *  Get Couple Login Redirection
         *  ----------------------------
         */
        public static function couple_login_redirection( $args = [] ){

            /**
             *  Extract
             *  -------
             */
            extract( wp_parse_args( $args, [

                'menu'          =>      apply_filters( 'sdweddingdirectory/couple/dashboard/menu', [] ),

                'handler'       =>      [],

                'prefix'        =>      esc_attr( 'couple_login' )

            ] ) );

            /**
             *  Vendor Dashboard Page links
             *  ---------------------------
             */
            if( parent:: _is_array( $menu ) ){

                foreach( $menu as $key => $value ){

                    unset( $menu[ 'logout' ] );

                    $handler[ esc_attr( $prefix . '_redirect_link_' . $key ) ]    =

                    add_query_arg(

                        /**
                         *  1. Parameters
                         *  -------------
                         */
                        array(

                            $prefix                         =>      esc_attr( parent:: popup_id( $prefix ) ),

                            $prefix . '_redirect_link'      =>      esc_url( $value[ 'menu_link' ] )
                        ),

                        /**
                         *  2. Home link
                         *  ------------
                         */
                        home_url( '/' )
                    );
                }
            }

            /**
             *  Update new args
             *  ---------------
             */
            return      $handler;
        }

        /**
         *  Email ShortCode Helper
         *  ----------------------
         */
        public static function user_login_after_redirection_helper( $args = [] ){

            /**
             *  Update new args
             *  ---------------
             */
            return      array_merge(  $args,

                            apply_filters( 'sdweddingdirectory/couple-dashboard/pages', [] ),

                            apply_filters( 'sdweddingdirectory/vendor-dashboard/pages', [] ),

                            apply_filters( 'sdweddingdirectory/couple-login/redirection', [] ),

                            apply_filters( 'sdweddingdirectory/vendor-login/redirection', [] ),
                        );
        }

        /**
         *  SDWeddingDirectory - Email - ShortCode Helper
         *  -------------------------------------
         */
        public static function email_helper_shortcode( $args = [] ){

            /**
             *  Extract
             *  -------
             */
            extract( [

                'handler'           =>      '',

                'social_media'      =>      [
                    [
                        'title' => 'Facebook',
                        'image' => esc_url( SDWEDDINGDIRECTORY_URL . 'admin-file/setting-option/settings-fields/images/social-image/facebook.png' ),
                        'link'  => 'https://www.facebook.com/WeddingDirectoryPro',
                    ],
                    [
                        'title' => 'Twitter',
                        'image' => esc_url( SDWEDDINGDIRECTORY_URL . 'admin-file/setting-option/settings-fields/images/social-image/twitter.png' ),
                        'link'  => 'https://www.twitter.com/weddingdirpro/',
                    ],
                    [
                        'title' => 'Instagram',
                        'image' => esc_url( SDWEDDINGDIRECTORY_URL . 'admin-file/setting-option/settings-fields/images/social-image/instagram.png' ),
                        'link'  => 'https://www.instagram.com/weddingdir/',
                    ],
                    [
                        'title' => 'Youtube',
                        'image' => esc_url( SDWEDDINGDIRECTORY_URL . 'admin-file/setting-option/settings-fields/images/social-image/youtube.png' ),
                        'link'  => 'https://www.youtube.com/channel/UCZl-7C5ibAUO-i80XF_FsPA/',
                    ],
                ],

                'email_logo'        =>      esc_url( get_theme_file_uri( 'assets/images/logo/logo_dark.jpg' ) ),

                'email_footer'      =>      'SDWeddingDirectory Founder!',

            ] );

            /**
             *  Have Social Media ?
             *  -------------------
             */
            if( parent:: _is_array( $social_media ) ){

                foreach( $social_media as $key => $value ){

                    /**
                     *  Extract Args
                     *  ------------
                     */
                    extract( $value );

                    /**
                     *  Have Image ?
                     *  ------------
                     */
                    if( ! empty( $image ) ){

                        $handler   .=

                        sprintf( '<a href="%1$s" target="_blank" style="color:#21759B;text-decoration:none!important">

                                    <img src="%2$s" width="32" height="32" alt="%3$s"

                                      style="width:32px;height:32px;-ms-interpolation-mode:bicubic;border:0;display:inline;outline:none;"  />

                                  </a>&nbsp;',

                                /**
                                 *  1. Link
                                 *  -------
                                 */
                                esc_url( $link ),

                                /**
                                 *  2. Image
                                 *  --------
                                 */
                                esc_url( $image ),

                                /**
                                 *  3. Name
                                 *  -------
                                 */
                                esc_attr( $title )
                        );
                    }
                }
            }

            /**
             *  Return Data
             *  -----------
             */
            return      array_merge( $args, [

                            'home_page'                 =>      esc_url( home_url( '/' ) ),

                            'site_name'                 =>      esc_attr( get_bloginfo( 'name' ) ),

                            'email_header_logo'         =>      esc_url( $email_logo ),

                            'footer_social_media'       =>      $handler,

                            'footer_content'            =>      $email_footer,

                            'email_button_style'        =>      'color:#ffffff;text-decoration:none;display:inline-block;-webkit-text-size-adjust:none;mso-hide:all;text-align:center;background-color:#00aeaf;border-color:#00aeaf;border-width:1px;border-radius:40px;border-style:solid;width:286px;line-height:40px;font-size:16px;font-weight:normal',

                            'primary_button_style'     =>  'text-decoration: none !important;display: inline-block;-webkit-text-size-adjust: none;text-align: center;border-width: 1px;border-style: solid;width: 286px;font-weight: normal;outline: none;padding: 14px 26px;font-size: 16px;line-height: 1.4;color: #fff;background-color: #f48f00;border-color: #f48f00;border-radius: 50px;',

                            'default_button_style'     =>   'text-decoration: none !important;display: inline-block;-webkit-text-size-adjust: none;text-align: center;border-width: 1px;border-style: solid;width: 286px;font-weight: normal;outline: none;padding: 14px 26px;font-size: 16px;line-height: 1.4;border-radius: 50px;color: #fff;background-color: #00aeaf;border-color: #00aeaf;',

                            'secondary_button_style'  =>  'text-decoration: none !important;display: inline-block;-webkit-text-size-adjust: none;text-align: center;border-width: 1px;border-style: solid;width: 286px;font-weight: normal;outline: none;padding: 14px 26px;font-size: 16px;line-height: 1.4;color: #fff;background-color: #4b4645;border-color: #4b4645;border-radius: 50px;'

                        ] );
        }

        /**
         *  SDWeddingDirectory - Email Button Wise Redirection on Page with Condition
         *  -----------------------------------------------------------------
         */
        public static function redirection_page(){

            /**
             *  Make sure user is login
             *  -----------------------
             */
            if( is_user_logged_in() && ( self:: is_vendor() || self:: is_couple() ) ){

                $_condition_1   =   isset( $_GET[ 'vendor_login' ] ) && parent:: _have_data( $_GET[ 'vendor_login' ] );

                $_condition_2   =   isset( $_GET[ 'vendor_login_redirect_link' ] ) && parent:: _have_data( $_GET[ 'vendor_login_redirect_link' ] );

                $_condition_3   =   isset( $_GET[ 'couple_login' ] ) && parent:: _have_data( $_GET[ 'couple_login' ] );

                $_condition_4   =   isset( $_GET[ 'couple_login_redirect_link' ] ) && parent:: _have_data( $_GET[ 'couple_login_redirect_link' ] );

                /**
                 *  If is vendor
                 *  ------------
                 */
                if( self:: is_vendor() && $_condition_1 && $_condition_2 ){

                    exit( wp_safe_redirect( esc_url( $_GET[ 'vendor_login_redirect_link' ] ) ) );
                }

                /**
                 *  If is couple
                 *  ------------
                 */
                if( self:: is_couple() && $_condition_3 && $_condition_4 ){

                    exit( wp_safe_redirect( esc_url( $_GET[ 'couple_login_redirect_link' ] ) ) );
                }
            }
        }
    }

    /**
     *  SDWeddingDirectory - Email Helper ShortCode Filters
     *  -------------------------------------------
     */
    SDWeddingDirectory_Email_Helper_ShortCodes:: get_instance();
}
