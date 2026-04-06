<?php
/**
 *  SDWeddingDirectory - Venue Filter & Hooks
 *  -----------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Venue_Singular_Left_Side_Widget_Menu' ) && class_exists( 'SDWeddingDirectory_Venue_Singular_Left_Side_Widget' ) ){

    /**
     *  SDWeddingDirectory - Venue Filter & Hooks
     *  -----------------------------------
     */
    class SDWeddingDirectory_Venue_Singular_Left_Side_Widget_Menu extends SDWeddingDirectory_Venue_Singular_Left_Side_Widget {

        /**
         *  Member Variable
         *  ---------------
         */
        private static $instance;

        /**
         *  Initiator
         *  ---------
         */
        public static function get_instance() {
          
            if ( ! isset( self::$instance ) ) {

                self::$instance = new self;
            }

            return self::$instance;
        }

        /**
         *  Construct
         *  ---------
         */
        public function __construct() {

            /**
             *  1. Venue Section Left Widget [ Team ]
             *  ---------------------------------------
             */
            add_action( 'sdweddingdirectory/venue/singular/left-side/widget', [ $this, 'widget' ], absint( '100' ), absint( '1' ) );
        }

        /**
         *  1. Venue Section Left Widget [ Team ]
         *  ---------------------------------------
         */
        public static function widget( $args = [] ){

            /**
             *  Is array ?
             *  ----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Merge Args
                 *  ----------
                 */
                $args       =       wp_parse_args( $args, [

                                        'post_id'               =>      absint( '0' ),

                                        'active_tab'            =>      false,

                                        'id'                    =>      sanitize_title( 'venue_menu' ),

                                        'icon'                  =>      'fa fa-cutlery',

                                        'heading'               =>      esc_attr__( 'Menu', 'sdweddingdirectory-venue' ),

                                        'card_info'             =>      true,

                                        'handler'               =>      '',

                                        'counter'               =>      absint( '1' )

                                    ] );

                /**
                 *  Extract the data
                 *  ----------------
                 */
                extract( $args );

                /**
                 *  Make sure post id not empty!
                 *  ----------------------------
                 */
                if( empty( $post_id ) ){

                    return;
                }


                /**
                 *  Get Content ?
                 *  -------------
                 */
                $_have_menu  =  get_post_meta( absint( $post_id ), sanitize_key( 'venue_menu' ), true );

                /**
                 *  Have Data ?
                 *  -----------
                 */
                if( parent:: _is_array( $_have_menu ) ){

                    /**
                     *  Layout 1
                     *  --------
                     */
                    if( $layout == absint( '1' ) ){


                        $handler    .=      '<div class="card-shadow-body">';

                        if( count( $_have_menu ) >= absint( '3' ) ){

                            $handler    .=      '<div class="owl-carousel owl-theme" id="venue-singular-menu">';

                        }else{

                            $handler    .=      '<div class="row row-cols-lg-2 row-cols-md-2 row-cols-sm-2 row-cols-1">';
                        }


                        foreach( $_have_menu as $key => $value ){

                            /**
                             *  Extract Args
                             *  ------------
                             */
                            extract( $value );

                            /**
                             *  Make sure menu item greter then 3
                             *  ---------------------------------
                             *  Then Load the slider
                             *  --------------------
                             */
                            if( count( $_have_menu ) >= absint( '3' ) ){

                                $handler    .=      '<div class="item">';

                            }else{

                                $handler    .=      '<div class="col mb-xs-30">';
                            }

                            $handler    .=      

                            sprintf(    '<div class="singular-menu-slider">

                                            <div class="top-head">

                                                <div class="head">

                                                    <i class="fa fa-cutlery"></i>

                                                    <div><span>%1$s</span> %2$s</div>

                                                </div>

                                                <div class="price">%3$s</div>

                                            </div>

                                            <div class="content-text">
                                                
                                                <div class="pop-btn">

                                                    <a data-bs-toggle="modal" data-bs-target="#%5$s" class="font-weight-bold">%4$s <i class="fa fa-chevron-right"></i></a>

                                                </div>

                                            </div>
                                            
                                        </div>', 

                                        /**
                                         *  1. Team Image
                                         *  -------------
                                         */
                                        esc_attr__( 'Cuisine Offer', 'sdweddingdirectory-venue' ),

                                        /**
                                         *  2. Name
                                         *  -------
                                         */
                                        esc_attr( $menu_title ),

                                        /**
                                         *  3. Position
                                         *  -----------
                                         */
                                        sdweddingdirectory_pricing_possition( $menu_price ),

                                        /**
                                         *  4. Translation Ready String
                                         *  ---------------------------
                                         */
                                        esc_attr__( 'Read More', 'sdweddingdirectory-venue' ),

                                        /**
                                         *  5. Menu Title
                                         *  -------------
                                         */
                                        parent:: _prefix( sanitize_title( $menu_title .'_'. $counter ) )
                            );

                            $handler    .=    '</div>';     $counter++;
                        }


                        $handler    .=    '</div></div>';


                        /**
                         *  Card Info Enable ?
                         *  ------------------
                         */
                        if(  $card_info  ){

                            /**
                             *  Output
                             *  ------
                             */
                            printf(    '<div class="card-shadow position-relative">

                                            <a id="section_%1$s" class="anchor-fake"></a>

                                            <div class="card-shadow-header">

                                                <h3><i class="%2$s"></i> %3$s</h3>

                                            </div>

                                            %4$s

                                        </div>',

                                        /**
                                         *  1. Tab name
                                         *  -----------
                                         */
                                        sanitize_key( $id ),

                                        /**
                                         *  2. Tab Icon
                                         *  -----------
                                         */
                                        $icon,

                                        /**
                                         *  3. Heading
                                         *  ----------
                                         */
                                        esc_attr( $heading ),

                                        /**
                                         *  4. Output
                                         *  ---------
                                         */
                                        $handler
                                );
                        }

                        /**
                         *  Direct Print Data
                         *  -----------------
                         */
                        else{

                            print       $handler;
                        }
                    }

                    /**
                     *  Layout 2
                     *  --------
                     */
                    if( $layout == absint( '2' ) ){

                        /**
                         *  Tab Overview
                         *  ------------
                         */
                        printf( '<a href="#section_%1$s" class="%2$s"><i class="%3$s"></i> %4$s</a>',

                                /**
                                 *  Tab name
                                 *  --------
                                 */
                                esc_attr( $id ),

                                /**
                                 *  Default Active
                                 *  --------------
                                 */
                                $active_tab   ?   sanitize_html_class( 'active' )  :  '',

                                /**
                                 *  Tab Icon
                                 *  --------
                                 */
                                $icon,

                                /**
                                 *  Tab Title
                                 *  ---------
                                 */
                                $heading
                        );
                    }

                    /**
                     *  Layout 3 - Tabing Style
                     *  -----------------------
                     */
                    if( $layout == absint( '3' ) ){

                        ob_start();

                        /**
                         *  List of slider tab icon
                         *  -----------------------
                         */
                        call_user_func( [ __CLASS__, __FUNCTION__ ], [

                            'post_id'       =>      absint( $post_id ),

                            'layout'        =>      absint( '1' ),

                            'card_info'     =>      false

                        ] );

                        $data   =   ob_get_contents();

                        ob_end_clean();

                        /**
                         *  Tab layout
                         *  ----------
                         */
                        printf( '[sdweddingdirectory_tab icon="%1$s" title="%2$s"]%3$s[/sdweddingdirectory_tab]', 

                            /**
                             *  Tab Icon
                             *  --------
                             */
                            $icon,

                            /**
                             *  Tab Title
                             *  ---------
                             */
                            $heading,

                            /**
                             *  Card Body
                             *  ---------
                             */
                            $data
                        );
                    }
                }
            }
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Venue_Singular_Left_Side_Widget_Menu:: get_instance();
}