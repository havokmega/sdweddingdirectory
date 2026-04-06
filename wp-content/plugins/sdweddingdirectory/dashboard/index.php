<?php
/**
 *  SDWeddingDirectory - Dashboard Setup
 *  ----------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Dashboard' ) && class_exists( 'SDWeddingDirectory_Config' ) ){

    /**
     *  SDWeddingDirectory - Dashboard Setup
     *  ----------------------------
     */
    class SDWeddingDirectory_Dashboard extends SDWeddingDirectory_Config{

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
             *  1. Dashboard Script
             *  -------------------
             */
            add_action( 'wp_enqueue_scripts', array( $this, 'sdweddingdirectory_script' ), absint( '1200' ) );

            /**
             *  2. Setup Dashboard
             *  ------------------
             */
            add_action( 'sdweddingdirectory/dashboard', [$this, 'sdweddingdirectory_dashboard_markup'], absint( '10' ) );

            /**
             *  3. Add Dashboard Menu Filter
             *  ----------------------------
             */
            add_filter( 'wp_nav_menu_items', [ $this, 'dashboard_menu_filter' ], absint( '10' ), absint( '2' ) );

            /**
             *  Couple Dashboard Managment
             *  --------------------------
             */
            require_once        'couple-file/index.php';

            /**
             *  Vendor Dashboard Managment
             *  --------------------------
             */
            require_once        'vendor-file/index.php';
        }

        /**
         *  1. Dashboard Script
         *  -------------------
         */
        public static function sdweddingdirectory_script(){

            /**
             *  Script run When vendor + couple dashboard page 
             *  ----------------------------------------------
             */
            if( is_page_template( 'user-template/couple-dashboard.php' ) || is_page_template( 'user-template/vendor-dashboard.php' ) ){

                /**
                 *  Slug
                 *  ----
                 */
                $slug       =       sanitize_title( __CLASS__ );

                /**
                 *  Load Fontawesome Icon Library
                 *  -----------------------------
                 */
                wp_enqueue_style(

                    /**
                     *  1. File Name
                     *  ------------
                     */
                    esc_attr(   $slug   ),

                    /**
                     *  2. File Path
                     *  ------------
                     */
                    esc_url(    plugin_dir_url( __FILE__ )    .   'style.css'   ),

                    /**
                     *  3. Dependency ( Load after bootstrap framework )
                     *  ------------------------------------------------
                     */
                    array( 'global-style', 'bootstrap' ),

                    /**
                     *  4. Version
                     *  ----------
                     */
                    esc_attr( parent:: _file_version( plugin_dir_path( __FILE__ ) . 'style.css' ) ),

                    /**
                     *  Media ALL
                     *  ---------
                     */
                    esc_attr(   'all'  )
                );

                /**
                 *  Load Library
                 *  ------------
                 */
                wp_enqueue_script( 

                    /**
                     *  File Name
                     *  ---------
                     */
                    esc_attr(   $slug   ),

                    /**
                     *  File Link
                     *  ---------
                     */
                    esc_url(    plugin_dir_url( __FILE__ )     .   'script.js'  ),

                    /**
                     *  Have Dependancy ?
                     *  -----------------
                     */
                    array( 'jquery', 'bootstrap' ),

                    /**
                     *  File Version ?
                     *  --------------
                     */
                    esc_attr( parent:: _file_version( plugin_dir_path( __FILE__ ) . 'script.js' ) ),

                    /**
                     *  Load in Footer ?
                     *  ----------------
                     */
                    true
                );

                /**
                 *  Load Library Condition
                 *  ----------------------
                 */
                add_filter( 'sdweddingdirectory/enable-script/media-upload', function( $args = [] ){

                    return  array_merge( $args, [

                                'dashboard'   =>  true

                            ] );
                } );
            }
        }

        /**
         *  2. Setup Dashboard
         *  ------------------
         */
        public static function sdweddingdirectory_dashboard_markup(){

            /**
             *  2.1 - Load Dashboard Header
             *  ---------------------------
             */
            self:: dashboard_header();

            /**
             *  2.2 - Load Sidebar
             *  ------------------
             */
            self:: sidebar_menu();


            ?><div class="body-content dashboard-body"><div class="main-contaner"><?php

                /**
                 *  2.3 - Main Page of Body Container
                 *  -------------------------------
                 */
                self:: main_body_container();

            ?></div></div><?php
        }

        /**
         *  3. Add Dashboard Menu Filter
         *  ----------------------------
         *  
         *  @link - https://wordpress.stackexchange.com/questions/163555/how-to-target-specific-wp-nav-menu-in-function
         *  
         */
        public static function dashboard_menu_filter( $items, $args ) {

            /**
             *  Make sure this page is not dashboard
             *  ------------------------------------
             */
            if( parent:: is_dashboard() && false ){

                return  $items;
            }

            $_is_admin      =   is_user_logged_in() && current_user_can('administrator');

            $_is_vendor     =   is_user_logged_in() && parent:: is_vendor();

            $_is_couple     =   is_user_logged_in() && parent:: is_couple();

            /**
             *  Is Couple or Vendor + Is Login ?
             *  --------------------------------
             */
            if( ( $_is_couple || $_is_vendor ) && ! $_is_admin ){

                /**
                 *  Is Primary Menu ?
                 *  -----------------
                 */
                if( $args->theme_location == esc_attr( 'primary-menu' ) ){

                    /**
                     *  Vendor Menu added in primary menu
                     *  ---------------------------------
                     */
                    if( $_is_vendor ){

                        $items .= 

                        sprintf( '<li class="nav-item dropdown user-profile">%1$s</li>', 

                            /**
                             *  1. Vendor Top Menu
                             *  ------------------
                             */
                            SDWeddingDirectory_Vendor_Menu:: vendor_dashboard_menu( 'top' ) 
                        );
                    }

                    /**
                     *  Couple Menu added in primary menu
                     *  ---------------------------------
                     */
                    if( $_is_couple ){

                        $items .= 

                        sprintf( '<li class="nav-item dropdown user-profile">%1$s</li>', 

                            /**
                             *  1. Vendor Top Menu
                             *  ------------------
                             */
                            SDWeddingDirectory_Couple_Menu:: couple_dashboard_menu( 'top' )
                        );
                    }
                }
            }

            return $items;
        }

        /**
         *   2.1 Load Dashboard Header
         *   -------------------------
         */
        public static function dashboard_header(){

            ?>
            <header class="dashboard-header-version-one header-version-two">

                <div class="fixed-top header-anim">
                    <!-- Main Navigation Start -->
                    <nav class="navbar navbar-expand-xl">
                        <div class="container-fluid text-nowrap bdr-nav px-0">

                            <a href="javascript:" class="sidebar-toggle" data-dashboard-tools-toggle="offcanvas">
                                <i class="fa fa-bars"></i>
                            </a>

                            <!-- Toggle Button Start -->
                            <button class="navbar-toggler x collapsed" type="button" data-dashboard-tools-toggle="offcanvas-mobile" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <!-- Toggle Button End -->
                
                            <!-- Main Navigation Start -->
                            <div class="collapse navbar-collapse offcanvas-collapse-mobile" id="navbarCollapse" data-hover="dropdown" data-animations="slideInUp slideInUp slideInUp slideInUp">

                                <?php

                                  if( has_nav_menu( 'primary-menu' ) ){

                                        wp_nav_menu( array(

                                              'theme_location'    =>    esc_attr( 'primary-menu' ),

                                              'depth'             =>    absint( '4' ),

                                              'container'         =>    false,

                                              'container_class'   =>    false,

                                              'container_id'      =>    false,

                                              'menu_class'        =>    'navbar-nav mx-auto',

                                              'walker'            =>    new     SDSDWeddingDirectoryectory_Navwalker(),
                                        ) );
                                  }

                                ?>

                            </div>
                            <!-- Main Navigation Start -->

                        </div>
                    </nav>
                    <!-- Main Navigation Start -->
                </div>

            </header>
            <?php
        }

        /**
         *  2.2 - Load Sidebar
         *  ------------------
         */
        public static function sidebar_menu(){

            if( parent:: is_vendor() ){

                echo SDWeddingDirectory_Vendor_Menu:: vendor_dashboard_menu( 'side' );
            }

            if( parent:: is_couple() ){

                echo SDWeddingDirectory_Couple_Menu:: couple_dashboard_menu( 'side' );
            }
        }

        /**
         *  2.3 - Main Page of Body Container
         *  -------------------------------
         */
	        public static function main_body_container(){

	            /**
	             *  Have Dashboard
	             *  --------------
	             */
	            $_have_dashboard    =   isset( $_GET['dashboard'] ) && !empty( $_GET['dashboard'] );
	            $_dashboard_page    =   isset( $_GET['dashboard'] ) ? sanitize_key( $_GET['dashboard'] ) : '';

	            /**
	             *  Is Vendor
	             *  ---------
	             */
	            if( parent:: is_vendor() ){

	                $_dashboard_page = parent:: vendor_dashboard_entry_page( wp_get_current_user(), $_dashboard_page );

	                /**
	                 *  Vendor Page Action
	                 *  ------------------
                 */
	                do_action( 'sdweddingdirectory/vendor-dashboard', [

	                    'layout'        =>      absint( '1' ),

	                    'page'          =>      esc_attr( $_dashboard_page ),

	                    'post_id'       =>      parent:: post_id()

	                ] );
            }

            /**
             *  Is Couple
             *  ---------
             */
            elseif( parent:: is_couple() ){

                if( empty( $_dashboard_page ) ){
                    $_dashboard_page = esc_attr( 'couple-dashboard' );
                }

                /**
                 *  Couple Page Action
                 *  ------------------
                 */
                do_action( 'sdweddingdirectory/couple-dashboard', [

                    'layout'        =>      absint( '1' ),

                    'page'          =>      esc_attr( $_dashboard_page ),

                    'post_id'       =>      parent:: post_id()

                ] );
            }

            /**
             *  Else
             *  ----
             */
            else{

                /**
                 *  Not a vendor or couple — redirect to login.
                 */
                wp_safe_redirect( wp_login_url( home_url( $_SERVER['REQUEST_URI'] ) ) );
                exit;
            }
        }

        /**
         *  Dashboard Header
         *  ----------------
         */
        public static function dashboard_page_header( $title, $description = '' ){

            /**
             *  Multiple Plugin Need Modify so i create new handler function
             *  ------------------------------------------------------------
             */
            self:: dashboard_page_info( [

                'name'              =>      $title,

                'description'       =>      $description

            ] );
        }

        /**
         *  Dashboard Header
         *  ----------------
         */
        public static function dashboard_page_info( $args = [] ){

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

                    'name'              =>      '',

                    'description'       =>      '',

                    'echo'              =>      true,

                    'handler'           =>      ''

                ] ) );

                /**
                 *  Content
                 *  -------
                 */
                $handler        =       sprintf('<div class="section-title">%1$s %2$s</div>',

                                            /**
                                             *  1. Title
                                             *  --------
                                             */
                                            !   empty( $name )

                                            ?   sprintf('<h2>%1$s</h2>', $name )

                                            :   '',

                                            /**
                                             *  2. Description
                                             *  --------------
                                             */
                                            !   empty( $description )

                                            ?   sprintf('<p>%1$s</p>', $description )

                                            :   ''
                                        );

                /**
                 *  Print Enable ?
                 *  --------------
                 */
                if(  $echo  ){

                    print       $handler;
                }

                else{

                    return      $handler;
                }
            }
        }
    }

    /**
     *  SDWeddingDirectory - Dashboard Setup
     *  ----------------------------
     */
    SDWeddingDirectory_Dashboard::get_instance();
}
