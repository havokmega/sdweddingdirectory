<?php
/**
 *  SDWeddingDirectory - 404 Error Page Template Helper
 *  -------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_404' ) && class_exists( 'SDWeddingDirectory' ) ){

    /**
     *  SDWeddingDirectory - 404 Error Page Template Helper
     *  -------------------------------------------
     */
    class SDWeddingDirectory_404 extends SDWeddingDirectory{

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
             *  1. 404 Error Page Content Filter
             *  --------------------------------
             */
            add_action( 'sdweddingdirectory/404-error/content', [ $this, 'graphics_load' ], absint( '10' ), absint( '1' ) );

            /**
             *  2. 404 Error Page Content Filter
             *  --------------------------------
             */
            add_action( 'sdweddingdirectory/404-error/content', [ $this, 'title_load' ], absint( '20' ), absint( '1' ) );

            /**
             *  3. 404 Error Page Content Filter
             *  --------------------------------
             */
            add_action( 'sdweddingdirectory/404-error/content', [ $this, 'description_load' ], absint( '30' ), absint( '1' ) );

            /**
             *  4. 404 Error Page Content Filter
             *  --------------------------------
             */
            add_action( 'sdweddingdirectory/404-error/content', [ $this, 'buttons_load' ], absint( '40' ), absint( '1' ) );
        }

        /**
         *  1. SDWeddingDirectory - 404 Page Have Graphics ?
         *  ----------------------------------------
         */
        public static function graphics_load( $args = [] ){

            /**
             *  Have args ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( wp_parse_args( $args, [

                    'layout'        =>      absint( '1' )

                ] ) );

                /**
                 *  Layout 1
                 *  --------
                 */
                if( $layout == absint( '1' ) ){

                    /**
                     *  SDWeddingDirectory - 404 Graphics Image
                     *  -------------------------------
                     */
                    printf( '<img loading="lazy" src="%1$s" alt="%2$s %3$s" class="mb-5" title="%2$s">',

                        /**
                         *  1. Setting Option to get 404 Error Page Image
                         *  ---------------------------------------------
                         */
                        esc_url( get_theme_file_uri( SDW_DEFAULT_404_IMAGE ) ),

                        /**
                         *  2. Blog Name
                         *  ------------
                         */
                        esc_attr( get_option( 'blogname' ) ),

                        /**
                         *  3. Translation Ready String
                         *  ---------------------------
                         */
                        esc_attr__( '404 Error Page', 'sdweddingdirectory' )
                    );
                }
            }
        }

        /**
         *  2. SDWeddingDirectory - 404 Page Have Title
         *  -----------------------------------
         */
        public static function title_load( $args = [] ){

            /**
             *  Have args ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( wp_parse_args( $args, [

                    'layout'        =>      absint( '1' )

                ] ) );

                /**
                 *  Layout 1
                 *  --------
                 */
                if( $layout == absint( '1' ) ){

                    /**
                     *  2. 404 Error Page Heading
                     *  -------------------------
                     */
                    printf( '<h2>%1$s</h2>', 

                        /**
                         *  1. Have Setting Option to set 404 Error Page Title ?
                         *  ----------------------------------------------------
                         */
                        esc_attr__( SDW_404_TITLE, 'sdweddingdirectory' )
                    );
                }
            }
        }

        /**
         *  3. SDWeddingDirectory - 404 Page Have Description
         *  -----------------------------------------
         */
        public static function description_load( $args = [] ){

            /**
             *  Have args ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( wp_parse_args( $args, [

                    'layout'        =>      absint( '1' )

                ] ) );

                /**
                 *  Layout 1
                 *  --------
                 */
                if( $layout == absint( '1' ) ){

                    /**
                     *  SDWeddingDirectory -404 Error Page Description
                     *  --------------------------------------
                     */
                    printf(     '<p>%1$s</p>',

                                /**
                                 *  Load Description
                                 *  ----------------
                                 */
                                esc_attr__( SDW_404_DESCRIPTION, 'sdweddingdirectory' )
                    );
                }
            }
        }

        /**
         *  4. SDWeddingDirectory - 404 Page Have Buttons ?
         *  ---------------------------------------
         */
        public static function buttons_load( $args = [] ){

            /**
             *  Have args ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( wp_parse_args( $args, [

                    'layout'        =>      absint( '1' )

                ] ) );

                /**
                 *  Layout 1
                 *  --------
                 */
                if( $layout == absint( '1' ) ){

                    /**
                     *  Buttons
                     *  -------
                     */
                    $_buttons   =   SDW_404_BUTTONS;

                    /**
                     *  Extra Buttons for redirection user on 404 Error Page
                     *  ----------------------------------------------------
                     */
                    if( parent:: _is_array( $_buttons ) ){

                        ?><div class="tags mt-5"><?php

                            foreach ( $_buttons as $key => $value ) {
                                
                                $button_link = isset( $value['link'] ) ? $value['link'] : '/';
                                $button_label = isset( $value['name'] ) ? $value['name'] : '';

                                printf( '<a href="%1$s">%2$s</a>',

                                        /**
                                         *  1. Button Link
                                         *  --------------
                                         */
                                        esc_url( home_url( $button_link ) ),
                                        
                                        /**
                                         *  2. Button Name
                                         *  --------------
                                         */
                                        esc_attr__( $button_label, 'sdweddingdirectory' )
                                );
                            }

                        ?></div><?php
                    }
                }
            }
        }
    }  

    /**
     *  SDWeddingDirectory - Body Markup Object
     *  -------------------------------
     */
    SDWeddingDirectory_404:: get_instance();
}
