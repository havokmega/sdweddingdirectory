<?php
/**
 *  -----------------------------------------
 *  SDWeddingDirectory - ShortCode - [ Row | Column ]
 *  -----------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Shortcode_Row_Column' ) ){

    /**
     *  -----------------------------------------
     *  SDWeddingDirectory - ShortCode - [ Row | Column ]
     *  -----------------------------------------
     */
    class SDWeddingDirectory_Shortcode_Row_Column {

        /**
         *  A reference to an instance of this class
         *  ----------------------------------------
         */
        private static $instance;

        /**
         *  Returns an instance of this class.
         *  ----------------------------------
         */
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
             *  Container
             *  ---------
             */
            add_shortcode( 'sdweddingdirectory_container', [ $this, 'sdweddingdirectory_container' ] );

            /**
             *  Row
             *  ---
             */
            add_shortcode( 'sdweddingdirectory_row', [ $this, 'sdweddingdirectory_row' ] );

            /**
             *  Grid
             *  ----
             */
            add_shortcode( 'sdweddingdirectory_grid', [ $this, 'sdweddingdirectory_grid' ] );

            /**
             *  Button : Filters
             *  ----------------
             */
            self:: shortcode_filters();
        }

        /**
         *  Have Filters ?
         *  --------------
         */
        public static function shortcode_filters(){

            /**
             *  1. SDWeddingDirectory ShortCode Filter
             *  ------------------------------
             */
            add_filter( 'sdweddingdirectory_shortcode_list', function( $args = [] ){

                /**
                 *  Get Plugin Information
                 *  ----------------------
                 */
                return  array_merge(

                            /**
                             *  Have Args ?
                             *  -----------
                             */
                            $args,

                            /**
                             *  Merge New Args
                             *  --------------
                             */
                            array(
                                
                                'sdweddingdirectory_container'          => '[sdweddingdirectory_container id="" class=""]Content HERE[/sdweddingdirectory_container]',

                                'sdweddingdirectory_row'                => '[sdweddingdirectory_row id="" class=""]Content HERE[/sdweddingdirectory_row]',

                                'sdweddingdirectory_grid'               => '[sdweddingdirectory_grid id="" class=""]Content HERE[/sdweddingdirectory_grid]',
                            )
                        );
            } );
        }

        /**
         *  Container : HTML
         *  ----------------
         */
        public static function sdweddingdirectory_container( $atts, $content = '' ){

            /**
             *  Default Args
             *  ------------
             */
            $atts = shortcode_atts( array(

                'id'        =>  '',

                'class'     =>  sanitize_html_class( 'container' ),

            ), $atts, esc_attr( __FUNCTION__ ) );

            /**
             *  Extract Args
             *  ------------
             */
            extract( $atts );

            /**
             *  Return <row> HTML
             *  -----------------
             */
            return  sprintf(    '<div %1$s class="%2$s"> %3$s </div>',

                        /**
                         *  1. Have ID ?
                         *  ------------
                         */
                        ( isset( $id ) && $id !== '' && ! empty( $id ) )

                        ?   sprintf( 'id="%1$s"', esc_attr( $id ) )

                        :   '',

                        /** 
                         *  2. Button Class
                         *  ---------------
                         */
                        esc_attr( $class ),

                        /** 
                         *  3. Inner Content
                         *  ----------------
                         */
                        do_shortcode( 

                            apply_filters(  'sdweddingdirectory_clean_shortcode',  $content )
                        )
                    );
        }

        /**
         *  Row : HTML
         *  ----------
         */
        public static function sdweddingdirectory_row( $atts, $content = '' ){

            /**
             *  Default Args
             *  ------------
             */
            $atts = shortcode_atts( array(

                'id'        =>  '',

                'class'     =>  '',

            ), $atts, esc_attr( __FUNCTION__ ) );

            /**
             *  Extract Args
             *  ------------
             */
            extract( $atts );

            /**
             *  Return <row> HTML
             *  -----------------
             */
            return  sprintf(    '<div %1$s class="row %2$s"> %3$s </div>',

                        /**
                         *  1. Have ID ?
                         *  ------------
                         */
                        ( isset( $id ) && $id !== '' && ! empty( $id ) )

                        ?   sprintf( 'id="%1$s"', esc_attr( $id ) )

                        :   '',

                        /** 
                         *  2. Button Class
                         *  ---------------
                         */
                        esc_attr( $class ),

                        /** 
                         *  3. Inner Content
                         *  ----------------
                         */
                        do_shortcode( 

                            apply_filters(  'sdweddingdirectory_clean_shortcode',  $content )
                        )
                    );
        }

        /**
         *  Grid : HTML
         *  -----------
         */
        public static function sdweddingdirectory_grid( $atts, $content = '' ){

            /**
             *  Default Args
             *  ------------
             */
            $atts = shortcode_atts( array(

                'id'        =>  '',

                'class'     =>  sanitize_html_class( 'col' ),

            ), $atts, esc_attr( __FUNCTION__ ) );

            /**
             *  Extract Args
             *  ------------
             */
            extract( $atts );

            /**
             *  Return <row> HTML
             *  -----------------
             */
            return  sprintf(    '<div %1$s class="%2$s"> %3$s </div>',

                        /**
                         *  1. Have ID ?
                         *  ------------
                         */
                        ( isset( $id ) && $id !== '' && ! empty( $id ) )

                        ?   sprintf( 'id="%1$s"', esc_attr( $id ) )

                        :   '',

                        /** 
                         *  2. Button Class
                         *  ---------------
                         */
                        esc_attr( $class ),

                        /** 
                         *  3. Inner Content
                         *  ----------------
                         */
                        do_shortcode( 

                            apply_filters(  'sdweddingdirectory_clean_shortcode',  $content )
                        )
                    );
        }
    }

    /**
     *  -----------------------------------------
     *  SDWeddingDirectory - ShortCode - [ Row | Column ]
     *  -----------------------------------------
     */
    SDWeddingDirectory_Shortcode_Row_Column::get_instance();
}