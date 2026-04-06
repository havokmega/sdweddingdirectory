<?php
/**
 *  --------------------------------------
 *  SDWeddingDirectory - ShortCode - [ Accordion ]
 *  --------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Shortcode_Accordion' ) && class_exists( 'SDWeddingDirectory_Shortcode' ) ){

    /**
     *  --------------------------------------
     *  SDWeddingDirectory - ShortCode - [ Accordion ]
     *  --------------------------------------
     */
    class SDWeddingDirectory_Shortcode_Accordion extends SDWeddingDirectory_Shortcode {

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
        public static function default_collapse(){

            return      [  'title'  =>   'Home'  ];
        }

        /**
         *  ShortCode Atts
         *  --------------
         */
        public static function default_collapsibles(){

            return      [
                            'id'        =>  esc_attr( parent:: _rand() ),

                            'class'     =>  '',

                            'layout'    =>  absint( '1' )
                        ];
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
            add_shortcode( 'sdweddingdirectory_collapsibles', [ $this, 'sdweddingdirectory_collapsibles' ] );

            /**
             *  Row
             *  ---
             */
            add_shortcode( 'sdweddingdirectory_collapse', [ $this, 'sdweddingdirectory_collapse' ] );

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

                            'sdweddingdirectory_collapsibles'   =>

                            sprintf( '[sdweddingdirectory_collapsibles %1$s][sdweddingdirectory_collapse %2$s]%3$s[/sdweddingdirectory_collapse][sdweddingdirectory_collapse %2$s]%4$s[/sdweddingdirectory_collapse][/sdweddingdirectory_collapsibles]',

                                /**
                                 *  1. Get Default Atts
                                 *  -------------------
                                 */
                                parent:: _shortcode_atts( self:: default_collapsibles() ),

                                /**
                                 *  2. Get Default Atts
                                 *  -------------------
                                 */
                                parent:: _shortcode_atts( self:: default_collapse() ),

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
        public static function sdweddingdirectory_collapsibles( $atts, $content = '' ){

            /**
             *  Extract Args
             *  ------------
             */
            extract( shortcode_atts( self:: default_collapsibles(), $atts, esc_attr( __FUNCTION__ ) ) );

            /**
             *  Have Content Parent Key
             *  -----------------------
             */
            if( isset( $GLOBALS[ 'sdweddingdirectory_collapsibles_count' ] ) ){

                $GLOBALS[ 'sdweddingdirectory_collapsibles_count' ]++;

            }else{

                $GLOBALS[ 'sdweddingdirectory_collapsibles_count' ]     =   absint( '1' );
            }

            /**
             *  Read the content data
             *  ---------------------
             */
            do_shortcode( apply_filters( 'sdweddingdirectory_clean_shortcode', $content ) );

            /**
             *  Have Parent ID ?
             *  ----------------
             */
            $_parent_id     =   ! empty( $id )  ? esc_attr( $id ) : esc_attr( parent:: _rand() );

            /**
             *  Layout 1
             *  --------
             */
            if( $layout == absint( '1' ) ){

                /**
                 *  Return : HTML
                 *  -------------
                 */
                return  sprintf(   '<div id="%1$s" class="accordion theme-accordian %2$s">

                                        %3$s

                                    </div>',

                                    /**
                                     *  1. ID
                                     *  -----
                                     */
                                    esc_attr( $_parent_id ),

                                    /**
                                     *  2. Have Class ?
                                     *  ---------------
                                     */
                                    isset( $class ) && $class !== '' ? esc_attr( $class ) : '',

                                    /**
                                     *  3. Have Accordion ?
                                     *  -------------------
                                     */
                                    self:: create_accordion(

                                        /**
                                         *  Parent ID
                                         *  ---------
                                         */
                                        esc_attr( $_parent_id ),

                                        $GLOBALS[ 'sdweddingdirectory_global_accordion_data' ][ $GLOBALS[ 'sdweddingdirectory_collapsibles_count' ] ]
                                    )
                        );
            }
        }

        /**
         *  Create Only list tab <li> only
         *  ------------------------------
         */
        public static function create_accordion( $parent_id = '', $args = [] ){

            /**
             *  Have Args ?
             *  -----------
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

                    sprintf(   '<div class="card-header" id="%1$s">

                                    <a href="javascript:" data-bs-toggle="collapse" data-bs-target="#collapse-%1$s" aria-expanded="%2$s" aria-controls="collapse-%1$s" class="%3$s">%5$s</a>

                                </div>

                                <div id="collapse-%1$s" class="collapse %4$s" aria-labelledby="%1$s" data-bs-parent="#%6$s">

                                    <div class="card-body">%7$s</div>

                                </div>',

                                /**
                                 *  1. ID
                                 *  -----
                                 */
                                esc_attr( parent:: _rand() ),

                                /**
                                 *  2. Collapse Expand ?
                                 *  --------------------
                                 */
                                $_counter == absint( '1' )

                                ?   esc_attr( 'true' )

                                :   esc_attr( 'false' ),

                                /**
                                 *  3. Collapse Expand Have class ?
                                 *  --------------------
                                 */
                                $_counter !== absint( '1' )

                                ?   sanitize_html_class( 'collapsed' )

                                :   '',

                                /**
                                 *  4. Content Show / Tab Active ?
                                 *  ------------------------------
                                 */
                                $_counter == absint( '1' )

                                ?   sanitize_html_class( 'show' )

                                :   '',

                                /**
                                 *  5. Heading ( Title )
                                 *  --------------------
                                 */
                                esc_attr( $title ),

                                /**
                                 *  6. Parent ID
                                 *  ------------
                                 */
                                esc_attr( $parent_id ),

                                /**
                                 *  7. Have Content ?
                                 *  -----------------
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
        public static function sdweddingdirectory_collapse( $atts, $content = '' ){

            /**
             *  Default Args
             *  ------------
             */
            $atts   =   shortcode_atts( self:: default_collapse(), $atts, esc_attr( __FUNCTION__ ) );

            /**
             *  Extract Args
             *  ------------
             */
            extract( $atts );

            /**
             *  Accordion
             *  ---------
             */
            $GLOBALS['sdweddingdirectory_collapsibles_count']   =   isset(  $GLOBALS['sdweddingdirectory_collapsibles_count']  )

                                                        ?   $GLOBALS['sdweddingdirectory_collapsibles_count']

                                                        :   absint( '1' );

            /**
             *  Tab data collection
             *  -------------------
             */
            $GLOBALS[ 'sdweddingdirectory_global_accordion_data' ][ $GLOBALS[ 'sdweddingdirectory_collapsibles_count' ] ][]      =  array(

                'title'         =>  $title,

                'content'       =>  do_shortcode( apply_filters( 'sdweddingdirectory_clean_shortcode', $content ) )
            );
        }
    }

    /**
     *  --------------------------------------
     *  SDWeddingDirectory - ShortCode - [ Accordion ]
     *  --------------------------------------
     */
    SDWeddingDirectory_Shortcode_Accordion::get_instance();
}