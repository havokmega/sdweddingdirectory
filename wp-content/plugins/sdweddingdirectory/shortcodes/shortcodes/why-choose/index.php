<?php
/**
 *  ------------------------------------------
 *  SDWeddingDirectory - ShortCode - [ Why Choose Us ]
 *  ------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Shortcode_Why_Choose_Us' ) && class_exists( 'SDWeddingDirectory_Shortcode' ) ){

    /**
     *  ------------------------------------------
     *  SDWeddingDirectory - ShortCode - [ Why Choose Us ]
     *  ------------------------------------------
     */
    class SDWeddingDirectory_Shortcode_Why_Choose_Us extends SDWeddingDirectory_Shortcode {

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
         *  ShortCode Atts
         *  --------------
         */
        public static function default_args(){

            return      [
                            'icon'          =>  esc_attr( 'sdweddingdirectory-heart-hand' ),

                            'title'         =>  esc_attr( '350,000 Vendors' ),

                            'button_name'   =>  esc_attr( 'Read More' ),

                            'button_link'   =>  esc_url( home_url( '/' ) ),

                            'layout'        =>  absint( '1' ),

                            'style'         =>  absint( '1' ),
                        ];
        }

        /**
         *  Construct
         *  ---------
         */
        public function __construct() {

            /**
             *  Button ShortCode
             *  ----------------
             */
            add_shortcode( 'sdweddingdirectory_why_choose_us', [ $this, 'sdweddingdirectory_why_choose_us' ] );

            /**
             *  SDWeddingDirectory - ShortCode Info
             *  ---------------------------
             */
            add_filter( 'sdweddingdirectory_shortcode_list', function( $args = [] ){

                /**
                 *  Get Plugin Information
                 *  ----------------------
                 */
                return  array_merge( $args, [

                            'sdweddingdirectory_why_choose_us'    =>    sprintf( '[sdweddingdirectory_why_choose_us %1$s]%2$s[/sdweddingdirectory_why_choose_us]',

                                                                    /**
                                                                     *  1. Default Attribute
                                                                     *  --------------------
                                                                     */
                                                                    parent:: _shortcode_atts( self:: default_args() ),

                                                                    /**
                                                                     *  2. Inside Content
                                                                     *  -----------------
                                                                     */
                                                                    'Sed ut perspiciatis und omnis iste natus errors sit.'
                                                                )
                        ] );
            } );
        }

        /**
         *  Output
         *  ------
         */
        public static function sdweddingdirectory_why_choose_us( $atts, $content = '' ){

            /**
             *  Extract Attr Data
             *  -----------------
             */
            extract( shortcode_atts( self:: default_args(), $atts, esc_attr( __FUNCTION__ ) ) );

            /**
             *  Var
             *  ---
             */
            $collection         =      '';

            $handler            =      '';

            $collection         .=      parent:: _shortcode_style_start( $style );

            /**
             *  Layout 1
             *  --------
             */
            if( $layout == absint( '1' ) ){

                /**
                 *  Have Icon
                 *  ---------
                 */
                if( ! empty( $icon ) ){

                    $handler    .=      sprintf( '<i class="%1$s"></i>', $icon );
                }

                /**
                 *  Have Title ?
                 *  ------------
                 */
                if( ! empty( $title ) ){

                    $handler    .=      sprintf( '<h4>%1$s</h4>', $title );
                }

                /**
                 *  Have Content ?
                 *  --------------
                 */
                if( ! empty( $content ) ){

                    $handler    .=      sprintf( '<p>%1$s</p>', 

                                            do_shortcode( apply_filters(  'sdweddingdirectory_clean_shortcode', $content ) )
                                        );
                }

                /**
                 *  Have Button + Link ?
                 *  --------------------
                 */
                if( ! empty( $button_name ) && ! empty( $button_link )  ){

                    $handler    .=      sprintf( '<a href="%1$s" class="btn btn-link btn-link-secondary">%2$s</a>',

                                            /**
                                             *  1. Link
                                             *  -------
                                             */
                                            esc_url( $button_link ),

                                            /**
                                             *  2. Button Name
                                             *  --------------
                                             */
                                            esc_attr( $button_name )
                                        );
                }

                /**
                 *  Collection
                 *  ----------
                 */
                $collection         .=      sprintf(   '<div class="why-choose-layout-1">%2$s</div>',

                                                        /**
                                                         *  1. Icon
                                                         *  -------
                                                         */
                                                        $icon,

                                                        /**
                                                         *  2. Content
                                                         *  ----------
                                                         */
                                                        $handler
                                            );
            }

            /**
             *  Layout 2
             *  --------
             */
            elseif( $layout == absint( '2' ) ){

                /**
                 *  Have Icon
                 *  ---------
                 */
                if( ! empty( $icon ) ){

                    $handler    .=      sprintf( '<div class="mb-3"><i class="%1$s fa-3x mb-3"></i></div>', $icon );
                }

                /**
                 *  Have Title ?
                 *  ------------
                 */
                if( ! empty( $title ) ){

                    $handler    .=      sprintf( '<h4 class="fw-bold mb-3"><a href="%1$s">%2$s</a></h4>', esc_url( $button_link ), $title );
                }

                /**
                 *  Have Content ?
                 *  --------------
                 */
                if( ! empty( $content ) ){

                    $handler    .=      sprintf( '<p>%1$s</p>', 

                                            do_shortcode( apply_filters(  'sdweddingdirectory_clean_shortcode', $content ) )
                                        );
                }

                /**
                 *  Have Button + Link ?
                 *  --------------------
                 */
                if( ! empty( $button_name ) && ! empty( $button_link )  ){

                    $handler    .=      sprintf( '<a href="%1$s" class="btn btn-link btn-link-secondary">%2$s</a>',

                                            /**
                                             *  1. Link
                                             *  -------
                                             */
                                            esc_url( $button_link ),

                                            /**
                                             *  2. Button Name
                                             *  --------------
                                             */
                                            esc_attr( $button_name )
                                        );
                }

                /**
                 *  Collection
                 *  ----------
                 */
                $collection         .=      sprintf(   '<div class="why-choose-layout-2 text-center">%2$s</div>',

                                                        /**
                                                         *  1. Icon
                                                         *  -------
                                                         */
                                                        $icon,

                                                        /**
                                                         *  2. Content
                                                         *  ----------
                                                         */
                                                        $handler
                                            );
            }

            /**
             *  Layout 3
             *  --------
             */
            elseif( $layout == absint( '3' ) ){

                /**
                 *  Have Title ?
                 *  ------------
                 */
                if( ! empty( $title ) ){

                    $handler    .=      sprintf( '<h3 class="">%1$s</h3>', $title );
                }

                /**
                 *  Have Content ?
                 *  --------------
                 */
                if( ! empty( $content ) ){

                    $handler    .=      sprintf( '<p>%1$s</p>', 

                                            do_shortcode( apply_filters(  'sdweddingdirectory_clean_shortcode', $content ) )
                                        );
                }

                /**
                 *  Have Button + Link ?
                 *  --------------------
                 */
                if( ! empty( $button_name ) && ! empty( $button_link )  ){

                    $handler    .=      sprintf( '<a href="%1$s" class="btn btn-link btn-link-secondary">%2$s</a>',

                                            /**
                                             *  1. Link
                                             *  -------
                                             */
                                            esc_url( $button_link ),

                                            /**
                                             *  2. Button Name
                                             *  --------------
                                             */
                                            esc_attr( $button_name )
                                        );
                }

                /**
                 *  Collection
                 *  ----------
                 */
                $collection         .=      sprintf(   '<div class="why-choose-layout-3">

                                                            <div class="row d-flex align-items-center mb-3">

                                                                <div class="col-2">%1$s</div>

                                                                <div class="col-10">%2$s</div>

                                                            </div>

                                                        </div>',

                                                        /**
                                                         *  1. Icon
                                                         *  -------
                                                         */
                                                        sprintf( '<i class="%1$s fa-3x"></i>', $icon ),

                                                        /**
                                                         *  2. Content
                                                         *  ----------
                                                         */
                                                        $handler
                                            );
            }


            $collection         .=      parent:: _shortcode_style_end( $style );

            /**
             *  Return Button HTML
             *  ------------------
             */
            return          $collection;
        }

        /**
         *  Page Builder : Args
         *  -------------------
         */
        public static function page_builder( $args = [] ){

            /** 
             *  Have Data ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract Setting with Merge Empty Fields
                 *  ---------------------------------------
                 */
                extract( wp_parse_args( $args, self:: default_args() ) );

                /**
                 *  Return Button HTML
                 *  ------------------
                 */
                return  do_shortcode(

                            sprintf(

                               '[sdweddingdirectory_why_choose_us icon="%1$s" title="%2$s" layout="%3$s" button_name="%4$s" button_link="%5$s"]%6$s[/sdweddingdirectory_why_choose_us]',

                                /**
                                 *  1. Have Icon ?
                                 *  --------------
                                 */
                                $icon,

                                /**
                                 *  2. Have Title ?
                                 *  ---------------
                                 */
                                $title,

                                /**
                                 *  3. Have Layout ?
                                 *  ----------------
                                 */
                                $layout,

                                /**
                                 *  4. Button Name ?
                                 *  ----------------
                                 */
                                $button_name,

                                /**
                                 *  5. Button Link ?
                                 *  ----------------
                                 */
                                $button_link,

                                /**
                                 *  6. Content here
                                 *  ---------------
                                 */
                                do_shortcode(

                                    apply_filters(  'sdweddingdirectory_clean_shortcode',  $content )
                                )
                            )
                        );
            }
        }
    }

    /**
     *  ------------------------------------------
     *  SDWeddingDirectory - ShortCode - [ Why Choose Us ]
     *  ------------------------------------------
     */
    SDWeddingDirectory_Shortcode_Why_Choose_Us::get_instance();
}