<?php
/**
 *  -------------------------------------------
 *  SDWeddingDirectory - ShortCode - [ Call To Action ]
 *  -------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Shortcode_Call_To_Action' ) && class_exists( 'SDWeddingDirectory_Shortcode' ) ){

    /**
     *  -------------------------------------------
     *  SDWeddingDirectory - ShortCode - [ Call To Action ]
     *  -------------------------------------------
     */
    class SDWeddingDirectory_Shortcode_Call_To_Action extends SDWeddingDirectory_Shortcode{

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
        public static function default_args_cta_one(){

            return      [
                            'id'            =>      '',

                            'class'         =>      '',

                            'media_url'     =>      esc_url(  parent:: placeholder( 'call_to_action_one' ) ),
                        ];
        }

        /**
         *  ShortCode Atts
         *  --------------
         */
        public static function default_args_cta_two(){

            return      [
                            'id'            =>      '',

                            'class'         =>      '',

                            'media_url'     =>      esc_url(  parent:: placeholder( 'call_to_action_two' ) ),
                        ];
        }

        /**
         *  ShortCode Attr
         *  --------------
         */
        public static function default_args(){

            return  array(

                        'class'         =>  '',

                        'id'            =>  '',

                        'content_two'   =>  sprintf( '<h1 class="mb-md-0 mb-xl-0 mb-lg-0 mb-4 text-white">%1$s</h1>', 

                                                /**
                                                 *  1. Translation Ready String
                                                 *  ---------------------------
                                                 */
                                                esc_attr__( 'Are You Looking For Vendor For Your Wedding', 'sdweddingdirectory-shortcodes' )
                                            ),

                        'content_one'   =>  sprintf(

                                                '<h1>%1$s</h1><p>%2$s</p>

                                                [sdweddingdirectory_button target="_self" class="btn btn-default btn-rounded btn-lg" id="" link="" ] %3$s 
                                                [/sdweddingdirectory_button]',

                                                /**
                                                 *  1. Translation Ready String
                                                 *  ---------------------------
                                                 */
                                                esc_attr__( 'The Best Wedding Vendor Service', 'sdweddingdirectory-shortcodes' ),

                                                /**
                                                 *  2. Translation Ready String
                                                 *  ---------------------------
                                                 */
                                                esc_attr__( 'Sed ut perspiciatis unde omnis iste oluptatem accusantium doloremque laud.', 

                                                    'sdweddingdirectory-shortcodes' 
                                                ),

                                                /**
                                                 *  3. Translation Ready String
                                                 *  ---------------------------
                                                 */
                                                esc_attr__( 'Button Text Here', 'sdweddingdirectory-shortcodes' )
                                            ),

                        'media_url'     =>  esc_url( parent:: placeholder( 'call_to_action_one' ) )
                    );
        }

        /**
         *  Construct
         *  ---------
         */
        public function __construct() {

            /**
             *  Call To Action [ ONE ] - ShortCode
             *  ----------------------------------
             */
            add_shortcode( 'sdweddingdirectory_call_to_action_one', [ $this, 'sdweddingdirectory_call_to_action_one' ] );

            /**
             *  Call To Action [ TWO ] - ShortCode
             *  ----------------------------------
             */
            add_shortcode( 'sdweddingdirectory_call_to_action_two', [ $this, 'sdweddingdirectory_call_to_action_two' ] );

            /**
             *  ShortCode Filter
             *  ----------------
             */
            add_filter( 'sdweddingdirectory_shortcode_list', function( $args = [] ){

                return  array_merge( $args, array(

                            'sdweddingdirectory_call_to_action_one'  =>    sprintf( '[sdweddingdirectory_call_to_action_one %1$s]<h1>The Best Wedding Vendor Service</h1><p>Sed ut perspiciatis unde omnis iste oluptatem accusantium doloremque laud.</p>[sdweddingdirectory_button target="_self" class="btn btn-default btn-rounded btn-lg" id="" link="" ] Button Text Here [/sdweddingdirectory_button][/sdweddingdirectory_call_to_action_one]',

                                /**
                                 *  1. Get Attributes
                                 *  -----------------
                                 */
                                parent:: _shortcode_atts( self:: default_args_cta_one() )
                            ),

                            'sdweddingdirectory_call_to_action_two'  =>    sprintf( '[sdweddingdirectory_call_to_action_two %1$s][sdweddingdirectory_row id="" class="align-items-center"] [sdweddingdirectory_grid id="" class="col-lg-6"]<h1 class="mb-md-0 mb-xl-0 mb-lg-0 mb-4 text-white">Are You Looking For Vendor For Your Wedding</h1>[/sdweddingdirectory_grid][sdweddingdirectory_grid id="" class="col-lg-6 text-lg-end mt-lg-0 mt-md-4"] [sdweddingdirectory_button_group class="" id=""][sdweddingdirectory_button target="_self" class="" id="" link="" ] Button Text Here [/sdweddingdirectory_button][sdweddingdirectory_button target="_self" class="" id="" link="" ] Button Text Here [/sdweddingdirectory_button][/sdweddingdirectory_button_group][/sdweddingdirectory_grid][/sdweddingdirectory_row][/sdweddingdirectory_call_to_action_two]',

                                parent:: _shortcode_atts( self:: default_args_cta_two() )
                            )
                        ) );
            } );

            /**
             *  Placeholder Filter
             *  ------------------
             */
            add_filter( 'sdweddingdirectory/placeholder/shortcode', function( $args = [] ){

                return  array_merge( $args, array(

                            'call_to_action_one'  =>  esc_url(  plugin_dir_url( __FILE__ ) . 'images/' . 'call_to_action_one.jpg' ),

                            'call_to_action_two'  =>  esc_url(  plugin_dir_url( __FILE__ ) . 'images/' . 'call_to_action_two.jpg' )

                        ) );
            } );
        }

        /**
         *  Call To Action [ ONE ] : HTML
         *  -----------------------------
         */
        public static function sdweddingdirectory_call_to_action_one( $atts, $content = '' ){

            /**
             *  Extract Attr Data
             *  -----------------
             */
            extract( shortcode_atts( self:: default_args_cta_one(), $atts, esc_attr( __FUNCTION__ ) ) );

            /**
             *  Return Button HTML
             *  ------------------
             */
            return  sprintf(    '<div %1$s class="callout-main %2$s">

                                    <div class="container-fluid pl-0">

                                        <div class="row">

                                            <div class="col-lg-6" style="background: url(%3$s) center center no-repeat; background-size: cover;">

                                                <img src="%3$s" class="d-lg-none invisible" alt="" />

                                            </div>

                                            <div class="col-md-12 col-lg-6">

                                                <div class="callout-text">%4$s</div>

                                            </div>

                                        </div>

                                    </div>

                                </div>',

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
                        esc_attr( $class ),

                        /** 
                         *  3. Media Link
                         *  -------------
                         */
                        esc_url( $media_url ),

                        /**
                         *  4. Button Text
                         *  --------------
                         */
                        do_shortcode(

                            apply_filters( 'sdweddingdirectory_clean_shortcode', $content )
                        )
                    );
        }

        /**
         *  Call To Action [ TWO ] : HTML
         *  -----------------------------
         */
        public static function sdweddingdirectory_call_to_action_two( $atts, $content = '' ){

            /**
             *  Extract Attr Data
             *  -----------------
             */
            extract( shortcode_atts( self:: default_args_cta_two(), $atts, esc_attr( __FUNCTION__ ) ) );

            /**
             *  Return Button HTML
             *  ------------------
             */
            return  sprintf(   '<section class="call-out-bg" style="background: url(%3$s) center center no-repeat; background-size: cover;">

                                    <div class="overlay"></div>

                                    <div class="container">

                                        %4$s

                                    </div>

                                </section>',

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
                        esc_attr( $class ),

                        /** 
                         *  3. Media Link
                         *  -------------
                         */
                        esc_url( $media_url ),

                        /**
                         *  4. Button Text
                         *  --------------
                         */
                        do_shortcode(

                            apply_filters(  'sdweddingdirectory_clean_shortcode',  $content )
                        )
                    );
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
                 *  Slider
                 *  ------
                 */
                if( $layout == esc_attr( '1' ) ){

                    return  do_shortcode( sprintf(

                                '[sdweddingdirectory_call_to_action_one %1$s %2$s %3$s]%4$s %5$s[/sdweddingdirectory_call_to_action_one]',

                                /**
                                 *  1. Have ID ?
                                 *  ------------
                                 */
                                parent:: _have_attr_value( array(

                                    'attr'    =>   esc_attr( 'id' ),

                                    'val'    =>   $id
                                ) ),

                                /**
                                 *  2. Have Class ?
                                 *  ---------------
                                 */
                                parent:: _have_attr_value( array(

                                    'attr'    =>   esc_attr( 'class' ),

                                    'val'    =>   $class
                                ) ),

                                /**
                                 *  3. Have Media Link ?
                                 *  --------------------
                                 */
                                parent:: _have_attr_value( array(

                                    'attr'    =>   esc_attr( 'media_url' ),

                                    'val'    =>   $media_url
                                ) ),

                                /**
                                 *  4. Button Text
                                 *  --------------
                                 */
                                do_shortcode(

                                    apply_filters(  'sdweddingdirectory_clean_shortcode',  $content_one )
                                ),

                                /**
                                 *  5. Button Group Load
                                 *  --------------------
                                 */
                                do_shortcode(

                                    apply_filters(  'sdweddingdirectory_clean_shortcode',  $button_group )
                                )
                            )
                        );
                }

                /**
                 *  Background Image
                 *  ----------------
                 */
                if( $layout == esc_attr( '2' ) ){

                    return  do_shortcode( sprintf(

                                '[sdweddingdirectory_call_to_action_two %1$s %2$s %3$s]

                                    [sdweddingdirectory_row id="" class="align-items-center"]

                                        [sdweddingdirectory_grid id="" class="col-lg-6"]<h1 class="mb-md-0 mb-xl-0 mb-lg-0 mb-4 text-white">%4$s</h1>[/sdweddingdirectory_grid]

                                        [sdweddingdirectory_grid id="" class="col-lg-6 text-lg-end mt-lg-0 mt-md-4"]%5$s[/sdweddingdirectory_grid]

                                    [/sdweddingdirectory_row]

                                [/sdweddingdirectory_call_to_action_two]',

                                /**
                                 *  1. Have ID ?
                                 *  ------------
                                 */
                                parent:: _have_attr_value( array(

                                    'attr'    =>   esc_attr( 'id' ),

                                    'val'    =>   $id
                                ) ),

                                /**
                                 *  2. Have Class ?
                                 *  ---------------
                                 */
                                parent:: _have_attr_value( array(

                                    'attr'    =>   esc_attr( 'class' ),

                                    'val'    =>   $class
                                ) ),

                                /**
                                 *  3. Have Media Link ?
                                 *  --------------------
                                 */
                                parent:: _have_attr_value( array(

                                    'attr'    =>   esc_attr( 'media_url' ),

                                    'val'    =>   $media_url
                                ) ),

                                /**
                                 *  4. Button Text
                                 *  --------------
                                 */
                                do_shortcode(

                                    apply_filters(  'sdweddingdirectory_clean_shortcode',  $content_two )
                                ),

                                /**
                                 *  5. Button Group Load
                                 *  --------------------
                                 */
                                do_shortcode(

                                    apply_filters(  'sdweddingdirectory_clean_shortcode',  $button_group )
                                )
                            )
                        );
                }
            }
        }
    }

    /**
     *  -------------------------------------------
     *  SDWeddingDirectory - ShortCode - [ Call To Action ]
     *  -------------------------------------------
     */
    SDWeddingDirectory_Shortcode_Call_To_Action::get_instance();
}