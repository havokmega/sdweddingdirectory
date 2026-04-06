<?php
/**
 *  -----------------------------------------------
 *  SDWeddingDirectory - ShortCode - [ Tabs | Tab Content ]
 *  -----------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Shortcode_Tabs' ) && class_exists( 'SDWeddingDirectory_Shortcode' ) ){

    /**
     *  -----------------------------------------------
     *  SDWeddingDirectory - ShortCode - [ Tabs | Tab Content ]
     *  -----------------------------------------------
     */
    class SDWeddingDirectory_Shortcode_Tabs extends SDWeddingDirectory_Shortcode {

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
        public static function default_tabs_args(){

            return  array(

                        'class'         =>  '',

                        'id'            =>  '',

                        'layout'        =>  absint( '1' )
                    );
        }

        /**
         *  ShortCode Atts
         *  --------------
         */
        public static function default_tab_args(){

            return  array(

                        'id'            =>  '',

                        'class'         =>  '',

                        'icon'          =>  '',

                        'title'         =>  esc_attr__( 'Heading Here', 'sdweddingdirectory-shortcodes' )
                    );
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
            add_shortcode( 'sdweddingdirectory_tabs', [ $this, 'sdweddingdirectory_tabs' ] );

            /**
             *  Row
             *  ---
             */
            add_shortcode( 'sdweddingdirectory_tab', [ $this, 'sdweddingdirectory_tab' ] );

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

                            'sdweddingdirectory_tabs'   =>

                            sprintf( '[sdweddingdirectory_tabs %1$s][sdweddingdirectory_tab %2$s]%3$s[/sdweddingdirectory_tab][sdweddingdirectory_tab %2$s]%4$s[/sdweddingdirectory_tab][/sdweddingdirectory_tabs]',

                                /**
                                 *  1. Get Default Atts
                                 *  -------------------
                                 */
                                parent:: _shortcode_atts( self:: default_tabs_args() ),

                                /**
                                 *  2. Get Default Atts
                                 *  -------------------
                                 */
                                parent:: _shortcode_atts( self:: default_tab_args() ),

                                /**
                                 *  3. String
                                 *  ---------
                                 */
                                parent:: _dummy_content(),

                                /**
                                 *  4. String
                                 *  ---------
                                 */
                                parent:: _dummy_content()
                            )

                        ] );
            } );
        }

        /**
         *  Container : HTML
         *  ----------------
         */
        public static function sdweddingdirectory_tabs( $atts, $content = '' ){

            /**
             *  Extract Args
             *  ------------
             */
            extract( shortcode_atts( self:: default_tabs_args(), $atts, esc_attr( __FUNCTION__ )  ) );

            /**
             *  Have Content Parent Key
             *  -----------------------
             */
            if( isset( $GLOBALS[ 'sdweddingdirectory_tabs_count' ] ) ){

                $GLOBALS[ 'sdweddingdirectory_tabs_count' ]++;

            }else{

                $GLOBALS[ 'sdweddingdirectory_tabs_count' ]     =   absint( '0' );
            }

            /**
             *  Read the content data
             *  ---------------------
             */
            do_shortcode( apply_filters( 'sdweddingdirectory_clean_shortcode', $content ) );

            /**
             *  Get Tab Data Collection
             *  -----------------------
             */
            $_tab_data_collection   =   parent:: _is_array( $GLOBALS[ 'sdweddingdirectory_global_tab_data' ][ $GLOBALS[ 'sdweddingdirectory_tabs_count' ] ] )

                                    ?   $GLOBALS[ 'sdweddingdirectory_global_tab_data' ][ $GLOBALS[ 'sdweddingdirectory_tabs_count' ] ]

                                    :   [];

            /**
             *  1. Create <li>
             *  --------------
             */
            $tab_li_list            =   self:: create_tab_with_li( $_tab_data_collection );

            /**
             *  2. Tab Content
             *  --------------
             */
            $tab_content            =   self:: create_tab_content( $_tab_data_collection );

            /**
             *  3. Create <a>
             *  --------------
             */
            $tab_a_list             =   self:: create_tab_with_a( $_tab_data_collection );

            /**
             *  Layout 1
             *  --------
             */
            if( $layout == absint( '1' ) ){

                /**
                 *  Return : HTML
                 *  -------------
                 */
                return  sprintf(   '<ul class="nav nav-pills theme-tabbing nav-fill" id="%1$s-tab" role="tablist">%2$s</ul>
                                    <div class="tab-content theme-tabbing" id="%1$s-tabContent">%3$s</div>',

                                    /**
                                     *  1. ID
                                     *  -----
                                     */
                                    esc_attr( parent:: _rand() ) ,

                                    /**
                                     *  2. Create Tab
                                     *  -------------
                                     */
                                    $tab_li_list,

                                    /**
                                     *  3. Create Tab Content
                                     *  ---------------------
                                     */
                                    $tab_content
                        );
            }

            if( $layout == absint( '2' ) ){

                /**
                 *  Return : HTML
                 *  -------------
                 */
                return  sprintf(   '<div class="row"> 

                                        <div class="col-12 col-md-4">

                                            <div class="nav flex-column nav-pills theme-tabbing-vertical"  id="%1$s-tab" role="tablist" aria-orientation="vertical">

                                                %2$s

                                            </div>

                                        </div>

                                        <div class="col-12 col-md-8">

                                            <div class="tab-content theme-tabbing" id="%1$s-tabContent">%3$s</div>

                                        </div>

                                    </div>',

                                    /**
                                     *  1. ID
                                     *  -----
                                     */
                                    esc_attr( parent:: _rand() ) ,

                                    /**
                                     *  2. Create Tab
                                     *  -------------
                                     */
                                    $tab_a_list,

                                    /**
                                     *  3. Create Tab Content
                                     *  ---------------------
                                     */
                                    $tab_content
                        );
            }

            if( $layout == absint( '3' ) ){

                /**
                 *  Return : HTML
                 *  -------------
                 */
                return  sprintf(   '<ul class="nav nav-pills mb-3 horizontal-tab-second justify-content-center nav-fill" id="%1$s-tab" role="tablist">%2$s</ul>
                                    <div class="tab-content theme-tabbing" id="%1$s-tabContent">%3$s</div>',

                                    /**
                                     *  1. ID
                                     *  -----
                                     */
                                    esc_attr( parent:: _rand() ) ,

                                    /**
                                     *  2. Create Tab
                                     *  -------------
                                     */
                                    $tab_li_list,

                                    /**
                                     *  3. Create Tab Content
                                     *  ---------------------
                                     */
                                    $tab_content
                        );
            }

            if( $layout == absint( '4' ) ){

                /**
                 *  Return : HTML
                 *  -------------
                 */
                return  sprintf(   '<ul class="nav nav-pills mb-3 horizontal-tab-second" id="%1$s-tab" role="tablist">%2$s</ul>
                                    <div class="tab-content theme-tabbing" id="%1$s-tabContent">%3$s</div>',

                                    /**
                                     *  1. ID
                                     *  -----
                                     */
                                    esc_attr( parent:: _rand() ) ,

                                    /**
                                     *  2. Create Tab
                                     *  -------------
                                     */
                                    $tab_li_list,

                                    /**
                                     *  3. Create Tab Content
                                     *  ---------------------
                                     */
                                    $tab_content
                        );
            }
        }

        /**
         *  Create Only list tab <li> only
         *  ------------------------------
         */
        public static function create_tab_with_li( $args = [] ){

            /**
             *  Have Args
             *  ---------
             */
            if( parent:: _is_array( $args ) ){

                $items      =   '';

                $_counter   =   absint( '1' );

                foreach( $args as $key => $value ){

                    /**
                     *  Extract Args
                     *  ------------
                     */
                    extract( $value );

                    $items      .=

                    sprintf(    '<li class="nav-item">

                                    <a  class="nav-link %3$s" 

                                        id="%1$s-tab" 

                                        data-bs-toggle="pill" 

                                        href="#%1$s" 

                                        role="tab" 

                                        aria-controls="%1$s" 

                                        aria-selected="true">%4$s %2$s</a>

                                </li>',

                                /**
                                 *  1. ID
                                 *  -----
                                 */
                                esc_attr( $id ),

                                /**
                                 *  2. Name
                                 *  -------
                                 */
                                esc_attr( $title ),

                                /**
                                 *  3. Is Active ?
                                 *  --------------
                                 */
                                ( $_counter == absint( '1' ) )

                                ?   sanitize_html_class( 'active' )

                                :   '',

                                /**
                                 *  4. Have Icon ?
                                 *  --------------
                                 */
                                parent:: _have_data( $icon  )

                                ?   sprintf( '<i class="%1$s"></i>', $icon )

                                :   ''
                    );

                    $_counter++;
                }

                return  $items;
            }
        }

        /**
         *  Create Only list tab <a> only
         *  ------------------------------
         */
        public static function create_tab_with_a( $args = [] ){

            /**
             *  Have Args
             *  ---------
             */
            if( parent:: _is_array( $args ) ){

                $items      =   '';

                $_counter   =   absint( '1' );

                foreach( $args as $key => $value ){

                    /**
                     *  Extract Args
                     *  ------------
                     */
                    extract( $value );

                    $items      .=

                    sprintf(   '<a  class="nav-link %3$s" 

                                        id="%1$s-tab" 

                                        data-bs-toggle="pill" 

                                        href="#%1$s" 

                                        role="tab" 

                                        aria-controls="%1$s" 

                                        aria-selected="true">%4$s %2$s

                                </a>',

                                /**
                                 *  1. ID
                                 *  -----
                                 */
                                esc_attr( $id ),

                                /**
                                 *  2. Name
                                 *  -------
                                 */
                                esc_attr( $title ),

                                /**
                                 *  3. Is Active ?
                                 *  --------------
                                 */
                                ( $_counter == absint( '1' ) )

                                ?   sanitize_html_class( 'active' )

                                :   '',

                                /**
                                 *  4. Have Icon ?
                                 *  --------------
                                 */
                                parent:: _have_data( $icon  )

                                ?   sprintf( '<i class="%1$s"></i>', $icon )

                                :   ''
                    );

                    $_counter++;
                }

                return  $items;
            }
        }

        /**
         *  Create Only list tab <ul> only
         *  ------------------------------
         */
        public static function create_tab_content( $args = [] ){

            /**
             *  Have Args
             *  ---------
             */
            if( parent:: _is_array( $args ) ){

                $items      =   '';

                $_counter   =   absint( '1' );

                foreach( $args as $key => $value ){

                    /**
                     *  Extract Args
                     *  ------------
                     */
                    extract( $value );

                    $items      .=

                    sprintf(    '<div id="%1$s" class="tab-pane fade %2$s" role="tabpanel" aria-labelledby="%1$s-tab">%3$s</div>',

                                /**
                                 *  1. ID
                                 *  -----
                                 */
                                esc_attr( $id ),

                                /**
                                 *  2. Is Active ?
                                 *  --------------
                                 */
                                ( $_counter == absint( '1' ) )

                                ?   esc_attr( 'show active' )

                                :   '',

                                /**
                                 *  3. Content
                                 *  ----------
                                 */
                                do_shortcode( apply_filters( 'sdweddingdirectory_clean_shortcode', $content ) )
                    );

                    $_counter++;
                }

                return  $items;
            }
        }

        /**
         *  Row : HTML
         *  ----------
         */
        public static function sdweddingdirectory_tab( $atts, $content = '' ){

            /**
             *  Extract Args
             *  ------------
             */
            extract( shortcode_atts( self:: default_tab_args(), $atts, esc_attr( __FUNCTION__ ) ) );

            /**
             *  Have Content Parent Key
             *  -----------------------
             */
            $current_tab_key   =   ( isset( $GLOBALS[ 'sdweddingdirectory_tabs_count' ] ) )

                                ?   $GLOBALS[ 'sdweddingdirectory_tabs_count' ]

                                :   $GLOBALS[ 'sdweddingdirectory_tabs_count' ]     =   absint( '0' );

            /**
             *  Tab data collection
             *  -------------------
             */
            $GLOBALS[ 'sdweddingdirectory_global_tab_data' ][ $current_tab_key ][]     =   array(

                'id'            =>  isset( $id ) && $id !== '' ? $id  : esc_attr( parent:: _rand() ) ,

                'class'         =>  $class,

                'title'         =>  $title,

                'icon'          =>  $icon,

                'content'       =>  do_shortcode( apply_filters( 'sdweddingdirectory_clean_shortcode', $content ) )
            );
        }
    }

    /**
     *  -----------------------------------------------
     *  SDWeddingDirectory - ShortCode - [ Tabs | Tab Content ]
     *  -----------------------------------------------
     */
    SDWeddingDirectory_Shortcode_Tabs::get_instance();
}