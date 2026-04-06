<?php
/**
 *  -------------------------------------
 *  OptionTree ( Theme Option Framework )
 *  -------------------------------------
 *  @author : By - Derek Herman
 *  ---------------------------
 *  @link - https://wordpress.org/plugins/option-tree/
 *  --------------------------------------------------
 *  Fields : https://github.com/valendesigns/option-tree-theme/blob/master/inc/theme-options.php
 *  --------------------------------------------------------------------------------------------
 *  SDWeddingDirectory Setting Option ( Framework )
 *  ---------------------------------------
 */

if ( ! class_exists( 'SDWeddingDirectory_FrameWork' ) && class_exists( 'SDWeddingDirectory_Admin_Settings' ) ) {

    /**
     *  SDWeddingDirectory Setting Option ( Framework )
     *  ---------------------------------------
     */
    class SDWeddingDirectory_FrameWork extends SDWeddingDirectory_Admin_Settings {

        /**
         *  Member Variable
         *  ---------------
         */
        private static $instance;

        /**
         *  Initiator
         *  ---------
         */
        public static function get_instance(){

            if( ! isset( self::$instance ) ){

                self::$instance = new self;
            }

            return  self::$instance;
        }

        /**
         *  Construct
         *  ---------
         */
        public function __construct() {

            /**
             *  Load one by one shortcode file
             *  ------------------------------
             */
            foreach ( glob( plugin_dir_path( __FILE__ ) . '/*/*.php' ) as $file ) {
           
                require_once $file;
            }

            /**
             *  Load Constant
             *  -------------
             */
            $this->constant();

            /**
             *  SDWeddingDirectory - Framework Filters Merge
             *  ------------------------------------
             */
            add_action( 'admin_init', [ $this, 'sdweddingdirectory_create_theme_option' ] );

            /**
             *  Opiton Tree - New Layout Filter
             *  -------------------------------
             */
            add_filter( 'ot_show_new_layout', '__return_false' );

            /**
             *  Option Tree - Textarea Filter
             *  -----------------------------
             */
            add_filter( 'ot_override_forced_textarea_simple', '__return_true' );

            /**
             *  Create Option Array to Convert Option Tree Framework Array
             *  ----------------------------------------------------------
             */
            add_filter( 'sdweddingdirectory/ot-tree/options', [ $this, 'sdweddingdirectory_option_tree_option' ], absint( '10' ), absint( '1' ) );

            /**
             *  Option Tree - Included Font Family List
             *  ---------------------------------------
             */
            add_filter( 'ot_google_fonts_api_key', 

                /**
                 *  Return : Google Font Family API
                 *  -------------------------------
                 */
                function() {

                    return  esc_attr( 'AIzaSyANMeyvv5WTJrbMuIMP4FZ_rbUaUt16Sfw' );
                }
            );

            /**
             *  Option Tree - Version
             *  ---------------------
             */
            add_filter( 'ot_header_version_text', 

                /**
                 *  Return Values
                 *  -------------
                 */
                function() {

                    return   esc_attr( 'SDWeddingDirectory' );
                }
            );

            /**
             *  Option Tree - Page Title
             *  ------------------------
             */
            add_filter( 'ot_theme_options_page_title', 

                [ $this, 'sdweddingdirectory_filter_page_title' ], 

                absint('10'), 

                absint('2')
            );

            /**
             *  Option Page - Menu Title
             *  ------------------------
             */
            add_filter( 'ot_theme_options_menu_title',

                [ $this, 'sdweddingdirectory_filter_page_title' ], 

                absint('10'),

                absint('2')
            );

            /**
             *  Option Tree - Upload Media Popup Button Text
             *  --------------------------------------------
             */
            add_filter( 'ot_upload_text', 

                [ $this, 'sdweddingdirectory_filter_upload_text' ], 

                absint('10'), 

                absint('2')
            );

            /**
             *  Option Tree - Header List of links
             *  ----------------------------------
             */
            add_filter( 'ot_header_list', [ $this, 'sdweddingdirectory_ot_header_list' ] );

            /**
             *  Option Tree - Page Header Style
             *  -------------------------------
             */
            add_action('admin_head', [ $this, 'sdweddingdirectory_ot_custom_option_style' ] );

            /**
             *  Opiton Tree - add Font family list
             *  ----------------------------------
             */
            add_filter('ot_recognized_font_families', 

                [ $this, 'sdweddingdirectory_filter_ot_recognized_font_families' ], 

                absint( '10' ), 

                absint( '2' ) 
            );

            /**
             *  Inline Style added in filter
             *  ----------------------------
             */
            add_filter( 'sdweddingdirectory/inline-style', [ $this, 'sdweddingdirectory_option_tree_stylesheet_print' ] );
        }

        /**
         *  Option list convert to option tree
         *  ----------------------------------
         */
        public static function sdweddingdirectory_option_tree_option( $args = [] ){

            /**
             *  Suppoter
             *  --------
             */
            $handler = [];

            /**
             *  Make sure have data
             *  -------------------
             */
            if( parent:: _is_array( $args ) ){

                foreach( $args as $key => $value ){

                    $handler[]    =   array(

                        'value'     =>  $key,

                        'label'     =>  $value,

                        'src'       =>  '',
                    );
                }
            }

            /**
             *  Return Collection
             *  -----------------
             */
            return  $handler;
        }

        /**
         *  Define Values
         *  -------------
         */
        private function constant(){

            /**
             *  SDWeddingDirectory - Documentation Link
             *  -------------------------------
             */
            define( 'SDWEDDINGDIRECTORY_DOCS', esc_url( 'sdweddingdirectory.net/documentation/' ) );

            /**
             *  SDWeddingDirectory - Feedback Form
             *  --------------------------
             */
            define( 'SDWEDDINGDIRECTORY_FEEDBACK', esc_url( 'forms.gle/x1UBGVRnQ696412EA') );

            /**
             *  SDWeddingDirectory - Recommend Hosting
             *  ------------------------------
             */
            define( 'SDWEDDINGDIRECTORY_HOSTING', esc_url( 'www.siteground.com/recommended?referrer_id=7401201' ) );

            /**
             *  SDWeddingDirectory - Support
             *  --------------------
             */
            define( 'SDWEDDINGDIRECTORY_SUPPORT', esc_url( 'themeforest.net/user/wp-organic?ref=wp-organic#contact' ) );

            /**
             *  SDWeddingDirectory Product link
             *  -----------------------
             */
            define( 'SDWEDDINGDIRECTORY_PRODUCT', esc_url( 'themeforest.net/item/sdweddingdirectory-directory-venue-wordpress-theme/34095894' ) );
        }

        /**
         *  SDWeddingDirectory - Register Section
         *  -----------------------------
         */
        public static function sdweddingdirectory_register_framework_section( $args = [] ){

            /**
             *  Have Args ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract Args
                 *  ------------
                 */
                extract( $args );

                /**
                 *  Section Name here
                 *  -----------------
                 */
                $section            =   esc_attr( $section_name );

                $have_setting       =   apply_filters(

                                            /**
                                             *  1. Filter Name
                                             *  --------------
                                             */
                                            sprintf( 'sdweddingdirectory_framework_{%1$s}_settings', esc_attr( $section_id ) ), 

                                            /**
                                             *  2. Default Args
                                             *  ---------------
                                             */
                                            [], 

                                            /**
                                             *  3. Section ID : Manage Settings
                                             *  -------------------------------
                                             */
                                            esc_attr( $section_id )
                                        );

                /**
                 *  Filter : Create Section
                 *  -----------------------
                 */
                return      array(

                                /**
                                 *  Create Section
                                 *  --------------
                                 */
                                'sections'   => array(

                                    array(

                                        'id'        =>  esc_attr( $section_id ),

                                        'title'     =>  esc_attr( $section ),
                                    ),
                                ),

                                /**
                                 *  Have section data ?
                                 *  -------------------
                                 */
                                'settings'      =>      parent:: _is_array( $have_setting ) 

                                                        ?   $have_setting

                                                        :   array(

                                                                array(

                                                                    'id'          =>    esc_attr( $section_id . '-info' ),

                                                                    'label'       =>    esc_attr( $section ),

                                                                    'desc'        =>    esc_attr( $section ),

                                                                    'type'        =>    esc_attr( 'textblock-titled' ),

                                                                    'section'     =>    esc_attr( $section_id ),
                                                                )
                                                            )
                            );
            }
        }

        /**
         *  SDWeddingDirectory - Framework
         *  ----------------------
         */
        public static function sdweddingdirectory_create_theme_option() {

            if ( ! function_exists('ot_settings_id') || !is_admin() ) {
                
                return false;
            }

            $saved_settings     =   get_option( ot_settings_id(), [] );

            $custom_settings    =   apply_filters( 'sdweddingdirectory_framework', [] );

            /**
             *  allow settings to be filtered before saving
             *  -------------------------------------------
             */
            $custom_settings    =   apply_filters( ot_settings_id() . '_args', $custom_settings);

            /**
             *  settings are not the same update the DB
             *  ---------------------------------------
             */
            if ($saved_settings !== $custom_settings) {

                update_option( ot_settings_id(), $custom_settings );
            }

            /**
             *  Lets OptionTree know the UI Builder is being overridden
             *  -------------------------------------------------------
             */
            global $ot_has_sdweddingdirectory_theme_options;

            $ot_has_sdweddingdirectory_theme_options = true;
        }

        public static function sdweddingdirectory_filter_page_title() {

            return      wp_kses( 

                            /**
                             *  1. Translation String
                             *  ---------------------
                             */
                            esc_attr__( 'SDWeddingDirectory Options', 'sdweddingdirectory' ), 

                            /**
                             *  2. Protocol
                             *  -----------
                             */
                            array(

                                'a'     =>  array(

                                                'href'      =>  [],

                                                'title'     =>  []
                                            )
                            )
                        );
        }

        public static function sdweddingdirectory_filter_upload_text() {

            return      wp_kses( 

                            /**
                             *  1. Translation String
                             *  ---------------------
                             */
                            esc_attr__( 'Send to SDWeddingDirectory', 'sdweddingdirectory' ), 

                            /**
                             *  2. Protocol
                             *  -----------
                             */
                            array(

                                'a'     =>  array(

                                                'href'      =>  [],

                                                'title'     =>  []
                                            )
                            )
                        );
        }

        public static function sdweddingdirectory_ot_header_list() {

            printf(    '<!-- <li class="theme_link right"><a href="%2$s" target="_blank">%1$s</a></li> -->

                        <li class="theme_link right"><a href="%4$s" target="_blank">%3$s</a></li>

                        <li class="theme_link right"><a href="%6$s" target="_blank">%5$s</a></li>

                        <li class="theme_link right"><a href="%10$s" target="_blank" id="feedback">%9$s</a></li>

                        <li class="theme_link right"><a href="%8$s" target="_blank" id="rate_us">%7$s</a></li>',

                        /**
                         *  1. Translation Ready String
                         *  ---------------------------
                         */
                        esc_attr__('Live Documentation', 'sdweddingdirectory'),

                        /**
                         *  2. Documentation Link
                         *  ---------------------
                         */
                        esc_url( SDWEDDINGDIRECTORY_DOCS ),

                        /**
                         *  3. Translation Ready String
                         *  ---------------------------
                         */
                        esc_attr__( 'Recommended Hosting', 'sdweddingdirectory' ),

                        /**
                         *  4. Hosting Link
                         *  ---------------
                         */
                        esc_url( SDWEDDINGDIRECTORY_HOSTING ),

                        /**
                         *  5. Translation Ready String
                         *  ---------------------------
                         */
                        esc_attr__( 'Support', 'sdweddingdirectory' ),

                        /**
                         *  6. Support Page Link
                         *  --------------------
                         */
                        esc_url( SDWEDDINGDIRECTORY_SUPPORT ),

                        /**
                         *  7. Translation Ready String
                         *  ---------------------------
                         */
                        esc_attr__( 'Rate Product!', 'sdweddingdirectory' ),

                        /**
                         *  8. Rate Us On Product
                         *  ---------------------
                         */
                        esc_url( SDWEDDINGDIRECTORY_PRODUCT ),

                        /**
                         *  9. Translation Ready String
                         *  ---------------------------
                         */
                        esc_attr__('Feedback', 'sdweddingdirectory'),

                        /**
                         *  10. Feedback Form Link
                         *  ----------------------
                         */
                        esc_url( SDWEDDINGDIRECTORY_FEEDBACK )
            );
        }

        public static function sdweddingdirectory_ot_custom_option_style() {

            $style = '';

            $style .= '#option-tree-header li a { color: #ffffff; }';

            $style .= '.hide-color-picker {display: block !important;}';

            $style .= '.wp-picker-container input[type=text].wp-color-picker{ width: auto; }';

            $style .= '#option-tree-header li.theme_link { line-height: 31px; }';

            $style .= '#option-tree-header li.theme_link a{ margin-right: 15px; }';

            $style .= '#option-tree-header .theme_link.right { float: right; }';

            $style .= '#option-tree-header li a{ cursor: pointer; color: #ffffff; }#option-tree-header li a:hover{ color: yellow;  cursor: pointer; }';

            $style .= '#option-tree-header li a#rate_us{color: #333333;background: yellow;border-radius: 2px;padding: 2px 6px; cursor: pointer; }';

            print '<style>' . $style . '</style>';
        }

        public static function sdweddingdirectory_filter_ot_recognized_font_families($array, $field_id) {

            $array['helveticaneue'] = "'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif";

            ot_fetch_google_fonts( true, false );

            $ot_google_fonts    =   wp_list_pluck(

                                        get_theme_mod(  'ot_google_fonts', []  ),

                                        esc_attr( 'family' )
                                    );

            $array = array_merge(   $array, $ot_google_fonts  );

            if ( get_option( 'sdwd_typekit_id', '' ) ) {

                $typekit_fonts  =   trim( get_option( 'sdwd_typekit_fonts', '' ), ' ' );

                $typekit_fonts  =   explode(',', $typekit_fonts);

                $array          =   array_merge($array, $typekit_fonts);
            }

            if( parent:: _is_array( $array ) ){

                foreach ($array as $font => $value) {

                    $thb_font_array[$value]     =   $value;
                }

                return $thb_font_array;
            }

            return;
        }

        public static function sdweddingdirectory_option_tree_value($class, $property, $value) {

            if (sdweddingdirectory_option($value) != '' && $class != '') {

                return sprintf('%1$s{ %2$s:%3$s; }',

                    // 1
                    $class,

                    // 2
                    $property,

                    // 3
                    sdweddingdirectory_option($value)
                );
            }

            return;
        }

        public static function sdweddingdirectory_option_tree_link_value($class, $color, $key) {

            if ($key == '' or $class == '' or $color == '') {
                return;
            }

            $_get_result = '';

            if (sdweddingdirectory_option($key)) {

                if (isset(sdweddingdirectory_option($key)['link'])) {
                    $_get_result .= sprintf('%1$s{ %2$s: %3$s; }', $class, $color, sdweddingdirectory_option($key)['link']);
                }
                if (isset(sdweddingdirectory_option($key)['hover'])) {
                    $_get_result .= sprintf('%1$s:hover{ %2$s: %3$s; }', $class, $color, sdweddingdirectory_option($key)['hover']);
                }
                if (isset(sdweddingdirectory_option($key)['active'])) {
                    $_get_result .= sprintf('%1$s:active{ %2$s: %3$s; }', $class, $color, sdweddingdirectory_option($key)['active']);
                }
                if (isset(sdweddingdirectory_option($key)['visited'])) {
                    $_get_result .= sprintf('%1$s:visited{ %2$s: %3$s; }', $class, $color, sdweddingdirectory_option($key)['visited']);
                }
                if (isset(sdweddingdirectory_option($key)['focus'])) {
                    $_get_result .= sprintf('%1$s:focus{ %2$s: %3$s; }', $class, $color, sdweddingdirectory_option($key)['focus']);
                }
                if (isset(sdweddingdirectory_option($key)['bg'])) {
                    $_get_result .= sprintf('%1$s{ background: %3$s; }', $class, $color, sdweddingdirectory_option($key)['bg']);
                }
                if (isset(sdweddingdirectory_option($key)['text'])) {
                    $_get_result .= sprintf('%1$s{ color: %3$s; }', $class, $color, sdweddingdirectory_option($key)['text']);
                }
                if (isset(sdweddingdirectory_option($key)['border'])) {
                    $_get_result .= sprintf('%1$s{ border-color: %3$s; }', $class, $color, sdweddingdirectory_option($key)['border']);
                }

                return $_get_result;
            }
        }

        public static function sdweddingdirectory_option_tree_typography_value($args) {

            $style = '';

            if ( sdweddingdirectory_option( $args ) != '' && is_array( sdweddingdirectory_option( $args ) ) ) {

                foreach ( sdweddingdirectory_option( $args ) as $key => $value ) {

                    if ($key === 'background-image' && $value != '') {

                        $style .= sprintf( '%1$s:url(%2$s);', $key, $value );

                    } elseif ($key === 'font-color' && $value != '') {

                        $style .= sprintf(' %1$s:%2$s;', esc_html('color'), $value );

                    } else {

                        if ($value != '') {

                            $style .= sprintf( '%1$s:%2$s;', $key, $value );
                        }
                    }
                }
            }

            return $style;
        }

        public static function sdweddingdirectory_option_tree_stylesheet_print( $args = [] ) {

            $style      =   '';

            $_data      =   apply_filters( 'sdweddingdirectory_framework', [] );

            /**
             *  SDWeddingDirectory - FrameWork Filter to Get Collection  for applying on front page
             *  ---------------------------------------------------------------------------
             */
            if ( parent::_is_array( $_data ) ) {

                $_color_customize = $_link_customize = $_theme_color = [];

                foreach ( $_data as $index => $index_value ) {

                    foreach ( $index_value as $key => $value ) {

                        if ( isset( $value['type'] ) ) {

                            if ( $value['type']         ==  esc_attr( 'colorpicker-opacity' ) ) {

                                $_color_customize[]      =  $value;

                            } elseif (  $value['type']  ==  esc_attr( 'link-color' ) ) {

                                $_link_customize[]       =  $value;

                            } elseif ($value['type']    ==  esc_attr( 'theme-color' ) ) {

                                $_theme_color[]          =  $value;
                            }
                        }
                    }
                }

                /**
                 *  Color Setting
                 *  -------------
                 */
                if ( parent::_is_array( $_color_customize ) ) {

                    foreach ( $_color_customize as $index => $index_value ) {

                        if ( isset( $index_value ['object'] ['class'] ) ) {

                            $style  .=  self::sdweddingdirectory_option_tree_value(

                                            $index_value['object']['class'],

                                            $index_value['object']['property'],

                                            $index_value['id']
                                        );
                        }

                        /**
                         *  Have Media Query CSS ?
                         *  ----------------------
                         */
                        if( isset( $index_value[ 'object' ][ 'media' ] ) ){

                            foreach ( $index_value[ 'object' ][ 'media' ] as $grid => $grid_value ) {

                                /**
                                 *  Applying Media CSS
                                 *  ------------------
                                 */
                                $style  .=  sprintf( '@media only screen and (max-width: %1$spx) { %2$s }',

                                                /**
                                                 *  1. Get Grid
                                                 *  -----------
                                                 */
                                                $grid,

                                                /**
                                                 *  2. Grid Applying CSS
                                                 *  --------------------
                                                 */
                                                self:: sdweddingdirectory_option_tree_value(

                                                    $grid_value[ 'class' ],

                                                    esc_attr( $index_value[ 'object' ][ 'property' ] ),

                                                    esc_attr( $index_value[ 'id' ] )
                                                )
                                            );
                            }
                        }
                    }
                }

                /**
                 *  Link Color Setting
                 *  ------------------
                 */
                if ( parent::_is_array( $_link_customize ) ) {

                    foreach ($_link_customize as $index => $index_value) {

                        $style  .=      self::sdweddingdirectory_option_tree_link_value(

                                            $index_value['object']['class'],

                                            $index_value['object']['property'],

                                            $index_value['id']
                                        );
                    }
                }

                /**
                 *  Theme Color Palette
                 *  -------------------
                 */
                $_root_var  =  $_root_style  =  '';

                if ( parent::_is_array( $_theme_color ) ) {

                    foreach (  $_theme_color as $index => $index_value  ) {

                        extract( $index_value );

                        $_color_palette   =   sdweddingdirectory_option( $id );

                        if( parent:: _is_array( $_color_palette ) ){

                            foreach( $_color_palette as $var => $code ){

                                $_root_style  .=   sprintf( '%1$s:%2$s;', $var, $code );
                            }
                        }
                    }

                    if( parent:: _have_data( $_root_style ) ){

                        $_root_var      =   sprintf( ':root{%1$s}', $_root_style );
                    }
                }

                // Typography now controlled by global.css - inline typography styles removed.
            }

            $main_style     =   '';

            // Custom CSS option removed - use theme stylesheets for overrides.

            if( parent:: _have_data( $_root_var ) ){

                $style      .=      $_root_var;
            }

            if( parent:: _have_data( $style ) ){

                /**
                 *  Inline Style added in filter
                 *  ----------------------------
                 */
                return  array_merge( $args, array( 'setting-option-style' => preg_replace('/\s+/', ' ', $style  ) ) );

            }else{

                return  array_merge( $args, array( 'setting-option-style' => '' ) );
            }
        }

        /**
         *  Admin Emails in Setting Options
         *  -------------------------------
         */
        public static function sdweddingdirectory_setting_option_admin_emails( $email_title = '', $have_section = '' ){

            /**
             *  Admin Email Setting
             *  -------------------
             */
            $args   =    [];

            /**
             *  Have Data ?
             *  -----------
             */
            if( parent:: _have_data( $email_title ) ){

                $args[]  =  array(

                                'id'        =>  esc_attr( 'admin-email-' . $email_title ),

                                'label'     =>  esc_attr__( 'Admin Email Enable ?', 'sdweddingdirectory' ),

                                'std'       =>  esc_attr( 'off' ),

                                'type'      =>  esc_attr( 'on-off' ),

                                'desc'      =>  esc_attr__( 'Admin wish to get email when this email sending from user ?', 'sdweddingdirectory' ),

                                'section'   =>  esc_attr( $have_section ),
                            );

                $args[]  =  array(

                                'id'        =>  esc_attr( 'admin-email-id-' . $email_title ),

                                'label'     =>  esc_attr__( 'Admin Receive Email ID ?', 'sdweddingdirectory' ),

                                'desc'      =>  sprintf( 'Please select admin email id to receive this email by default i will send <code>%1$s</code> email id.',

                                                    get_bloginfo( 'admin_email' )

                                                ),

                                'std'       =>  esc_attr( 'off' ),

                                'type'      =>  esc_attr( 'radio' ),

                                'section'   =>  esc_attr( $have_section ),

                                'condition' =>  esc_attr( 'admin-email-' . $email_title ) . esc_attr( ':is(on)' ),

                                'choices'   =>  apply_filters( 'sdweddingdirectory/ot-tree/options',

                                                    parent:: _is_array( sdweddingdirectory_option( 'admin_emails' ) )

                                                    ?   array_column( sdweddingdirectory_option( 'admin_emails' ), esc_attr( 'email_id' ) )

                                                    :   array( get_bloginfo( 'admin_email' ) )
                                                )
                            );

            }

            /**
             *  Return Email Setting
             *  --------------------
             */
            return  $args;
        }
    }

    /**
     *  SDWeddingDirectory - Framework
     *  ----------------------
     */
    SDWeddingDirectory_FrameWork::get_instance();
}

/**
 *  -------------------
 *  Theme Color Palatte
 *  -------------------
 */
if ( ! function_exists( 'ot_type_theme_color' ) ) {

  function ot_type_theme_color( $args = [] ) {

    /* turns arguments array into variables */
    extract( $args );

    /* verify a description */
    $has_desc = $field_desc ? true : false;

    /* format setting outer wrapper */
    echo '<div class="format-setting type-link-color ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

      /* description */
      echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

      /* format setting inner wrapper */
      echo '<div class="format-setting-inner">';

        /* allow fields to be filtered */
        $ot_recognized_link_color_fields = apply_filters( 'ot_recognized_link_color_fields', array(

            '--sdweddingdirectory-color-orange'             => _x( 'Orange', 'color picker', 'option-tree' ),

            '--sdweddingdirectory-color-light-orange'       => _x( 'Light Organge', 'color picker', 'option-tree' ),

            '--sdweddingdirectory-color-rgba-orange'        => _x( 'Rgba Orange', 'color picker', 'option-tree' ),

            '--sdweddingdirectory-color-cyan'               => _x( 'Cyan', 'color picker', 'option-tree' ),

            '--sdweddingdirectory-color-dark-cyan'          => _x( 'Dark Cyan', 'color picker', 'option-tree' ),

            '--sdweddingdirectory-color-light-cyan'         => _x( 'Light Cyan', 'color picker', 'option-tree' ),

            '--sdweddingdirectory-color-rgba-cyan'          => _x( 'Cyan RGBA', 'color picker', 'option-tree' ),

            '--sdweddingdirectory-color-teal'               => _x( 'Teal', 'color picker', 'option-tree' ),

            '--sdweddingdirectory-color-skin'               => _x( 'Skin', 'color picker', 'option-tree' ),

        ), $field_id );

        /* build link color fields */
        foreach( $ot_recognized_link_color_fields as $type => $label ) {

          if ( array_key_exists( $type, $ot_recognized_link_color_fields ) ) {
            
            echo '<div class="option-tree-ui-colorpicker-input-wrap">';

              echo '<label for="' . esc_attr( $field_id ) . '-picker-' . $type . '" class="option-tree-ui-colorpicker-label">' . esc_attr( $label ) . '</label>';

              /* colorpicker JS */
              echo '<script>jQuery(document).ready(function($) { OT_UI.bind_colorpicker("' . esc_attr( $field_id ) . '-picker-' . $type . '"); });</script>';

              /* set color */
              $color = isset( $field_value[ $type ] ) ? esc_attr( $field_value[ $type ] ) : '';
              
              /* set default color */
              $std = isset( $field_std[ $type ] ) ? 'data-default-color="' . $field_std[ $type ] . '"' : '';

              /* input */
              echo '<input autocomplete="off" type="text" name="' . esc_attr( $field_name ) . '[' . $type . ']" id="' . esc_attr( $field_id ) . '-picker-' . $type . '" value="' . $color . '" class="hide-color-picker ot-colorpicker-opacity ' . esc_attr( $field_class ) . '" ' . $std . ' />';

            echo '</div>';

          }

        }

      echo '</div>';

    echo '</div>';

  }

}
