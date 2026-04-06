<?php
/**
 *  SDWeddingDirectory - Scripts
 *  --------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Theme_Scripts' ) && class_exists( 'SDWeddingDirectory' ) ){

    /**
     *  SDWeddingDirectory - Scripts
     *  --------------------
     */
    class SDWeddingDirectory_Theme_Scripts extends SDWeddingDirectory {

        /**
         *  Member Variable
         *  ---------------
         *  @var instance
         *  -------------
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
             *  Load SDWeddingDirectory - Style + Script Here
             *  -------------------------------------
             */
            add_action( 'wp_enqueue_scripts', [ $this, 'sdweddingdirectory_scripts_markup' ], absint( '999' ) );

            /**
             *  SDWeddingDirectory - Icon Manager Filter
             *  --------------------------------
             */
            add_filter( 'sdweddingdirectory_icon_library', [ $this, 'add_new_icon_library' ] );
        }

        /**
         *   Add New Font Library link
         *   -------------------------
         */
        public static function add_new_icon_library( $args = [] ){

            return      array_merge(

                            /**
                             *  Have args ?
                             *  -----------
                             */
                            $args,

                            /**
                             *  Add New font
                             *  ------------
                             */
                            array(

                                'fontawesome'       =>  esc_url(    SDWEDDINGDIRECTORY_THEME_LIBRARY . 'fontawesome-4.7.0/font-awesome.min.css'   ),

                                'theme-icon'        =>  esc_url(    SDWEDDINGDIRECTORY_THEME_LIBRARY . 'theme-icon/style.css'   ),
                            )
                        );
        }

        /**
         *  2. Enqueue scripts and styles
         *  -----------------------------
         */
        public static function sdweddingdirectory_scripts_markup(){

            global $wp_styles;

            /**
             *  1. Load Bootstrap Framework ( MINFY + UNMINFY - Both file included in this package )
             *  ------------------------------------------------------------------------------------
             */
            self:: bootstrap();

            /**
             *  2. Load Fontawesome Icon
             *  ------------------------
             */
            self:: fontawesome();

            /**
             *  3. Load SDWeddingDirectory Icon
             *  -----------------------
             */
            self:: sdweddingdirectory_icon();

            /**
             *  4. Load Owl Carousel Library
             *  ----------------------------
             */
            self:: owl_carousel();

            /**
             *  6. Bootstrap Menu Load
             *  ----------------------
             */
            self:: bootstrap_menu();

            /**
             *  7. Load SDWeddingDirectory - Theme Style
             *  --------------------------------
             */
            self:: theme_style();

            /**
             *  8. Load SDWeddingDirectory - Theme Script
             *  ---------------------------------
             */
            self:: theme_script();

            /**
             *  8a. Load Vendor / Venue Profile Assets
             *  --------------------------------------
             */
            self:: profile_page_assets();

            /**
             *  9. Load Comment Reply Script Loaded in page
             *  -------------------------------------------
             */
            if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {

                wp_enqueue_script( 'comment-reply' );
            }

            /**
             *  10. Register local fonts in SDWeddingDirectory Theme
             *  ---------------------------------------------------
             */
            self:: sdweddingdirectory_font();
        }

        /**
         *  1. Load Bootstrap Framework ( MINFY + UNMINFY - Both File is included in this package )
         *  ---------------------------------------------------------------------------------------
         */
        public static function bootstrap(){

            /**
             *  Load Style
             *  ----------
             */
            wp_enqueue_style( 

                /**
                 *  1. File Name
                 *  ------------
                 */
                esc_attr( 'bootstrap' ),

                /**
                 *  2. File Path
                 *  ------------
                 */
                esc_url(    SDWEDDINGDIRECTORY_THEME_LIBRARY . 'bootstrap-5.0.2/css/bootstrap.min.css'    ),

                /**
                 *  3. Have Dependancy ?
                 *  --------------------
                 */
                [ 'owl-carousel' ],

                /**
                 *  4. Bootstrap - Library Version
                 *  ------------------------------
                 */
                esc_attr( parent:: _file_version( 'assets/library/bootstrap-5.0.2/css/bootstrap.min.css' ) ),

                /**
                 *  5. Load All Media
                 *  -----------------
                 */
                esc_attr( 'all' )
            );

            /**
             *  Bootstrap FrameWork JS File ( MIN FILE + UNMINFY FILE IS INCLUDED IN LIBRARY FOLDER )
             *  -------------------------------------------------------------------------------------
             */
            wp_enqueue_script( 

                /**
                 *  1. File Name
                 *  ------------
                 */
                esc_attr( 'bootstrap' ), 

                /**
                 *  2. File Path
                 *  ------------
                 */
                esc_url(   SDWEDDINGDIRECTORY_THEME_LIBRARY . 'bootstrap-5.0.2/js/bootstrap.bundle.min.js'   ),

                /**
                 *  3. Load After "JQUERY" Load
                 *  ---------------------------
                 */
                array( 'jquery' ),

                /**
                 *  4. Bootstrap - Library Version
                 *  ------------------------------
                 */
                esc_attr( parent:: _file_version( 'assets/library/bootstrap-5.0.2/js/bootstrap.bundle.min.js' ) ),

                /**
                 *  5. Load in Footer
                 *  -----------------
                 */
                true 
            );
        }

        /**
         *  2. Load : Fontawesome 4.7.0 - ICON
         *  ----------------------------------
         */
        public static function fontawesome(){

          /**
           *  Load Fontawesome Icon Library
           *  -----------------------------
           */
          wp_enqueue_style(

                  /**
                   *  1. File Name
                   *  ------------
                   */
                  esc_attr(   'fontawesome'   ),

                  /**
                   *  2. File Path
                   *  ------------
                   */
                  esc_url(    SDWEDDINGDIRECTORY_THEME_LIBRARY . 'fontawesome-4.7.0/font-awesome.min.css'   ), 

                  /**
                   *  3. Dependency ( Load after bootstrap framework )
                   *  ------------------------------------------------
                   */
                  array( 'bootstrap' ), 

                  /**
                   *  4. Fontawesome Library Version
                   *  ------------------------------
                   */
                  esc_attr( parent:: _file_version( 'assets/library/fontawesome-4.7.0/font-awesome.min.css' ) ),

                  /**
                   *  Media ALL
                   *  ---------
                   */
                  esc_attr(   'all'  )
            );
        }

        /**
         *  2. Load SDWeddingDirectory Icon
         *  -----------------------
         */
        public static function sdweddingdirectory_icon(){

          /**
           *  Load Fontawesome Icon Library
           *  -----------------------------
           */
          wp_enqueue_style(

                  /**
                   *  1. File Name
                   *  ------------
                   */
                  esc_attr(   'theme-icon'   ),

                  /**
                   *  2. File Path
                   *  ------------
                   */
                  esc_url(    SDWEDDINGDIRECTORY_THEME_LIBRARY . 'theme-icon/style.css'   ),

                  /**
                   *  3. Dependency ( Load after bootstrap framework )
                   *  ------------------------------------------------
                   */
                  array( 'bootstrap' ), 

                  /**
                   *  4. Fontawesome Library Version
                   *  ------------------------------
                   */
                  esc_attr( parent:: _file_version( 'assets/library/theme-icon/style.css' ) ),

                  /**
                   *  Media ALL
                   *  ---------
                   */
                  esc_attr(   'all'  )
            );
        }

        /**
         *  3. Bootstrap Menu Load
         *  ----------------------
         */
        public static function bootstrap_menu(){

            /**
             *  Bootstrap Menu File
             *  -------------------
             */
            wp_enqueue_style( 

              /**
               *  File Name
               *  ---------
               */
              esc_attr(   'bootstrap_menu'  ), 

              /**
               *  File Path
               *  ---------
               */
              esc_url(    SDWEDDINGDIRECTORY_THEME_LIBRARY . 'bootstrap-menu/stylesheet.css'   ), 

              /**
               *  Load after bootstrap library load
               *  ---------------------------------
               */
              array( 'bootstrap' ),

              /**
               *  Bootstrap Menu version
               *  ----------------------
               */
              esc_attr( parent:: _file_version( 'assets/library/bootstrap-menu/stylesheet.css' ) ),

              /**
               *  Load in All Media
               *  -----------------
               */
              esc_attr(   'all'  )

            );

            /**
             *  Bootstrap Menu Script
             *  ---------------------
             */
            wp_enqueue_script( 

                /**
                 *  File Name
                 *  ---------
                 */
                esc_attr(   'bootstrap_menu'  ), 

                /**
                 *  File Path
                 *  ---------
                 */
                esc_url(    SDWEDDINGDIRECTORY_THEME_LIBRARY . 'bootstrap-menu/script.js'   ), 

                /**
                 *  Dependency
                 *  ----------
                 */
                array( 'jquery', 'bootstrap' ),

                /**
                 *  Menu Version ?
                 *  --------------
                 */
                esc_attr( parent:: _file_version( 'assets/library/bootstrap-menu/script.js' ) ),

                /**
                 *  Load in Footer ?
                 *  ----------------
                 */
                true 
            );
        }

        /**
         *  3. Load Owl Carousel Library
         *  ----------------------------
         */
        public static function owl_carousel(){

           /**
            *  Load Owl Carouse Script
            *  -----------------------
            */
            wp_enqueue_style(

                /**
                 *  File Name
                 *  ---------
                 */
                esc_attr(   'owl-carousel'   ),

                /**
                 *  File path
                 *  ---------
                 */
                esc_url(    SDWEDDINGDIRECTORY_THEME_LIBRARY . 'owlcarousel/owl.carousel.min.css'   ),

                /**
                 *  Dependency
                 *  ----------
                 */
                [],

                /**
                 *  File Version
                 *  ------------
                 */
                esc_attr( parent:: _file_version( 'assets/library/owlcarousel/owl.carousel.min.css' ) ),

                /**
                 *  Load Media in All ?
                 *  -------------------
                 */
                esc_attr(   'all'    )
            );

            /**
             *  Load Owl carouse script
             *  -----------------------
             */
            wp_enqueue_script( 

                  /**
                   *  File Name
                   *  ---------
                   */
                  esc_attr(   'owl-carousel'   ),

                  /**
                   *  File Path
                   *  ---------
                   */
                  esc_url(    SDWEDDINGDIRECTORY_THEME_LIBRARY . 'owlcarousel/owl.carousel.min.js'   ), 

                  /**
                   *  Dependency
                   *  ----------
                   */
                  array( 'jquery' ),

                  /**
                   *  Owl carouse library version ?
                   *  -----------------------------
                   */
                  esc_attr( parent:: _file_version( 'assets/library/owlcarousel/owl.carousel.min.js' ) ),

                  /**
                   *  Load in Footer ?
                   *  ----------------
                   */
                  true 
            );
        }

        /**
         *  5. Load SDWeddingDirectory - Theme Style
         *  --------------------------------
         */
        public static function theme_style(){

            /**
             *  Load SDWD Foundation — design-system tokens, shared components
             *  --------------------------------------------------------------
             */
            wp_enqueue_style(
                esc_attr( 'sdwd-foundation' ),
                esc_url( SDWEDDINGDIRECTORY_THEME_DIR . 'assets/css/sdwd-foundation.css' ),
                array( 'bootstrap' ),
                esc_attr( parent:: _file_version( 'assets/css/sdwd-foundation.css' ) ),
                esc_attr( 'all' )
            );

            /**
             *  Load SDWeddingDirectory - Style
             *  -----------------------
             */
            wp_enqueue_style(

                  /**
                   *  File Name
                   *  ---------
                   */
                  esc_attr(  'global-style'  ),

                  /**
                   *  File Path
                   *  ---------
                   */
                  esc_url( SDWEDDINGDIRECTORY_THEME_DIR . 'assets/css/global.css' ),

                  /**
                   *  Load SDWeddingDirectory - Style After Foundation + Bootsrap
                   *  -----------------------------------------------------------
                   */
                  array( 'sdwd-foundation' ),

                  /**
                   *  SDWeddingDirectory - Theme Version
                   *  --------------------------
                   */
                  esc_attr( parent:: _file_version( 'assets/css/global.css' ) ),

                  /**
                   *  Load Media in All
                   *  -----------------
                   */
                  esc_attr(  'all'  )
            );

            /**
             *  Load SDWeddingDirectory - Theme Style + Script
             *  --------------------------------------
             */
            if( ! is_singular( 'website' ) ){

                /**
                 *  Load SDWeddingDirectory - Style
                 *  -----------------------
                 */
                wp_enqueue_style(

                    /**
                     *  File Name
                     *  ---------
                     */
                    esc_attr(  'sdweddingdirectory-custom-theme-style'  ),

                    /**
                     *  File Path
                     *  ---------
                     */
                    esc_url(    SDWEDDINGDIRECTORY_THEME_DIR . 'assets/css/theme-style.css'    ),

                    /**
                     *  Load SDWeddingDirectory - Style After Bootsrap Library
                     *  ----------------------------------------------
                     */
                    array( 'global-style' ),

                    /**
                     *  SDWeddingDirectory - Theme Version
                     *  --------------------------
                     */
                    esc_attr( parent:: _file_version( 'assets/css/theme-style.css' ) ),

                    /**
                     *  Load Media in All
                     *  -----------------
                     */
                    esc_attr(  'all'  )
                );
            }

            /**
             *  SDWeddingDirectory - style.css Loaded
             *  -----------------------------
             */
            wp_enqueue_style(

                  /**
                   *  File Name
                   *  ---------
                   */
                  esc_attr(  'sdweddingdirectory-parent-style'  ),

                  /**
                   *  File Path
                   *  ---------
                   */
                  esc_url(    SDWEDDINGDIRECTORY_THEME_DIR . 'style.css'    ),

                  /**
                   *  Load SDWeddingDirectory - Style After Bootsrap Library
                   *  ----------------------------------------------
                   */
                  ! is_singular( 'website' )

                  ?     [ 'sdweddingdirectory-custom-theme-style' ]

                  :     [ 'global-style' ],

                  /**
                   *  SDWeddingDirectory - Theme Version
                   *  --------------------------
                   */
                  esc_attr( parent:: _file_version( 'style.css' ) ),

                  /**
                   *  Load Media in All
                   *  -----------------
                   */
                  esc_attr(  'all'  )
            );

            /**
             *  Have inline Data ?
             *  ------------------
             */
            $_have_inline_style     =   implode( '', apply_filters( 'sdweddingdirectory/inline-style', [] ) );

            /**
             *  Enforce local font stack only by removing injected font-family rules.
             */
            $_have_inline_style = preg_replace( '/font-family\s*:\s*[^;]+;?/i', '', $_have_inline_style );

            /**
             *  Insert Inline Style
             *  -------------------
             */
            if( parent:: _have_data( $_have_inline_style ) ){

                wp_add_inline_style(

                    /**
                     *  Insert Inline Style
                     *  -------------------
                     */
                    esc_attr(  'sdweddingdirectory-parent-style'  ),

                    /**
                     *  Insert Inline Style
                     *  -------------------
                     */
                    preg_replace('/\s+/', ' ', $_have_inline_style  )
                );
            }
        }

        /**
         *  6. Load SDWeddingDirectory - Theme Script
         *  ---------------------------------
         */
        public static function theme_script(){

            /**
             *  Load SDWeddingDirectory Script
             *  ----------------------
             */
            wp_enqueue_script(

                /**
                 *  1. File Name
                 *  ------------
                 */
                esc_attr(   'sdweddingdirectory-theme-script'   ), 

                /** 
                 *  2. File Link
                 *  ------------
                 */
                esc_url(    SDWEDDINGDIRECTORY_THEME_DIR . 'assets/js/theme-script.js'   ), 

                /**
                 *  3. Dependency
                 *  -------------
                 */
                array( 'jquery', 'bootstrap' ),

                /**
                 *  4. SDWeddingDirectory - Theme Version
                 *  -----------------------------
                 */
                esc_attr( parent:: _file_version( 'assets/js/theme-script.js' ) ),

                /**
                 *  5. Load in Footer ?
                 *  -------------------
                 */
                true 
            );
        }

        /**
         *  6a. Load Singular Profile Assets
         *  --------------------------------
         */
        public static function profile_page_assets(){

            if( is_singular( 'vendor' ) || is_singular( 'venue' ) ){

                wp_enqueue_style(
                    esc_attr( 'sdweddingdirectory-profile-style' ),
                    esc_url( SDWEDDINGDIRECTORY_THEME_DIR . 'assets/css/sd-profile.css' ),
                    [ 'global-style' ],
                    esc_attr( parent::_file_version( 'assets/css/sd-profile.css' ) ),
                    esc_attr( 'all' )
                );

                wp_enqueue_script(
                    esc_attr( 'sdweddingdirectory-profile-sidebar' ),
                    esc_url( SDWEDDINGDIRECTORY_THEME_DIR . 'assets/js/sd-profile-sidebar.js' ),
                    [ 'jquery' ],
                    esc_attr( parent::_file_version( 'assets/js/sd-profile-sidebar.js' ) ),
                    true
                );

                wp_enqueue_script(
                    esc_attr( 'sdweddingdirectory-availability-calendar' ),
                    esc_url( SDWEDDINGDIRECTORY_THEME_DIR . 'assets/js/sd-availability-calendar.js' ),
                    [ 'jquery' ],
                    esc_attr( parent::_file_version( 'assets/js/sd-availability-calendar.js' ) ),
                    true
                );

                wp_localize_script(
                    esc_attr( 'sdweddingdirectory-availability-calendar' ),
                    esc_attr( 'sdAvailability' ),
                    [
                        'ajaxUrl'   => esc_url_raw( admin_url( 'admin-ajax.php' ) ),
                        'vendorId'  => absint( get_the_ID() ),
                    ]
                );
            }
        }
            
        /**
         *  7. Register local fonts in SDWeddingDirectory Theme
         *  --------------------------------------------------
         */
        public static function sdweddingdirectory_font(){

            wp_enqueue_style(
                esc_attr( 'sdweddingdirectory-fonts' ),
                esc_url_raw( self:: sdweddingdirectory_google_fonts() ),
                [],
                esc_attr( parent::_file_version( 'assets/css/local-fonts.css' ) ),
                esc_attr( 'all' )
            );
        }

        /**
         *  8. Editor Style
         *  ---------------
         */
        public static function sdweddingdirectory_editor_style(){

            /**
             *  SDWeddingDirectory - Editor Style
             *  -------------------------
             */
            wp_enqueue_style(

                /**
                *  File Name
                *  ---------
                */
                esc_attr(  'sdweddingdirectory-editor-style'  ),

                /**
                *  File Path
                *  ---------
                */
                esc_url(

                    trailingslashit( SDWEDDINGDIRECTORY_THEME_DIR ) . 'assets/css/editor-style.css'
                ),

                /**
                *  Load SDWeddingDirectory - Style After Bootsrap Library
                *  ----------------------------------------------
                */
                array( 'bootstrap', 'sdweddingdirectory-parent-style' ),

                /**
                *  SDWeddingDirectory - Theme Version
                *  --------------------------
                */
                esc_attr( parent:: _file_version( 'assets/css/editor-style.css' ) ),

                /**
                *  Load Media in All
                *  -----------------
                */
                esc_attr(  'all'  )
            );
        }

        /**
         *  SDWeddingDirectory - Local Font Family URL
         *  -----------------------------------------
         */
        public static function sdweddingdirectory_google_fonts() {
        
            return esc_url_raw(
                trailingslashit( SDWEDDINGDIRECTORY_THEME_DIR ) . 'assets/css/local-fonts.css'
            );
        }
    }

    /**
     *  SDWeddingDirectory - Script Object Call
     *  -------------------------------
     */
    SDWeddingDirectory_Theme_Scripts::get_instance();
}
