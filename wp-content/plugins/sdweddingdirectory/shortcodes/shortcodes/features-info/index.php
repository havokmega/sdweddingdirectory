<?php
/**
 *  -----------------------------------------
 *  SDWeddingDirectory - ShortCode - [ Feature Info ]
 *  -----------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Shortcode_Feature_Info' ) && class_exists( 'SDWeddingDirectory_Shortcode' ) ){

    /**
     *  -----------------------------------------
     *  SDWeddingDirectory - ShortCode - [ Feature Info ]
     *  -----------------------------------------
     */
    class SDWeddingDirectory_Shortcode_Feature_Info extends SDWeddingDirectory_Shortcode {

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
                            'layout'            =>      absint( '1' ),

                            'media_url'         =>      '',
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
            add_shortcode( 'sdweddingdirectory_featuer_info', [ $this, 'sdweddingdirectory_featuer_info' ] );

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

                            'sdweddingdirectory_featuer_info'    =>  sprintf( '[sdweddingdirectory_featuer_info %1$s]%2$s[/sdweddingdirectory_featuer_info]',

                                                                /**
                                                                 *  1. Heading
                                                                 *  ----------
                                                                 */
                                                                parent:: _shortcode_atts( self:: default_args() ),

                                                                /**
                                                                 *  2. Content
                                                                 *  ----------
                                                                 */
                                                                sprintf( '<h1 class="mb-3">%1$s</h1><p class="lead">%2$s</p>', 

                                                                    /**
                                                                     *  1. Heading
                                                                     *  ----------
                                                                     */
                                                                    esc_attr( 'Upload videos' ),

                                                                    /**
                                                                     *  2. Description
                                                                     *  --------------
                                                                     */
                                                                    esc_attr( 'Promote your wedding business and services by adding unlimited videos to your venue.' )
                                                                )
                                                            )
                        ] );
            } );
        }

        /**
         *  Output
         *  ------
         */
        public static function sdweddingdirectory_featuer_info( $atts, $content = '' ){

            /**
             *  Extract Attr Data
             *  -----------------
             */
            extract( shortcode_atts( self:: default_args(), $atts, esc_attr( __FUNCTION__ ) ) );

            /**
             *  Collectoin
             *  ----------
             */
            $collection         =       '';

            /**
             *  Layout 1
             *  --------
             */
            if( $layout == absint( '1' ) ){

                $collection         .=      

                sprintf( 

                    '<div class="container features-info-layout-1 py-4">

                        <div class="row d-flex align-items-center">

                            <div class="col-md-6 col-12"><img src="%1$s" class="rounded" /></div>

                            <div class="col-md-6 col-12"><div class="p-5">%2$s</div></div>

                        </div>

                    </div>',

                    /**
                     *  1. Image
                     *  --------
                     */
                    esc_url( $media_url ),

                    /**
                     *  2. Content
                     *  ----------
                     */
                    do_shortcode( apply_filters(  'sdweddingdirectory_clean_shortcode', $content ) )
                );
            }

            /**
             *  Layout 2
             *  --------
             */
            elseif( $layout == absint( '2' ) ){

                $collection         .=      

                sprintf( 

                    '<div class="container features-info-layout-2 py-4">

                        <div class="row d-flex align-items-center">

                            <div class="col-md-6 col-12 order-md-1 order-md-1 order-2"><div class="p-5">%2$s</div></div>

                            <div class="col-md-6 col-12 order-md-2 order-md-2 order-1"><img src="%1$s" class="rounded" /></div>

                        </div>

                    </div>',

                    /**
                     *  1. Image
                     *  --------
                     */
                    esc_url( $media_url ),

                    /**
                     *  2. Content
                     *  ----------
                     */
                    do_shortcode( apply_filters(  'sdweddingdirectory_clean_shortcode', $content ) )
                );                
            }

            /**
             *  Return Data
             *  -----------
             */
            return      $collection;
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
                return  do_shortcode( sprintf(

                            '[sdweddingdirectory_featuer_info layout="%1$s" media_url="%2$s"]%3$s[/sdweddingdirectory_featuer_info]',

                            /**
                             *  1. Layout
                             *  ---------
                             */
                            $layout,

                            /**
                             *  2. Media URL
                             *  ------------
                             */
                            $media_url,

                            /**
                             *  3. Content
                             *  ----------
                             */
                            $content

                        ) );
            }
        }
    }

    /**
     *  -----------------------------------------
     *  SDWeddingDirectory - ShortCode - [ Feature Info ]
     *  -----------------------------------------
     */
    SDWeddingDirectory_Shortcode_Feature_Info::get_instance();
}