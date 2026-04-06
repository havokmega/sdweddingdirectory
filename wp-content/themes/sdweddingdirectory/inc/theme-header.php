<?php
/**
 *  SDWeddingDirectory - Header Action / Hooks
 *  ----------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Header' ) && class_exists( 'SDWeddingDirectory' ) ){

    /**
     *  SDWeddingDirectory - Header Action / Hooks
     *  ----------------------------------
     */
    class SDWeddingDirectory_Header extends SDWeddingDirectory {

        private static $instance;

        public static function get_instance() {

            if ( null == self::$instance ) {

                self::$instance = new self();
            }

            return self::$instance;
        }

        /**
         *  Construct
         *  ---------
         */
        public function __construct() {

            /**
             *  2. SDWeddingDirectory - Header Markup
             *  -----------------------------
             */
            add_action( 'sdweddingdirectory/header', [$this, 'have_header_full_width_image' ], absint( '10' ) );

            /**
             *  2. SDWeddingDirectory - Header Markup
             *  -----------------------------
             */
            add_action( 'sdweddingdirectory/header', [$this, 'load_header_version' ], absint( '20' ) );

            /**
             *  4. SDWeddingDirectory - Custom Header
             *  -----------------------------
             */
            add_action( 'after_setup_theme', [$this, 'sdweddingdirectory_custom_header_setup' ] );

            /**
             *  5. SDWeddingDirectory - Load Header Version One
             *  ---------------------------------------
             */
            add_action( 'sdweddingdirectory_header_version', [ $this, 'sdweddingdirectory_header_version' ], absint( '10' ), absint( '1' ) );

            /**
             *  6. Add Primary Menu
             *  -------------------
             */
            add_filter( 'sdweddingdirectory/nav-menus', function( $args = [] ){

                /**
                 *  Return the list of Menu
                 *  -----------------------
                 */
                return      array_merge(  

                                /**
                                 *  Have Menu ?
                                 *  -----------
                                 */
                                $args,

                                /**
                                 *  1. Merge New Menu
                                 *  -----------------
                                 */
                                array(

                                    /**
                                     *  Primary Menu - Header Menu Location
                                     *  -----------------------------------
                                     */
                                    'primary-menu'      =>  esc_attr( 'Primary Menu' ),
                                )
                            );

            }, absint( '10' ) );
        }

        /**
         *  Custom Heade
         *  ------------
         *  @credit - http://codex.wordpress.org/Custom_Headers
         *  ---------------------------------------------------
         */
        public static function sdweddingdirectory_custom_header_setup() {

            /**
             *  SDWeddingDirectory Supported - Custom Header
             *  ------------------------------------
             */
            add_theme_support( 

                /**
                 *  1. Custom Header ID
                 *  -------------------
                 */
                esc_attr( 'custom-header' ),

                /**
                 *  2. Options
                 *  ----------
                 */
                array(

                    'default-image'          =>  '',

                    'default-text-color'     =>  '',

                    'width'                  =>  absint( '2100' ),

                    'height'                 =>  absint( '300' ),

                    'flex-height'            =>  true,

                    'wp-head-callback'       =>  [ __CLASS__, esc_attr( 'sdweddingdirectory_custom_header_style' ) ],

                    'admin-head-callback'    =>  [ __CLASS__, esc_attr( 'sdweddingdirectory_admin_header_style' )  ],

                    'admin-preview-callback' =>  [ __CLASS__, esc_attr( 'sdweddingdirectory_admin_header_image' )  ],
                )
            );
        }

        /**
         *  Have Custom Header Support ?
         *  ----------------------------
         */
        public static function sdweddingdirectory_custom_header_style() {
            
            $header_text_color  =   get_header_textcolor();

            /**
             *  Have Custom Header Support ?
             *  ----------------------------
             */
            if ( add_theme_support( 'custom-header' ) == $header_text_color ) {
                return;
            }

            /**
             *  Inline style
             *  ------------
             */
            ?><style type="text/css"><?php

                if ( 'blank' == $header_text_color ){

                    printf(    '.sdweddingdirectory-site-title {
                                    position    : absolute;
                                    clip        : rect(1px, 1px, 1px, 1px);
                                }'
                    );

                }else{

                    printf(    '.sdweddingdirectory-site-title a{
                                    color: #%1$s;
                                }',

                                /**
                                 *  1. Header Text Color
                                 *  --------------------
                                 */
                                $header_text_color
                    );
                }

            ?></style><?php
        }

        /**
         *  When user are on customizer ( admin css )
         *  -----------------------------------------
         */
        public static function sdweddingdirectory_admin_header_style() {
        ?>
            <style type="text/css">
                .appearance_page_custom-header #headimg {
                    border: none;
                }
                #headimg h1,
                #desc {
                }
                #headimg h1 {
                }
                #headimg h1 a {
                }
                #desc {
                }
                #headimg img {
                }
            </style>
        <?php
        }

        /**
         *  SDWeddingDirectory - Header Markup / Header Full Width Image
         *  ----------------------------------------------------
         */
        public static function have_header_full_width_image(){

            /**
             *  Is not couple + vendor dashboard page template
             *  ----------------------------------------------
             */
            if( !  parent:: is_dashboard() ){

                /**
                 *  SDWeddingDirectory Header Image
                 *  -----------------------
                 */
                self:: sdweddingdirectory_admin_header_image();
            }
        }

        /**
         *  Have Header Image ?
         *  -------------------
         */
        public static function sdweddingdirectory_admin_header_image(){

            /**
             *  Check Have Header Image to load
             *  -------------------------------
             */
            if ( get_header_image() ){

                /**
                 *  Update Header Image
                 *  -------------------
                 */
                printf( '<img src="%1$s" alt="%2$s" height="%3$s" width="%4$s" />',

                    /**
                     *  1. Load Header Image SRC
                     *  ------------------------
                     */
                    esc_url(    get_header_image()      ),

                    /**
                     *  2. Image Alt text
                     *  -----------------
                     */
                    esc_attr(   get_bloginfo( 'title' )     ),

                    /**
                     *  3. Image Height
                     *  ---------------
                     *  esc_attr( get_custom_header()->height )
                     *  ---------------------------------------
                     */
                    esc_attr( 'auto' ),

                    /**
                     *  4. Image Width
                     *  --------------
                     *  esc_attr( get_custom_header()->width )
                     *  --------------------------------------
                     */
                    esc_attr( '100%' )
                );
            }
        }

        /**
         *  Always load header version two
         */
        public static function load_header_version(){

            if( ! parent:: is_dashboard() ){

                get_header( 'style-2' );
            }
        }

        /**
         *  Header Version One : Left Side
         *  ------------------------------
         */
        public static function header_one_have_left_side_data(){

            $_have_data     =   '';

            $_have_menu     =   SDW_HEADER_ONE_TOP_NAV_ITEMS;

            /**
             *  Have Setting Option Set Header Top Bar Data ?
             *  ---------------------------------------------
             */
            if ( parent:: _is_array( $_have_menu ) ) {

                foreach ( $_have_menu as $value ) {
            
                    /**
                     *  Print the Top Left Side Data
                     *  ----------------------------
                     */
                    $_have_data     .=      sprintf(   '<li class="me-3 float-start"><i class="fa me-1 %1$s"></i> <span>%2$s</span> </li>',

                                                        /**
                                                         *  1. Get Icon
                                                         *  -----------
                                                         */
                                                        esc_attr( $value[ 'icon' ] ),

                                                        /**
                                                         *  2. Content - This is Editor value
                                                         *  ---------------------------------
                                                         */
                                                        strtr(

                                                            /**
                                                             *  Content
                                                             *  -------
                                                             */
                                                            $value[ 'content' ],

                                                            /**
                                                             *  Replace Args
                                                             *  ------------
                                                             */
                                                            array(

                                                                '<p>'      =>  '',

                                                                '</p>'     =>  '',
                                                            )
                                                        )
                                            );
                }

                /**
                 *  Have Left Side Data ?
                 *  ---------------------
                 */
                if( parent:: _have_data( $_have_data ) ){

                    return

                    sprintf(    '<div class="col-lg-9 col-sm-12">

                                    <ul class="top-icons float-start">%1$s</ul>

                                </div>',

                                /**
                                 *  1. Update Top Left Sidebar Data
                                 *  -------------------------------
                                 */
                                $_have_data
                    );
                }

            }else{

                return       false;
            }
        }

        /**
         *  Header Version One : Right Side
         *  -------------------------------
         */
        public static function header_one_have_right_side_data(){

            $_have_data     =   '';

            $_have_menu     =   SDW_HEADER_ONE_SOCIAL_ITEMS;

            /**
             *  Have Setting Option Set Header Top Bar Data ?
             *  ---------------------------------------------
             */
            if ( parent:: _is_array( $_have_menu ) ) {

                foreach ( $_have_menu as $value ) {
            
                    /**
                     *  Print the Top Left Side Data
                     *  ----------------------------
                     */
                    $_have_data     .=          sprintf(   '<li>

                                                                <a href="%2$s" target="_blank"><i class="fa %1$s"></i></a>

                                                            </li>',

                                                            /**
                                                             *  1. Get Icon
                                                             *  -----------
                                                             */
                                                            esc_attr( $value[ 'icon' ] ), 

                                                            /**
                                                             *  2. Get Link
                                                             *  -----------
                                                             */
                                                            esc_url( $value[ 'link' ] ) 
                                                );
                }

                /**
                 *  Have Left Side Data ?
                 *  ---------------------
                 */
                if( parent:: _have_data( $_have_data ) ){

                    return

                    sprintf(    '<div class="col-lg-3 col-sm-12 col-lg">

                                    <div class="social-icons">

                                        <ul class="list-unstyled"> %1$s </ul>

                                    </div>

                                </div>',

                                /**
                                 *  1. Update Top Left Sidebar Data
                                 *  -------------------------------
                                 */
                                $_have_data
                    );
                }

            }else{

                return       false;
            }
        }

        /**
         *  SDWeddingDirectory - Theme Logo / Brand Name
         *  -------------------------------------------
         */
        public static function sdweddingdirectory_brand( $args = [] ){

            $args   =   wp_parse_args( $args, [
                'image_class'   =>  esc_attr( 'logo' ),
                'print'         =>  true,
            ] );

            $logo_path  =   defined( 'SDW_LOGO_PATH' ) ? SDW_LOGO_PATH : 'assets/images/logo/logo_dark.svg';
            $logo_url   =   get_theme_file_uri( $logo_path );
            $logo_alt   =   esc_attr__( 'Brand Site Logo', 'sdweddingdirectory' );

            $_sdweddingdirectory = sprintf( '<a class="navbar-brand" href="%1$s" rel="home">
                                <img class="%2$s" alt="%3$s" src="%4$s" />
                            </a>',
                                esc_url( home_url( '/' ) ),
                                esc_attr( $args['image_class'] ),
                                $logo_alt,
                                esc_url( $logo_url )
                        );

            if( $args['print'] ){

                print   apply_filters( 'sdweddingdirectory_brand', $_sdweddingdirectory );

            }else{

                return  apply_filters( 'sdweddingdirectory_brand', $_sdweddingdirectory );
            }
        }

        /**
         *  SDWeddingDirectory - Header Version One Logo
         *  ------------------------------------
         */
        public static function have_brand( $args = [] ){

            /**
             *  Have args ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){


                $args   =   wp_parse_args( $args, array(

                                'print'     =>      false

                            ) );

                /**
                 *  Load SDWeddingDirectory - Brand ( Logo / Plain Text )
                 *  ---------------------------------------------
                 */
                printf(    '<div class="d-flex %2$s">

                                %1$s 

                            </div>', 

                            /**
                             *  1. SDWeddingDirectory - Brand Logo
                             *  --------------------------
                             */
                            SDWeddingDirectory_Header:: sdweddingdirectory_brand( $args ),

                            /**
                             *  2. Is Dashboard ?
                             *  -----------------
                             */
                            parent:: is_dashboard()

                            ?   esc_attr( join( ' ', array_map(

                                    /**
                                     *  Sanitize Html Class
                                     *  -------------------
                                     */
                                    esc_attr( 'sanitize_html_class' ), 

                                    /**
                                     *  SDWeddingDirectory - Custom Class
                                     *  -------------------------
                                     */
                                    array( 

                                        esc_attr( 'align-items-center' ), 

                                        esc_attr( 'mx-auto' )
                                    )

                                ) ) )

                            :  sanitize_html_class( 'me-3' )
                );
            }
        }

        /**
         *  4. SDWeddingDirectory - Load Header Version One
         *  ---------------------------------------
         */
        public static function sdweddingdirectory_header_version( $args = [] ){

            /**
             *  Have Args ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Have Args ?
                 *  -----------
                 */
                $args           =       wp_parse_args( 

                                            /**
                                             *  1. Have Args ?
                                             *  --------------
                                             */
                                            $args,

                                            /**
                                             *  2. Default Args
                                             *  ---------------
                                             */
                                            array(

                                                'layout'        =>      absint( '1' )
                                            )
                                        );
                /**
                 *  Extract Values
                 *  --------------
                 */
                extract( $args );

                /**
                 *  Allow themes/customizations to disable the left brand block.
                 */
                $show_left_brand = apply_filters( 'sdweddingdirectory/header/show-left-brand', true, $args );

                /**
                 *  Is Layout ONE ?
                 *  ---------------
                 */
                if( $layout     ==      absint( '1' ) ){

                    /**
                     *  1. Load Logo
                     *  ------------
                     */
                    if( $show_left_brand ){
                        self:: have_brand( $args );
                    }

                    /**
                     *  3. Have Menu ?
                     *  --------------
                     */
                    self:: sdweddingdirectory_menu();

                    /**
                     *  2. Have Login Button ?
                     *  ----------------------
                     */
                    self:: have_user_buttons( $args );
                }

                /**
                 *  Is Layout Two ?
                 *  ---------------
                 */
                if( $layout     ==      absint( '2' ) ){

                    /**
                     *  1. Load Logo
                     *  ------------
                     */
                    if( $show_left_brand ){
                        self:: have_brand( $args );
                    }

                    /**
                     *  3. Have Menu ?
                     *  --------------
                     */
                    self:: sdweddingdirectory_menu();

                    /**
                     *  2. Have Login Button ?
                     *  ----------------------
                     */
                    self:: have_user_buttons( $args );
                }
            }
        }

        /**
         *  2. Update the Buttons for login ( Couple + Vendor )
         *  ---------------------------------------------------
         */
        public static function have_user_buttons( $args = [] ){

            /**
             *  Have Args ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Merge Default Args
                 *  ------------------
                 */
                $args   =   wp_parse_args(  

                                /**
                                 *  1. Have Args
                                 *  ------------
                                 */
                                $args,

                                /**
                                 *  2. Default Args
                                 *  ---------------
                                 */
                                array(

                                    'layout'        =>      absint( '1' )
                                )
                            );

                /**
                 *  Extrac Args
                 *  -----------
                 */
                extract( $args );

                /**
                 *  Vendor + Couple Registration / Login Model Popup
                 *  ------------------------------------------------
                 */
                do_action( 'sdweddingdirectory/header/button', $args );
            }
        }

        /**
         *  3. Have Menu ?
         *  --------------
         */
        public static function sdweddingdirectory_menu(){

            // /**
            //  *  Page ID
            //  *  -------
            //  */
            // $page_id            =       absint( parent:: sdweddingdirectory_page_id() );

            // $page_menu_id       =       get_post_meta(  $page_id, sanitize_key( 'header_menu' ), true );

            // $menu_slug          =       esc_attr( 'primary-menu' );

            // /**
            //  *  Select Menu
            //  *  -----------
            //  */
            // if( ! empty( $page_menu_id ) ){

            //     /**
            //      *  Menu Object
            //      *  -----------
            //      */
            //     $menu_object        =       get_term_by( 'id', $page_menu_id, 'nav_menu' );

            //     /**
            //      *  Get Menu List
            //      *  -------------
            //      */
            //     if( ! empty( $menu_object ) ){

            //         $menu_slug      =       esc_attr( $menu_object->slug );
            //     }
            // }

            ?>

            <!-- Toggle Button Start -->
            <button class="navbar-toggler x collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false"
                aria-label="Toggle navigation">

                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>

            </button>
            <!-- Toggle Button End -->

            <!-- Topbar Request Quote End -->
            <div class="collapse navbar-collapse" id="navbarCollapse" 

                data-hover="dropdown" data-animations="slideInUp slideInUp slideInUp slideInUp">

                <?php

                    /**
                     *  Have Primary Menu ?
                     *  -------------------
                     */
                    if( has_nav_menu( 'primary-menu' ) ){

                            /**
                             *  Load Menu
                             *  ---------
                             */
                            wp_nav_menu( array(

                                'theme_location'    =>  esc_attr( 'primary-menu' ),

                                'depth'             =>  absint( '4' ),

                                'container'         =>  false,

                                'container_class'   =>  false,

                                'container_id'      =>  false,

                                'menu_class'        =>  'navbar-nav mx-auto',

                                'walker'            =>  new     SDSDWeddingDirectoryectory_Navwalker(),
                          ) );
                    }

                ?>

            </div><!-- / Topbar Request Quote End -->
            <?php
        }
    }   

    /**
     *  SDWeddingDirectory - Header Markup Object
     *  ---------------------------------
     */
    SDWeddingDirectory_Header:: get_instance();
}
