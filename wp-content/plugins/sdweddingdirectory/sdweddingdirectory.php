<?php
/**
 *  ------------------------
 *  SDWeddingDirectory - Core Plugin
 *  ------------------------
 *
 *  @package     SDWeddingDirectory
 *  @author      SDWeddingDirectory
 *  @copyright   2026 SDWeddingDirectory
 *
 *  @wordpress-plugin
 *  -----------------
 *  Plugin Name:     SDWeddingDirectory
 *  Plugin URI:      http://sdweddingdirectory.net
 *  Description:     SDWeddingDirectory - Core plugin used for custom post type, import content, shortcode and meta data.
 *  Version:         1.0.0
 *  Author:          SDWeddingDirectory
 *  Author URI:      https://sdweddingdirectory.net/
 *  Text Domain:     sdweddingdirectory
 *  Domain Path:     /languages
 *  License:         GPL-2.0+
 *  License URI:     http://www.gnu.org/licenses/gpl-2.0.txt
 */

/** 
 *  Exit if accessed directly
 *  -------------------------
 */
defined( 'ABSPATH' ) || exit;

/**
 *  @article - https://developer.woocommerce.com/2024/11/11/developer-advisory-translation-loading-changes-in-wordpress-6-7/
 *  ------------------------------------------------------------------------------------------------------------------------
 *  Temporly - Disable this filter issue facing November 12, 2024 / WordPress 6.7
 *  =============================================================================
 */
add_filter( 'doing_it_wrong_trigger_error', '__return_false' );

/**
 *  -------------------------------
 *  SDWeddingDirectory - Core Plugin Object
 *  -------------------------------
 */
if ( ! class_exists( 'SDWeddingDirectory_Loader' ) ) {

    /**
     *  -------------------------------
     *  SDWeddingDirectory - Core Plugin Object
     *  -------------------------------
     */
    class SDWeddingDirectory_Loader {

        /**
         *  Plugin - Version
         *  ================
         */
        const   VERSION     =       '1.0.0';

        /**
         *  Plugin - Slug
         *  =============
         */
        const   SLUG        =       'sdweddingdirectory';

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
             *  SDWeddingDirectory - Plugin Activate
             *  ----------------------------
             */
            register_activation_hook( __FILE__, [ $this, 'plugin_activation' ] );

            /**
             *  SDWeddingDirectory - Plugin Deactivate
             *  ------------------------------
             */
            register_deactivation_hook( __FILE__, [ $this, 'plugin_deactivation' ] );

            /**
             *  SDWeddingDirectory - Plugin Load
             *  ------------------------
             */
            add_action( 'plugins_loaded', [ $this, 'plugins_loaded' ], absint( '100' ) );

            /**
             *  Class Filter
             *  ------------
             */
            add_filter( 'sdweddingdirectory/class', [ $this, '_class' ], absint( '10' ), absint( '1' ) );

            /**
             *  Testing Purpose
             *  ---------------
             */
            add_action( 'init', [ $this, 'test_code' ], absint( '999999' ) );
        }

        /**
         *  SDWeddingDirectory - User
         *  -----------------
         */
        public static function sdweddingdirectory_user(){

            /**
             *  SDWeddingDirectory User
             *  ---------------
             */
            return      [
                            'couple'    =>      esc_attr( 'Couple' ),
                                
                            'vendor'    =>      esc_attr( 'Vendor' )
                        ];
        }

        /**
         *  User Capability
         *  ---------------
         */
        public static function sdweddingdirectory_user_capability(){

            return      [ 'read', 'upload_files', 'delete_posts', 'edit_posts' ];
        }

        /**
         *  Activatation Process
         *  --------------------
         */
        public static function plugin_activation(){

            /**
             *  Enable Cron
             *  -----------
             */
            if ( ! wp_next_scheduled( 'sdweddingdirectory_cron' ) ) {

                wp_schedule_event( time(), 'sdweddingdirectory_daily_cron', 'sdweddingdirectory_cron' );
            }

            /**
             *  Get User List
             *  -------------
             */
            $sdweddingdirectory_user    =   self:: sdweddingdirectory_user();

            $user_capability    =   self:: sdweddingdirectory_user_capability();

            /**
             *  Make sure user found
             *  --------------------
             */
            if( self:: _is_array( $sdweddingdirectory_user ) ){

                /**
                 *  Update Capability
                 *  -----------------
                 */
                foreach( $sdweddingdirectory_user as $slug => $name ){

                    /**
                     *  Create SDWeddingDirectory Roles
                     *  -----------------------
                     */
                    add_role( $slug, $name );

                    /**
                     *  Have User Capability ?
                     *  ----------------------
                     */
                    if( self:: _is_array( $user_capability ) ){

                        $role   =   get_role( $slug );

                        foreach( $user_capability as $index => $cap ){

                            $role->add_cap( $cap );
                        }
                    }
                }
            }
        }
        
        /**
         *  Deactivatation Process
         *  ----------------------
         */
        public static function plugin_deactivation(){

            /**
             *  Remove SDWeddingDirectory Cron
             *  ----------------------
             */
            wp_unschedule_event( wp_next_scheduled( 'sdweddingdirectory_cron' ), 'sdweddingdirectory_cron' );

            /**
             *  Get User List
             *  -------------
             */
            $sdweddingdirectory_user    =   self:: sdweddingdirectory_user();

            $user_capability    =   self:: sdweddingdirectory_user_capability();

            /**
             *  Make sure user found
             *  --------------------
             */
            if( self:: _is_array( $sdweddingdirectory_user ) ){

                /**
                 *  Update Capability
                 *  -----------------
                 */
                foreach( $sdweddingdirectory_user as $slug => $name ){

                    /**
                     *  Have User Capability ?
                     *  ----------------------
                     */
                    if( self:: _is_array( $user_capability ) ){

                        $role   =   get_role( $slug );

                        foreach( $user_capability as $index => $cap ){

                            $role->remove_cap( $cap );
                        }
                    }
                }
            }
        }

        /**
         *  Load Plugin
         *  -----------
         */
        public function plugins_loaded(){

            /**
             *  Load language file
             *  ------------------
             */
            self:: textdomain();

            /**
             *  SDWeddingDirectory Hooks
             *  ----------------
             */
            self:: sdweddingdirectory_hooks();

            /**
             *  Define Constant
             *  ---------------
             */
            self:: constant();

            /**
             *  Required Files
             *  --------------
             */
            self:: files();
        }

        /**
         *  Load language file
         *  ------------------
         */
        public function textdomain(){

            load_plugin_textdomain( self:: SLUG, false, basename( dirname( __FILE__ ) ) . '/languages' );
        }

        /**
         *  Plugin Update Checker
         *  ---------------------
         */
        public static function get_update_link(){

            return      esc_url( sprintf( 'sdweddingdirectory.net/required-plugins/%1$s/info.json', self:: SLUG  ) );
        }

        /**
         *  2. SDWeddingDirectory - Hooks
         *  ---------------------
         */
        public static function sdweddingdirectory_hooks(){

            /**
             *  Is Admin ?
             *  ----------
             */
            if(  is_admin()  ){

                /**
                 *  Plugin Update Checker
                 *  ---------------------
                 */
                add_filter( 'sdweddingdirectory_plugin_update', function( $args = [] ){

                    return      array_merge( $args, [

                                    [   'json'  =>  esc_url( self:: get_update_link() ),

                                        'path'  =>  __FILE__,

                                        'slug'  =>  self:: SLUG
                                    ]

                                ] );
                } );

            }else{

                /**
                 *  Plugin information filter
                 *  -------------------------
                 */
                add_filter( 'sdweddingdirectory/plugin', function( $args = [] ){

                    return      array_merge( $args, [

                                    apply_filters( 'sdweddingdirectory/plugin-info-data', get_plugin_data( __FILE__ ) )

                                ] );
                } );
            }
        }

        /**
         *  SDWeddingDirectory - Const
         *  ------------------
         */
        private function constant(){

            global $wp_query, $post, $wpdb, $page;

            /**
             *  SDWeddingDirectory - Plugin URL
             *  -----------------------
             */
            define( 'SDWEDDINGDIRECTORY_URL', plugin_dir_url( __FILE__ ) );

            /**
             *  SDWeddingDirectory - Plugin DIR
             *  -----------------------
             */
            define( 'SDWEDDINGDIRECTORY_DIR', plugin_dir_path( __FILE__ ) );

            /**
             *  SDWeddingDirectory - Image Path
             *  -----------------------
             */
            define( 'SDWEDDINGDIRECTORY_IMAGES', SDWEDDINGDIRECTORY_URL . '/assets/images/' );

            /**
             *  SDWeddingDirectory - Placeholder
             *  ------------------------
             */
            define( 'SDWEDDINGDIRECTORY_PLACEHOLDER', SDWEDDINGDIRECTORY_URL . '/assets/images/placeholders/' );

            /**
             *  SDWeddingDirectory - Plugin Library
             *  ---------------------------
             */
            define( 'SDWEDDINGDIRECTORY_LIB', SDWEDDINGDIRECTORY_URL . '/assets/library/' );

            /**
             *  SDWeddingDirectory - Development is On / Off
             *  ------------------------------------
             */
            $_author_email  =   in_array(   get_bloginfo( 'admin_email' ), [ 

                                    'mhitesh2212@gmail.com', 'sdweddingdirectorypro@gmail.com', 'hiteshmahavar22@gmail.com'

                                ] );

            $_wpdb_prefix   =   in_array(   $wpdb->prefix, [

                                    'wprx_', 'demo_', 'theme_unit_test_'

                                ] );

            /**
             *  Development Purpose Only
             *  ------------------------
             */
            if( $_author_email && $_wpdb_prefix ){

                define( 'SDWEDDINGDIRECTORY_DEV', absint( '1' ) );

            }else{

                define( 'SDWEDDINGDIRECTORY_DEV', absint( '0' ) );
            }
            
            /**
             *  SDWeddingDirectory - Placeholder Condition ( Live / Demo )
             *  --------------------------------------------------
             */
            define( 'SDWEDDINGDIRECTORY_LIVE_IMAGE', SDWEDDINGDIRECTORY_DEV ? 'live/' : 'demo/' );

            /**
             *  SDWeddingDirectory Plugin Version
             *  -------------------------
             */
            define( 'SDWEDDINGDIRECTORY_VERSION', self:: VERSION );

            /**
             *  SDWeddingDirectory - Skip License Checks (Local Build)
             *  ---------------------------------------------
             */
            if( ! defined( 'SDWEDDINGDIRECTORY_SKIP_LICENSE' ) ){

                define( 'SDWEDDINGDIRECTORY_SKIP_LICENSE', true );
            }
        }

        /**
         *  4. Required - Files
         *  -------------------
         */
        private function files(){

            /**
             *  Term Meta Helpers (ACF-free term field access)
             *  ----------------------------------------------
             */
            require_once 'config-file/term-meta-helpers.php';

            /**
             *  Cofiguration Files
             *  ------------------
             */
            require_once 'config-file/index.php';

            /**
             *  Register Custom Post Type
             *  -------------------------
             */
            require_once 'admin-file/index.php';

            /**
             *  Load Libray with Script and Style
             *  ---------------------------------
             */
            require_once 'assets/index.php';

            /**
             *  Front Page Files
             *  ----------------
             */
            require_once 'front-file/index.php';

            /**
             *  Shortcodes Module (Integrated from sdweddingdirectory-shortcodes)
             *  Note: No [shortcode] tags are used in any post content, but several
             *  shortcode classes provide page_builder() methods called directly
             *  from theme templates (hero slider, search form, real wedding grids,
             *  team, venue category listings). The module must remain loaded.
             */
            require_once 'shortcodes/index.php';

            /**
             *  Venue Module (Integrated from sdweddingdirectory-venue)
             *  -----------------------------------------------------------
             */
            require_once 'venue/index.php';

            /**
             *  Claim Venue Module (Integrated from sdweddingdirectory-claim-venue)
             *  -----------------------------------------------------------------------
             */
            require_once 'claim-venue/index.php';

            /**
             *  Real Wedding Module (Integrated from sdweddingdirectory-real-wedding)
             *  --------------------------------------------------------------------
             */
            require_once 'real-wedding/index.php';

            /**
             *  Dashboard
             *  ---------
             */
            require_once 'dashboard/index.php';

            /**
             *  Filter Managment
             *  ----------------
             */
            require_once 'filters-hook/index.php';

            /**
             *  AJAX Scripts
             *  ------------
             */
            require_once 'ajax/index.php';
        }

        /**
         *  Is Array ?
         *  ----------
         */
        public static function _is_array( $a ){

            if ( is_array($a) && count($a) >= absint('1') && !empty($a) && $a !== null ) {

                return  true;

            }else{

                return false;
            }
        }

        /**
         *  Is Object ?
         *  ----------
         */
        public static function _is_object( $a ){

            if ( self:: _is_array( (array) $a ) ) {

                return  true;

            }else{

                return false;
            }
        }

        /**
         *  Check Have Variable Value ?
         *  ---------------------------
         */
        public static function _have_data( $a = '0' ){

            /**
             *  If Is Array and have at lest one args ?
             *  ---------------------------------------
             */
            if ( isset( $a ) && ! empty( $a ) && $a !== '' && $a !== NULL ) {

                return true;

            } else {

                return false; 
            }
        }

        /**
         *  Check AJAX POST is Set or not ?
         *  -------------------------------
         */
        public static function _is_set( $a ){

            if( isset( $a ) && $a !== null && $a !== '' ){

                return      true;

            }else{

                return      false;
            }
        }

        /**
         *  Have Attr with Value ?
         *  ----------------------
         *  example : id="value"
         *  --------------------
         */
        public static function _have_attr_value( $args = [] ){

            /**
             *  Have Args ?
             *  -----------
             */
            if( self:: _is_array( $args ) ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( wp_parse_args( $args, [

                    'attr'  =>  '',

                    'val'   =>  '',

                ] ) );

                /**
                 *  Have Class ?
                 *  ------------
                 */
                $_condition_1   =   self:: _have_data( $attr );

                $_condition_2   =   self:: _have_data( $val );

                /** 
                 *  Condition Check
                 *  ---------------
                 */
                if( $_condition_1 && $_condition_2 ){

                    return  

                    sprintf( '%1$s="%2$s"',

                        /**
                         *  1. HTML Tag
                         *  -----------
                         */
                        esc_attr( $attr ),

                        /**
                         *  2. 
                         */
                        esc_attr( $val ) 
                    );

                }else{

                    return;
                }

            }
        }

        /**
         *  Get Dummy Placeholder
         *  ---------------------
         */
        public static function _placeholder( $width = '', $height = '', $bg = '00aeaf' ){

            /**
             *  Placeholder
             *  -----------
             */
            return      esc_url( sprintf( 'https://placehold.co/%1$sx%2$s/%3$s/ffffff.png',

                            /**
                             *  1. Width
                             *  --------
                             */
                            absint( $width ),

                            /**
                             *  2. Height
                             *  ---------
                             */
                            absint( $height ),

                            /**
                             *  3. BG
                             *  -----
                             */
                            esc_attr( $bg )

                        ) );
        }

        /**
         *  Get Placeholders Images
         *  -----------------------
         */
        public static function placeholder( $name = '' ){

            /**
             *  Make sure placeholder is not empty!
             *  -----------------------------------
             */
            $_have_data                 =   apply_filters( 'sdweddingdirectory/placeholder', [] );

            $_list_of_placeholder       =   [];

            /**
             *  Is array ?
             *  ----------
             */
            if( ! self:: _is_array( $_have_data ) ){

                return;
            }

            /**
             *  Have Collection ?
             *  -----------------
             */
            $_list_of_placeholder   =   array_column(

                                            /**
                                             *  1. Collection
                                             *  -------------
                                             */
                                            $_have_data, 

                                            /**
                                             *  2. Key
                                             *  ------
                                             */
                                            esc_attr( 'placeholder' )
                                        );

            $placeholders           =   [];

            /**
             *  Have collection of placeholders ?
             *  ---------------------------------
             */
            if( self:: _is_array( $_list_of_placeholder ) ){

                foreach( $_list_of_placeholder as $_key => $_value ){

                    foreach( $_value as $key => $value ){

                        $placeholders[ $key ]  =   $value;
                    }
                }
            }

            /**
             *  Check the placeholder images
             *  ----------------------------
             */
            if( self:: _have_data( $name ) && self:: _is_array( $placeholders ) ){

                // OptionTree placeholder overrides were removed - load named files from theme assets/images/placeholders.
                $_theme_relative_paths = [
                    sprintf( 'assets/images/placeholders/%1$s.jpg', sanitize_file_name( $name ) ),
                    sprintf( 'assets/images/placeholders/%1$s.png', sanitize_file_name( $name ) ),
                    sprintf( 'assets/images/placeholders/%1$s.svg', sanitize_file_name( $name ) ),
                    sprintf( 'assets/images/placeholders/%1$s/%1$s.jpg', sanitize_file_name( $name ) ),
                    sprintf( 'assets/images/placeholders/%1$s/%1$s.png', sanitize_file_name( $name ) ),
                    sprintf( 'assets/images/placeholders/%1$s/%1$s.svg', sanitize_file_name( $name ) ),
                ];

                foreach ( $_theme_relative_paths as $_relative_path ) {

                    $_absolute_path = trailingslashit( get_template_directory() ) . ltrim( $_relative_path, '/' );

                    if ( file_exists( $_absolute_path ) ) {

                        return esc_url( trailingslashit( get_template_directory_uri() ) . ltrim( $_relative_path, '/' ) );
                    }
                }

                if( isset( $placeholders[ $name ] ) ){

                    return  esc_url( $placeholders[ $name ] );
                }
            }
        }

        /**
         *  Brand Prefix
         *  ------------
         */
        public static function _prefix( $args = '' ){

            return  sanitize_title( self:: SLUG . '_' . $args );
        }

        /**
         *  Random Value
         *  ------------
         */
        public static function _rand( $length = 10 ){

            return      self:: _prefix(

                            substr( str_shuffle( "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ" ), absint( '0' ), absint( $length ) )
                        );
        }

        /**
         *  Image ALT
         *  ---------
         */
        public static function _alt( $args = [] ){

            /**
             *  Image Alt : Handler
             *  -------------------
             */
            $_img_alt       =   [];

            /**
             *  Have args
             *  ---------
             */
            if( self:: _is_array( $args ) ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( wp_parse_args( $args, array(

                    'media_id'          =>      absint( '0' ), 

                    'post_id'           =>      absint( '0' ),

                    'site_name'         =>      esc_attr( get_bloginfo( 'name' ) ),

                    'start_alt'         =>      '',

                    'end_alt'           =>      ''

                )  )  );

                /**
                 *  Extra Alt
                 *  ---------
                 */
                if( self:: _have_data( $start_alt ) ){

                    $_img_alt[]     =   esc_attr( $start_alt );
                }

                /**
                 *  Post ID 
                 *  -------
                 */
                if( self:: _have_data( $post_id ) ){

                    $_img_alt[]     =   esc_attr( get_the_title( absint( $post_id ) ) );
                }

                /**
                 *  Have media id ?
                 *  ---------------
                 */
                if( self:: _have_media( $media_id ) ){

                    /**
                     *  Image Have alt ?
                     *  ----------------
                     */
                    $_img_alt[]     =   apply_filters( 'sdweddingdirectory/media-data', [

                                            'media_id'      =>      absint( $media_id ),

                                            'get_data'      =>      esc_attr( 'alt' )

                                        ] );
                }

                /**
                 *  Extra Alt
                 *  ---------
                 */
                if( self:: _have_data( $end_alt ) ){

                    $_img_alt[]     =   esc_attr( $end_alt );
                }

            }else{

                /**
                 *  Website Name
                 *  ------------
                 */
                $_img_alt[]     =   esc_attr( get_bloginfo( 'name' ) );
            }

            /**
             *  Return : Image Alt + Website Name
             *  ---------------------------------
             */
            if( self:: _is_array( $_img_alt ) ){

                /** 
                 *  Return : Image Alt
                 *  ------------------
                 */
                return  esc_attr(

                            strtr( 

                                /**
                                 *  1. Have Data
                                 *  ------------
                                 */
                                trim( 

                                    implode( ' ', $_img_alt )
                                ),

                                /**
                                 *  2. Replace Args
                                 *  ---------------
                                 */
                                [ '  ' => ' ' ]
                            )
                        );
            }
        }

        /**
         *  Have Media ?
         *  ------------
         */
        public static function _have_media( $media_id = 0 ){

            $_condition_1   =   ! empty( $media_id );

            $_condition_2   =   ! empty( wp_get_attachment_url( $media_id ) );

            /**
             *  Check the media id
             *  ------------------
             */
            return      $_condition_1   &&  $_condition_2;
        }

        /**
         *  Coma String to get array
         *  ------------------------
         */
        public static function _coma_to_array( $string = '' ){

            /**
             *  Make sure string not empty!
             *  ---------------------------
             */
            if( ! empty( $string ) && self:: _is_array( preg_split ("/\,/", $string ) ) ){

                return      preg_split ("/\,/", $string );

            }else{

                return      [ $string ];
            }
        }

        /**
         *  Make sure media id have attached image
         *  --------------------------------------
         */
        public static function _filter_media_ids( $media_ids = '' ){

            /**
             *  Make sure have data
             *  -------------------
             */
            if( empty( $media_ids ) ){

                return;
            }

            $media_ids_collection  =    [];

            /**
             *  Have Data ?
             *  -----------
             */
            if( self:: _is_array( $media_ids ) ){

                /**
                 *  Collection verify
                 *  -----------------
                 */
                foreach( $media_ids as $key => $value ){

                    /**
                     *  Make sure media id have attached url
                     *  ------------------------------------
                     */
                    if( self:: _have_media( $value ) ){

                        $media_ids_collection[]  =  absint( $value );
                    }
                }

            }else{

                return  self:: _filter_media_ids( self:: _coma_to_array( $media_ids ) );
            }

            return  $media_ids_collection;
        }

        /**
         *  Media ID to get SRC
         *  -------------------
         */
        public static function media_id_to_get_src( $args = [] ){

            /**
             *  Have Args ?
             *  -----------
             */
            if( self:: _is_array( $args ) ){

                /**
                 *  Merge Default Args with Extract
                 *  -------------------------------
                 */
                extract(  wp_parse_args(  $args, array(

                    'media_id'      =>   absint( '0' ),

                    'size'          =>   esc_attr( 'thumbnail' ),

                    'placeholder'   =>   ''

                ) ) );

                /**
                 *   Media SRC
                 *   ---------
                 */
                if( self:: _have_media( $media_id ) ){
                    
                    /**
                     *  Get Media SRC
                     *  -------------
                     */
                    $_have_media    =   wp_get_attachment_image_src(

                                            /**
                                             *  Media ID
                                             *  --------
                                             */
                                            absint( $media_id ),

                                            /**
                                             *  2. Size
                                             *  -------
                                             */
                                            esc_attr( $size )
                                        );
                    /**
                     *  Have Data ?
                     *  -----------
                     */
                    if( self:: _is_array( $_have_media ) ){

                        return  esc_url( $_have_media[ absint( '0' ) ] );
                    }

                }else{

                    /**
                     *  Have Placeholder ?
                     *  ------------------
                     */
                    if( self:: _have_data( $placeholder ) ){

                        return  esc_url( self:: placeholder( $placeholder ) );
                    }
                }
            }
        }

        /**
         *  File Version
         *  ------------
         */
        public static function _file_version( $file = '' ){

            /**
             *  Is Empty ?
             *  ----------
             */
            if( empty( $file ) ){

                /**
                 *  Get Style Version
                 *  -----------------
                 */

                return  esc_attr( SDWEDDINGDIRECTORY_VERSION );
            }

            /**
             *  File Found
             *  ----------
             */
            else{

                /*
                 *  For File Save timestrap version to clear the catch auto
                 *  -------------------------------------------------------
                 *  # https://developer.wordpress.org/reference/functions/wp_enqueue_style/#comment-6386
                 *  ------------------------------------------------------------------------------------
                 */

                return      absint( filemtime( $file ) );
            }
        }

        /**
         *  Array Conver to Sanitize Class
         *  ------------------------------
         *  @article - https://developer.wordpress.org/reference/functions/sanitize_html_class/
         *  -----------------------------------------------------------------------------------
         */
        public static function _class( $class = [] ){

            /**
             *  Make sure it's array 
             *  --------------------
             */
            if( self:: _is_array( $class ) ){

                /**
                 *  Return Class
                 *  ------------
                 */
                return      esc_attr(  join( ' ', array_map( 'sanitize_html_class', $class ) )  );
            }
        }

        /**
         *  Random Order
         *  ------------
         */
        public static function _random_order( $args = [] ){

            /**
             *  Make sure have collection
             *  -------------------------
             */
            if( self:: _is_array( $args ) ){

                /**
                 *  Generate a list of keys based on the original array
                 *  ---------------------------------------------------
                 */
                $keys       =       array_keys( $args );

                /**
                 *  Shuffle the keys
                 *  ----------------
                 */
                shuffle( $keys );

                /**
                 *  Rebuild the array using shuffled keys
                 *  -------------------------------------
                 */
                return      array_map( function($key) use ($args) {

                                return      $args[ $key ];

                            }, $keys );
            }
        }

        /**
         *  Testing Purpose
         *  ---------------
         */
        public static function test_code(){

            // test code
        }
    }

    /**
     *  -------------------------------
     *  SDWeddingDirectory - Core Plugin Object
     *  -------------------------------
     */
    SDWeddingDirectory_Loader::get_instance();
}
