<?php
/**
 *  -----------------------------------------
 *  SDWeddingDirectory - ShortCode - [ Button Group ]
 *  -----------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Shortcode_Button_Group' ) && class_exists( 'SDWeddingDirectory_Shortcode' ) ){

    /**
     *  -----------------------------------------
     *  SDWeddingDirectory - ShortCode - [ Button Group ]
     *  -----------------------------------------
     */
    class SDWeddingDirectory_Shortcode_Button_Group extends SDWeddingDirectory_Shortcode{

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
             *  Button ShortCode
             *  ----------------
             */
            add_shortcode( 'sdweddingdirectory_button_group', [ $this, 'sdweddingdirectory_button_group' ] );

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

                                'sdweddingdirectory_button_group'  =>  '[sdweddingdirectory_button_group class="" id=""][sdweddingdirectory_button target="_self" class="" id="" link="" ]Button Text Here [/sdweddingdirectory_button][sdweddingdirectory_button target="_self" class="" id="" link="" ] Button Text Here [/sdweddingdirectory_button][/sdweddingdirectory_button_group]'
                            )
                        );
            } );

            /**
             *  3. SDWeddingDirectory - Default Setting
             *  -------------------------------
             */
            add_filter( 'sdweddingdirectory_button_group_setting', function(){

                /**
                 *  Add Slider Placeholder
                 *  ----------------------
                 */
                return  array(

                            'class'     =>  '',

                            'id'        =>  '',

                            'align'     =>  sanitize_html_class( 'text-start' ),

                            'content'   =>  ''
                        );
            } );
        }

        /**
         *  Output
         *  ------
         */
        public static function sdweddingdirectory_button_group( $atts, $content = '' ){

            /**
             *  Default Args
             *  ------------
             */
            $atts = shortcode_atts( array(

                'id'        =>  '',

                'class'     =>  sanitize_html_class( 'text-start' ),

            ), $atts, esc_attr( __FUNCTION__ ) );

            /**
             *  Extract Args
             *  ------------
             */
            extract( $atts );

            /**
             *  Return Button HTML
             *  ------------------
             */
            return  sprintf(  '<div %1$s %2$s> %3$s </div>',

                        /**
                         *  1. Have ID ?
                         *  ------------
                         */
                        parent:: _have_attr_value( array(

                            'attr'    =>   esc_attr( 'id' ),

                            'val'    =>   $id
                        ) ),

                        /** 
                         *  2. Button Class
                         *  ---------------
                         */
                        parent:: _have_attr_value( array(

                            'attr'    =>   esc_attr( 'class' ),

                            'val'    =>   $class
                        ) ),

                        /**
                         *  5. Button Text
                         *  --------------
                         */
                        do_shortcode( 

                            apply_filters(  'sdweddingdirectory_clean_shortcode',  $content )
                        )
                    );
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
                 *  Extract Args
                 *  ------------
                 */
                extract(

                    wp_parse_args( 

                        $args, 

                        apply_filters( 'sdweddingdirectory_button_group_setting', [] )
                    ) 
                );

                /**
                 *  Render : HTML
                 *  -------------
                 */
                return  do_shortcode(

                            sprintf(   '[sdweddingdirectory_button_group %1$s %2$s] %3$s [/sdweddingdirectory_button_group]',

                                /**
                                 *  1. Have ID ?
                                 *  ------------
                                 */
                                parent:: _have_attr_value( array(

                                    'attr'  =>  esc_attr( 'id' ),

                                    'val'   =>  esc_attr( $id )

                                ) ),

                                /** 
                                 *  2. Button Class
                                 *  ---------------
                                 */
                                sprintf( 'class="%1$s %2$s"',

                                    /**
                                     *  Have Group Class ?
                                     *  ------------------
                                     */
                                    esc_attr( $class ),

                                    /**
                                     *  Have Group Align ?
                                     *  ------------------
                                     */
                                    sanitize_html_class( $align )
                                ),

                                /**
                                 *  5. Button Text
                                 *  --------------
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
     *  -----------------------------------------
     *  SDWeddingDirectory - ShortCode - [ Button Group ]
     *  -----------------------------------------
     */
    SDWeddingDirectory_Shortcode_Button_Group::get_instance();
}