<?php
/**
 * -------------------------
 * Exit if accessed directly
 * -------------------------
 */
defined( 'ABSPATH' ) || exit;

/**
 *  -------------------------------------------
 *  SDWeddingDirectory - WordPress Theme Functions File
 *  -------------------------------------------
 *  Load SDWeddingDirectory Object
 *  ----------------------
 */
if ( ! class_exists( 'SDWeddingDirectory' ) ) {

    /**
     *  ----------------------------
     *  SDWeddingDirectory - WordPress Theme
     *  ----------------------------
     *  @package sdweddingdirectory
     *  -------------------
     *  @since 1.0.0
     *  ------------
     */
    class SDWeddingDirectory {

        /**
         *  Member Variable
         *  ---------------
         *  @var instance
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
             *  1. Load Constant
             *  ----------------
             */
            $this->constant();

            /**
             *  2. Required Files
             *  -----------------
             */
            add_action( 'after_setup_theme', [ $this, 'sdweddingdirectory_theme_setup' ] );

            /**
             *  Register Sidebar
             *  ----------------
             */
            add_action( 'widgets_init',  [ $this, 'sdweddingdirectory_widget_init' ] );

            /**
             *  IS SSL Connection ? 
             *  -------------------
             *  @link - https://developer.wordpress.org/reference/hooks/https_ssl_verify/#user-contributed-notes
             *  ------------------------------------------------------------------------------------------------
             */
            if( ! is_ssl() ){

                add_filter( 'https_ssl_verify', '__return_false' );
            }

            /**
             *  SDWeddingDirectory - Required Files
             *  ---------------------------
             */
            self:: sdweddingdirectory_required_files();
        }

        /**
         *  SDWeddingDirectory - Product Constant
         *  -----------------------------
         */
        public static function constant(){

            /**
             *   @ref : https://codex.wordpress.org/Content_Width
             *   ------------------------------------------------
             */
            if ( ! isset( $content_width ) ){

                $content_width      =   absint( '1320' );
            }

            /**
             *  SDWeddingDirectory - Theme DEV MODE IS ON / OFF ?
             *  -----------------------------------------
             */
            if( ! defined( 'SDWEDDINGDIRECTORY_THEME_DEV' ) ){

                define( 'SDWEDDINGDIRECTORY_THEME_DEV',  apply_filters( 'SDWEDDINGDIRECTORY_DEV', false ) );
            }

            /**
             *  SDWeddingDirectory - Theme Version
             *  --------------------------
             */
            if( ! defined( 'SDWEDDINGDIRECTORY_THEME_VERSION' ) ){

                define( 'SDWEDDINGDIRECTORY_THEME_VERSION',  esc_attr(  wp_get_theme()->get( 'Version' ) )   );
            }

            /**
             *  SDWeddingDirectory - Theme Directory Path
             *  ---------------------------------
             */
            if( ! defined( 'SDWEDDINGDIRECTORY_THEME_DIR' ) ){

                define( 'SDWEDDINGDIRECTORY_THEME_DIR',   trailingslashit( get_template_directory_uri() )  );
            }

            /**
             *  SDWeddingDirectory - Theme Path
             *  -----------------------
             */
            if( ! defined( 'SDWEDDINGDIRECTORY_THEME_PATH' ) ){

                define( 'SDWEDDINGDIRECTORY_THEME_PATH',   trailingslashit( get_template_directory() )  );
            }

            /**
             *  SDWeddingDirectory - Theme Library Folder Path
             *  --------------------------------------
             */
            if( ! defined( 'SDWEDDINGDIRECTORY_THEME_LIBRARY' ) ){

                define( 'SDWEDDINGDIRECTORY_THEME_LIBRARY',   SDWEDDINGDIRECTORY_THEME_DIR . '/assets/library/'  );
            }

            /**
             *  SDWeddingDirectory - Theme Image Folder Path
             *  ------------------------------------
             */
            if( ! defined( 'SDWEDDINGDIRECTORY_THEME_MEDIA' ) ){

                define( 'SDWEDDINGDIRECTORY_THEME_MEDIA',   SDWEDDINGDIRECTORY_THEME_DIR . '/assets/images/'   );
            }

            /**
             *  SDWeddingDirectory - PREFIX
             *  -------------------
             */
            if( ! defined( 'SDWEDDINGDIRECTORY_THEME_PREFIX' ) ){

                define( 'SDWEDDINGDIRECTORY_THEME_PREFIX',  esc_attr( sanitize_title(  wp_get_theme()->get( 'Name' ) ) )  );
            }

            /**
             *  SDWeddingDirectory - Footer Widget Prefix ID
             *  ------------------------------------
             */
            if( ! defined( 'SDWEDDINGDIRECTORY_FOOTER_WIDGET' ) ){

                define( 'SDWEDDINGDIRECTORY_FOOTER_WIDGET',  esc_attr( SDWEDDINGDIRECTORY_THEME_PREFIX  . '-footer-widget-'  )  );
            }
        }

        /**
         *  Load SDWeddingDirectory - Files
         *  -----------------------
         */
        public static function sdweddingdirectory_theme_setup(){

            /**
             *  1. Load translation domain
             *  --------------------------
             */
            load_theme_textdomain( 'sdweddingdirectory', trailingslashit( get_template_directory() ) . '/language' );

            /**
             *  2. Register Menus
             *  -----------------
             */
            register_nav_menus( apply_filters( 'sdweddingdirectory/nav-menus', [] ) );

            /**
             *  3. Add theme support for Automatic Feed Links
             *  ---------------------------------------------
             */
            add_theme_support( 'automatic-feed-links' );    

            /**
             *  4. Add theme support for document Title tag
             *  -------------------------------------------
             */
            add_theme_support( 'title-tag' );

            /**
             *  5. Post Thumbnails Support
             *  --------------------------
             *  @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
             *  ---------------------------------------------------------------------------------------- 
             */
            add_theme_support( 'post-thumbnails' );

            /**
             *  6. Add theme support for HTML5 Semantic Markup
             *  ----------------------------------------------
             */
            add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

            /**
             *  7. Add theme support for Post Formats
             *  -------------------------------------
             */
            add_theme_support( 'post-formats', array( 'status', 'quote', 'gallery', 'image', 'video', 'audio', 'link', 'aside', 'chat' ) );

            /**
             *  8. Add theme support for Custom Background
             *  ------------------------------------------
             */
            add_theme_support( 'custom-background', array( 'default-color' => 'ffffff', 'default-image' => '' ) );

            /**
             *  --------------------------------------
             *  9. SDWeddingDirectory - Have Image Size Crop ?
             *  --------------------------------------
             *  @credit -  https://developer.wordpress.org/reference/functions/add_image_size/
             *  ------------------------------------------------------------------------------
             */
            $_have_image_size   =   apply_filters( 'sdweddingdirectory/image-size', [] );

            /**
             *  Image Size Available ?
             *  ----------------------
             */
            if( self:: _is_array( $_have_image_size ) ){

                /**
                 *  Collection
                 *  ----------
                 */
                $_collection =  [];

                /**
                 *  Loop
                 *  ----
                 */
                foreach( $_have_image_size as $key => $value ){

                    /**
                     *  Extract Args
                     *  ------------
                     */
                    extract( $value );

                    $_collection[ $id ][ 'width' ]      =   absint( $width );

                    $_collection[ $id ][ 'height' ]     =   absint( $height );

                    $_collection[ $id ][ 'name' ][]     =   esc_attr( $name );
                }

                /**
                 *   Add Image Size Register
                 *   -----------------------
                 */
                if( self:: _is_array( $_collection ) ){

                    /**
                     *  Have image size in SDWeddingDirectory ?
                     *  -------------------------------
                     */
                    foreach( $_collection as $key => $value ){

                        /**
                         *  Extract Args
                         *  ------------
                         */
                        extract( $value );

                        /**
                         *  Create Image Size
                         *  -----------------
                         */
                        add_image_size( esc_attr( $key ), absint( $width ), absint( $height ), true );
                    }
                }
            }

            /**
             *  10. SDWeddingDirectory - WordPress Media Image Size Update in list
             *  ----------------------------------------------------------
             */
            add_filter( 'image_size_names_choose', function ( $size = [] ) {

                /**
                 *  Get Image Size
                 *  --------------
                 */
                $_have_image_size   =   apply_filters( 'sdweddingdirectory/image-size', [] );

                /**
                 *  Collection
                 *  ----------
                 */
                $sdweddingdirectory_media_size =  [];

                /**
                 *  Loop
                 *  ----
                 */
                foreach( $_have_image_size as $key => $value ){

                    /**
                     *  Extract Args
                     *  ------------
                     */
                    extract( $value );

                    /**
                     *  Update Image Slug
                     *  -----------------
                     */
                    $sdweddingdirectory_media_size[ $id ]     =   esc_attr( $name );
                }

                /**
                 *  Merge Image Size Name
                 *  ---------------------
                 */
                return      array_merge( $size, $sdweddingdirectory_media_size );

            } );

            /**
             *  --------------------------
             *  18. SDWeddingDirectory - Menu Load
             *  --------------------------
             *  @credit : https://github.com/wp-bootstrap/wp-bootstrap-navwalker
             *  ----------------------------------------------------------------
             *  Installation as instruction : after_setup_theme
             *  -------------------------------------------------------------------
             *  # https://github.com/wp-bootstrap/wp-bootstrap-navwalker#installation
             *  ---------------------------------------------------------------------
             */
            if( ! class_exists( 'WP_Bootstrap_Navwalker' ) ){
                require_once 'inc/bootstrap-menu.php';
            }
            require_once 'inc/sd-mega-navwalker.php';

            /**
             *  Page Builder - Configuration (removed — Elementor is no longer active)
             *  ----------------------------------------------------------------------
             */
        }

        /**
         *  SDWeddingDirectory - Required Files
         *  ---------------------------
         */
        public static function sdweddingdirectory_required_files(){

            /**
             *  SDWeddingDirectory - Template Helper Load
             *  ---------------------------------
             */
            require_once    'inc/template-helper.php';

            /**
             *  SDWeddingDirectory - OptionTree migration
             *  -----------------------------------------
             */
            require_once    'inc/migrate-optiontree.php';

            /**
             *  SDWeddingDirectory - Native admin settings pages
             *  -----------------------------------------------
             */
            if ( is_admin() ) {
                require_once 'inc/admin-settings.php';
            }

            /**
             *  SDWeddingDirectory - Site Config Constants
             *  -------------------------------------------
             */
            if ( file_exists( get_template_directory() . '/config/site-config.php' ) ) {
                require_once    'config/site-config.php';
            }

            /**
             *  SDWeddingDirectory - Used Style + Scripts
             *  ---------------------------------
             */
            require_once    'inc/theme-scripts.php';

            /**
             *  Gutenberg Compatible Theme
             *  --------------------------
             */
            require_once    'inc/gutenberg-support.php';

            /**
             *  SDWeddingDirectory - Theme Head Tag Actions
             *  -----------------------------------
             */
            require_once    'inc/theme-head.php';

            /**
             *  SDWeddingDirectory - SEO Helpers
             *  --------------------------------
             */
            require_once    'inc/seo.php';

            /**
             *  SDWeddingDirectory - Structured Data
             *  ------------------------------------
             */
            require_once    'inc/structured-data.php';

            /**
             *  SDWeddingDirectory - Theme Header Tag Actions
             *  -------------------------------------
             */
            require_once    'inc/theme-header.php';

            /**
             *  SDWeddingDirectory - Theme Footer
             *  -------------------------
             */
            require_once    'inc/theme-footer.php';

            /**
             *  SDWeddingDirectory - Admin Pages (couple/vendor)
             *  ----------------------------------------------
             */
            require_once    'inc/admin-pages.php';

            /**
             *  SDWeddingDirectory - Comment Section Helper
             *  -----------------------------------
             */
            require_once    'inc/comment-template-part.php';

            /**
             *  SDWeddingDirectory - Page Grid Managment
             *  --------------------------------
             */
            require_once    'inc/grid-managmnet/index.php';            

            /**
             *  SDWeddingDirectory - Container Managment
             *  --------------------------------
             */
            require_once    'inc/grid-managmnet/container.php';

            /** 
             *  SDWeddingDirectory - Sidebar Managment
             *  ------------------------------
             */
            require_once    'inc/grid-managmnet/sidebar-managment.php';

            /**
             *  SDWeddingDirectory- Theme Body Markup ( attributes )
             *  --------------------------------------------
             */
            require_once    'inc/theme-body.php';

            /**
             *  SDWeddingDirectory - Page Header Banner Object
             *  --------------------------------------
             */
            require_once    'inc/page-header-banner.php';

            /**
             *  SDWeddingDirectory - Planning CTA Images
             *  ----------------------------------------
             */
            require_once    'inc/planning-cta-images.php';

            /**
             *  SDWeddingDirectory - Blog Load Helper
             *  -----------------------------
             */
            require_once    'template-parts/content-helper.php';

            /**
             *  SDWeddingDirectory - Blog Post
             *  ----------------------
             */
            require_once    'template-parts/blog.php';

            /**
             *  SDWeddingDirectory - Blog Load Helper
             *  -----------------------------
             */
            require_once    'template-parts/content-helper.php';

            /**
             *  SDWeddingDirectory - Page Grid Managment
             *  --------------------------------
             */
            require_once    'inc/404-error-page.php';

        }

        /**
         *  Register Widget Area
         *  --------------------
         *  @link http://codex.wordpress.org/Function_Reference/register_sidebar
         *  --------------------------------------------------------------------
         */
        public static function sdweddingdirectory_widget_init(){

            /**
             *  Register : Primary Menu Sidebar
             *  -------------------------------
             */
            register_sidebar( array(

                'name'          =>  esc_attr__( 'Primary Widget Area', 'sdweddingdirectory' ),

                'id'            =>  esc_attr( 'sdweddingdirectory-widget-primary' ),

                'description'   =>  esc_attr__( 'Add widgets here to appear in your sidebar section Primary Sidebar.', 'sdweddingdirectory' ),

                'before_widget' =>  '<div id="%1$s" class="sdweddingdirectory-sidebar side-widget widget %2$s">',

                'after_widget'  =>  '</div>',

                'before_title'  =>  '<h3 class="widget-title">',

                'after_title'   =>  '</h3>',

            ) );

            /**
             *  Have Setting Option Framework Object Exists ?
             *  ---------------------------------------------
             */
            /**
             *  Register - Extra Sidebar
             *  ------------------------
             */
            register_sidebar( array(

                'name'          =>  esc_attr__( 'Secondary Widget Area', 'sdweddingdirectory' ),

                'id'            =>  esc_attr( 'sdweddingdirectory-widget-secondary' ),

                'description'   =>  esc_attr__( 'Add widgets here to appear in your sidebar section Secondary Sidebar.', 'sdweddingdirectory' ),

                'before_widget' =>  '<div class="col-md-12"><div id="%1$s" class="sdweddingdirectory-sidebar side-widget widget %2$s">',

                'after_widget'  =>  '</div></div>',

                'before_title'  =>  '<h3 class="widget-title">',

                'after_title'   =>  '</h3>',
            ) );

            /**
             *  Register - Footer Sidebar
             *  -------------------------
             */
            for( $i = absint('1'); $i <= absint('6'); $i++ ){

                register_sidebar( array(

                    'name'          =>  sprintf( esc_attr__( 'Footer Column %1$s', 'sdweddingdirectory' ),  absint( $i )  ),

                    'id'            =>  esc_attr(  SDWEDDINGDIRECTORY_FOOTER_WIDGET . absint( $i )  ),

                    'description'   =>  sprintf( esc_attr__( 'Footer Widget %1$s Column.', 'sdweddingdirectory' ), absint( $i )  ),

                    'before_widget' =>  '<div id="%1$s" class="footer-widget sdweddingdirectory-footerbar %2$s">',

                    'before_title'  =>  '<h3 class="widget-title">',

                    'after_title'   =>  '</h3>',

                    'after_widget'  =>  '</div>',

                ) );
            }
        }

        /**
         *  Test : Array Value
         *  ------------------
         */
        public static function _print_r( $a = [] ){

            /**
             *  If Is Array and have at lest one args ?
             *  ---------------------------------------
             */
            if ( self:: _is_array( $a ) ) {

                print '<pre>';  print_r( $a );  print '</pre>';
            }

            else {

                return      false;
            }
        }

        /**
         *  Check is array or not ?
         *  -----------------------
         */
        public static function _is_array( $a = [] ){

            /**
             *  If Is Array and have at lest one args ?
             *  ---------------------------------------
             */
            if ( is_array($a) && count($a) >= absint('1') && !empty($a) && $a !== null ) {

                return      true;
            }

            else {

                return      false; 
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

                return      true;
            } 

            else { 

                return      false; 
            }
        }

        /** 
         *   Return Current Page Object ID
         *   -----------------------------
         */
        public static function sdweddingdirectory_page_id(){

            global $wp_query, $post;

            /**
             *  Have page query ?
             *  -----------------
             */
            if( is_object( $wp_query ) ){

                return      absint( $wp_query->queried_object_id );
            }
        }

        /**
         *  Get Page Meta information with current page object id
         *  -----------------------------------------------------
         */
        public static function sdweddingdirectory_meta_value( $meta_key = '' ){

            /**
             *  Is Empty!
             *  ---------
             */
            if( empty( $meta_key ) ){

                return;
            }

            /**
             *  Return Meta Value
             *  -----------------
             */
            return      esc_attr(  get_post_meta( absint( self:: sdweddingdirectory_page_id() ), sanitize_key( $meta_key ), true ) );
        }

        /**
         *  Is Dashboard Page Template ?
         *  ----------------------------
         */
        public static function is_dashboard(){

            /**
             *  Is *couple* dashboard - Page Template
             *  -------------------------------------
             */
            $_condition_1   =   is_page_template( 'user-template/couple-dashboard.php' );

            /**
             *  Is *vendor* dashboard - Page Template
             *  -------------------------------------
             */
            $_condition_2   =   is_page_template( 'user-template/vendor-dashboard.php' );

            /**
             *  Is Dashboard Page Template ?
             *  ----------------------------
             */
            return      $_condition_1 || $_condition_2;
        }

        /**
         *  Page Show Sidebar Condition
         *  ---------------------------
         */
        public static function page_have_sidebar(){

            /**
             *  1. Make sure is : Home Page + Front Page
             *  ----------------------------------------
             */
            $_condition_1   =   is_front_page() && is_home();

            /**
             *  2. Make sure sidebar have at least one widget activated
             *  -------------------------------------------------------
             */
            $_condition_2   =   is_active_sidebar( esc_attr( 'sdweddingdirectory-widget-primary' ) );

            /**
             *  Make sure is home page + front page
             *  -----------------------------------
             */
            if( $_condition_1 && $_condition_2 ){

                return      false;
            }

            elseif( ! $_condition_1 ){

                return      true;
            }

            else{

                return      false;
            }
        }

        /**
         *  Check the enable library / script / style request filter available ?
         *  --------------------------------------------------------------------
         */
        public static function _load_script( $hook = '' ){

            /**
             *  Make sure hook not empty
             *  ------------------------
             */
            if( empty( $hook ) ){

                return      false;
            }

            /**
             *  Have Collection ?
             *  -----------------
             */
            $_request_collection    =   apply_filters( $hook, [] );

            /**
             *  Have Request ?
             *  --------------
             */
            if( self:: _is_array( $_request_collection ) && in_array( 'true', $_request_collection ) ){

                return      true;
            }

            else{

                return      false;
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

                return  esc_attr( SDWEDDINGDIRECTORY_THEME_VERSION );
            }

            else{

                /*
                 *  For File Save timestrap version to clear the catch auto
                 *  -------------------------------------------------------
                 *  # https://developer.wordpress.org/reference/functions/wp_enqueue_style/#comment-6386
                 *  ------------------------------------------------------------------------------------
                 */

                return      absint( filemtime(  SDWEDDINGDIRECTORY_THEME_PATH . $file ) );
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
    }

    /**
     *  Load SDWeddingDirectory Core Plugin Object
     *  ---------------------------------
     */
    SDWeddingDirectory::get_instance();
}

/**
 * Get a random banner image URI from a set of numbered variants.
 *
 * @param string $prefix Filename prefix inside assets/images/banners/.
 * @param int    $count  Number of variants.
 * @param string $ext    File extension.
 *
 * @return string
 */
if( ! function_exists( 'sdweddingdirectory_random_banner' ) ){
    function sdweddingdirectory_random_banner( $prefix, $count = 5, $ext = 'png' ){
        $variant = wp_rand( 1, max( 1, absint( $count ) ) );
        return get_theme_file_uri( "assets/images/banners/{$prefix}-{$variant}.{$ext}" );
    }
}

/**
 * SDSDWeddingDirectoryectory customizations.
 */
if( ! function_exists( 'sdsdweddingdirectoryectory_register_taxonomy_args' ) ){
    function sdsdweddingdirectoryectory_register_taxonomy_args( $args, $taxonomy ){
        if( $taxonomy === 'venue-location' ){
            $args['rewrite'] = array_merge( (array) ( $args['rewrite'] ?? [] ), [
                'slug'       => 'locations',
                'with_front' => false,
                'hierarchical' => false,
            ] );
        }

        if( $taxonomy === 'vendor-category' ){
            $args['rewrite'] = array_merge( (array) ( $args['rewrite'] ?? [] ), [
                'slug'       => 'vendors',
                'with_front' => false,
            ] );
        }

        if( $taxonomy === 'venue-type' ){
            $args['rewrite'] = array_merge( (array) ( $args['rewrite'] ?? [] ), [
                'slug'         => 'venue-types',
                'with_front'   => false,
                'hierarchical' => false,
            ] );
        }

        return $args;
    }
    add_filter( 'register_taxonomy_args', 'sdsdweddingdirectoryectory_register_taxonomy_args', 10, 2 );
}

if( ! function_exists( 'sdsdweddingdirectoryectory_maybe_flush_rewrite_rules' ) ){
    function sdsdweddingdirectoryectory_maybe_flush_rewrite_rules(){
        $version = 'sdsdweddingdirectoryectory-rewrite-v2';
        $stored  = get_option( 'sdsdweddingdirectoryectory_rewrite_version' );

        if( $stored !== $version ){
            flush_rewrite_rules();
            update_option( 'sdsdweddingdirectoryectory_rewrite_version', $version );
        }
    }
    add_action( 'init', 'sdsdweddingdirectoryectory_maybe_flush_rewrite_rules', 30 );
}

if( ! function_exists( 'sdsdweddingdirectoryectory_menu_login_redirects' ) ){
    function sdsdweddingdirectoryectory_menu_login_redirects( $atts, $item, $args ){
        if( empty( $item->url ) || ! class_exists( 'SDWeddingDirectory_Template' ) ){
            return $atts;
        }

        if( is_array( $item->classes ) && in_array( 'sd-mega', $item->classes, true ) ){
            return $atts;
        }

        $dashboard_template = SDWeddingDirectory_Template:: couple_dashboard_template();
        if( empty( $dashboard_template ) ){
            return $atts;
        }

        $item_parts = wp_parse_url( $item->url );
        $dashboard_parts = wp_parse_url( $dashboard_template );

        if( empty( $item_parts['path'] ) || empty( $dashboard_parts['path'] ) ){
            return $atts;
        }

        $item_path = untrailingslashit( $item_parts['path'] );
        $dashboard_path = untrailingslashit( $dashboard_parts['path'] );

        if( $item_path !== $dashboard_path ){
            return $atts;
        }

        parse_str( $item_parts['query'] ?? '', $query );
        if( empty( $query['dashboard'] ) ){
            return $atts;
        }

        if( class_exists( 'SDWeddingDirectory_Config' ) ){
            $atts['data-bs-toggle'] = 'modal';
            $atts['data-bs-target'] = '#' . SDWeddingDirectory_Config:: popup_id( 'couple_login' );
            $atts['data-modal-id'] = 'couple_login';
            $atts['data-modal-redirection'] = esc_url_raw( $item->url );
            $atts['data-sdweddingdirectory-login'] = 'couple';
        }

        return $atts;
    }
    add_filter( 'nav_menu_link_attributes', 'sdsdweddingdirectoryectory_menu_login_redirects', 10, 3 );
}

if( ! function_exists( 'sdsdweddingdirectoryectory_login_menu_script' ) ){
    function sdsdweddingdirectoryectory_login_menu_script(){
        $script = 'jQuery(function($){$(document).on("click","a[data-sdweddingdirectory-login=\\"couple\\"]",function(e){if(!$("body").hasClass("logged-in")){e.preventDefault();}});});';
        wp_add_inline_script( 'sdweddingdirectory-core-script', $script );
    }
    add_action( 'wp_enqueue_scripts', 'sdsdweddingdirectoryectory_login_menu_script', 20 );
}

if( ! function_exists( 'sdsdweddingdirectoryectory_enqueue_hero_search_toggle_script' ) ){
    function sdsdweddingdirectoryectory_enqueue_hero_search_toggle_script(){
        if( ! is_front_page() && ! is_page( 'vendors' ) && ! is_page( 'venues' ) ){
            return;
        }

        $script_path = get_theme_file_path( 'assets/js/hero-search-toggle.js' );

        if( empty( $script_path ) || ! file_exists( $script_path ) ){
            return;
        }

        wp_enqueue_script(
            'sd-hero-search-toggle',
            get_theme_file_uri( 'assets/js/hero-search-toggle.js' ),
            [ 'jquery' ],
            filemtime( $script_path ),
            true
        );
    }
    add_action( 'wp_enqueue_scripts', 'sdsdweddingdirectoryectory_enqueue_hero_search_toggle_script', 30 );
}

/**
 * Remove the General Setting tab from the WeddingDirectory admin panel.
 */
if( ! function_exists( 'sdweddingdirectory_remove_general_setting_tab' ) ){
    function sdweddingdirectory_remove_general_setting_tab( $tabs = [] ){

        if( ! is_array( $tabs ) || empty( $tabs ) ){
            return $tabs;
        }

        $general_label = esc_attr__( 'General Setting', 'sdweddingdirectory' );
        $general_slug  = sanitize_title( 'General Setting' );

        foreach( $tabs as $key => $tab ){

            $tab_name = isset( $tab['tab'] ) ? $tab['tab'] : '';

            if( $tab_name === $general_label || $key === $general_slug ){
                unset( $tabs[ $key ] );
            }
        }

        return $tabs;
    }
    add_filter( 'sdweddingdirectory/setting-page/tabs', 'sdweddingdirectory_remove_general_setting_tab', 999 );
}

/**
 * Replace "Home" menu label with the site logo in primary navigation.
 */
if( ! function_exists( 'sdsdweddingdirectoryectory_replace_home_menu_with_logo' ) ){
    function sdsdweddingdirectoryectory_replace_home_menu_with_logo( $items, $args ){
        if( is_admin() || ! is_array( $items ) || ! is_object( $args ) ){
            return $items;
        }

        if( empty( $args->theme_location ) || $args->theme_location !== 'primary-menu' ){
            return $items;
        }

        $logo_path = defined( 'SDW_LOGO_PATH' ) ? SDW_LOGO_PATH : 'assets/images/logo/logo_dark.svg';
        $logo_url = esc_url( get_theme_file_uri( $logo_path ) );

        if( empty( $logo_url ) ){
            return $items;
        }

        foreach( $items as $item ){
            $classes = is_array( $item->classes ) ? $item->classes : [];
            $is_home_class = in_array( 'menu-item-home', $classes, true );
            $is_home_url = ! empty( $item->url ) && untrailingslashit( $item->url ) === untrailingslashit( home_url( '/' ) );

            if( ! $is_home_class && ! $is_home_url ){
                continue;
            }

            $item->classes[] = 'sd-home-logo-item';
            $item->title = sprintf(
                '<img class="sd-home-logo-image" src="%1$s" alt="%2$s" />',
                $logo_url,
                esc_attr( get_bloginfo( 'name' ) )
            );
            break;
        }

        return $items;
    }
    add_filter( 'wp_nav_menu_objects', 'sdsdweddingdirectoryectory_replace_home_menu_with_logo', 20, 2 );
}

/**
 * Keep accessibility/title attributes clean for the Home-as-logo menu item.
 */
if( ! function_exists( 'sdsdweddingdirectoryectory_home_logo_menu_link_attrs' ) ){
    function sdsdweddingdirectoryectory_home_logo_menu_link_attrs( $atts, $item, $args ){
        if( ! is_object( $args ) || empty( $args->theme_location ) || $args->theme_location !== 'primary-menu' ){
            return $atts;
        }

        $classes = is_array( $item->classes ) ? $item->classes : [];
        if( ! in_array( 'sd-home-logo-item', $classes, true ) ){
            return $atts;
        }

        $link_class = isset( $atts['class'] ) ? $atts['class'] : '';
        $atts['class'] = trim( $link_class . ' sd-home-logo-link' );
        $atts['title'] = esc_attr__( 'Home', 'sdweddingdirectory' );
        $atts['aria-label'] = esc_attr__( 'Home', 'sdweddingdirectory' );

        return $atts;
    }
    add_filter( 'nav_menu_link_attributes', 'sdsdweddingdirectoryectory_home_logo_menu_link_attrs', 20, 3 );
}

if( ! function_exists( 'sdsdweddingdirectoryectory_home_logo_nav_body_class' ) ){
    function sdsdweddingdirectoryectory_home_logo_nav_body_class( $classes ){
        if( ! is_admin() ){
            $classes[] = 'sd-home-logo-nav';
        }
        return $classes;
    }
    add_filter( 'body_class', 'sdsdweddingdirectoryectory_home_logo_nav_body_class' );
}

/**
 * Remove the left-side header brand block.
 * The logo is rendered in the primary nav Home slot instead.
 */
if( ! function_exists( 'sdsdweddingdirectoryectory_hide_left_header_brand' ) ){
    function sdsdweddingdirectoryectory_hide_left_header_brand( $show, $args ){
        return false;
    }
    add_filter( 'sdweddingdirectory/header/show-left-brand', 'sdsdweddingdirectoryectory_hide_left_header_brand', 20, 2 );
}

/**
 *  Disable the "team" CPT archive so /team/ doesn't exist.
 *  Team members are displayed via the Elementor widget on /our-team/.
 */
if( ! function_exists( 'sdsdweddingdirectoryectory_disable_team_archive' ) ){
    function sdsdweddingdirectoryectory_disable_team_archive( $args, $post_type ){
        if( $post_type === 'team' ){
            $args['has_archive'] = false;
        }
        return $args;
    }
    add_filter( 'register_post_type_args', 'sdsdweddingdirectoryectory_disable_team_archive', 10, 2 );
}

/**
 *  Override "Venues" vendor-category term link to point to /venues/ page
 *  instead of the default /vendors/venues/ archive.
 */
if( ! function_exists( 'sdsdweddingdirectoryectory_venues_term_link' ) ){
    function sdsdweddingdirectoryectory_venues_term_link( $url, $term, $taxonomy ){
        if( $taxonomy === 'vendor-category' && $term->slug === 'venues' ){
            return home_url( '/venues/' );
        }
        return $url;
    }
    add_filter( 'term_link', 'sdsdweddingdirectoryectory_venues_term_link', 10, 3 );
}

/**
 * Disable auto-paragraph formatting for /wedding-planning/ custom HTML sections.
 */
if( ! function_exists( 'sdsdweddingdirectoryectory_wedding_planning_disable_autop' ) ){
    function sdsdweddingdirectoryectory_wedding_planning_disable_autop(){
        $page_id     = absint( get_queried_object_id() );
        $parent_id   = absint( wp_get_post_parent_id( $page_id ) );
        $is_planning = is_page( 4180 ) || $parent_id === 4180;

        if( $is_planning ){
            remove_filter( 'the_content', 'wpautop' );
        }
    }
    add_action( 'wp', 'sdsdweddingdirectoryectory_wedding_planning_disable_autop' );
}

if( ! function_exists( 'sdwd_profile_user_items' ) ){
    function sdwd_profile_user_items( $meta_key = '', $user_id = 0 ){
        $user_id = $user_id ? absint( $user_id ) : absint( get_current_user_id() );
        if( ! $user_id || $meta_key === '' ){
            return [];
        }

        $items = get_user_meta( $user_id, sanitize_key( $meta_key ), true );
        if( ! is_array( $items ) ){
            return [];
        }

        return array_values( array_unique( array_map( 'absint', $items ) ) );
    }
}

if( ! function_exists( 'sdwd_profile_user_has_item' ) ){
    function sdwd_profile_user_has_item( $meta_key = '', $post_id = 0, $user_id = 0 ){
        $post_id = absint( $post_id );
        if( ! $post_id ){
            return false;
        }

        $items = sdwd_profile_user_items( $meta_key, $user_id );
        return in_array( $post_id, $items, true );
    }
}

if( ! function_exists( 'sdwd_profile_toggle_item' ) ){
    function sdwd_profile_toggle_item( $meta_key = '', $post_id = 0, $user_id = 0 ){
        $post_id = absint( $post_id );
        $user_id = $user_id ? absint( $user_id ) : absint( get_current_user_id() );

        if( ! $post_id || ! $user_id || $meta_key === '' ){
            return false;
        }

        $items = sdwd_profile_user_items( $meta_key, $user_id );

        if( in_array( $post_id, $items, true ) ){
            $items = array_values( array_diff( $items, [ $post_id ] ) );
            update_user_meta( $user_id, sanitize_key( $meta_key ), $items );
            return false;
        }

        $items[] = $post_id;
        $items = array_values( array_unique( array_map( 'absint', $items ) ) );
        update_user_meta( $user_id, sanitize_key( $meta_key ), $items );
        return true;
    }
}

if( ! function_exists( 'sdwd_profile_valid_post_id' ) ){
    function sdwd_profile_valid_post_id( $post_id = 0, $post_type = '' ){
        $post_id = absint( $post_id );
        if( ! $post_id ){
            return false;
        }

        $post = get_post( $post_id );
        if( ! $post || ! in_array( $post->post_type, [ 'vendor', 'venue' ], true ) ){
            return false;
        }

        if( $post_type !== '' && $post->post_type !== sanitize_key( $post_type ) ){
            return false;
        }

        return true;
    }
}

if( ! function_exists( 'sdwd_profile_target_email' ) ){
    function sdwd_profile_target_email( $post_id = 0 ){
        $post_id = absint( $post_id );
        if( ! $post_id ){
            return '';
        }

        $company_email = sanitize_email( get_post_meta( $post_id, sanitize_key( 'company_email' ), true ) );
        if( is_email( $company_email ) ){
            return $company_email;
        }

        $author_id = absint( get_post_field( 'post_author', $post_id ) );
        if( $author_id ){
            $author = get_userdata( $author_id );
            if( $author && is_email( $author->user_email ) ){
                return $author->user_email;
            }
        }

        $admin_email = sanitize_email( get_option( 'admin_email' ) );
        return is_email( $admin_email ) ? $admin_email : '';
    }
}

if( ! function_exists( 'sdwd_profile_toggle_saved_ajax' ) ){
    function sdwd_profile_toggle_saved_ajax(){
        check_ajax_referer( 'sdwd_profile_actions', 'nonce' );

        if( ! is_user_logged_in() || ! current_user_can( 'read' ) ){
            wp_send_json_error( [ 'message' => esc_html__( 'Please log in to save profiles.', 'sdweddingdirectory' ) ], 401 );
        }

        $post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;
        if( ! sdwd_profile_valid_post_id( $post_id ) ){
            wp_send_json_error( [ 'message' => esc_html__( 'Invalid profile.', 'sdweddingdirectory' ) ], 400 );
        }

        $active = sdwd_profile_toggle_item( 'sdwd_saved_profiles', $post_id );
        wp_send_json_success( [ 'active' => (bool) $active ] );
    }
    add_action( 'wp_ajax_sdwd_profile_toggle_saved', 'sdwd_profile_toggle_saved_ajax' );
}

if( ! function_exists( 'sdwd_profile_toggle_hired_ajax' ) ){
    function sdwd_profile_toggle_hired_ajax(){
        check_ajax_referer( 'sdwd_profile_actions', 'nonce' );

        if( ! is_user_logged_in() || ! current_user_can( 'read' ) ){
            wp_send_json_error( [ 'message' => esc_html__( 'Please log in to mark hired.', 'sdweddingdirectory' ) ], 401 );
        }

        $post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;
        if( ! sdwd_profile_valid_post_id( $post_id ) ){
            wp_send_json_error( [ 'message' => esc_html__( 'Invalid profile.', 'sdweddingdirectory' ) ], 400 );
        }

        $active = sdwd_profile_toggle_item( 'sdwd_hired_profiles', $post_id );
        wp_send_json_success( [ 'active' => (bool) $active ] );
    }
    add_action( 'wp_ajax_sdwd_profile_toggle_hired', 'sdwd_profile_toggle_hired_ajax' );
}

if( ! function_exists( 'sdwd_profile_send_message_ajax' ) ){
    function sdwd_profile_send_message_ajax(){
        check_ajax_referer( 'sdwd_profile_actions', 'nonce' );

        if( ! is_user_logged_in() || ! current_user_can( 'read' ) ){
            wp_send_json_error( [ 'message' => esc_html__( 'Please log in to send a message.', 'sdweddingdirectory' ) ], 401 );
        }

        $post_id   = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;
        $post_type = isset( $_POST['post_type'] ) ? sanitize_key( wp_unslash( $_POST['post_type'] ) ) : '';

        if( ! sdwd_profile_valid_post_id( $post_id, $post_type ) ){
            wp_send_json_error( [ 'message' => esc_html__( 'Invalid profile target.', 'sdweddingdirectory' ) ], 400 );
        }

        $full_name   = isset( $_POST['full_name'] ) ? sanitize_text_field( wp_unslash( $_POST['full_name'] ) ) : '';
        $email       = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
        $phone       = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
        $event_date  = isset( $_POST['event_date'] ) ? sanitize_text_field( wp_unslash( $_POST['event_date'] ) ) : '';
        $message_raw = isset( $_POST['message_body'] ) ? wp_unslash( $_POST['message_body'] ) : '';
        $message     = sanitize_textarea_field( $message_raw );

        if( $full_name === '' || ! is_email( $email ) || $event_date === '' || $message === '' ){
            wp_send_json_error( [ 'message' => esc_html__( 'Please complete all required fields.', 'sdweddingdirectory' ) ], 400 );
        }

        $target_email = sdwd_profile_target_email( $post_id );
        if( ! is_email( $target_email ) ){
            wp_send_json_error( [ 'message' => esc_html__( 'No valid destination email found for this profile.', 'sdweddingdirectory' ) ], 500 );
        }

        $profile_title = get_the_title( $post_id );
        $subject = sprintf(
            /* translators: %s: profile title */
            esc_html__( 'New inquiry for %s', 'sdweddingdirectory' ),
            $profile_title
        );

        $body_lines = [
            'SDWeddingDirectory Profile Inquiry',
            '--------------------------------',
            'Profile: ' . $profile_title,
            'Type: ' . strtoupper( $post_type ),
            'Name: ' . $full_name,
            'Email: ' . $email,
            'Phone: ' . ( $phone !== '' ? $phone : 'N/A' ),
            'Event Date: ' . $event_date,
            '',
            'Message:',
            $message,
        ];

        $headers = [ 'Content-Type: text/plain; charset=UTF-8' ];
        $headers[] = 'Reply-To: ' . $full_name . ' <' . $email . '>';

        $mail_sent = wp_mail( $target_email, $subject, implode( "\n", $body_lines ), $headers );

        $message_log = get_post_meta( $post_id, sanitize_key( 'sdwd_profile_message_log' ), true );
        if( ! is_array( $message_log ) ){
            $message_log = [];
        }

        $message_log[] = [
            'timestamp'  => current_time( 'mysql' ),
            'user_id'    => absint( get_current_user_id() ),
            'name'       => $full_name,
            'email'      => $email,
            'phone'      => $phone,
            'event_date' => $event_date,
            'message'    => $message,
        ];

        if( count( $message_log ) > 50 ){
            $message_log = array_slice( $message_log, -50 );
        }

        update_post_meta( $post_id, sanitize_key( 'sdwd_profile_message_log' ), $message_log );

        if( ! $mail_sent ){
            wp_send_json_success( [ 'message' => esc_html__( 'Message saved. Email delivery is currently unavailable.', 'sdweddingdirectory' ) ] );
        }

        wp_send_json_success( [ 'message' => esc_html__( 'Message sent successfully.', 'sdweddingdirectory' ) ] );
    }
    add_action( 'wp_ajax_sdwd_profile_send_message', 'sdwd_profile_send_message_ajax' );
}
