<?php
/**
 *  ----------------------------------------
 *  SDWeddingDirectory - ShortCode - [ Contact Box ]
 *  ----------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Shortcode_Contact_Box' ) && class_exists( 'SDWeddingDirectory_Shortcode' ) ){

    /**
     *  ----------------------------------------
     *  SDWeddingDirectory - ShortCode - [ Contact Box ]
     *  ----------------------------------------
     */
    class SDWeddingDirectory_Shortcode_Contact_Box extends SDWeddingDirectory_Shortcode{

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

            return  array(

                        'style'                     =>  absint( '1' ),

                        'heading'                   =>  esc_attr( 'Customer Support' ),

                        'icon'                      =>  esc_attr( 'sdweddingdirectory-support' ),
                    );
        }

        /**
         *  Construct
         *  ---------
         */
        public function __construct() {

            /**
             *  Brand Carousel [ Parent ] - ShortCode
             *  -------------------------------------
             */
            add_shortcode( 'sdweddingdirectory_contact_box', [ $this, 'sdweddingdirectory_contact_box' ] );

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

                            'sdweddingdirectory_contact_box'    =>  sprintf( '[sdweddingdirectory_contact_box %1$s][/sdweddingdirectory_contact_box]',

                                                                parent:: _shortcode_atts( self:: default_args() )
                                                            )
                        ] );
            } );
        }

        /**
         *  Brand Carousel [ Parent ] - ShortCode
         *  -------------------------------------
         */        
        public static function sdweddingdirectory_contact_box( $atts, $content = '' ){

            /**
             *  Extract Attr Data
             *  -----------------
             */
            extract( shortcode_atts( self:: default_args(), $atts, esc_attr( __FUNCTION__ ) ) );

            /**
             *  Var
             *  ---
             */
            $collection         =       '';

            /**
             *  Style
             *  -----
             */
            $collection        .=       parent:: _shortcode_style_start( $style );

            /**
             *  Return Carousel Parent
             *  ----------------------
             */
            $collection        .=       sprintf(   '<div class="contact-details-wrap">

                                                        <i class="%1$s"></i> 

                                                        <h3 class="text-primary">%2$s</h3> 

                                                        %3$s

                                                    </div>',

                                                /** 
                                                 *  1. Icon
                                                 *  -------
                                                 */
                                                esc_attr( $icon ),

                                                /** 
                                                 *  2. Heading
                                                 *  ----------
                                                 */
                                                esc_attr( $heading ),

                                                /**
                                                 *  3. Content
                                                 *  ----------
                                                 */
                                                $content
                                        );

            /**
             *  Style
             *  -----
             */
            $collection        .=       parent:: _shortcode_style_end( $style );

            /**
             *  Return
             *  ------
             */
            return          $collection;
        }

        /**
         *  Page Builder *Value* pass here to print features
         *  ------------------------------------------------
         */
        public static function page_builder( $args = [] ){

            /**
             *  Have Args ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract Setting with Merge Empty Fields
                 *  ---------------------------------------
                 */
                extract( wp_parse_args( $args, self:: default_args() ) );

                /**
                 *  Return Data
                 *  -----------
                 */
                return  do_shortcode(

                            sprintf(

                                '[sdweddingdirectory_contact_box icon="%1$s" heading="%2$s" style="%3$s"]%4$s[/sdweddingdirectory_contact_box]',

                                /**
                                 *  1. Have Media Link ?
                                 *  --------------------
                                 */
                                $icon,

                                /**
                                 *  2. Heading ?
                                 *  ------------
                                 */
                                $heading,

                                /**
                                 *  3. Style ?
                                 *  ----------
                                 */
                                $style,

                                /**
                                 *  4. Descripton
                                 *  -------------
                                 */
                                apply_filters(  'sdweddingdirectory_clean_shortcode',  $description )
                            )
                        );
            }
        }
    }

    /**
     *  ----------------------------------------
     *  SDWeddingDirectory - ShortCode - [ Contact Box ]
     *  ----------------------------------------
     */
    SDWeddingDirectory_Shortcode_Contact_Box::get_instance();
}