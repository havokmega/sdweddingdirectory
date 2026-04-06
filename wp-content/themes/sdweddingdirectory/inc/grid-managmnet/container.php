<?php
/**
 *  SDWeddingDirectory - Page Container Managment
 *  -------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Container_Managment' ) && class_exists( 'SDWeddingDirectory_Grid_Managment' ) ) {

    /**
     *  SDWeddingDirectory - Page Container Managment
     *  -------------------------------------
     */
    class SDWeddingDirectory_Container_Managment extends SDWeddingDirectory_Grid_Managment {

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
             *  Container Header Load
             *  ---------------------
             */
            add_action( 'sdweddingdirectory_main_container', [ $this, 'sdweddingdirectory_main_container_markup' ] );

            /**
             *  Container End Action
             *  --------------------
             */
            add_action( 'sdweddingdirectory_main_container_end', [ $this, 'sdweddingdirectory_main_container_end_markup' ] );

            /**
             *  Container Start
             *  ---------------
             */
            add_action( 'sdweddingdirectory_container_start', [ $this, 'sdweddingdirectory_container_start' ] );

            /**
             *  Container End
             *  -------------
             */
            add_action( 'sdweddingdirectory_container_end', [ $this, 'sdweddingdirectory_container_end' ] );
        }

        /**
         *   Start Content Wrapper Structure
         *   -------------------------------
         */
        public static function sdweddingdirectory_main_container_markup(){

            global $post, $wp_query, $page;

            /**
             *  1. Load Header Version
             *  ----------------------
             */
            get_header();

            /**
             *  2. Page header banner
             *  ---------------------
             */
            do_action( 'sdweddingdirectory/page-header-banner' );

            /**
             *  3. Page wrapper 
             *  ---------------
             */
            do_action( 'sdweddingdirectory_container_start' );

            /**
             *  4. Main page of content container
             *  ---------------------------------
             */
            printf( '<section id="primary" class="%1$s">',

                    /**
                     *  Have Class ?
                     *  ------------
                     */
                    esc_attr( join( ' ', self:: sdweddingdirectory_get_primary_class( '' ) ) )
            );
        }

        /**
         *  Container Width Body Class
         *  --------------------------
         */
        public static function sdweddingdirectory_get_primary_class( $class = [] ) {

            /**
             *  array of class names
             *  --------------------
             */
            $classes        =   [];

            /**
             *  default class for content area
             *  ------------------------------
             */
            $classes[]  =   sanitize_html_class( 'content-area' );

            /**
             *  primary base class
             *  ------------------
             */
            $classes[]  =   sanitize_html_class( 'primary' );

            /**
             *  Is Container
             *  ------------
             */
            if( parent:: is_container() || parent:: is_container_fluid() ){

                /**
                 *  Full Width if no sidebar or sidebar widget empty!
                 *  -------------------------------------------------
                 */
                if( empty( parent:: sdweddingdirectory_sidebar() ) || parent:: is_no_sidebar() ){

                    /**
                     *  Full Width Column Grid
                     *  ----------------------
                     */
                    $classes = array_merge( $classes, parent:: get_grid( '1' ) );
                }

                /**
                 *  Make sure sidebar have widget
                 *  -----------------------------
                 */
                elseif ( ! empty( parent:: sdweddingdirectory_sidebar() ) && is_active_sidebar( parent:: sdweddingdirectory_sidebar() ) ){

                    /**
                     *  Full Width Column Grid
                     *  ----------------------
                     */
                    $classes = array_merge( $classes, parent:: get_grid( '3' ) );
                }

                /**
                 *  Normally Full Width
                 *  -------------------
                 */
                else{

                    /**
                     *  Sidebar Column Grid
                     *  -------------------
                     */
                    $classes = array_merge( $classes, parent:: get_grid( '1' ) );
                }
            }

            /**
             *  Make sure it's singular page
             *  ----------------------------
             */
            elseif( is_singular() ){

                /**
                 *  Sidebar Column Grid
                 *  -------------------
                 */
                $classes = array_merge( $classes, parent:: get_grid( '1' ) );
            }

            /**
             *  Option Tree Not Install
             *  -----------------------
             */
            else{

                if ( ! empty( parent:: sdweddingdirectory_sidebar() ) && is_active_sidebar( parent:: sdweddingdirectory_sidebar() ) ){

                    /**
                     *  Full Width Column Grid
                     *  ----------------------
                     */
                    $classes = array_merge( $classes, parent:: get_grid( '3' ) );
                }

                /**
                 *  Normally Full Width
                 *  -------------------
                 */
                else{

                    /**
                     *  Sidebar Column Grid
                     *  -------------------
                     */
                    $classes = array_merge( $classes, parent:: get_grid( '1' ) );
                }
            }

            /**
             *  Have extra class arguments ?
             *  ----------------------------
             */
            if ( ! empty( $class ) ) {

                    if ( ! is_array( $class ) ) {

                            $class = preg_split( '#\s+#', $class );
                    }

                    $classes = array_merge( $classes, $class );

            } else {

                    /**
                     *  Ensure that we always coerce class to being an array
                     *  ----------------------------------------------------
                     */
                    $class = [];
            }

            /**
             *  Filter primary div class names
             *  ------------------------------
             */

            $classes    =   apply_filters( 'sdweddingdirectory_get_full_width_container_class', $classes, $class );

            $classes    =   array_map( 'sanitize_html_class', $classes );

            return          array_unique( $classes );
        }

        /**
         *  Container End Action
         *  --------------------
         */
        public static function sdweddingdirectory_main_container_end_markup(){

            /**
             *  </section>
             *
             *  section page of content container
             *  ---------------------------------
             */
            ?></section><?php

            /**
             *  page wrapper end
             *  ----------------
             */
            do_action( 'sdweddingdirectory_container_end' );

            /**
             *  page footer
             *  -----------
             */
            get_footer();
        }

        /**
         *  Container Start
         *  ---------------
         */
        public static function sdweddingdirectory_container_start(){

            /**
             *  Have Option Tree Plugin ?
             *  -------------------------
             */
            if( parent:: page_have_sidebar() ){

                /**
                 *  If ( Option Tree Plugin is activate then we have more page meta option )
                 *  ------------------------------------------------------------------------
                 */
                if(  parent:: is_container()  ){

                    /**
                     *  Page Start
                     *  ----------
                     */
                    self:: page_container_start();

                    /**
                     *  If Have Sidebar
                     *  ---------------
                     */
                    do_action( 'sdweddingdirectory_left_sidebar' );
                }

                /**
                 *  If ( Option Tree Plugin is activate then we have more page meta option )
                 *  ------------------------------------------------------------------------
                 */
                if(  parent:: is_container_fluid()  ){

                    /**
                     *  Page Start
                     *  ----------
                     */
                    self:: page_container_fluid_start();

                    /**
                     *  If Have Sidebar
                     *  ---------------
                     */
                    do_action( 'sdweddingdirectory_left_sidebar' );
                }
            }

            /**
             *  Make sure it's single blog post
             *  -------------------------------
             */
            elseif( is_singular() ){

                /**
                 *  Page Start
                 *  ----------
                 */
                self:: page_container_start();
            }

            /**
             *  Else
             *  ----
             */
            else{

                /**
                 *  Page Start
                 *  ----------
                 */
                self:: page_container_start();
            }
        }

        /**
         *  SDWeddingDirectory - Page Start Div Structure
         *  -------------------------------------
         */
        public static function page_container_start(){
            
            printf( '<div class="%1$s"><div class="container"><div class="row">',

                /**
                 *  Main Container Class
                 *  --------------------
                 */
                parent:: _class( apply_filters( 'sdweddingdirectory/container/class', [

                    'main-content', 

                    'content', 

                    'wide-tb-90'

                ] ) )
            );
        }

        /**
         *  SDWeddingDirectory - Page End Div Structure
         *  -----------------------------------
         */
        public static function page_container_end(){

                            ?>
                            
                        </div>

                    </div>

                </div>

            <?php
        }

        /**
         *  SDWeddingDirectory - Page Start Div Structure
         *  -------------------------------------
         */
        public static function page_container_fluid_start(){

            printf( '<div class="%1$s"><div class="container-fluid"><div class="row">',

                /**
                 *  Main Container Class
                 *  --------------------
                 */
                parent:: _class( apply_filters( 'sdweddingdirectory/container-fluid/class', [

                    'main-content', 

                    'content', 

                    'wide-tb-90'

                ] ) )
            );
        }

        /**
         *  SDWeddingDirectory - Page End Div Structure
         *  -----------------------------------
         */
        public static function page_container_fluid_end(){

                            ?>
                            
                        </div>

                    </div>

                </div>

            <?php
        }

        /**
         *  Container End
         *  -------------
         */
        public static function sdweddingdirectory_container_end(){

            if( parent:: is_container() ){

                /**
                 *  Have Sidebar ?
                 *  --------------
                 */
                do_action( 'sdweddingdirectory_right_sidebar' );

                /**
                 *  Page Container End
                 *  ------------------
                 */
                self:: page_container_end();
            }

            elseif( parent:: is_container_fluid() ){

                /**
                 *  Have Sidebar ?
                 *  --------------
                 */
                do_action( 'sdweddingdirectory_right_sidebar' );

                /**
                 *  Page Container End
                 *  ------------------
                 */
                self:: page_container_fluid_end();
            }

            /**
             *  Make sure it's single blog post
             *  -------------------------------
             */
            elseif( is_singular() ){

                /**
                 *  Page Container End
                 *  ------------------
                 */
                self:: page_container_end();
            }

            /**
             *  Else
             *  ----
             */
            else{

                /**
                 *  Have Sidebar
                 *  ------------
                 */
                do_action( 'sdweddingdirectory_right_sidebar' );

                /**
                 *  Page Container End
                 *  ------------------
                 */
                self:: page_container_end();
            }
        }
    }

    /**
     *  SDWeddingDirectory - Page Template Helper
     *  ---------------------------------
     */
    SDWeddingDirectory_Container_Managment::get_instance();
}
