<?php
/**
 *  SDWeddingDirectory - Page Grid Managment
 *  ---------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Grid_Managment' ) && class_exists( 'SDWeddingDirectory' ) ) {

    /**
     *  SDWeddingDirectory - Page Grid Managment
     *  ---------------------------------
     */
    class SDWeddingDirectory_Grid_Managment extends SDWeddingDirectory {

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

        }

        /**
         *  SDWeddingDirectory - Grid
         *  -----------------
         */
        public static function get_grid( $grid = '1' ){

            $grid_class     =       [];

            /**
             *  Is Full Widht Container Class
             *  -----------------------------
             */
            if( $grid == absint( '1' ) ){

                $grid_class[]   =   sanitize_html_class( 'col-xl-12' );

                $grid_class[]   =   sanitize_html_class( 'col-lg-12' );

                $grid_class[]   =   sanitize_html_class( 'col-md-12' );

                $grid_class[]   =   sanitize_html_class( 'col-sm-12' );

                $grid_class[]   =   sanitize_html_class( 'col-12' );
            }

            if( $grid == absint( '2' ) ){

                $grid_class[]   =   sanitize_html_class( 'col-xl-4' );

                $grid_class[]   =   sanitize_html_class( 'col-lg-4' );

                $grid_class[]   =   sanitize_html_class( 'col-md-12' );

                $grid_class[]   =   sanitize_html_class( 'col-sm-12' );

                $grid_class[]   =   sanitize_html_class( 'col-12' );
            }

            if( $grid == absint( '3' ) ){

                $grid_class[] = sanitize_html_class( 'col-xl-8' );

                $grid_class[] = sanitize_html_class( 'col-lg-8' );

                $grid_class[] = sanitize_html_class( 'col-md-12' );

                $grid_class[] = sanitize_html_class( 'col-sm-12' );

                $grid_class[] = sanitize_html_class( 'col-12' );
            }

            /**
             *  Return Grid Class
             *  -----------------
             */
            return  $grid_class;
        }

        /**
         *  Have [ Container OR Custom Structure ]
         *  --------------------------------------
         */
        public static function page_container(){

            $_page_wrapper      =    esc_attr(  parent:: sdweddingdirectory_meta_value(

                                        /**
                                         *  1. Meta Key
                                         *  -----------
                                         */
                                        sanitize_key( 'container_style' )

                                    ) );

            /**
             *  Not Found Value : Default ( Container )
             *  ---------------------------------------
             */
            if( parent:: _have_data( $_page_wrapper ) ){

                return $_page_wrapper;

            }else{

                return  esc_attr( 'container' );
            }
        }


        /**
         *  Is Container Width ?
         *  --------------------
         */
        public static function is_container(){

            if( self:: page_container() == esc_attr( 'container' ) ){

                return      true;

            }else{

                return      false;
            }
        }

        /**
         *  Is Container Fluid Width ?
         *  --------------------------
         */
        public static function is_container_fluid(){

            if( self:: page_container() == esc_attr( 'container-fluid' ) ){

                return      true;

            }else{

                return      false;
            }
        }

        /**
         *  Have Page Sidebar ?
         *  -------------------
         */
        public static function page_sidebar(){

            if( parent:: page_have_sidebar() ){

                /**
                 *  Have Sidebar ?
                 *  --------------
                 */
                $_sidebar   =   esc_attr(  parent:: sdweddingdirectory_meta_value(

                                    /**
                                     *  1. Meta Key
                                     *  -----------
                                     */
                                    sanitize_key( 'sidebar_position' )

                                ) );

                /**
                 *  Sidebar not set to return : No Sidebar
                 *  --------------------------------------
                 */
                if(  parent:: _have_data( $_sidebar )  ){

                    return  esc_attr( $_sidebar );

                }else{

                    return  esc_attr( 'no-sidebar' );
                }

            }else{

                return esc_attr( 'right-sidebar' );
            }
        }

        /**
         *  Is Right Sidebar ?
         *  ------------------
         */
        public static function is_right_sidebar(){

            if( self:: page_sidebar()   ==  esc_attr( 'right-sidebar' ) ){

                return      true;

            }else{

                return      false;
            }
        }

        /**
         *  Is Left Sidebar ?
         *  -----------------
         */
        public static function is_left_sidebar(){

            if( self:: page_sidebar()   ==  esc_attr( 'left-sidebar' ) ){

                return      true;

            }else{

                return      false;
            }
        }

        /**
         *  Is Left Sidebar ?
         *  -----------------
         */
        public static function have_sidebar(){

            $_condition_1       =       ! ( self:: page_sidebar() ==  esc_attr( 'no-sidebar' ) );

            $_condition_2       =       ! empty( self:: page_sidebar() ) && self:: page_sidebar()   !=  '';

            $_condition_3       =       self:: page_sidebar()   ==  esc_attr( 'left-sidebar' );

            $_condition_4       =       self:: page_sidebar()   ==  esc_attr( 'right-sidebar' );

            /**
             *  Have Sidebar ( right sidebar or left sidebar )
             *  ----------------------------------------------
             */
            if(  $_condition_1 && $_condition_2 && ( $_condition_3 || $_condition_4 )  ){

                return      true;

            }else{

                return      false;
            }
        }

        /**
         *  No Sidebar ?
         *  ------------
         */
        public static function is_no_sidebar(){

            /**
             *  No Sidebar
             *  ----------
             */
            if(  self:: page_sidebar() ==  esc_attr( 'no-sidebar' )  ){

                return      true;

            }else{

                return      false;
            }
        }

        /**
         *  Have Sidebar ?
         *  --------------
         */
        public static function sdweddingdirectory_sidebar(){

            /**
             *  Have Sidebar ?
             *  --------------
             */
            if( parent:: page_have_sidebar() ){

                /** ------------
                 *  Is Container
                 *  -----------------------------
                 *  Right sidebar or Left Sidebar
                 *  -----------------------------
                 *  Is Not Used ( No Sidebar )
                 *  --------------------------
                 */
                
                $_condition_1   =   self:: is_container() || self:: is_container_fluid();

                $_condition_2   =   self:: have_sidebar();

                /**
                 *  Have Sidebar ?
                 *  --------------
                 */
                if(  $_condition_1 && $_condition_2  ){

                    /**
                     *  Return Current Page Set Sidebar Possition
                     *  -----------------------------------------
                     */
                    return      esc_attr(  parent:: sdweddingdirectory_meta_value(

                                    /**
                                     *  1. Meta Key
                                     *  -----------
                                     */
                                    sanitize_key( 'get_sidebar' )

                                ) );
                }

            }else{

                return  esc_attr( 'sdweddingdirectory-widget-primary' );
            }
        }
    }

    /**
     *  SDWeddingDirectory - Page Template Helper
     *  ---------------------------------
     */
    SDWeddingDirectory_Grid_Managment::get_instance();
}