<?php
/**
 *  -----------------------------------------------
 *  SDWeddingDirectory - ShortCode - [ Pricing Table Post ]
 *  -----------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Shortcode_Pricing_Post' ) && class_exists( 'SDWeddingDirectory_Shortcode' ) ){

    /**
     *  -----------------------------------------------
     *  SDWeddingDirectory - ShortCode - [ Pricing Table Post ]
     *  -----------------------------------------------
     */
    class SDWeddingDirectory_Shortcode_Pricing_Post extends SDWeddingDirectory_Shortcode {

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
                            'post_id'       =>      '',

                            'style'         =>      '',

                            'layout'        =>      absint( '1' ),

                            'button_text'   =>      esc_attr__( 'Choose Package', 'sdweddingdirectory-shortcodes' )
                        ];
        }

        /**
         *  Construct
         *  ---------
         */
        public function __construct() {

            /**
             *  1. Find Venue Form Section
             *  ----------------------------
             */
            add_shortcode( 'sdweddingdirectory_pricing', [ $this, 'sdweddingdirectory_pricing' ] );

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

                            'sdweddingdirectory_pricing'    =>  sprintf( '[sdweddingdirectory_pricing %1$s][/sdweddingdirectory_pricing]', 

                                                            parent:: _shortcode_atts( self:: default_args() )
                                                        )
                        ] );
            } );
        }

        /**
         *  Search Box Start
         *  ----------------
         */
        public static function sdweddingdirectory_pricing( $atts, $content = null ){

            /**
             *  Extract Attr Data
             *  -----------------
             */
            extract( shortcode_atts( self:: default_args(), $atts, esc_attr( __FUNCTION__ ) ) );

            /**
             *  Make sure post id not empty!
             *  ----------------------------
             */
            if( empty( $post_id ) ){

                return;
            }

            /**
             *  Collection Var
             *  --------------
             */
            $collection         =   '';

            /**
             *  Have Shortcode Post ID set ?
             *  ----------------------------
             */
            $_post_post_id      =   parent:: _is_array( preg_split ("/\,/", $post_id ) ) &&

                                    parent:: _have_data( $post_id )

                                ?   preg_split ("/\,/", $post_id )

                                :   [];

            /**
             *  Create Query
             *  ------------
             */
            $query              =   new WP_Query( [

                                        'post_type'         =>  esc_attr( 'pricing' ),

                                        'post_status'       =>  esc_attr( 'publish' ),

                                        'post__in'          =>  $_post_post_id

                                    ] );

            /**
             *  Have venue post data ?
             *  ------------------------
             */
            if ( $query->have_posts() ){

                /**
                 *  One by one pass venue post id
                 *  -------------------------------
                 */
                while ( $query->have_posts() ) {  $query->the_post();

                    /**
                     *  Load Post ID
                     *  ------------
                     */
                    $post_id        =       absint( get_the_ID() );

                    /**
                     *  Style
                     *  -----
                     */
                    $collection    .=       self:: _shortcode_style_start( $style );


                    /**
                     *  Layout 1
                     *  --------
                     */
                    if( $layout == absint( '1' ) ){

                        $collection     .=      apply_filters( 'sdweddingdirectory/pricing/post/layout-one', [

                                                    'post_id'       =>      absint( $post_id )

                                                ] );
                    }

                    /**
                     *  Layout 2
                     *  --------
                     */
                    elseif( $layout == absint( '2' ) ){

                        $collection     .=      apply_filters( 'sdweddingdirectory/pricing/post/layout-two', [

                                                    'post_id'       =>      absint( $post_id )

                                                ] );
                    }

                    /**
                     *  Layout 3
                     *  --------
                     */
                    elseif( $layout == absint( '3' ) ){

                        $collection     .=      apply_filters( 'sdweddingdirectory/pricing/post/layout-three', [

                                                    'post_id'       =>      absint( $post_id )

                                                ] );
                    }

                    /**
                     *  Style
                     *  -----
                     */
                    $collection    .=       self:: _shortcode_style_end( $style );
                }

                /**
                 *  Reset Shortcode Venue Query
                 *  -----------------------------
                 */
                if( isset( $query ) ){

                    wp_reset_postdata();
                }
            }

            /**
             *  Return Button HTML
             *  ------------------
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
                return  do_shortcode(

                            sprintf(

                                '[sdweddingdirectory_pricing layout="%1$s" post_id="%2$s" button_text="%3$s" style="%4$s"][/sdweddingdirectory_pricing]',

                                /**
                                 *  1. Have Layout ?
                                 *  ----------------
                                 */
                                $layout,

                                /**
                                 *  2. Have Post ?
                                 *  --------------
                                 */
                                parent:: _is_array( $post_id )

                                ?   implode( ',', $post_id )

                                :   $post_id,

                                /**
                                 *  3. Button Text
                                 *  --------------
                                 */
                                $button_text,

                                /**
                                 *  4. Have Style ?
                                 *  ---------------
                                 */
                                $style
                            )
                        );
            }
        }
    }

    /**
     *  -----------------------------------------------
     *  SDWeddingDirectory - ShortCode - [ Pricing Table Post ]
     *  -----------------------------------------------
     */
    SDWeddingDirectory_Shortcode_Pricing_Post::get_instance();
}